import os
import pandas as pd
from prophet import Prophet
import matplotlib.pyplot as plt

# Always find the path relative to this script
base_dir = os.path.dirname(__file__)

# Navigate to ../data/Car_data.csv
csv_path = os.path.join(base_dir, '..', 'data', 'Car_data.csv')

# Normalize path (optional but safe)
csv_path = os.path.abspath(csv_path)

# Read CSV
df = pd.read_csv(csv_path)


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
    except Exception as e:
        print(f"Error with {model_name} in {region_name}: {e}")
        continue

# STEP 6: Combine all forecasts into one DataFrame
if forecast_results:
    final_forecast_df = pd.concat(forecast_results, ignore_index=True)
    final_forecast_df.to_csv("model_region_forecast.csv", index=False)
    print("\n\n\n\nForecast saved to model_region_forecast.csv")
else:
    print("\n\n\nNo forecast data generated.")
    
filtered = df[
    (df['Model'] == 'Camry') & 
    (df['Dealer_Region'] == 'Toronto')
]

print(len(filtered))
print(filtered[['Date', 'Model', 'Dealer_Region']])
