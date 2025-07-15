@extends('layouts.dashboard')

@section('title', 'Vendor Dashboard')

@section('sidebar-content')
    @include('dashboards.vendor.sidebar')
@endsection

@section('content')
<div class="content-card vendor-delivery-dashboard">
    <h2 style="color: var(--primary); margin-bottom: 1.5rem; font-size: 1.8rem;">
        Car Delivery Management
    </h2>
    <!-- Stat Tabs Row -->
    <div class="delivery-stat-tabs" style="display: flex; gap: 1.5rem; margin-bottom: 1.2rem;">
        <div class="stat-tab active" data-tab="shipments"><i class="fas fa-truck"></i> Total Shipments</div>
        <div class="stat-tab" data-tab="process"><i class="fas fa-shipping-fast"></i> On Process</div>
        <div class="stat-tab" data-tab="success"><i class="fas fa-check-circle"></i> Delivery Success</div>
        <div class="stat-tab" data-tab="proceeds"><i class="fas fa-dollar-sign"></i> Proceeds</div>
    </div>
    <div id="statTabContent">
        <!-- Detailed cards for each stat -->
        <div class="stat-detail-card" data-content="shipments" style="display: block; background: #fff; border-radius: 14px; box-shadow: var(--shadow); padding: 1.5rem 2.5rem; margin-bottom: 2rem;">
            <div style="color: var(--primary); font-weight: 700; font-size: 1.2rem;"><i class="fas fa-truck"></i> Total Shipments</div>
            <div style="font-size: 2.5rem; font-weight: bold; color: var(--text-dark); margin: 0.5rem 0;">12,930</div>
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span class="stat-badge up">+10.5% vs last week</span>
            </div>
            <div style="margin-top: 1rem; color: var(--text-light);">Total number of shipments processed and delivered by your company this month. Track your logistics performance and growth over time.</div>
        </div>
        <div class="stat-detail-card" data-content="process" style="display: none; background: #fff; border-radius: 14px; box-shadow: var(--shadow); padding: 1.5rem 2.5rem; margin-bottom: 2rem;">
            <div style="color: var(--primary-light); font-weight: 700; font-size: 1.2rem;"><i class="fas fa-shipping-fast"></i> On Process</div>
            <div style="font-size: 2.5rem; font-weight: bold; color: var(--text-dark); margin: 0.5rem 0;">6,345</div>
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span class="stat-badge up">+7.5% vs last week</span>
            </div>
            <div style="margin-top: 1rem; color: var(--text-light);">Shipments currently in process, including those being packed, loaded, or en route. Monitor your active logistics operations here.</div>
        </div>
        <div class="stat-detail-card" data-content="success" style="display: none; background: #fff; border-radius: 14px; box-shadow: var(--shadow); padding: 1.5rem 2.5rem; margin-bottom: 2rem;">
            <div style="color: var(--danger); font-weight: 700; font-size: 1.2rem;"><i class="fas fa-check-circle"></i> Delivery Success</div>
            <div style="font-size: 2.5rem; font-weight: bold; color: var(--text-dark); margin: 0.5rem 0;">8,645</div>
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span class="stat-badge down">-5.5% vs last week</span>
            </div>
            <div style="margin-top: 1rem; color: var(--text-light);">Number of successful deliveries completed. Keep an eye on your delivery success rate and customer satisfaction.</div>
        </div>
        <div class="stat-detail-card" data-content="proceeds" style="display: none; background: #fff; border-radius: 14px; box-shadow: var(--shadow); padding: 1.5rem 2.5rem; margin-bottom: 2rem;">
            <div style="color: var(--accent); font-weight: 700; font-size: 1.2rem;"><i class="fas fa-dollar-sign"></i> Proceeds</div>
            <div style="font-size: 2.5rem; font-weight: bold; color: var(--text-dark); margin: 0.5rem 0;">$876,345</div>
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span class="stat-badge up">+11.5% vs last week</span>
            </div>
            <div style="margin-top: 1rem; color: var(--text-light);">Total proceeds from all deliveries. Review your revenue and financial performance for the selected period.</div>
        </div>
    </div>
    <style>
    .stat-tab {
        background: #fff;
        color: var(--primary);
        font-weight: 700;
        font-size: 1.1rem;
        border-radius: 10px 10px 0 0;
        padding: 0.8rem 2rem;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        transition: background 0.2s, color 0.2s;
        border-bottom: 3px solid transparent;
    }
    .stat-tab.active {
        background: var(--accent);
        color: #fff;
        border-bottom: 3px solid var(--accent);
        z-index: 2;
    }
    .stat-tab:not(.active):hover {
        background: #f0f8f0;
    }
    .stat-detail-card {
        animation: fadeIn 0.3s;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: none; }
    }
    </style>
    <!-- Date Selector Bar -->
    @php
        // Collect all unique dates from the table rows
        $tableDates = [
            '2025-07-14', '2025-07-15', '2025-07-16', '2025-07-17', '2025-07-18', '2025-07-19', '2025-07-20',
            '2025-07-21', '2025-07-22', '2025-07-23'
        ];
        $dateLabels = [];
        foreach ($tableDates as $iso) {
            $carbon = \Carbon\Carbon::parse($iso);
            $dateLabels[] = $carbon->format('l|d M Y|Y-m-d');
        }
    @endphp
    <div class="delivery-date-bar" style="display: flex; align-items: center; justify-content: space-between; background: #f8f8f8; border-radius: 10px; padding: 1rem 2rem; margin-bottom: 2rem;">
        <button id="dateLeft" style="background: none; border: none; font-size: 1.3rem; color: var(--primary);"><i class="fas fa-chevron-left"></i></button>
        <div id="dateSelector" style="display: flex; flex-wrap: wrap; gap: 1.2rem;">
            @foreach($dateLabels as $i => $date)
                @php [$day, $disp, $iso] = explode('|', $date); @endphp
                <span class="date-item{{ $i == 0 ? ' active' : '' }}" data-date="{{ $iso }}" style="cursor: pointer; text-align: center; padding: 0.5rem 1.2rem; border-radius: 8px;">
                    {{ $day }}<br>{{ $disp }}
                </span>
            @endforeach
        </div>
        <button id="dateRight" style="background: none; border: none; font-size: 1.3rem; color: var(--primary);"><i class="fas fa-chevron-right"></i></button>
    </div>
    <style>
    .date-item.active {
        background: var(--primary) !important;
        color: #fff !important;
        font-weight: bold;
        text-decoration: underline;
        border-radius: 8px;
    }
    @media (max-width: 700px) {
      #dateSelector {
        gap: 0.5rem !important;
        justify-content: flex-start !important;
      }
      .date-item {
        min-width: 110px;
        font-size: 0.95rem;
      }
    }
    </style>
    <!-- Tracking Table Card -->
    <div class="warehouse-table-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <div class="chart-title"><i class="fas fa-table"></i> Tracking</div>
            <form id="trackSearchForm" style="display: flex; gap: 1rem; align-items: center; margin: 0;">
                <input id="trackSearchInput" type="text" placeholder="Search Track" style="padding: 0.5rem 1rem; border-radius: 8px; border: 1px solid #ddd; font-size: 1rem;">
                <button type="button" id="filterBtn" style="background: var(--primary-light); color: var(--primary); border: none; border-radius: 8px; padding: 0.5rem 1.2rem; font-weight: 600;">Filters</button>
                <button type="button" id="exportBtn" style="background: var(--primary); color: #fff; border: none; border-radius: 8px; padding: 0.5rem 1.2rem; font-weight: 600;">Export</button>
            </form>
        </div>
        <div class="table-responsive">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th><input type="checkbox"></th>
                        <th>ID Track</th>
                        <th>Car Model</th>
                        <th>Payload</th>
                        <th>Delivery Type</th>
                        <th>Weight</th>
                        <th>Travel Route</th>
                        <th>Distance</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- 2025-07-14 -->
                    <tr data-date="2025-07-14">
                        <td><input type="checkbox"></td>
                        <td>12-334-18091</td>
                        <td>Toyota Corolla 2022</td>
                        <td>Passenger</td>
                        <td>Standard</td>
                        <td>448.01 kg</td>
                        <td><span style="color: #2563eb;">Lagos</span> &rarr; <span style="color: #e67e22;">Abuja</span></td>
                        <td>73.4 km</td>
                        <td><span class="status-badge completed">Delivery</span></td>
                    </tr>
                    <tr data-date="2025-07-14">
                        <td><input type="checkbox"></td>
                        <td>12-334-18092</td>
                        <td>Honda Accord 2024</td>
                        <td>Passenger</td>
                        <td>Express</td>
                        <td>410.00 kg</td>
                        <td><span style="color: #2563eb;">Ibadan</span> &rarr; <span style="color: #e67e22;">Kano</span></td>
                        <td>120.1 km</td>
                        <td><span class="status-badge success">Success</span></td>
                    </tr>
                    <!-- 2025-07-15 -->
                    <tr data-date="2025-07-15">
                        <td><input type="checkbox"></td>
                        <td>12-334-12641</td>
                        <td>Honda Civic 2023</td>
                        <td>Passenger</td>
                        <td>Express</td>
                        <td>343.04 kg</td>
                        <td><span style="color: #2563eb;">Ibadan</span> &rarr; <span style="color: #e67e22;">Kano</span></td>
                        <td>72.34 km</td>
                        <td><span class="status-badge completed">Delivery</span></td>
                    </tr>
                    <tr data-date="2025-07-15">
                        <td><input type="checkbox"></td>
                        <td>12-334-12642</td>
                        <td>Ford Explorer 2022</td>
                        <td>SUV</td>
                        <td>Standard</td>
                        <td>520.00 kg</td>
                        <td><span style="color: #2563eb;">Enugu</span> &rarr; <span style="color: #e67e22;">Port Harcourt</span></td>
                        <td>95.2 km</td>
                        <td><span class="status-badge delayed">Delayed</span></td>
                    </tr>
                    <!-- 2025-07-16 -->
                    <tr data-date="2025-07-16">
                        <td><input type="checkbox"></td>
                        <td>12-334-12241</td>
                        <td>Ford Focus 2021</td>
                        <td>Passenger</td>
                        <td>Standard</td>
                        <td>248.54 kg</td>
                        <td><span style="color: #2563eb;">Enugu</span> &rarr; <span style="color: #e67e22;">Port Harcourt</span></td>
                        <td>82.32 km</td>
                        <td><span class="status-badge success">Success</span></td>
                    </tr>
                    <tr data-date="2025-07-16">
                        <td><input type="checkbox"></td>
                        <td>12-334-12242</td>
                        <td>Chevrolet Malibu 2023</td>
                        <td>Passenger</td>
                        <td>Express</td>
                        <td>300.00 kg</td>
                        <td><span style="color: #2563eb;">Benin</span> &rarr; <span style="color: #e67e22;">Jos</span></td>
                        <td>110.0 km</td>
                        <td><span class="status-badge completed">Delivery</span></td>
                    </tr>
                    <!-- 2025-07-17 -->
                    <tr data-date="2025-07-17">
                        <td><input type="checkbox"></td>
                        <td>12-334-34564</td>
                        <td>Hyundai Elantra 2022</td>
                        <td>Passenger</td>
                        <td>Express</td>
                        <td>341.83 kg</td>
                        <td><span style="color: #2563eb;">Benin</span> &rarr; <span style="color: #e67e22;">Jos</span></td>
                        <td>13.33 km</td>
                        <td><span class="status-badge completed">Delivery</span></td>
                    </tr>
                    <tr data-date="2025-07-17">
                        <td><input type="checkbox"></td>
                        <td>12-334-34565</td>
                        <td>Kia Seltos 2024</td>
                        <td>SUV</td>
                        <td>Standard</td>
                        <td>410.00 kg</td>
                        <td><span style="color: #2563eb;">Owerri</span> &rarr; <span style="color: #e67e22;">Uyo</span></td>
                        <td>121.21 km</td>
                        <td><span class="status-badge delayed">Delayed</span></td>
                    </tr>
                    <!-- 2025-07-18 -->
                    <tr data-date="2025-07-18">
                        <td><input type="checkbox"></td>
                        <td>12-334-24355</td>
                        <td>Nissan Altima 2023</td>
                        <td>Passenger</td>
                        <td>Standard</td>
                        <td>342.34 kg</td>
                        <td><span style="color: #2563eb;">Owerri</span> &rarr; <span style="color: #e67e22;">Uyo</span></td>
                        <td>121.21 km</td>
                        <td><span class="status-badge completed">Delivery</span></td>
                    </tr>
                    <tr data-date="2025-07-18">
                        <td><input type="checkbox"></td>
                        <td>12-334-24356</td>
                        <td>Toyota Camry 2022</td>
                        <td>Passenger</td>
                        <td>Express</td>
                        <td>534.85 kg</td>
                        <td><span style="color: #2563eb;">Abeokuta</span> &rarr; <span style="color: #e67e22;">Ilorin</span></td>
                        <td>82.17 km</td>
                        <td><span class="status-badge success">Success</span></td>
                    </tr>
                    <!-- 2025-07-19 -->
                    <tr data-date="2025-07-19">
                        <td><input type="checkbox"></td>
                        <td>12-334-35135</td>
                        <td>Kia Rio 2022</td>
                        <td>Passenger</td>
                        <td>Standard</td>
                        <td>84.13 kg</td>
                        <td><span style="color: #2563eb;">Calabar</span> &rarr; <span style="color: #e67e22;">Makurdi</span></td>
                        <td>52.35 km</td>
                        <td><span class="status-badge success">Success</span></td>
                    </tr>
                    <tr data-date="2025-07-19">
                        <td><input type="checkbox"></td>
                        <td>12-334-35136</td>
                        <td>Volkswagen Passat 2023</td>
                        <td>Passenger</td>
                        <td>Express</td>
                        <td>90.00 kg</td>
                        <td><span style="color: #2563eb;">Lokoja</span> &rarr; <span style="color: #e67e22;">Bauchi</span></td>
                        <td>75.83 km</td>
                        <td><span class="status-badge completed">Delivery</span></td>
                    </tr>
                    <!-- 2025-07-20 -->
                    <tr data-date="2025-07-20">
                        <td><input type="checkbox"></td>
                        <td>12-334-62578</td>
                        <td>Mazda 3 2023</td>
                        <td>Passenger</td>
                        <td>Standard</td>
                        <td>64.55 kg</td>
                        <td><span style="color: #2563eb;">Minna</span> &rarr; <span style="color: #e67e22;">Katsina</span></td>
                        <td>56.33 km</td>
                        <td><span class="status-badge completed">Delivery</span></td>
                    </tr>
                    <tr data-date="2025-07-20">
                        <td><input type="checkbox"></td>
                        <td>12-334-65497</td>
                        <td>Chevrolet Spark 2022</td>
                        <td>Passenger</td>
                        <td>Express</td>
                        <td>534.85 kg</td>
                        <td><span style="color: #2563eb;">Abakaliki</span> &rarr; <span style="color: #e67e22;">Zaria</span></td>
                        <td>98.313 km</td>
                        <td><span class="status-badge success">Success</span></td>
                    </tr>
                    <!-- 2025-07-21 -->
                    <tr data-date="2025-07-21">
                        <td><input type="checkbox"></td>
                        <td>12-334-78901</td>
                        <td>Subaru Impreza 2023</td>
                        <td>Passenger</td>
                        <td>Standard</td>
                        <td>312.00 kg</td>
                        <td><span style="color: #2563eb;">Jos</span> &rarr; <span style="color: #e67e22;">Kaduna</span></td>
                        <td>67.45 km</td>
                        <td><span class="status-badge completed">Delivery</span></td>
                    </tr>
                    <tr data-date="2025-07-21">
                        <td><input type="checkbox"></td>
                        <td>12-334-78902</td>
                        <td>Peugeot 508 2022</td>
                        <td>Passenger</td>
                        <td>Express</td>
                        <td>280.50 kg</td>
                        <td><span style="color: #2563eb;">Ibadan</span> &rarr; <span style="color: #e67e22;">Oshogbo</span></td>
                        <td>45.20 km</td>
                        <td><span class="status-badge success">Success</span></td>
                    </tr>
                    <!-- 2025-07-22 -->
                    <tr data-date="2025-07-22">
                        <td><input type="checkbox"></td>
                        <td>12-334-78903</td>
                        <td>Renault Duster 2024</td>
                        <td>SUV</td>
                        <td>Standard</td>
                        <td>410.00 kg</td>
                        <td><span style="color: #2563eb;">Abeokuta</span> &rarr; <span style="color: #e67e22;">Ibadan</span></td>
                        <td>38.00 km</td>
                        <td><span class="status-badge delayed">Delayed</span></td>
                    </tr>
                    <tr data-date="2025-07-22">
                        <td><input type="checkbox"></td>
                        <td>12-334-78904</td>
                        <td>Mercedes-Benz C300 2023</td>
                        <td>Passenger</td>
                        <td>Express</td>
                        <td>350.00 kg</td>
                        <td><span style="color: #2563eb;">Kano</span> &rarr; <span style="color: #e67e22;">Sokoto</span></td>
                        <td>120.00 km</td>
                        <td><span class="status-badge completed">Delivery</span></td>
                    </tr>
                    <!-- 2025-07-23 -->
                    <tr data-date="2025-07-23">
                        <td><input type="checkbox"></td>
                        <td>12-334-78905</td>
                        <td>BMW X5 2022</td>
                        <td>SUV</td>
                        <td>Standard</td>
                        <td>520.00 kg</td>
                        <td><span style="color: #2563eb;">Makurdi</span> &rarr; <span style="color: #e67e22;">Calabar</span></td>
                        <td>98.00 km</td>
                        <td><span class="status-badge success">Success</span></td>
                    </tr>
                    <tr data-date="2025-07-23">
                        <td><input type="checkbox"></td>
                        <td>12-334-78906</td>
                        <td>Volkswagen Golf 2023</td>
                        <td>Passenger</td>
                        <td>Express</td>
                        <td>210.00 kg</td>
                        <td><span style="color: #2563eb;">Ilorin</span> &rarr; <span style="color: #e67e22;">Abeokuta</span></td>
                        <td>60.00 km</td>
                        <td><span class="status-badge completed">Delivery</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Advanced Filters Modal -->
