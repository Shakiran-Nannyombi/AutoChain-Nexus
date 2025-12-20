import pandas as pd
import numpy as np
from xgboost import XGBRegressor
from sklearn.preprocessing import OneHotEncoder, StandardScaler
from sklearn.compose import ColumnTransformer
import matplotlib.pyplot as plt
import joblib
from datetime import datetime
import seaborn as sns
import os

class DemandForecaster:
    def __init__(self):
        self.model = None
        self.preprocessor = None
        self.demand_data = None
        self.model_path = 'demand_xgb_model.pkl'
        self.preprocessor_path = 'demand_xgb_preprocessor.pkl'
    
    def model_exists(self):
        return os.path.exists(self.model_path) and os.path.exists(self.preprocessor_path)

    def load_model(self):
        self.model = joblib.load(self.model_path)
        self.preprocessor = joblib.load(self.preprocessor_path)

    def save_model(self):
        joblib.dump(self.model, self.model_path)
        joblib.dump(self.preprocessor, self.preprocessor_path)

    def load_data(self):
        base_dir = os.path.dirname(__file__)
        csv_path = os.path.abspath(os.path.join(base_dir, '..', 'data', 'Car_data.csv'))
        try:
            df = pd.read_csv(csv_path)
            df['Date'] = pd.to_datetime(df['Date'], format='%m/%d/%Y')
            return df
        except Exception as e:
            raise ValueError(f"Error loading data: {str(e)}")

    def prepare_features(self, df):
        # Group by month and model only, ignore region
        demand_data = (
            df.groupby([pd.Grouper(key='Date', freq='ME'), 'Model'])
            .agg(Demand=('Demand', 'sum'))
            .reset_index()
        )
        demand_data['Dealer_Region'] = 'all'  # Use a dummy region for compatibility
        demand_data['Month'] = demand_data['Date'].dt.month
        demand_data['Year'] = demand_data['Date'].dt.year
        demand_data['Lag_Demand'] = demand_data.groupby(['Model'])['Demand'].shift(1)
        demand_data = demand_data.dropna()
        return demand_data

    def train(self, df=None):
        if df is None:
            df = self.load_data()
        self.demand_data = self.prepare_features(df)
        self.preprocessor = ColumnTransformer([
            ('cat', OneHotEncoder(handle_unknown='ignore'), ['Model', 'Dealer_Region']),
            ('num', StandardScaler(), ['Month', 'Year', 'Lag_Demand'])
        ])
        X = self.demand_data[['Model', 'Dealer_Region', 'Month', 'Year', 'Lag_Demand']]
        y = self.demand_data['Demand']
        X_processed = self.preprocessor.fit_transform(X)
        self.model = XGBRegressor(
            n_estimators=100,
            max_depth=3,
            random_state=42,
            eval_metric='mae'
        )
        self.model.fit(X_processed, y)
        self.save_model()

    def predict(self, model_name, region=None, months=12):
        # Ignore region, always use 'all'
        region = 'all'
        if not self.model or not self.preprocessor:
            raise ValueError("Model not loaded. Call load_model() or train() first.")
        if self.demand_data is None:
            raise ValueError("Demand data not loaded. Call train() with data or set demand_data.")
        history = self.demand_data[
            (self.demand_data['Model'] == model_name)
        ]
        if history.empty:
            raise ValueError(f"No historical data found for {model_name}")
        last_date = self.demand_data['Date'].max()
        future_dates = pd.date_range(
            start=last_date + pd.DateOffset(months=1),
            periods=months,
            freq='ME'
        )
        pred_data = pd.DataFrame({
            'Date': future_dates,
            'Model': model_name,
            'Dealer_Region': region,
            'Month': future_dates.month,
            'Year': future_dates.year,
            'Lag_Demand': history['Demand'].iloc[-1]
        })
        X_pred = pred_data[['Model', 'Dealer_Region', 'Month', 'Year', 'Lag_Demand']]
        X_processed = self.preprocessor.transform(X_pred)
        pred_data['Predicted_Demand'] = np.round(self.model.predict(X_processed))
        return history, pred_data

    def plot_forecast(self, history, predictions):
        plt.figure(figsize=(12, 6))
        sns.lineplot(x='Date', y='Demand', data=history, label='Historical Demand', marker='o')
        sns.lineplot(x='Date', y='Predicted_Demand', data=predictions, label='Forecasted Demand', marker='o', linestyle='--')
        model_name = predictions['Model'].iloc[0]
        region = predictions['Dealer_Region'].iloc[0]
        plt.title(f"Demand Forecast: {model_name} in {region}")
        plt.xlabel('Date')
        plt.ylabel('Units Sold')
        plt.legend()
        plt.grid(True)
        plt.tight_layout()
        timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
        plot_path = f"forecast_{model_name}_{region}_{timestamp}.png"
        plt.savefig(plot_path)
        plt.close()
        return plot_path

if __name__ == "__main__":
    forecaster = DemandForecaster()
    print("Training model...")
    forecaster.train()
    test_model = "Expedition"
    test_region = "Middletown"
    try:
        print(f"\nGenerating forecast for {test_model} in {test_region}...")
        history, predictions = forecaster.predict(test_model, test_region)
        plot_path = forecaster.plot_forecast(history, predictions)
        print(f"Forecast plot saved to: {plot_path}")
        print("\nPredicted Demand:")
        print(predictions[['Date', 'Predicted_Demand']].to_string(index=False))
    except Exception as e:
        print(f"Error: {str(e)}")