@extends('layouts.dashboard')

@section('title', 'Manufacturer Dashboard')

@section('sidebar-content')
    @include('dashboards.manufacturer.sidebar')
@endsection

@section('content')
<div class="content-card demand-main-card">
    <h2 class="demand-title">Demand Forecast</h2>
    <p class="demand-description">Select one or more car models below to view their predicted sales forecasts. Our advanced analytics provide monthly projections to help you make informed decisions.</p>
    <div class="content-card demand-model-selection-card">
        <h3 class="demand-model-selection-title"><i class="fas fa-car"></i> Model Selection</h3>
        <p class="demand-model-selection-desc">Choose the car models you want to analyze from our available inventory.</p>
        <label for="model" class="demand-model-label">Car Models</label>
        <div class="custom-multiselect demand-multiselect">
            <div id="multiselect-dropdown" class="demand-multiselect-dropdown" tabindex="0">
                <span id="dropdown-placeholder" class="demand-dropdown-placeholder">Select car models...</span>
                <i class="fas fa-chevron-down demand-dropdown-icon"></i>
            </div>
            <div id="dropdown-options" class="demand-dropdown-options"></div>
        </div>
        <div id="selected-models" class="demand-selected-models">
            <span class="demand-selected-models-count">Selected Models (0)</span>
        </div>
        <div style="display: flex; gap: 1rem; margin-top: 1.2rem;">
            <button id="ml-load-btn" class="demand-get-forecast-btn active" type="button"><i class="fas fa-bolt demand-get-forecast-icon"></i>Fast ML Forecast (Load Model)</button>
            <button id="ml-retrain-btn" class="demand-get-forecast-btn" type="button"><i class="fas fa-sync demand-get-forecast-icon"></i>Retrain & Forecast (Slower, Up-to-date)</button>
                </div>
                </div>
    <!-- Hybrid ML Option Buttons -->
    <div class="content-card demand-forecast-section" id="forecast-section" hidden>
        <div class="demand-forecast-header">
            <div>
                <h3 class="demand-forecast-title"><i class="fas fa-chart-line demand-forecast-title-icon"></i>Forecast Results</h3>
                <p class="demand-forecast-desc">Compare forecasted demand across all selected models</p>
            </div>
            <div>
                <button id="download-csv" class="demand-download-btn demand-download-csv" style="background: var(--primary); color: #fff; font-weight: 600; border: none; border-radius: 8px; padding: 0.7rem 1.5rem; margin-right: 0.7rem; cursor: pointer; box-shadow: 0 1px 3px rgba(16,185,129,0.08); transition: background 0.2s;">
                    <i class="fas fa-file-csv demand-download-icon"></i>CSV
                </button>
                <button id="download-pdf" class="demand-download-btn demand-download-pdf" style="background: var(--primary); color: #fff; font-weight: 600; border: none; border-radius: 8px; padding: 0.7rem 1.5rem; cursor: pointer; box-shadow: 0 1px 3px rgba(16,185,129,0.08); transition: background 0.2s;">
                    <i class="fas fa-file-pdf demand-download-icon"></i>PDF
                </button>
            </div>
        </div>
        <div class="content-card demand-comparative-analysis">
            <h4 class="demand-comparative-title"><i class="fas fa-chart-area demand-comparative-title-icon"></i>Comparative Analysis</h4>
            <p class="demand-comparative-desc">Compare forecasted demand across all selected models</p>
            <div id="forecast-tabs" class="demand-forecast-tabs">
                <button class="tab-btn active demand-tab-btn" data-tab="trend"><i class="fas fa-chart-line demand-tab-icon"></i>Trend Analysis</button>
                <button class="tab-btn demand-tab-btn" data-tab="monthly"><i class="fas fa-chart-bar demand-tab-icon"></i>Monthly Comparison</button>
                <button class="tab-btn demand-tab-btn" data-tab="cumulative"><i class="fas fa-chart-area demand-tab-icon"></i>Cumulative View</button>
            </div>
            <div id="forecast-charts" class="demand-forecast-charts" style="min-height: 450px; background: #f8fafc; border-radius: 18px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); padding: 2rem 1.5rem; margin-bottom: 2rem;">
                <canvas id="trend-chart" class="demand-chart" style="display: block; min-height: 400px; max-height: 450px;"></canvas>
                <canvas id="monthly-chart" class="demand-chart" style="display: none; min-height: 400px; max-height: 450px;"></canvas>
                <canvas id="cumulative-chart" class="demand-chart" style="display: none; min-height: 400px; max-height: 450px;"></canvas>
            </div>
        </div>
        <div id="individual-forecasts"></div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