<div id="filterModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.3); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:12px; padding:2rem; min-width:320px; max-width:90vw; box-shadow:0 8px 32px rgba(0,0,0,0.15);">
        <h3 style="color:var(--primary); font-size:1.2rem; font-weight:700; margin-bottom:1rem;">Advanced Filters</h3>
        <form id="advancedFilterForm" style="display:flex; flex-direction:column; gap:1rem;">
            <div>
                <label>Status:</label>
                <select id="filterStatus" style="width:100%; padding:0.5rem; border-radius:8px;">
                    <option value="">All</option>
                    <option value="Delivery">Delivery</option>
                    <option value="Success">Success</option>
                    <option value="Delayed">Delayed</option>
                </select>
            </div>
            <div>
                <label>Car Model:</label>
                <input id="filterCarModel" type="text" placeholder="e.g. Toyota" style="width:100%; padding:0.5rem; border-radius:8px;">
            </div>
            <div>
                <label>Payload Type:</label>
                <input id="filterPayload" type="text" placeholder="e.g. Passenger, SUV" style="width:100%; padding:0.5rem; border-radius:8px;">
            </div>
            <div style="display:flex; gap:1rem; justify-content:flex-end;">
                <button type="button" id="closeFilterModal" style="background:#eee; color:#333; border:none; border-radius:8px; padding:0.5rem 1.2rem;">Cancel</button>
                <button type="submit" style="background:var(--primary); color:#fff; border:none; border-radius:8px; padding:0.5rem 1.2rem; font-weight:600;">Apply</button>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
