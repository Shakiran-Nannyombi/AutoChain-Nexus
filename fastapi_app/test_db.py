import pymysql
import json
import os

with open(os.path.join(os.path.dirname(__file__), '../ml/db_config.json')) as f:
    db_config = json.load(f)

conn = pymysql.connect(
    host=db_config['host'],
    user=db_config['user'],
    password=db_config['password'],
    database=db_config['database'],
    cursorclass=pymysql.cursors.DictCursor
)
with conn.cursor() as cursor:
    cursor.execute("SELECT COUNT(*) as count FROM vendor_orders WHERE status = 'fulfilled';")
    print(cursor.fetchone())
conn.close() 