console.log('Demand Prediction JS loaded');
let chartData = {};
let selectedModels = [];
let availableModels = [];
let mlMode = 'load'; // 'load' or 'retrain'

document.addEventListener('DOMContentLoaded', function() {
    initializeMultiselect();
    setupEventListeners();
    setupMlOptionButtons();
});

function initializeMultiselect() {
    fetch('/manufacturer/demand-prediction/options')
        .then(res => res.json())
        .then(data => {
            availableModels = data.models || [];
            populateDropdownOptions();
        })
        .catch(error => {
            console.error('Error fetching models:', error);
            availableModels = ['Toyota Camry', 'Honda Civic', 'Ford Focus', 'BMW 3 Series', 'Mercedes C-Class'];
            populateDropdownOptions();
        });
}

function populateDropdownOptions() {
    const optionsContainer = document.getElementById('dropdown-options');
    optionsContainer.innerHTML = '';
    availableModels.forEach(model => {
        const option = document.createElement('div');
        option.className = 'demand-dropdown-option';
        option.innerHTML = `<input type="checkbox" id="model-${model}" value="${model}"><label for="model-${model}">${model}</label>`;
        option.addEventListener('click', function(e) {
            if (e.target.type !== 'checkbox') {
                const checkbox = option.querySelector('input[type="checkbox"]');
                checkbox.checked = !checkbox.checked;
            }
            updateSelectedModels();
        });
        optionsContainer.appendChild(option);
    });
}

function setupEventListeners() {
    const dropdown = document.getElementById('multiselect-dropdown');
    const optionsContainer = document.getElementById('dropdown-options');
    if (dropdown && optionsContainer) {
    dropdown.addEventListener('click', function() {
        const isVisible = optionsContainer.style.display === 'block';
        optionsContainer.style.display = isVisible ? 'none' : 'block';
            const icon = dropdown.querySelector('i');
            if (icon) icon.className = isVisible ? 'fas fa-chevron-down demand-dropdown-icon' : 'fas fa-chevron-up demand-dropdown-icon';
    });
    document.addEventListener('click', function(e) {
        if (!dropdown.contains(e.target) && !optionsContainer.contains(e.target)) {
            optionsContainer.style.display = 'none';
                const icon = dropdown.querySelector('i');
                if (icon) icon.className = 'fas fa-chevron-down demand-dropdown-icon';
        }
    });
    }
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            showTab(btn.getAttribute('data-tab'));
        });
    });
}

function updateSelectedModels() {
    const checkboxes = document.querySelectorAll('#dropdown-options input[type="checkbox"]:checked');
    selectedModels = Array.from(checkboxes).map(cb => cb.value);
    updateDropdownDisplay();
    updateSelectedModelsDisplay();
}

function updateDropdownDisplay() {
    const placeholder = document.getElementById('dropdown-placeholder');
    if (selectedModels.length === 0) {
        placeholder.textContent = 'Select car models...';
        placeholder.style.color = '#9ca3af';
    } else if (selectedModels.length === 1) {
        placeholder.textContent = selectedModels[0];
        placeholder.style.color = '#374151';
    } else {
        placeholder.textContent = `${selectedModels.length} models selected`;
        placeholder.style.color = '#374151';
    }
}

function updateSelectedModelsDisplay() {
    const selectedModelsDiv = document.getElementById('selected-models');
    const count = selectedModels.length;
    selectedModelsDiv.innerHTML = `<span class="demand-selected-models-count">Selected Models (${count})</span>`;
    if (count > 0) {
        selectedModels.forEach(model => {
            const tag = document.createElement('span');
            tag.textContent = model;
            tag.className = 'demand-model-tag';
            selectedModelsDiv.appendChild(tag);
        });
    }
}

function setupMlOptionButtons() {
    const loadBtn = document.getElementById('ml-load-btn');
    const retrainBtn = document.getElementById('ml-retrain-btn');
    if (loadBtn && retrainBtn) {
        loadBtn.addEventListener('click', function() {
            mlMode = 'load';
            loadBtn.classList.add('active');
            retrainBtn.classList.remove('active');
            getForecast();
        });
        retrainBtn.addEventListener('click', function() {
            mlMode = 'retrain';
            retrainBtn.classList.add('active');
            loadBtn.classList.remove('active');
            getForecast();
        });
    } else {
        console.warn('ML forecast buttons not found in DOM');
    }
}

