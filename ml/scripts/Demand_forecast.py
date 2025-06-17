import os
import pandas as pd

# Always find the path relative to this script
base_dir = os.path.dirname(__file__)

# Navigate to ../data/Car_data.csv
csv_path = os.path.join(base_dir, '..', 'data', 'Car_data.csv')

# Normalize path (optional but safe)
csv_path = os.path.abspath(csv_path)

# Read CSV
df = pd.read_csv(csv_path)
# print(df.head())
df['Date'] = pd.to_datetime(df['Date'])  

grouped_df = df.groupby([
    pd.Grouper(key='Date', freq='M'),  # Monthly grouping
    'Model',
    'Dealer_Region'
]).size().reset_index(name='quantity_sold')

grouped_df = grouped_df.rename(columns={'Date': 'ds', 'quantity_sold': 'y'})

print(grouped_df.head())

