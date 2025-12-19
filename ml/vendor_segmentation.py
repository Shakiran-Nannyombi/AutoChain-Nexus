import pandas as pd
import numpy as np
from sqlalchemy import create_engine
from datetime import datetime
from sklearn.cluster import KMeans
import os
import warnings
import random

warnings.filterwarnings("ignore", category=FutureWarning)

def perform_segmentation():
    # Database config (SQLite)
    db_path = os.path.abspath(os.path.join(os.path.dirname(__file__), '..', 'database', 'database.sqlite'))
    engine = create_engine(f'sqlite:///{db_path}')

    # Query vendor order data (include more fields for calculations)
    query = '''
    SELECT v.id as vendor_id, v.user_id, v.name,
           vo.id as order_id, vo.product, vo.quantity, vo.ordered_at, vo.status, p.price
    FROM vendors v
    LEFT JOIN vendor_orders vo ON v.user_id = vo.vendor_id
    LEFT JOIN products p ON vo.product = p.name
    '''
    try:
        df = pd.read_sql(query, engine)
    except Exception as e:
        print(f"Error reading from database: {e}")
        return {"error": str(e)}

    if df.empty:
        return {"message": "No data found for segmentation."}

    now = datetime.now()

    # Group by vendor for aggregations
    def get_mode(series):
        return series.mode().iloc[0] if not series.mode().empty else 'None'

    vendors = []
    for vendor_id, group in df.groupby('vendor_id'):
        name = group['name'].iloc[0]
        total_orders = group['order_id'].nunique()
        # count valid orders only? keeping logical consistency with original script
        
        # Handle quantity and price safely
        quantity = group['quantity'].fillna(0)
        price = group['price'].fillna(0)
        total_quantity = quantity.sum()
        total_value = (quantity * price).sum()
        
        most_ordered_product = get_mode(group['product'].dropna())
        
        if total_orders > 0:
            fulfillment_rate = (group['status'] == 'fulfilled').sum() / total_orders * 100
            cancellation_rate = (group['status'] == 'cancelled').sum() / total_orders * 100
        else:
            fulfillment_rate = 0
            cancellation_rate = 0

        # Only one of fulfillment_rate or cancellation_rate should be 100%, the other 0 logic from original script
        if fulfillment_rate >= cancellation_rate:
            if total_orders > 0:
                fulfillment_rate = 100.0
                cancellation_rate = 0.0
        else:
            fulfillment_rate = 0.0
            cancellation_rate = 100.0
            
        group['ordered_at'] = pd.to_datetime(group['ordered_at'], errors='coerce')
        last_order_date = group['ordered_at'].max()
        first_order_date = group['ordered_at'].min()
        
        recency_days = (now - last_order_date).days if pd.notnull(last_order_date) else 9999
        top_3_products = ', '.join(group['product'].value_counts().head(3).index) if total_orders > 0 else 'None'
        avg_order_value = total_value / total_orders if total_orders > 0 else 0
        
        # Order frequency: orders per month
        if pd.notnull(first_order_date) and pd.notnull(last_order_date) and total_orders > 1:
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
            'order_frequency': int(round(order_frequency)),
            'cancellation_rate': round(cancellation_rate, 2),
            'first_order_date': first_order_date.strftime('%Y-%m-%d') if pd.notnull(first_order_date) else 'None',
            'last_order_date': last_order_date.strftime('%Y-%m-%d') if pd.notnull(last_order_date) else 'None',
            'segment_name': None,
        })

    vendors_df = pd.DataFrame(vendors)

    if vendors_df.empty:
         return {"message": "No vendor data constructed."}

    # Feature engineering for clustering
    features = vendors_df[['total_orders', 'total_value', 'recency_days']].fillna(0)

    # KMeans clustering
    # Check if we have enough samples for KMeans
    n_samples = features.shape[0]
    n_clusters = 3
    if n_samples < n_clusters:
        n_clusters = n_samples # Adjust clusters if fewer samples

    if n_clusters > 0:
        kmeans = Kmeans = KMeans(n_clusters=n_clusters, random_state=42)
        vendors_df['segment'] = kmeans.fit_predict(features)
        
        # Determine segment names based on logic (Platinum = highest value)
        # We need to robustly map this. 
        # Simple heuristic: Rank clusters by average total_value
        cluster_avg_value = vendors_df.groupby('segment')['total_value'].mean().sort_values(ascending=False)
        
        sorted_segments = cluster_avg_value.index.tolist() # [best_segment_id, medium_segment_id, worst_segment_id]
        segment_names = ['Platinum', 'Gold', 'Silver']
        
        mapping = {}
        for i, seg_id in enumerate(sorted_segments):
            if i < len(segment_names):
                mapping[seg_id] = segment_names[i]
            else:
                 mapping[seg_id] = 'Bronze' # Fallback
                 
        vendors_df['segment_name'] = vendors_df['segment'].map(mapping)
    else:
         vendors_df['segment_name'] = 'Unsegmented'


    # Formatting
    vendors_dict = vendors_df.to_dict(orient='records')
    
    # Save to CSV as requested by original script?
    output_fields = [
        'vendor_id', 'name', 'segment_name', 'total_orders', 'total_quantity', 'total_value',
        'most_ordered_product', 'order_frequency', 'fulfillment_rate', 'cancellation_rate'
    ]
    output_path = os.path.join(os.path.dirname(__file__), 'vendor_segments.csv')
    vendors_df[output_fields].to_csv(output_path, index=False)
    
    return {"status": "success", "file": output_path, "count": len(vendors_df)}

if __name__ == "__main__":
    perform_segmentation()