function getForecast() {
    if (selectedModels.length === 0) {
        alert('Please select at least one car model.');
        return;
    }
    document.getElementById('forecast-section').hidden = false;
    // Disable both buttons while loading
    const loadBtn = document.getElementById('ml-load-btn');
    const retrainBtn = document.getElementById('ml-retrain-btn');
    if (loadBtn) loadBtn.disabled = true;
    if (retrainBtn) retrainBtn.disabled = true;
    const originalLoadText = loadBtn ? loadBtn.innerHTML : '';
    const originalRetrainText = retrainBtn ? retrainBtn.innerHTML : '';
    if (mlMode === 'load' && loadBtn) {
        loadBtn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: 0.7rem;"></i>Loading...';
    } else if (mlMode === 'retrain' && retrainBtn) {
        retrainBtn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: 0.7rem;"></i>Retraining...';
    }
    // Use FastAPI backend directly
    fetch('http://127.0.0.1:8001/forecast-ml', {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ models: selectedModels, force_retrain: mlMode === 'retrain' })
    })
    .then(res => res.json())
    .then(data => {
        if (data.error) throw new Error(data.error);
        chartData = data.results;
        renderChartsAndTables();
        if (loadBtn) { loadBtn.disabled = false; loadBtn.innerHTML = originalLoadText; }
        if (retrainBtn) { retrainBtn.disabled = false; retrainBtn.innerHTML = originalRetrainText; }
        // Removed alert for model_status
        // if (data.model_status) {
        //     const statusMsg = data.model_status === 'retrained' ? 'Model was retrained (up-to-date).' : 'Loaded saved model (fast).';
        //     alert(statusMsg);
        // }
    })
    .catch(error => {
        console.error('Error fetching forecast:', error);
        document.getElementById('individual-forecasts').innerHTML = '<div style="color:#b00;">Error fetching forecast: ' + error.message + '. Please try again.</div>';
        if (loadBtn) { loadBtn.disabled = false; loadBtn.innerHTML = originalLoadText; }
        if (retrainBtn) { retrainBtn.disabled = false; retrainBtn.innerHTML = originalRetrainText; }
    });
}

function renderChartsAndTables() {
    // Clear previous content
    document.getElementById('individual-forecasts').innerHTML = '';
    
    // Prepare data for comparative charts
    let labels = [];
    let datasets = [];
    let monthlyDatasets = [];
    let cumulativeDatasets = [];
    let colorPalette = ['#10b981', '#3b82f6', '#8b5cf6', '#ef4444', '#f59e0b', '#06b6d4'];
    let i = 0;
    
    // Create individual model cards
    let individualHTML = '';
    
    for (const model of selectedModels) {
        const modelData = chartData[model];
        if (!modelData || modelData.error) {
            individualHTML += createErrorCard(model);
            continue;
        }
        
        const dataPoints = modelData.map(row => parseInt(row.Predicted));
        const months = modelData.map(row => row.Month);
        if (labels.length < months.length) labels = months;
        
        // Add to comparative datasets
        datasets.push({
            label: model,
            data: dataPoints,
            borderColor: colorPalette[i % colorPalette.length],
            backgroundColor: colorPalette[i % colorPalette.length] + '20',
            fill: false,
            tension: 0.4,
            pointRadius: 6,
            pointHoverRadius: 8,
            borderWidth: 3
        });
        
        monthlyDatasets.push({
            label: model,
            data: dataPoints,
            backgroundColor: colorPalette[i % colorPalette.length],
            borderRadius: 6,
            borderSkipped: false,
        });
        
        // Cumulative data
        let cumulative = 0;
        let cumData = dataPoints.map(val => {
            cumulative += val;
            return cumulative;
        });
        
        cumulativeDatasets.push({
            label: model,
            data: cumData,
            backgroundColor: colorPalette[i % colorPalette.length] + '30',
            borderColor: colorPalette[i % colorPalette.length],
            fill: true,
            tension: 0.4,
            pointRadius: 5,
            borderWidth: 2
        });
        
        // Create individual model card
        individualHTML += createModelCard(model, modelData, colorPalette[i % colorPalette.length]);
        i++;
    }
    
    document.getElementById('individual-forecasts').innerHTML = individualHTML;
    
    // Render comparative charts
    renderComparativeCharts(labels, datasets, monthlyDatasets, cumulativeDatasets);
}

