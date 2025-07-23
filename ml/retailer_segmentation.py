import os
import pandas as pd
from sqlalchemy import create_engine
from sklearn.cluster import KMeans
import json
from datetime import datetime
import numpy as np

# Use absolute path for db_config.json
base_dir = os.path.dirname(os.path.abspath(__file__))
config_path = os.path.join(base_dir, 'db_config.json')
with open(config_path, 'r') as f:
    config = json.load(f)
DATABASE_URI = f"mysql+pymysql://{config['user']}:{config['password']}@{config['host']}/{config['database']}"
engine = create_engine(DATABASE_URI)

# Query retailer order data, joining users for retailer info
query = '''
SELECT u.id as retailer_id, u.name,
       ro.id as order_id, ro.car_model, ro.quantity, ro.ordered_at, ro.status, ro.total_amount, ro.vendor_id
FROM users u
INNER JOIN retailer_orders ro ON u.id = ro.user_id
WHERE u.role = 'retailer' AND u.status = 'approved'
'''
df = pd.read_sql(query, engine)

# After loading df from SQL (should have columns: retailer_id, name, order_id, total_amount, ordered_at, ...)

# Compute features for each retailer
agg = df.groupby(['retailer_id', 'name']).agg(
    total_orders=('order_id', 'count'),
    total_value=('total_amount', 'sum'),
    recency_days=('ordered_at', lambda x: (pd.Timestamp.now() - pd.to_datetime(x).max()).days if len(x) > 0 else np.nan)
).reset_index()

# Use agg as the DataFrame for clustering
features = agg[['total_orders', 'total_value', 'recency_days']].fillna(0)

if features.shape[0] == 0:
    print("No retailers with orders found. Exiting.")
    exit()
# Determine number of clusters dynamically
n_clusters = min(3, len(agg)) if len(agg) > 0 else 1
kmeans = KMeans(n_clusters=n_clusters, random_state=42)
agg['segment'] = kmeans.fit_predict(features)
# Assign segment names robustly based on number of clusters
possible_names = ['High Value', 'Occasional', 'At Risk']
num_clusters = n_clusters
sorted_segments = agg.groupby('segment')['total_value'].mean().sort_values(ascending=False).index
for idx, seg in enumerate(sorted_segments):
    seg_name = possible_names[idx] if idx < len(possible_names) else f'Segment {idx+1}'
    agg.loc[agg['segment'] == seg, 'segment_name'] = seg_name

# Compute total_quantity
agg['total_quantity'] = df.groupby(['retailer_id', 'name'])['quantity'].sum().values

# Compute average_order_value
agg['average_order_value'] = agg['total_value'] / agg['total_orders']
agg['average_order_value'] = agg['average_order_value'].fillna(0)

# Compute order_frequency (orders per month)
def compute_order_frequency(group):
    if group['ordered_at'].count() > 1:
        first = pd.to_datetime(group['ordered_at']).min()
        last = pd.to_datetime(group['ordered_at']).max()
        months = max(1, (last.year - first.year) * 12 + (last.month - first.month))
        return group['order_id'].count() / months
    else:
        return 1
agg['order_frequency'] = df.groupby(['retailer_id', 'name']).apply(compute_order_frequency).values

output_fields = [
    'retailer_id', 'name', 'segment_name', 'total_orders', 'total_quantity', 'total_value',
    'order_frequency', 'recency_days', 'average_order_value'
]
output_path = os.path.join(base_dir, 'retailer_segments.csv')
agg[output_fields].to_csv(output_path, index=False)
print(f'Segmentation complete. Output: {output_path}') 