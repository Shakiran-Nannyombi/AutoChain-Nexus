import os
from fastapi import FastAPI
from pydantic import BaseModel
import pandas as pd
from prophet import Prophet
from fastapi.middleware.cors import CORSMiddleware
import subprocess
import requests
import pymysql
import json
from fastapi import Request
from fastapi.responses import JSONResponse

app = FastAPI()

# CORS settings
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

base_dir = os.path.dirname(__file__)

# Load DB config
with open(os.path.join(base_dir, '..', 'ml', 'db_config.json')) as f:
    db_config = json.load(f)

def get_sales_df():
    try:
        conn = pymysql.connect(
            host=db_config['host'],
            user=db_config['user'],
            password=db_config['password'],
            database=db_config['database'],
            cursorclass=pymysql.cursors.DictCursor
        )
        query = """
            SELECT car_model, quantity_sold, created_at, retailer_id
            FROM retailer_sales
        """
        df = pd.read_sql(query, conn)
        conn.close()
        df['created_at'] = pd.to_datetime(df['created_at'])
        return df
    except Exception as e:
        raise Exception(f"Database connection failed: {e}")

class ForecastRequest(BaseModel):
    model: str
    region: str

class ForecastFileRequest(BaseModel):
    file_id: str = None
    file_path: str = None
    model: str = None
    region: str = None

@app.post("/forecast")
def forecast_demand(request: ForecastFileRequest):
    # If file_path is provided, use file for prediction
    if request.file_path:
        file_path = request.file_path
        ext = os.path.splitext(file_path)[1].lower()
        try:
            if ext == '.csv' or ext == '.txt':
                df = pd.read_csv(file_path)
            elif ext == '.json':
                df = pd.read_json(file_path)
            else:
                return {"error": "Unsupported file format."}
            # Try to normalize columns
            if 'date' in df.columns:
                df['date'] = pd.to_datetime(df['date'])
                df = df.rename(columns={'date': 'created_at'})
            if 'model' in df.columns:
                df = df.rename(columns={'model': 'car_model'})
            if 'region' in df.columns:
                df = df.rename(columns={'region': 'region'})
            if 'quantity_sold' not in df.columns:
                # Try to infer
                for col in ['quantity', 'sales', 'amount']:
                    if col in df.columns:
                        df = df.rename(columns={col: 'quantity_sold'})
                        break
            if 'created_at' not in df.columns or 'car_model' not in df.columns or 'region' not in df.columns or 'quantity_sold' not in df.columns:
                return {"error": "File must contain columns: created_at, car_model, region, quantity_sold"}
            df['created_at'] = pd.to_datetime(df['created_at'])
            grouped = (
                df.groupby([pd.Grouper(key='created_at', freq='M'), 'car_model', 'region'])
                .agg({'quantity_sold': 'sum'})
                .reset_index()
                .rename(columns={'created_at': 'ds', 'quantity_sold': 'y'})
            )
            # If model/region not provided, use the only available or return error
            model = request.model or (grouped['car_model'].iloc[0] if grouped['car_model'].nunique() == 1 else None)
            region = request.region or (grouped['region'].iloc[0] if grouped['region'].nunique() == 1 else None)
            if not model or not region:
                return {"error": "Please specify model and region."}
            filtered = grouped[
                (grouped['car_model'] == model) &
                (grouped['region'] == region)
            ][['ds', 'y']]
            if len(filtered) < 3:
                return {"error": "Not enough data to forecast"}
            prophet = Prophet()
            prophet.fit(filtered)
            future = prophet.make_future_dataframe(periods=6, freq='M')
            forecast = prophet.predict(future)
            output = forecast[['ds', 'yhat']].tail(6).copy()
            output['Month'] = output['ds'].dt.strftime('%Y-%m')
            output = output.rename(columns={'yhat': 'Predicted'})
            output = output[['Month', 'Predicted']]
            return output.to_dict(orient="records")
        except Exception as e:
            return {"error": f"File read/forecast error: {str(e)}"}
    # Fallback: use DB
    df = get_sales_df()
    if 'region' not in df.columns:
        df['region'] = df.get('retailer_id', 'Unknown')
    grouped = (
        df.groupby([pd.Grouper(key='created_at', freq='M'), 'car_model', 'region'])
        .agg({'quantity_sold': 'sum'})
        .reset_index()
        .rename(columns={'created_at': 'ds', 'quantity_sold': 'y'})
    )
    model = request.model
    region = request.region
    filtered = grouped[
        (grouped['car_model'] == model) &
        (grouped['region'] == region)
    ][['ds', 'y']]
    if len(filtered) < 3:
        return {"error": "Not enough data to forecast"}
    prophet = Prophet()
    prophet.fit(filtered)
    future = prophet.make_future_dataframe(periods=6, freq='M')
    forecast = prophet.predict(future)
    output = forecast[['ds', 'yhat']].tail(6).copy()
    output['Month'] = output['ds'].dt.strftime('%Y-%m')
    output = output.rename(columns={'yhat': 'Predicted'})
    output = output[['Month', 'Predicted']]
    return output.to_dict(orient="records")

@app.post("/segment-vendors")
def segment_vendors():
    try:
        script_path = os.path.abspath(os.path.join(base_dir, '..', 'ml', 'vendor_segmentation.py'))
        result = subprocess.run(['python3', script_path], capture_output=True, text=True)
        if result.returncode != 0:
            return {"success": False, "error": f"Segmentation script failed: {result.stderr}"}
        import_url = "http://127.0.0.1:8000/vendor-segments/import"
        import_resp = requests.get(import_url)
        if import_resp.status_code != 200 or not import_resp.json().get('message', '').startswith('Segments imported'):
            return {"success": False, "error": f"Import failed: {import_resp.text}"}
        return {"success": True}
    except Exception as e:
        return {"success": False, "error": str(e)}

@app.post("/api/predict")
async def api_predict(request: Request):
    data = await request.json()
    model = data.get("model")
    region = data.get("region")
    # Use the same logic as /forecast (DB fallback)
    df = get_sales_df()
    if 'region' not in df.columns:
        df['region'] = df.get('retailer_id', 'Unknown')
    grouped = (
        df.groupby([pd.Grouper(key='created_at', freq='M'), 'car_model', 'region'])
        .agg({'quantity_sold': 'sum'})
        .reset_index()
        .rename(columns={'created_at': 'ds', 'quantity_sold': 'y'})
    )
    filtered = grouped[
        (grouped['car_model'] == model) &
        (grouped['region'] == region)
    ][['ds', 'y']]
    if len(filtered) < 3:
        return JSONResponse({"success": False, "message": "Not enough data to forecast", "forecast": []})
    prophet = Prophet()
    prophet.fit(filtered)
    future = prophet.make_future_dataframe(periods=6, freq='M')
    forecast = prophet.predict(future)
    output = forecast[['ds', 'yhat']].tail(6).copy()
    output['month'] = output['ds'].dt.strftime('%Y-%m')
    output = output.rename(columns={'yhat': 'value'})
    output = output[['month', 'value']]
    forecast_list = output.to_dict(orient="records")
    return JSONResponse({"success": True, "forecast": forecast_list})