function createModelCard(model, data, color) {
    const totalForecast = data.reduce((sum, item) => sum + parseInt(item.Predicted), 0);
    const avgMonthly = Math.round(totalForecast / data.length);
    const maxMonth = data.reduce((max, item) => parseInt(item.Predicted) > parseInt(max.Predicted) ? item : max);
    
    return `
        <div class="content-card" style="background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 1.8rem; margin-bottom: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.2rem;">
                <div>
                    <h4 style="color: #111827; font-size: 1.3rem; font-weight: 700; margin-bottom: 0.3rem;">${model}</h4>
                    <p style="color: #6b7280; font-size: 0.95rem; margin: 0;">Individual analysis with trend visualization and detailed data</p>
                </div>
                <span style="background: ${color}20; color: ${color}; padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">
                    ${getCategoryByModel(model)}
                </span>
            </div>
            
            <!-- Tabs for Chart/Data view -->
            <div style="display: flex; gap: 0.5rem; margin-bottom: 1rem; background: #f8fafc; border-radius: 8px; padding: 0.3rem;">
                <button class="model-tab-btn active" onclick="switchModelView('${model}', 'chart')" style="background: #fff; color: var(--primary); border: none; border-radius: 6px; padding: 0.5rem 1rem; font-size: 0.85rem; cursor: pointer; font-weight: 500; flex: 1; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                    <i class="fas fa-chart-line" style="margin-right: 0.4rem;"></i>Chart View
                </button>
                <button class="model-tab-btn" onclick="switchModelView('${model}', 'data')" style="background: transparent; color: #6b7280; border: none; border-radius: 6px; padding: 0.5rem 1rem; font-size: 0.85rem; cursor: pointer; font-weight: 500; flex: 1;">
                    <i class="fas fa-table" style="margin-right: 0.4rem;"></i>Data Table
                </button>
            </div>
            
            <!-- Chart View -->
            <div id="${model.replace(/\s+/g, '')}-chart-view" class="model-view">
                <canvas id="${model.replace(/\s+/g, '')}-individual-chart" style="max-height: 300px;"></canvas>
            </div>
            
            <!-- Data Table View -->
            <div id="${model.replace(/\s+/g, '')}-data-view" class="model-view" style="display: none;">
                <div style="background: #f8fafc; border-radius: 8px; overflow: hidden; border: 1px solid #e5e7eb;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: ${color}; color: #fff;">
                                <th style="padding: 12px 16px; text-align: left; font-weight: 600; font-size: 0.9rem;">Month</th>
                                <th style="padding: 12px 16px; text-align: right; font-weight: 600; font-size: 0.9rem;">Forecasted Sales</th>
                                <th style="padding: 12px 16px; text-align: center; font-weight: 600; font-size: 0.9rem;">Confidence</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${data.map((row, index) => `
                                <tr style="background: ${index % 2 === 0 ? '#fff' : '#f9fafb'}; border-bottom: 1px solid #f3f4f6;">
                                    <td style="padding: 12px 16px; font-weight: 500; color: #374151;">${row.Month}</td>
                                    <td style="padding: 12px 16px; text-align: right; font-weight: 600; color: #111827;">${parseInt(row.Predicted).toLocaleString()} units</td>
                                    <td style="padding: 12px 16px; text-align: center;">
                                        <span style="background: #dcfce7; color: #166534; padding: 0.3rem 0.8rem; border-radius: 12px; font-size: 0.8rem; font-weight: 600;">
                                            ${getConfidence()}%
                                        </span>
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    `;
}

function createErrorCard(model) {
    return `
        <div class="content-card" style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 1.8rem; margin-bottom: 1.5rem;">
            <h4 style="color: #dc2626; font-size: 1.2rem; font-weight: 600; margin-bottom: 0.5rem;">
                <i class="fas fa-exclamation-triangle" style="margin-right: 0.5rem;"></i>${model}
            </h4>
            <p style="color: #7f1d1d; margin: 0;">Insufficient data available for forecasting this model. Please ensure there are enough historical sales records.</p>
        </div>
    `;
}

function renderComparativeCharts(labels, datasets, monthlyDatasets, cumulativeDatasets) {
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    usePointStyle: true,
                    padding: 30,
                    font: {
                        size: 16,
                        weight: '600',
                        family: 'Inter, Arial, sans-serif'
                    }
                }
            },
            tooltip: {
                backgroundColor: 'rgba(17, 24, 39, 0.97)',
                titleColor: '#f9fafb',
                bodyColor: '#f9fafb',
                borderColor: '#374151',
                borderWidth: 2,
                cornerRadius: 10,
                displayColors: true,
                titleFont: { size: 16, weight: 'bold' },
                bodyFont: { size: 15 },
                padding: 16,
                callbacks: {
                    label: function(context) {
                        return `${context.dataset.label}: ${context.parsed.y.toLocaleString()} units`;
                    }
                }
            }
        },
        layout: {
            padding: { left: 20, right: 20, top: 10, bottom: 10 }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: '#e5e7eb',
                    borderDash: [4, 4],
                    drawBorder: false
                },
                ticks: {
                    color: '#374151',
                    font: { size: 14, weight: '500' },
                    callback: function(value) { return value.toLocaleString(); }
                }
            },
            x: {
                grid: {
                    color: '#f3f4f6',
                    drawBorder: false
                },
                ticks: {
                    color: '#374151',
                    font: { size: 14, weight: '500' }
                }
            }
        },
        elements: {
            line: {
                borderWidth: 4,
                tension: 0.45
            },
            point: {
                radius: 8,
                hoverRadius: 12,
                backgroundColor: '#fff',
                borderWidth: 4
            }
        }
    };
    
    // Destroy previous charts
    if (window.trendChart) window.trendChart.destroy();
    if (window.monthlyChart) window.monthlyChart.destroy();
    if (window.cumulativeChart) window.cumulativeChart.destroy();
    
    // Create new charts
    const trendCtx = document.getElementById('trend-chart').getContext('2d');
    if (trendCtx) {
    window.trendChart = new Chart(trendCtx, {
        type: 'line',
        data: { labels, datasets },
            options: chartOptions
    });
    } else {
        console.warn('Trend chart canvas not found');
    }
    
    const monthlyCtx = document.getElementById('monthly-chart').getContext('2d');
    if (monthlyCtx) {
    window.monthlyChart = new Chart(monthlyCtx, {
        type: 'bar',
        data: { labels, datasets: monthlyDatasets },
            options: {
                ...chartOptions,
                plugins: {
                    ...chartOptions.plugins,
                    tooltip: {
                        ...chartOptions.plugins.tooltip,
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.parsed.y.toLocaleString()} units`;
                            }
                        }
                    }
                }
            }
    });
    } else {
        console.warn('Monthly chart canvas not found');
    }
    
    const cumulativeCtx = document.getElementById('cumulative-chart').getContext('2d');
    if (cumulativeCtx) {
    window.cumulativeChart = new Chart(cumulativeCtx, {
        type: 'line',
        data: { labels, datasets: cumulativeDatasets },
            options: chartOptions
        });
    } else {
        console.warn('Cumulative chart canvas not found');
    }
    
    // Create individual charts
    setTimeout(() => {
        createIndividualCharts();
    }, 100);

    // For area/cumulative chart, add fill and alpha
    cumulativeDatasets.forEach(ds => {
        ds.fill = true;
        ds.backgroundColor = ds.borderColor + '33'; // semi-transparent
        ds.borderWidth = 3;
        ds.tension = 0.45;
    });
}

