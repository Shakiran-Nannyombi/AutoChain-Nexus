import os
import pandas as pd
from prophet import Prophet
import matplotlib.pyplot as plt
from prophet.diagnostics import cross_validation, performance_metrics
import logging

def generate_forecast():
    # Always find the path relative to this script
    base_dir = os.path.dirname(__file__)

    # Navigate to data/Car_data.csv (assuming relative path from ml/predict.py)
    # The original script was in ml/scripts/old.py and went to ../data/Car_data.csv
    # So from ml/predict.py, it should be data/Car_data.csv
    csv_path = os.path.join(base_dir, 'data', 'Car_data.csv')

    # Normalize path
    csv_path = os.path.abspath(csv_path)

    if not os.path.exists(csv_path):
        return {"error": f"Data file not found at {csv_path}"}

    # Read CSV
    try:
        df = pd.read_csv(csv_path)
    except Exception as e:
        return {"error": f"Failed to read CSV: {str(e)}"}


    df['Date'] = pd.to_datetime(df['Date'])

    # Group by month, model, region, and count sales
    grouped_df = (
        df.groupby([pd.Grouper(key='Date', freq='M'), 'Model', 'Dealer_Region'])
        .size()
        .reset_index(name='quantity_sold')
    )

    # Rename for Prophet format
    grouped_df = grouped_df.rename(columns={'Date': 'ds', 'quantity_sold': 'y'})

    # Loop through unique (Model, Region) pairs
    forecast_results = []
    unique_pairs = grouped_df[['Model', 'Dealer_Region']].drop_duplicates()

    logs = []

    for _, row in unique_pairs.iterrows():
        model_name = row['Model']
        region_name = row['Dealer_Region']

        filtered = grouped_df[
            (grouped_df['Model'] == model_name) &
            (grouped_df['Dealer_Region'] == region_name)
        ][['ds', 'y']]

        if len(filtered) < 3:
            continue  # skip if not enough data to train

        try:
            model = Prophet()
            model.fit(filtered)

            future = model.make_future_dataframe(periods=6, freq='M')
            forecast = model.predict(future)

            # ds - datestamp/date, 
            predicted = forecast[['ds', 'yhat']].tail(6).copy()
            predicted['Model'] = model_name
            predicted['Dealer_Region'] = region_name

            forecast_results.append(predicted)

            # Perform cross-validation (Skipping for API speed, or make optional)
            # df_cv = cross_validation(model, initial='365 days', period='180 days', horizon = '90 days')
            # df_p = performance_metrics(df_cv)
            
            logs.append(f"Forecast generated for {model_name} in {region_name}")

        except Exception as e:
            logs.append(f"Error with {model_name} in {region_name}: {e}")
            continue

    # Combine all forecasts into one DataFrame
    if forecast_results:
        final_forecast_df = pd.concat(forecast_results, ignore_index=True)
        output_path = os.path.join(base_dir, "model_region_forecast.csv")
        final_forecast_df.to_csv(output_path, index=False)
        return {
            "status": "success",
            "message": "Forecast saved to model_region_forecast.csv",
            "output_path": output_path,
            "logs": logs
        }
    else:
        return {
            "status": "warning",
            "message": "No forecast data generated.",
            "logs": logs
        }

if __name__ == "__main__":
    print(generate_forecast())