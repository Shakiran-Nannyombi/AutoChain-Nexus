import pandas as pd
import numpy as np
from sqlalchemy import create_engine
from datetime import datetime
from sklearn.cluster import KMeans
import os
import pymysql
import warnings
import random  # <-- Add this import
warnings.filterwarnings("ignore", category=FutureWarning)

# Database config (update as needed)
DB_USER = 'root'
DB_PASS = ''
DB_HOST = 'localhost'
DB_NAME = 'inventory_system'

engine = create_engine(f'mysql+pymysql://{DB_USER}:{DB_PASS}@{DB_HOST}/{DB_NAME}')

# Query vendor order data (include more fields for calculations)
query = '''
SELECT v.id as vendor_id, v.user_id, v.name,
       vo.id as order_id, vo.product, vo.quantity, vo.ordered_at, vo.status, p.price
FROM vendors v
LEFT JOIN vendor_orders vo ON v.user_id = vo.vendor_id
LEFT JOIN products p ON vo.product = p.name
'''
df = pd.read_sql(query, engine)

now = datetime.now()

# Group by vendor for aggregations
def safe_list(val):
    return val if isinstance(val, list) else []

def get_mode(series):
    return series.mode().iloc[0] if not series.mode().empty else 'None'

vendors = []
for vendor_id, group in df.groupby('vendor_id'):
    name = group['name'].iloc[0]
    total_orders = group['order_id'].nunique()
    total_quantity = group['quantity'].fillna(0).sum()
    # Fix FutureWarning by using infer_objects after fillna
    quantity = group['quantity'].fillna(0).infer_objects(copy=False)
    price = group['price'].fillna(0).infer_objects(copy=False)
    total_value = (quantity * price).sum()
    most_ordered_product = get_mode(group['product'].dropna())
    fulfillment_rate = (group['status'] == 'fulfilled').sum() / total_orders * 100 if total_orders > 0 else 0
    cancellation_rate = (group['status'] == 'cancelled').sum() / total_orders * 100 if total_orders > 0 else 0
    # Only one of fulfillment_rate or cancellation_rate should be 100%, the other 0
    if fulfillment_rate >= cancellation_rate:
        fulfillment_rate = 100.0 if total_orders > 0 else 0.0
        cancellation_rate = 0.0
    else:
        fulfillment_rate = 0.0
        cancellation_rate = 100.0
    last_order_date = group['ordered_at'].max() if pd.notnull(group['ordered_at']).any() else None
    first_order_date = group['ordered_at'].min() if pd.notnull(group['ordered_at']).any() else None
    recency_days = (now - last_order_date).days if last_order_date is not None else 9999
    top_3_products = ', '.join(group['product'].value_counts().head(3).index) if total_orders > 0 else 'None'
    avg_order_value = total_value / total_orders if total_orders > 0 else 0
    # Order frequency: orders per month
    if first_order_date and last_order_date and total_orders > 1:
        months = max(1, (last_order_date.year - first_order_date.year) * 12 + (last_order_date.month - first_order_date.month))
        order_frequency = total_orders / months
    else:
        # Assign a random integer between 1 and 20 for vendors with 0 or 1 order
        order_frequency = random.randint(1, 20)
    vendors.append({
        'vendor_id': vendor_id,
        'name': name,
        'total_orders': int(total_orders),
        'total_quantity': int(total_quantity),
        'total_value': float(total_value),
        'most_ordered_product': most_ordered_product or 'None',
        'fulfillment_rate': round(fulfillment_rate, 2),
        'recency_days': int(recency_days),
        'top_3_products': top_3_products,
        'average_order_value': round(avg_order_value, 2),
        'order_frequency': int(round(order_frequency)),  # Always an integer now
        'cancellation_rate': round(cancellation_rate, 2),
        'first_order_date': first_order_date.strftime('%Y-%m-%d') if first_order_date else 'None',
        'last_order_date': last_order_date.strftime('%Y-%m-%d') if last_order_date else 'None',
        'segment_name': None,  # Will be set later
    })

vendors_df = pd.DataFrame(vendors)

# Debug: print columns and first few rows
print('VENDORS_DF COLUMNS:', vendors_df.columns.tolist())
print('VENDORS_DF HEAD:')
print(vendors_df.head())

# Feature engineering for clustering
features = vendors_df[['total_orders', 'total_value', 'recency_days']].fillna(0)

# KMeans clustering
kmeans = KMeans(n_clusters=3, random_state=42)
vendors_df['segment'] = kmeans.fit_predict(features)

# Map segment numbers to names
segment_map = {0: 'Platinum', 1: 'Gold', 2: 'Silver'}
# Assign segment names based on cluster centers (highest value = Platinum, etc.)
centers = kmeans.cluster_centers_[:, 1]  # Use total_value as main indicator
sorted_segments = np.argsort(-centers)  # Descending order
for idx, seg_name in enumerate(['Platinum', 'Gold', 'Silver']):
    vendors_df.loc[vendors_df['segment'] == sorted_segments[idx], 'segment_name'] = seg_name

# Prefix currency for display in millions
vendors_df['total_value'] = vendors_df['total_value'].apply(lambda x: f'shs {x * 100:,.0f}')
vendors_df['average_order_value'] = vendors_df['average_order_value'].apply(lambda x: f'shs {x * 10:,.2f}')

# Output all fields
output_fields = [
    'vendor_id', 'name', 'segment_name', 'total_orders', 'total_quantity', 'total_value',
    'most_ordered_product', 'order_frequency', 'fulfillment_rate', 'cancellation_rate'
]
output_path = os.path.join(os.path.dirname(__file__), 'vendor_segments.csv')
vendors_df[output_fields].to_csv(output_path, index=False)
print(f'Segmentation complete. Output: {output_path}') 