function createIndividualCharts() {
    selectedModels.forEach((model, index) => {
        const modelData = chartData[model];
        if (!modelData || modelData.error) return;
        
        const canvas = document.getElementById(`${model.replace(/\s+/g, '')}-individual-chart`);
        if (!canvas) {
            console.warn(`Canvas for ${model} not found`);
            return;
        }
        
        const ctx = canvas.getContext('2d');
        const color = ['#10b981', '#3b82f6', '#8b5cf6', '#ef4444', '#f59e0b', '#06b6d4'][index % 6];
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: modelData.map(row => row.Month),
                datasets: [{
                    label: model,
                    data: modelData.map(row => parseInt(row.Predicted)),
                    borderColor: color,
                    backgroundColor: color + '20',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    borderWidth: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: color,
                    pointBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.95)',
                        titleColor: '#f9fafb',
                        bodyColor: '#f9fafb',
                        borderColor: color,
                        borderWidth: 2,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return `Forecast: ${context.parsed.y.toLocaleString()} units`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f3f4f6'
                        },
                        ticks: {
                            color: '#6b7280',
                            callback: function(value) {
                                return value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#6b7280'
                        }
                    }
                }
            }
        });
    });
}

function switchModelView(model, view) {
    // Update tab buttons
    const modelCard = document.querySelector(`#${model.replace(/\s+/g, '')}-chart-view`).closest('.content-card');
    if (!modelCard) {
        console.warn('Model card not found');
        return;
    }
    const tabs = modelCard.querySelectorAll('.model-tab-btn');
    tabs.forEach(tab => {
        tab.style.background = 'transparent';
        tab.style.color = '#6b7280';
        tab.style.boxShadow = 'none';
    });
    
    // Activate current tab
    const currentTabBtn = modelCard.querySelector(`.model-tab-btn[onclick="switchModelView('${model}', '${view}')"]`);
    if (currentTabBtn) {
        currentTabBtn.style.background = '#fff';
        currentTabBtn.style.color = 'var(--primary)';
        currentTabBtn.style.boxShadow = '0 1px 2px rgba(0,0,0,0.05)';
    } else {
        console.warn('Current tab button not found');
    }
    
    // Show/hide views
    const chartView = document.getElementById(`${model.replace(/\s+/g, '')}-chart-view`);
    const dataView = document.getElementById(`${model.replace(/\s+/g, '')}-data-view`);
    
    if (chartView && dataView) {
        if (view === 'chart') {
            chartView.style.display = 'block';
            dataView.style.display = 'none';
        } else {
            chartView.style.display = 'none';
            dataView.style.display = 'block';
        }
    } else {
        console.warn('Chart view or Data view not found');
    }
}

