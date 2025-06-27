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

df = pd.read_csv("car_data.csv")
df['Date'] = pd.to_datetime(df['Date'])

filtered = df[
    (df['Model'] == 'Camry') & 
    (df['Dealer_Region'] == 'Toronto')
]

print(len(filtered))
print(filtered[['Date', 'Model', 'Dealer_Region']])
