import './bootstrap';
import { createRoot } from 'react-dom/client';
import { BrowserRouter } from 'react-router-dom';
import App from './App';
import '../css/app.css';

// Wait for the DOM to be ready
document.addEventListener('DOMContentLoaded', () => {
    const rootElement = document.getElementById('app');
    
    if (rootElement) {
        const root = createRoot(rootElement);
        root.render(
            <BrowserRouter>
                <App />
            </BrowserRouter>
        );
    }
}); 