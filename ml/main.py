from fastapi import FastAPI
from ml.predict import generate_forecast
from ml.vendor_segmentation import perform_segmentation
import os

app = FastAPI()

@app.get("/")
def read_root():
    return {"status": "online", "service": "Autochain Nexus ML Service"}

@app.post("/predict-demand")
def predict_demand():
    try:
        result = generate_forecast()
        return result
    except Exception as e:
        return {"error": str(e)}

@app.get("/vendor-segmentation")
def vendor_segmentation():
    try:
        result = perform_segmentation()
        return result
    except Exception as e:
        return {"error": str(e)}

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8001)
