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
        
    def load_data(self):
        """Load and preprocess the raw data"""
        base_dir = os.path.dirname(__file__)
        csv_path = os.path.abspath(os.path.join(base_dir, '..', 'data', 'Car_data.csv'))
        
        try:
            df = pd.read_csv(csv_path)
            df['Date'] = pd.to_datetime(df['Date'], format='%m/%d/%Y')
            return df
        except Exception as e:
            raise ValueError(f"Error loading data: {str(e)}")

    def prepare_features(self, df):
        """Create time-series features from raw data"""
        # Monthly demand per model/region
        demand_data = (
            df.groupby([pd.Grouper(key='Date', freq='ME'), 'Model', 'Dealer_Region'])
            .agg(Demand=('Customer Name', 'count'))
            .reset_index()
        )
        
        # Feature engineering
        demand_data['Month'] = demand_data['Date'].dt.month
        demand_data['Year'] = demand_data['Date'].dt.year
        demand_data['Lag_Demand'] = demand_data.groupby(['Model', 'Dealer_Region'])['Demand'].shift(1)
        demand_data = demand_data.dropna()
        
        return demand_data

    def train(self):
        """Train the forecasting model"""
        df = self.load_data()
        self.demand_data = self.prepare_features(df)
        
        # Preprocessor pipeline
        self.preprocessor = ColumnTransformer([
            ('cat', OneHotEncoder(handle_unknown='ignore'), 
             ['Model', 'Dealer_Region']),
            ('num', StandardScaler(), 
             ['Month', 'Year', 'Lag_Demand'])
        ])
        
        # Model training
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
        
        # Save artifacts
        self.save_model()

    def save_model(self):
        """Save model and preprocessor"""
        timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
        joblib.dump(self.model, f'model_{timestamp}.pkl')
        joblib.dump(self.preprocessor, f'preprocessor_{timestamp}.pkl')
        print(f"Model saved with timestamp: {timestamp}")

    def predict(self, model_name, region, months=12):
        """Generate demand predictions"""
        if not self.model or not self.preprocessor:
            raise ValueError("Model not trained. Call train() first.")
        if self.demand_data is None:
            raise ValueError("Demand data not loaded. Call train() first.")
            
        # Get historical data
        history = self.demand_data[
            (self.demand_data['Model'] == model_name) & 
            (self.demand_data['Dealer_Region'] == region)
        ]
        
        if history.empty:
            raise ValueError(f"No historical data found for {model_name} in {region}")

        # Generate future dates
        last_date = self.demand_data['Date'].max()
        future_dates = pd.date_range(
            start=last_date + pd.DateOffset(months=1),
            periods=months,
            freq='ME'
        )
        
        # Prepare prediction data
        pred_data = pd.DataFrame({
            'Date': future_dates,
            'Model': model_name,
            'Dealer_Region': region,
            'Month': future_dates.month,
            'Year': future_dates.year,
            'Lag_Demand': history['Demand'].iloc[-1]  # Last known demand
        })
        
        # Make predictions
        X_pred = pred_data[['Model', 'Dealer_Region', 'Month', 'Year', 'Lag_Demand']]
        X_processed = self.preprocessor.transform(X_pred)
        pred_data['Predicted_Demand'] = np.round(self.model.predict(X_processed))
        
        return history, pred_data

    def plot_forecast(self, history, predictions):
        """Generate and save forecast visualization"""
        plt.figure(figsize=(12, 6))
        
        # Historical data
        sns.lineplot(
            x='Date', y='Demand', 
            data=history, 
            label='Historical Demand',
            marker='o'
        )
        
        # Predicted data
        sns.lineplot(
            x='Date', y='Predicted_Demand', 
            data=predictions, 
            label='Forecasted Demand',
            marker='o',
            linestyle='--'
        )
        
        model_name = predictions['Model'].iloc[0]
        region = predictions['Dealer_Region'].iloc[0]
        plt.title(f"Demand Forecast: {model_name} in {region}")
        plt.xlabel('Date')
        plt.ylabel('Units Sold')
        plt.legend()
        plt.grid(True)
        plt.tight_layout()
        
        # Save plot
        timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
        plot_path = f"forecast_{model_name}_{region}_{timestamp}.png"
        plt.savefig(plot_path)
        plt.close()
        
        return plot_path

if __name__ == "__main__":
    # Example usage
    forecaster = DemandForecaster()
    
    # Step 1: Train the model
    print("Training model...")
    forecaster.train()
    
    # Step 2: Make predictions
    test_model = "Expedition"
    test_region = "Middletown"
    
    try:
        print(f"\nGenerating forecast for {test_model} in {test_region}...")
        history, predictions = forecaster.predict(test_model, test_region)
        
        # Step 3: Visualize results
        plot_path = forecaster.plot_forecast(history, predictions)
        print(f"Forecast plot saved to: {plot_path}")
        
        # Print predictions
        print("\nPredicted Demand:")
        print(predictions[['Date', 'Predicted_Demand']].to_string(index=False))
        
    except Exception as e:
        print(f"Error: {str(e)}")