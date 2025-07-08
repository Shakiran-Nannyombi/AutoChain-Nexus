import os
from fastapi import FastAPI
from pydantic import BaseModel
import pandas as pd
from prophet import Prophet
from fastapi.middleware.cors import CORSMiddleware


app = FastAPI()

# CORS settings
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # Or ['http://localhost:3000'] if using a Vue/React frontend
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)


# Always find the path relative to this script
base_dir = os.path.dirname(__file__)

csv_path = os.path.join(base_dir, '..', 'ml', 'data', 'Car_data.csv')



# Normalize path (optional but safe)
csv_path = os.path.abspath(csv_path)

# Read CSV
df = pd.read_csv(csv_path)
print("Loading CSV from:", csv_path)

df['Date'] = pd.to_datetime(df['Date'])

grouped = (
    df.groupby([pd.Grouper(key='Date', freq='M'), 'Model', 'Dealer_Region'])
    .size()
    .reset_index(name='quantity_sold')
    .rename(columns={'Date': 'ds', 'quantity_sold': 'y'})
)

# Input format
class ForecastRequest(BaseModel):
    model: str
    region: str

@app.post("/forecast")
def forecast_demand(request: ForecastRequest):
    # Filter
    filtered = grouped[
        (grouped['Model'] == request.model) &
        (grouped['Dealer_Region'] == request.region)
    ][['ds', 'y']]

    if len(filtered) < 3:
        return {"error": "Not enough data to forecast"}

    # Forecast
    prophet = Prophet()
    prophet.fit(filtered)
    future = prophet.make_future_dataframe(periods=6, freq='M')
    forecast = prophet.predict(future)

    # Clean and return result
    output = forecast[['ds', 'yhat']].tail(6).copy()
    # formatting ds as YYYY.MM
    output['Month'] = output['ds'].dt.strftime('%Y-%m')
    output = output.rename(columns={'yhat': 'Predicted'})
    output = output[['Month', 'Predicted']]
    
    #output['Predicted'] = output['Predicted'].round().astype(int) # Makes pridicted whole numbers

    output = output.rename(columns={'ds': 'Month', 'yhat': 'Predicted'})
    return output.to_dict(orient="records")
