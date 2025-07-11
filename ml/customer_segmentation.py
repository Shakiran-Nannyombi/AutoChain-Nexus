import json
import pandas as pd
from datetime import datetime
from sklearn.cluster import KMeans
from sklearn.preprocessing import StandardScaler
from sqlalchemy import create_engine, text

# Load DB config
with open('ml/db_config.json', 'r') as f:
    config = json.load(f)

# Build SQLAlchemy connection string
DATABASE_URI = f"mysql+pymysql://{config['user']}:{config['password']}@{config['host']}/{config['database']}"
engine = create_engine(DATABASE_URI)

# Extract customer features
query = '''
SELECT c.id AS customer_id,
       COUNT(p.id) AS total_purchases,
       COALESCE(SUM(p.amount), 0) AS total_spent,
       DATEDIFF(CURDATE(), MAX(p.purchase_date)) AS recency
FROM customers c
LEFT JOIN purchases p ON c.id = p.customer_id
GROUP BY c.id
'''
df = pd.read_sql(query, engine)

# Handle customers with no purchases
if not df.empty:
    df['recency'] = df['recency'].fillna(df['recency'].max() if not df['recency'].isnull().all() else 9999)
    df[['total_purchases', 'total_spent']] = df[['total_purchases', 'total_spent']].fillna(0)
    features = df[['total_purchases', 'total_spent', 'recency']]
    scaler = StandardScaler()
    X_scaled = scaler.fit_transform(features)
    n_clusters = 3
    kmeans = KMeans(n_clusters=n_clusters, random_state=42, n_init=10)
    df['segment'] = kmeans.fit_predict(X_scaled) + 1  # Segments start at 1
    # Update the segment column in the customers table
    with engine.begin() as conn:
        for _, row in df.iterrows():
            conn.execute(
                text(f"UPDATE customers SET segment = {int(row['segment'])} WHERE id = {int(row['customer_id'])}")
            )
    print("Segmentation complete. Customers table updated.")
else:
    print("No customers found in the database.")