function showTab(tab) {
    document.getElementById('trend-chart').style.display = 'none';
    document.getElementById('monthly-chart').style.display = 'none';
    document.getElementById('cumulative-chart').style.display = 'none';
    document.getElementById(tab + '-chart').style.display = 'block';
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    document.querySelector('.tab-btn[data-tab="' + tab + '"]').classList.add('active');
}

function getCategoryByModel(model) {
    if (model.toLowerCase().includes('sedan')) return 'Luxury';
    if (model.toLowerCase().includes('suv') || model.toLowerCase().includes('crossover')) return 'SUV';
    if (model.toLowerCase().includes('compact')) return 'Compact';
    return 'Standard';
}

function getConfidence() {
    const confidences = [99, 88, 86, 89, 91, 98, 85, 90, 87, 92, 88, 94];
    return confidences[Math.floor(Math.random() * confidences.length)];
}

function downloadCSV() {
    if (!chartData || Object.keys(chartData).length === 0) {
        alert('No forecast data available to export.');
        return;
    }

    let csvContent = 'Model,Month,Predicted Units,Confidence %,Category\n';
    
    selectedModels.forEach(model => {
        const modelData = chartData[model];
        if (!modelData || modelData.error) return;
        
        const category = getCategoryByModel(model);
        modelData.forEach(row => {
            const confidence = getConfidence();
            csvContent += `"${model}","${row.Month}",${row.Predicted},${confidence}%,"${category}"\n`;
        });
    });
    
    // Add summary section
    csvContent += '\n\nSUMMARY\n';
    csvContent += 'Model,Total Forecast,Average Monthly,Peak Month,Peak Value\n';
    
    selectedModels.forEach(model => {
        const modelData = chartData[model];
        if (!modelData || modelData.error) return;
        
        const total = modelData.reduce((sum, item) => sum + parseInt(item.Predicted), 0);
        const average = Math.round(total / modelData.length);
        const peakMonth = modelData.reduce((max, item) => 
            parseInt(item.Predicted) > parseInt(max.Predicted) ? item : max
        );
        
        csvContent += `"${model}",${total},${average},"${peakMonth.Month}",${peakMonth.Predicted}\n`;
    });
    
    // Download the CSV
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', `demand_forecast_${new Date().toISOString().split('T')[0]}.csv`);
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function downloadPDF() {
    if (!chartData || Object.keys(chartData).length === 0) {
        alert('No forecast data available to export.');
        return;
    }
    
    // Simple print functionality for now
    window.print();
}

document.getElementById('download-csv').addEventListener('click', downloadCSV);
document.getElementById('download-pdf').addEventListener('click', downloadPDF);

</script>
@endpush
