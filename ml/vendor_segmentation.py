import pandas as pd
import numpy as np
from sqlalchemy import create_engine
from datetime import datetime
from sklearn.cluster import KMeans

# Database config (update as needed)
DB_USER = 'root'
DB_PASS = ''
DB_HOST = 'localhost'
DB_NAME = 'inventory_system'

engine = create_engine(f'mysql+pymysql://{DB_USER}:{DB_PASS}@{DB_HOST}/{DB_NAME}')

# Query vendor order data
query = '''
SELECT v.id as vendor_id, v.user_id, v.name,
       COUNT(vo.id) as total_orders,
       COALESCE(SUM(vo.quantity), 0) as total_quantity,
       COALESCE(SUM(vo.quantity * p.price), 0) as total_value,
       MAX(vo.ordered_at) as last_order_date
FROM vendors v
LEFT JOIN vendor_orders vo ON v.user_id = vo.vendor_id
LEFT JOIN products p ON vo.product = p.name
GROUP BY v.id, v.user_id, v.name
'''
df = pd.read_sql(query, engine)

# Feature engineering
now = datetime.now()
df['recency_days'] = df['last_order_date'].apply(lambda x: (now - x).days if pd.notnull(x) else 9999)
features = df[['total_orders', 'total_value', 'recency_days']].fillna(0)

# KMeans clustering
kmeans = KMeans(n_clusters=3, random_state=42)
df['segment'] = kmeans.fit_predict(features)

# Output segments
output = df[['vendor_id', 'segment']]
output.to_csv('vendor_segments.csv', index=False)
print('Segmentation complete. Output: ml/vendor_segments.csv') 