console.log('Delivery dashboard script loaded!');
document.addEventListener('DOMContentLoaded', function () {
    // SEARCH
    const searchInput = document.getElementById('trackSearchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const filter = searchInput.value.toLowerCase();
            document.querySelectorAll('.dashboard-table tbody tr').forEach(row => {
                const idTrack = row.children[1].textContent.toLowerCase();
                const carModel = row.children[2].textContent.toLowerCase();
                row.style.display = (idTrack.includes(filter) || carModel.includes(filter)) ? '' : 'none';
            });
        });
    }
    // FILTER MODAL
    const filterBtn = document.getElementById('filterBtn');
    const filterModal = document.getElementById('filterModal');
    const closeFilterModal = document.getElementById('closeFilterModal');
    const advancedFilterForm = document.getElementById('advancedFilterForm');
    if (filterBtn && filterModal && closeFilterModal && advancedFilterForm) {
        filterBtn.addEventListener('click', function(e) {
            e.preventDefault();
            filterModal.style.display = 'flex';
        });
        closeFilterModal.addEventListener('click', function() {
            filterModal.style.display = 'none';
        });
        filterModal.addEventListener('click', function(e) {
            if (e.target === filterModal) filterModal.style.display = 'none';
        });
        advancedFilterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const status = document.getElementById('filterStatus').value.toLowerCase();
            const carModel = document.getElementById('filterCarModel').value.toLowerCase();
            const payload = document.getElementById('filterPayload').value.toLowerCase();
            document.querySelectorAll('.dashboard-table tbody tr').forEach(row => {
                let show = true;
                if (status && !row.innerHTML.toLowerCase().includes(status)) show = false;
                if (carModel && !row.children[2].textContent.toLowerCase().includes(carModel)) show = false;
                if (payload && !row.children[3].textContent.toLowerCase().includes(payload)) show = false;
                row.style.display = show ? '' : 'none';
            });
            filterModal.style.display = 'none';
        });
    }
    // EXPORT
    const exportBtn = document.getElementById('exportBtn');
    if (exportBtn) {
        exportBtn.addEventListener('click', function () {
            const rows = Array.from(document.querySelectorAll('.dashboard-table tbody tr')).filter(r => r.style.display !== 'none');
            if (!rows.length) return alert('No data to export!');
            let csv = '';
            const headers = Array.from(document.querySelectorAll('.dashboard-table thead th')).map(th => th.textContent.trim());
            csv += headers.join(',') + '\n';
            rows.forEach(row => {
                const cells = Array.from(row.children).map(td => td.textContent.trim().replace(/\s+/g, ' '));
                csv += cells.join(',') + '\n';
            });
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'deliveries.csv';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        });
    }
    // DATE SELECTOR INTERACTIVITY
    const dateItems = document.querySelectorAll('.date-item');
    dateItems.forEach(item => {
        item.addEventListener('click', function() {
            // Remove active from all
            dateItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');
            // Filter table rows by date
            const selectedDate = this.getAttribute('data-date');
            document.querySelectorAll('.dashboard-table tbody tr').forEach(row => {
                if (row.getAttribute('data-date') === selectedDate) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
    // On page load, show only the first date's rows
    const firstActive = document.querySelector('.date-item.active');
    if (firstActive) {
        const selectedDate = firstActive.getAttribute('data-date');
        document.querySelectorAll('.dashboard-table tbody tr').forEach(row => {
            if (row.getAttribute('data-date') === selectedDate) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    // STAT TABS INTERACTIVITY
    const statTabs = document.querySelectorAll('.stat-tab');
    const statCards = document.querySelectorAll('.stat-detail-card');
    statTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            statTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            const tabName = this.getAttribute('data-tab');
            statCards.forEach(card => {
                card.style.display = (card.getAttribute('data-content') === tabName) ? 'block' : 'none';
            });
        });
    });
});
</script>
@endpush
@endsection