import os
from fastapi import FastAPI
from pydantic import BaseModel
import pandas as pd
import numpy as np
from prophet import Prophet
from fastapi.middleware.cors import CORSMiddleware
import subprocess
import requests
import pymysql
import json
from fastapi import Request
from fastapi.responses import JSONResponse
import sys
sys.path.append(os.path.abspath(os.path.join(os.path.dirname(__file__), '../ml/scripts')))
from Forecast_Allmodels import DemandForecaster

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
            SELECT product as car_model, quantity as quantity_sold, ordered_at as created_at, manufacturer_id as region
            FROM vendor_orders
            WHERE status = 'fulfilled'
        """
        df = pd.read_sql(query, conn)
        conn.close()
        df['created_at'] = pd.to_datetime(df['created_at'], errors='coerce')
        df = df.dropna(subset=['created_at'])
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
async def forecast_demand(request: Request):
    try:
        data = await request.json()
        models = data.get("models", [])
        
        if not models:
            return {"error": "No car models provided."}
        
        # Get sales data from database
        df = get_sales_df()
        results = {}
        
        for model in models:
            try:
                # Filter data for this car model
                model_df = df[df['car_model'] == model].copy()
                
                if len(model_df) < 3:
                    results[model] = [{"error": "Not enough data to forecast."}]
                    continue
                
                # Group by month and sum quantities
                monthly = (
                    model_df.groupby(pd.Grouper(key='created_at', freq='M'))['quantity_sold']
                    .sum()
                    .reset_index()
                    .rename(columns={'created_at': 'ds', 'quantity_sold': 'y'})
                )
                
                # Remove rows with zero or null values
                monthly = monthly[monthly['y'] > 0]
                
                if len(monthly) < 3:
                    results[model] = [{"error": "Not enough valid data points for forecasting."}]
                    continue
                
                # Create and fit Prophet model
                prophet_model = Prophet(
                    daily_seasonality=False,
                    weekly_seasonality=False,
                    yearly_seasonality=True
                )
                prophet_model.fit(monthly)
                
                # Create future dataframe for 12 months
                future = prophet_model.make_future_dataframe(periods=12, freq='M')
                forecast = prophet_model.predict(future)
                
                # Get the last 12 predictions (future months)
                future_forecast = forecast.tail(12).copy()
                future_forecast['Month'] = future_forecast['ds'].dt.strftime('%Y-%m')
                future_forecast['Predicted'] = future_forecast['yhat'].round().astype(int)
                
                # Ensure predictions are positive
                future_forecast['Predicted'] = future_forecast['Predicted'].clip(lower=0)
                
                # Format for frontend
                forecast_data = future_forecast[['Month', 'Predicted']].to_dict('records')
                results[model] = forecast_data
                
            except Exception as e:
                results[model] = [{"error": f"Forecasting failed for {model}: {str(e)}"}]
        
        return results
        
    except Exception as e:
        return {"error": f"Forecast generation failed: {str(e)}"}

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

@app.post("/forecast-ml")
async def forecast_ml(request: Request):
    data = await request.json()
    models = data.get("models", [])
    force_retrain = data.get("force_retrain", False)
    if not models:
        return {"error": "No car models provided."}
    # Load live data from vendor_orders
    try:
        print("DB CONFIG:", db_config)  # Debug print
        conn = pymysql.connect(
            host=db_config['host'],
            user=db_config['user'],
            password=db_config['password'],
            database=db_config['database'],
            cursorclass=pymysql.cursors.DictCursor
        )
        query = """
            SELECT product as Model, quantity as Demand, ordered_at as Date, manufacturer_id as Dealer_Region
            FROM vendor_orders
            WHERE status = 'fulfilled'
        """
        with conn.cursor() as cursor:
            cursor.execute(query)
            rows = cursor.fetchall()
        conn.close()
        print("RAW ROWS:", rows[:5])  # Print first 5 rows for debug
        import pandas as pd
        df = pd.DataFrame(rows)
        print("DF FROM DICTS:", df.head())
        print("DF dtypes:", df.dtypes)
        df['Date'] = pd.to_datetime(df['Date'], errors='coerce')
        df = df.dropna(subset=['Date'])
        print("DF HEAD:", df.head(10))
        print("DF COLUMNS:", df.columns.tolist())
        print("DF MODELS:", df['Model'].unique())
        print("DF ROWS:", len(df))
    except Exception as e:
        return {"error": f"Database connection failed: {e}"}
    # Prepare and forecast
    results = {}
    model_status = "loaded"
    for model in models:
        try:
            forecaster = DemandForecaster()
            forecaster.demand_data = None  # Always use fresh data
            if force_retrain or not forecaster.model_exists():
                forecaster.train(df)
                model_status = "retrained"
            else:
                forecaster.load_model()
                forecaster.demand_data = forecaster.prepare_features(df)
            print(f"PREDICT: Looking for model {model}")
            print("PREDICT: Available models:", forecaster.demand_data['Model'].unique())
            _, pred = forecaster.predict(model, months=12)
            pred['Month'] = pred['Date'].dt.strftime('%Y-%m')
            pred['Predicted'] = pred['Predicted_Demand'].astype(int)
            results[model] = pred[['Month', 'Predicted']].to_dict('records')
        except Exception as e:
            results[model] = [{"error": f"Forecasting failed: {str(e)}"}]
    print("ML forecast results:", results)
    return {"results": results, "model_status": model_status}

@app.post("/forecast-ml/retrain")
async def retrain_forecast_ml():
    # Force retrain and save model
    try:
        conn = pymysql.connect(
            host=db_config['host'],
            user=db_config['user'],
            password=db_config['password'],
            database=db_config['database'],
            cursorclass=pymysql.cursors.DictCursor
        )
        query = """
            SELECT product as Model, quantity as Demand, ordered_at as Date, manufacturer_id as Dealer_Region
            FROM vendor_orders
            WHERE status = 'fulfilled'
        """
        df = pd.read_sql(query, conn)
        conn.close()
        df['Date'] = pd.to_datetime(df['Date'], errors='coerce')
        df = df.dropna(subset=['Date'])
        forecaster = DemandForecaster()
        forecaster.train(df)
        return {"success": True, "message": "Model retrained and saved."}
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
