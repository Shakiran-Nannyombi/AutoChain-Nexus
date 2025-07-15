@extends('layouts.dashboard')

@section('title', 'Vendor Orders')

@section('sidebar-content')
    @include('dashboards.vendor.sidebar')
@endsection

@section('content')
<div class="content-card">
    <h2 style="color: var(--primary); margin-bottom: 1.5rem; font-size: 1.8rem;">
        <i class="fas fa-file-invoice"></i> Orders & Invoices
    </h2>
    
    <!-- Order Form -->
    <div class="order-form-container" style="background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h3 style="color: var(--primary); margin-bottom: 1.5rem; font-size: 1.4rem; font-weight: 700;">
            <i class="fas fa-plus-circle"></i> Create New Order
        </h3>
        
        <form id="newOrderForm" method="POST" action="{{ route('vendor.orders.create') }}" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            @csrf
            
            <!-- Order Type Selection -->
            <div class="form-group" style="grid-column: 1 / -1;">
                <label for="order_type" style="font-weight: 600; color: #374151; margin-bottom: 0.5rem; display: block;">Order Type</label>
                <select id="order_type" name="order_type" required style="width: 100%; padding: 0.8rem 1rem; border-radius: 8px; border: 1px solid #d1d5db; font-size: 1rem; background: #f9fafb;">
                    <option value="">Select Order Type</option>
                    <option value="manufacturer">Order to Manufacturer</option>
                    <option value="vendor">Confirm Vendor Order</option>
                </select>
            </div>
            
            <!-- Manufacturer/Vendor Selection -->
            <div class="form-group" id="partner_selection" style="display: none;">
                <label for="partner_id" style="font-weight: 600; color: #374151; margin-bottom: 0.5rem; display: block;">Select Partner</label>
                <select id="partner_id" name="partner_id" required style="width: 100%; padding: 0.8rem 1rem; border-radius: 8px; border: 1px solid #d1d5db; font-size: 1rem; background: #f9fafb;">
                    <option value="">Select Partner</option>
                </select>
            </div>
            
            <!-- Product Selection -->
            <div class="form-group" id="product_selection" style="display: none;">
                <label for="product_id" style="font-weight: 600; color: #374151; margin-bottom: 0.5rem; display: block;">Select Product</label>
                <select id="product_id" name="product_id" required style="width: 100%; padding: 0.8rem 1rem; border-radius: 8px; border: 1px solid #d1d5db; font-size: 1rem; background: #f9fafb;">
                    <option value="">Select Product</option>
                </select>
            </div>
            
            <!-- Quantity -->
            <div class="form-group" id="quantity_input" style="display: none;">
                <label for="quantity" style="font-weight: 600; color: #374151; margin-bottom: 0.5rem; display: block;">Quantity</label>
                <input type="number" id="quantity" name="quantity" min="1" required style="width: 100%; padding: 0.8rem 1rem; border-radius: 8px; border: 1px solid #d1d5db; font-size: 1rem; background: #f9fafb;">
            </div>
            
            <!-- Delivery Date -->
            <div class="form-group" id="delivery_date_input" style="display: none;">
                <label for="delivery_date" style="font-weight: 600; color: #374151; margin-bottom: 0.5rem; display: block;">Expected Delivery Date</label>
                <input type="date" id="delivery_date" name="delivery_date" required style="width: 100%; padding: 0.8rem 1rem; border-radius: 8px; border: 1px solid #d1d5db; font-size: 1rem; background: #f9fafb;">
            </div>
            
            <!-- Special Instructions -->
            <div class="form-group" style="grid-column: 1 / -1;">
                <label for="special_instructions" style="font-weight: 600; color: #374151; margin-bottom: 0.5rem; display: block;">Special Instructions (Optional)</label>
                <textarea id="special_instructions" name="special_instructions" rows="3" placeholder="Any special requirements or notes..." style="width: 100%; padding: 0.8rem 1rem; border-radius: 8px; border: 1px solid #d1d5db; font-size: 1rem; background: #f9fafb; resize: vertical;"></textarea>
            </div>
            
            <!-- Form Actions -->
            <div class="form-actions" style="grid-column: 1 / -1; display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" id="cancelOrder" style="background: #6b7280; color: #fff; border: none; border-radius: 8px; padding: 0.8rem 2rem; font-size: 1rem; font-weight: 600; cursor: pointer;">Cancel</button>
                <button type="submit" id="submitOrder" style="background: var(--primary); color: #fff; border: none; border-radius: 8px; padding: 0.8rem 2rem; font-size: 1rem; font-weight: 600; cursor: pointer;">Create Order</button>
            </div>
        </form>
    </div>
    <div id="orderFormMessage" style="grid-column: 1 / -1; margin-top: 1rem; font-weight: 600;"></div>
    <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem;">
        <button id="retailerOrdersBtn" class="order-filter-btn active" style="background: var(--primary); color: #fff; border: none; border-radius: 8px; padding: 0.5rem 1.2rem; font-weight: 600;">Retailer Orders</button>
        <button id="manufacturerOrdersBtn" class="order-filter-btn" style="background: #f5f5f5; color: var(--primary); border: none; border-radius: 8px; padding: 0.5rem 1.2rem; font-weight: 600;">Manufacturer Orders</button>
    </div>
    <div id="ordersContainer">
        <!-- JS will render orders here -->
    </div>
</div>
<style>
.order-header {
    display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.2rem; flex-wrap: wrap; gap: 1rem;
}
.order-actions button {
    margin-left: 0.7rem;
    font-weight: 600;
    border-radius: 8px;
    padding: 0.4rem 1.1rem;
    border: none;
    cursor: pointer;
    font-size: 1rem;
}
.order-actions .delete { background: var(--danger-light); color: var(--danger); }
.order-actions .track { background: var(--primary-light); color: var(--primary); }
.order-actions .edit { background: var(--accent); color: #fff; }
.order-progress {
    display: flex; gap: 2.5rem; align-items: center; margin-bottom: 1.2rem; margin-top: 0.5rem;
}
.order-progress-step {
    display: flex; flex-direction: column; align-items: center; font-size: 0.98rem; color: var(--text-light);
}
.order-progress-step.active { color: var(--primary); font-weight: 700; }
.order-progress-bar {
    flex: 1; height: 6px; background: #eee; border-radius: 4px; margin: 0 0.5rem; position: relative;
}
.order-progress-bar-inner {
    height: 100%; background: var(--primary); border-radius: 4px; transition: width 0.3s;
}
.order-section {
    display: flex; gap: 2rem; margin-bottom: 2rem; flex-wrap: wrap;
}
.order-main {
    flex: 2 1 400px;
}
.order-side {
    flex: 1 1 280px; min-width: 260px;
    display: flex; flex-direction: column; gap: 1.2rem;
}
.order-table {
    width: 100%; border-collapse: collapse; margin-bottom: 1.2rem; background: #fff; border-radius: 10px; overflow: hidden;
}
.order-table th, .order-table td {
    padding: 0.7rem 0.8rem; text-align: left; border-bottom: 1px solid #f0f0f0;
}
.order-table th { background: #f8f8f8; color: var(--primary); font-weight: 700; }
.order-table tr:last-child td { border-bottom: none; }
.order-table img { width: 48px; height: 48px; object-fit: cover; border-radius: 8px; }
.status-badge { border-radius: 8px; font-size: 0.95rem; padding: 0.2rem 0.8rem; font-weight: 600; }
.status-badge.Ready { background: #eafbe7; color: var(--primary); }
.status-badge.Packaging { background: #fff6e5; color: #e67e22; }
.status-badge.Processing { background: #e7f0fb; color: #2563eb; }
.status-badge.Delivered { background: #eafbe7; color: var(--primary); }
.status-badge.Shipping { background: #f0f8ff; color: #2563eb; }
.order-summary, .order-customer {
    background: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 1.2rem 1rem; margin-bottom: 1.2rem;
}
.order-summary-title, .order-customer-title { font-weight: 700; color: var(--primary); font-size: 1.1rem; margin-bottom: 0.7rem; }
.order-timeline {
    background: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 1.2rem 1rem; margin-bottom: 1.2rem;
}
.timeline-item { display: flex; align-items: flex-start; gap: 0.7rem; margin-bottom: 1rem; }
.timeline-dot { width: 10px; height: 10px; border-radius: 50%; margin-top: 0.3rem; background: var(--primary); }
.timeline-content { font-size: 0.98rem; color: var(--text-dark); }
.timeline-time { font-size: 0.92rem; color: var(--text-light); margin-left: 0.5rem; }
@media (max-width: 900px) {
    .order-section { flex-direction: column; }
    .order-main, .order-side { flex: 1 1 100%; }
}
.order-form-container .form-actions button {
    transition: all 0.18s cubic-bezier(.4,0,.2,1);
    box-shadow: 0 2px 4px rgba(37,99,235,0.08);
    font-weight: 600;
}
.order-form-container .form-actions button#submitOrder {
    background: var(--primary);
    color: #fff;
}
.order-form-container .form-actions button#submitOrder:hover:not(:disabled) {
    background: #14532d; /* Slightly darker green or your --primary-dark */
    transform: translateY(-2px) scale(1.03);
    box-shadow: 0 6px 18px rgba(22,101,52,0.13);
}
.order-form-container .form-actions button#cancelOrder {
    background: #6b7280;
    color: #fff;
}
.order-form-container .form-actions button#cancelOrder:hover:not(:disabled) {
    background: #374151;
    transform: translateY(-2px) scale(1.03);
    box-shadow: 0 6px 18px rgba(55,65,81,0.13);
}
.order-form-container .form-actions button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    box-shadow: none;
    transform: none;
}
</style>
@push('scripts')
<script>
let orderIdToDelete = null;
const retailerOrders = [
  {
    id: 'S-10242002', date: 'April 05, 2024 at 9:48 pm', status: 2, progress: ['Order Confirming', 'Payment Pending', 'Processing', 'Shipping', 'Delivered'],
    products: [
      { image: '/images/car1.png', name: 'Toyota Corolla 2022', category: 'Sedan', status: 'Ready', qty: 3, price: 22000, tax: 300, amount: 66300 },
      { image: '/images/car2.png', name: 'Honda CR-V 2023', category: 'SUV', status: 'Packaging', qty: 1, price: 28000, tax: 187, amount: 28187 },
    ],
    payment: { subtotal: 90000, discount: 5000, shipping: 200, tax: 800, total: 85800 },
    customer: { company: 'Auto Sales Network', email: 'retailer@autosales.com', phone: '555-1234', shipping: '100 Main St, Anytown, CA', billing: 'Same as shipping' },
    timeline: [
      { text: 'The Packaging has been started', by: 'Jane Doe', time: 'April 01, 2024, 09:40 pm' },
      { text: 'The Invoice has been sent to the Customer', by: '', time: 'April 01, 2024, 09:42 pm' },
    ]
  }
];
const manufacturerOrders = [
  {
    id: 'M-20432001', date: 'April 02, 2024 at 2:15 pm', status: 3, progress: ['Order Confirming', 'Payment Pending', 'Processing', 'Shipping', 'Delivered'],
    products: [
      { image: '/images/car3.png', name: 'Ford F-150', category: 'Truck', status: 'Processing', qty: 2, price: 35000, tax: 500, amount: 71000 },
      { image: '/images/car4.png', name: 'BMW 3 Series', category: 'Sedan', status: 'Shipping', qty: 1, price: 41000, tax: 410, amount: 41410 },
    ],
    payment: { subtotal: 112000, discount: 8000, shipping: 300, tax: 1200, total: 105500 },
    customer: { company: 'Speed Parts International', email: 'vendor@speedparts.com', phone: '555-5678', shipping: '200 Vendor Ave, Miami, FL', billing: 'Same as shipping' },
    timeline: [
      { text: 'Order confirmed by manufacturer', by: 'Admin', time: 'April 01, 2024, 10:00 am' },
      { text: 'Order shipped to vendor', by: '', time: 'April 02, 2024, 02:00 pm' },
    ]
  }
];

function renderOrder(order) {
  const progressSteps = order.progress.map((step, i) => `
    <div class="order-progress-step${i <= order.status ? ' active' : ''}">
      <div><i class="fas fa-check-circle"></i></div>
      <div>${step}</div>
    </div>
    ${i < order.progress.length - 1 ? `<div class="order-progress-bar"><div class="order-progress-bar-inner" style="width:${i < order.status ? '100%' : '0%'}"></div></div>` : ''}
  `).join('');
  return `
    <div class="order-header">
      <div>
        <div style="font-size:1.3rem; font-weight:700; color:var(--primary-dark);">#${order.id}</div>
        <div style="color:var(--text-light); font-size:0.98rem;">${order.date}</div>
      </div>
      <div class="order-actions">
        <button class="button button-red" data-order-id="${order.id}"><i class="fas fa-trash"></i> Delete Order</button>
        <button class="button button-green" data-order-id="${order.id}"><i class="fas fa-map-marker-alt"></i> Track Order</button>
        <button class="button button-yellow" data-order-id="${order.id}"><i class="fas fa-edit"></i> Edit Order</button>
      </div>
    </div>
    <div class="order-progress">${progressSteps}</div>
    <div class="order-section">
      <div class="order-main">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.7rem;">
          <div style="font-weight:700; color:var(--primary); font-size:1.1rem;">Product</div>
          <button class="button button-green download-csv" data-order-id="${order.id}"><i class="fas fa-download"></i> Download CSV</button>
        </div>
        <table class="order-table">
          <thead>
            <tr><th>Item</th><th>Status</th><th>Quantity</th><th>Price</th><th>Tax</th><th>Amount</th></tr>
          </thead>
          <tbody>
            ${order.products.map(p => `
              <tr>
                <td><img src="${p.image}" alt="${p.name}"><div style="display:inline-block;vertical-align:top;margin-left:0.7rem;"><div style="font-weight:600;">${p.name}</div><div style="font-size:0.92rem;color:var(--text-light);">${p.category}</div></div></td>
                <td><span class="status-badge ${p.status}">${p.status}</span></td>
                <td>${p.qty}</td>
                <td>$${p.price.toLocaleString()}</td>
                <td>$${p.tax.toLocaleString()}</td>
                <td>$${p.amount.toLocaleString()}</td>
              </tr>
            `).join('')}
          </tbody>
        </table>
        <div class="order-timeline">
          <div style="font-weight:700; color:var(--primary); margin-bottom:0.7rem;">Timeline</div>
          ${order.timeline.map(t => `
            <div class="timeline-item">
              <div class="timeline-dot"></div>
              <div>
                <div class="timeline-content">${t.text}${t.by ? ` <span style='color:var(--text-light);font-size:0.95em;'>Confirmed by ${t.by}</span>` : ''}</div>
                <div class="timeline-time">${t.time}</div>
              </div>
            </div>
          `).join('')}
        </div>
      </div>
      <div class="order-side">
        <div class="order-summary">
          <div class="order-summary-title">Payment</div>
          <div style="display:flex; justify-content:space-between;"><span>Subtotal</span><span>$${order.payment.subtotal.toLocaleString()}</span></div>
          <div style="display:flex; justify-content:space-between;"><span>Discount</span><span>-$${order.payment.discount.toLocaleString()}</span></div>
          <div style="display:flex; justify-content:space-between;"><span>Shipping</span><span>$${order.payment.shipping.toLocaleString()}</span></div>
          <div style="display:flex; justify-content:space-between;"><span>Tax</span><span>$${order.payment.tax.toLocaleString()}</span></div>
          <hr>
          <div style="display:flex; justify-content:space-between; font-weight:700;"><span>Total</span><span>$${order.payment.total.toLocaleString()}</span></div>
          <button class="button button-yellow download-invoice" data-order-id="${order.id}" style="margin-top:0.7rem;"><i class="fas fa-download"></i> Download Invoice</button>
        </div>
        <div class="order-customer">
          <div class="order-customer-title">Customer</div>
          <div style="margin-bottom:0.7rem;"><b>General Information</b><br>${order.customer.company}<br>${order.customer.email}<br>${order.customer.phone}</div>
          <div style="margin-bottom:0.7rem;"><b>Shipping Address</b><br>${order.customer.shipping}</div>
          <div><b>Billing Address</b><br>${order.customer.billing}</div>
        </div>
      </div>
    </div>
  `;
}

function renderOrders(type) {
  const orders = type === 'retailer' ? retailerOrders : manufacturerOrders;
  document.getElementById('ordersContainer').innerHTML = orders.map(renderOrder).join('');
}

document.getElementById('retailerOrdersBtn').addEventListener('click', function() {
  this.classList.add('active');
  document.getElementById('manufacturerOrdersBtn').classList.remove('active');
  renderOrders('retailer');
});
document.getElementById('manufacturerOrdersBtn').addEventListener('click', function() {
  this.classList.add('active');
  document.getElementById('retailerOrdersBtn').classList.remove('active');
  renderOrders('manufacturer');
});

// Initial render
renderOrders('retailer');

// Attach action button listeners after each render
function attachOrderActionListeners() {
  // Delete Order
  document.querySelectorAll('.button-red').forEach(btn => {
    btn.addEventListener('click', function() {
      const orderId = btn.dataset.orderId;
      if (!orderId) return;
      orderIdToDelete = orderId;
      document.getElementById('deleteOrderModalText').textContent = `Are you sure you want to delete order #${orderId}?`;
      document.getElementById('deleteOrderModal').style.display = 'flex';
    });
  });
  // Track Order
  document.querySelectorAll('.button-green').forEach(btn => {
    if (btn.textContent.includes('Track Order')) {
      btn.addEventListener('click', function() {
        const orderId = btn.dataset.orderId;
        if (!orderId) return;
        const isRetailer = document.getElementById('retailerOrdersBtn').classList.contains('active');
        let arr = isRetailer ? retailerOrders : manufacturerOrders;
        const order = arr.find(o => o.id === orderId);
        if (!order) return;
        let html = `
  <div style='display:flex;align-items:center;justify-content:space-between;margin-bottom:1.2rem;'>
    <h3 style='color:var(--primary);font-size:1.35rem;font-weight:800;margin:0;display:flex;align-items:center;gap:0.5rem;'>
      <i class='fas fa-map-marker-alt'></i> Tracking Order <span style='color:var(--accent);'>#${order.id}</span>
    </h3>
    <button id='closeTrackOrderModal' style='background:none;border:none;font-size:2rem;color:var(--danger);cursor:pointer;line-height:1;' title='Close'>&times;</button>
  </div>
  <div style='margin-bottom:1.2rem;display:flex;align-items:center;gap:1rem;'>
    <span style='font-weight:700;'>Status:</span>
    <span style='background:var(--primary-light);color:var(--primary);padding:0.3em 1em;border-radius:1em;font-weight:700;font-size:1.05rem;'>${order.progress[order.status]}</span>
  </div>
  <div style='margin-bottom:1.2rem;'>
    <span style='font-weight:700;'>Timeline:</span>
    <ul style='list-style:none;padding:0;margin:0;margin-top:0.7em;'>
      ${order.timeline.map((t,i) => `
        <li style='display:flex;align-items:flex-start;gap:0.7em;margin-bottom:1.1em;'>
          <span style='margin-top:0.2em;color:var(--primary);font-size:1.1em;'><i class="fas fa-circle"></i></span>
          <div>
            <div style='font-size:1.01em;'><span style='color:var(--primary);font-weight:600;'>${t.time}</span> - ${t.text}${t.by ? ` <span style='color:var(--text-light);font-size:0.95em;'>by ${t.by}</span>` : ''}</div>
          </div>
        </li>
      `).join('')}
    </ul>
  </div>
  <div style='margin-bottom:0.7rem;'>
    <span style='font-weight:700;'>Map:</span>
    <div id='orderMap' style='width:100%;height:200px;border-radius:12px;box-shadow:0 2px 12px rgba(0,0,0,0.07);margin-top:0.5em;'></div>
  </div>
`;
        document.getElementById('trackOrderModalContent').innerHTML = html;
        document.getElementById('trackOrderModal').style.display = 'flex';
        setTimeout(function() {
          if (window.orderMapInstance) {
            window.orderMapInstance.remove();
          }
          // Default coordinates (e.g., San Francisco). Replace with dynamic coords if available.
          var lat = 37.7749, lng = -122.4194;
          window.orderMapInstance = L.map('orderMap').setView([lat, lng], 12);
          L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
          }).addTo(window.orderMapInstance);
          L.marker([lat, lng]).addTo(window.orderMapInstance)
            .bindPopup('Order Location')
            .openPopup();
        }, 100);
      });
    }
    if (btn.textContent.includes('Download CSV')) {
      btn.addEventListener('click', function() {
        const orderId = btn.dataset.orderId;
        if (!orderId) return;
        const isRetailer = document.getElementById('retailerOrdersBtn').classList.contains('active');
        let arr = isRetailer ? retailerOrders : manufacturerOrders;
        const order = arr.find(o => o.id === orderId);
        if (!order) return;
        let csv = 'Product,Category,Status,Quantity,Price,Tax,Amount\n';
        order.products.forEach(p => {
          csv += `"${p.name}","${p.category}","${p.status}",${p.qty},${p.price},${p.tax},${p.amount}\n`;
        });
        const blob = new Blob([csv], {type: 'text/csv'});
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `order_${orderId}_products.csv`;
        document.body.appendChild(a);
        a.click();
        setTimeout(() => { document.body.removeChild(a); URL.revokeObjectURL(url); }, 100);
      });
    }
  });
  // Edit Order
  document.querySelectorAll('.button-yellow').forEach(btn => {
    if (btn.textContent.includes('Edit Order')) {
      btn.addEventListener('click', function() {
        const orderId = btn.dataset.orderId;
        if (!orderId) return;
        const isRetailer = document.getElementById('retailerOrdersBtn').classList.contains('active');
        let arr = isRetailer ? retailerOrders : manufacturerOrders;
        const order = arr.find(o => o.id === orderId);
        if (!order) return;
        let html = `<h3 style='color:var(--primary);font-size:1.3rem;font-weight:700;margin-bottom:1rem;'><i class='fas fa-edit'></i> Edit Order #${order.id}</h3>`;
        html += `<form id='editOrderForm'>`;
        html += `<div style='margin-bottom:1rem;'><label>Status:<br><select name='status' style='width:100%;padding:0.4em;border-radius:6px;'>`;
        order.progress.forEach((step, i) => {
          html += `<option value='${i}'${i===order.status?' selected':''}>${step}</option>`;
        });
        html += `</select></label></div>`;
        html += `<div style='margin-bottom:1rem;'><label>Customer Name:<br><input name='company' value='${order.customer.company}' style='width:100%;padding:0.4em;border-radius:6px;'></label></div>`;
        html += `<div style='margin-bottom:1rem;'><label>Email:<br><input name='email' value='${order.customer.email}' style='width:100%;padding:0.4em;border-radius:6px;'></label></div>`;
        html += `<div style='margin-bottom:1rem;'><label>Phone:<br><input name='phone' value='${order.customer.phone}' style='width:100%;padding:0.4em;border-radius:6px;'></label></div>`;
        html += `<div style='margin-bottom:1rem;'><label>Shipping Address:<br><input name='shipping' value='${order.customer.shipping}' style='width:100%;padding:0.4em;border-radius:6px;'></label></div>`;
        html += `<div style='margin-bottom:1rem;'><label>Billing Address:<br><input name='billing' value='${order.customer.billing}' style='width:100%;padding:0.4em;border-radius:6px;'></label></div>`;
        html += `<div style='margin-bottom:1rem;'><b>Product Quantities:</b><br>`;
        order.products.forEach((p, idx) => {
          html += `<div style='margin-bottom:0.5em;'><span style='font-weight:600;'>${p.name}</span> <input type='number' min='1' name='qty${idx}' value='${p.qty}' style='width:60px;padding:0.2em 0.4em;border-radius:6px;margin-left:0.5em;'></div>`;
        });
        html += `</div>`;
        html += `<button type='submit' class='button button-green' style='margin-top:0.7rem;'>Save Changes</button>`;
        html += `</form>`;
        document.getElementById('editOrderModalContent').innerHTML = html;
        document.getElementById('editOrderModal').style.display = 'flex';
        document.getElementById('editOrderForm').onsubmit = function(e) {
          e.preventDefault();
          const fd = new FormData(this);
          order.status = parseInt(fd.get('status'));
          order.customer.company = fd.get('company');
          order.customer.email = fd.get('email');
          order.customer.phone = fd.get('phone');
          order.customer.shipping = fd.get('shipping');
          order.customer.billing = fd.get('billing');
          order.products.forEach((p, idx) => {
            p.qty = parseInt(fd.get('qty'+idx));
          });
          document.getElementById('editOrderModal').style.display = 'none';
          renderOrders(isRetailer ? 'retailer' : 'manufacturer');
        };
      });
    }
    if (btn.textContent.includes('Download Invoice')) {
      btn.addEventListener('click', function() {
        const orderId = btn.dataset.orderId;
        if (!orderId) return;
        const isRetailer = document.getElementById('retailerOrdersBtn').classList.contains('active');
        let arr = isRetailer ? retailerOrders : manufacturerOrders;
        const order = arr.find(o => o.id === orderId);
        if (!order) return;
        if (window.jspdf || window.jspdf?.jsPDF || window.jspdf?.jsPDF) {
          const { jsPDF } = window.jspdf;
          const doc = new jsPDF();
          doc.setFontSize(16);
          doc.text(`Invoice for Order #${order.id}`, 10, 15);
          doc.setFontSize(11);
          doc.text(`Date: ${order.date}`, 10, 25);
          doc.text(`Customer: ${order.customer.company}`, 10, 32);
          doc.text(`Email: ${order.customer.email}`, 10, 39);
          doc.text(`Phone: ${order.customer.phone}`, 10, 46);
          doc.text(`Shipping: ${order.customer.shipping}`, 10, 53);
          doc.text(`Billing: ${order.customer.billing}`, 10, 60);
          doc.text('Products:', 10, 70);
          let y = 76;
          order.products.forEach((p, i) => {
            doc.text(`${i+1}. ${p.name} (${p.category}) x${p.qty} - $${p.amount}`, 12, y);
            y += 7;
          });
          y += 3;
          doc.text(`Subtotal: $${order.payment.subtotal}`, 10, y); y += 6;
          doc.text(`Discount: -$${order.payment.discount}`, 10, y); y += 6;
          doc.text(`Shipping: $${order.payment.shipping}`, 10, y); y += 6;
          doc.text(`Tax: $${order.payment.tax}`, 10, y); y += 6;
          doc.setFontSize(13);
          doc.text(`Total: $${order.payment.total}`, 10, y);
          doc.save(`order_${order.id}_invoice.pdf`);
        } else {
          alert('PDF generation coming soon!');
        }
      });
    }
  });
}

// Attach listeners after initial render
attachOrderActionListeners();
// Re-attach listeners after switching order type
const origRenderOrders = renderOrders;
renderOrders = function(type) {
  origRenderOrders(type);
  attachOrderActionListeners();
};

// Order Form Functionality
document.addEventListener('DOMContentLoaded', function() {
    // Sample data for manufacturers and vendors
    const manufacturers = [
        { id: 1, name: 'Toyota Manufacturing Co.', email: 'orders@toyota-mfg.com' },
        { id: 2, name: 'Honda Production Ltd.', email: 'orders@honda-prod.com' },
        { id: 3, name: 'Ford Assembly Plant', email: 'orders@ford-assembly.com' },
        { id: 4, name: 'BMW Manufacturing', email: 'orders@bmw-mfg.com' }
    ];
    
    const vendors = [
        { id: 1, name: 'Auto Parts Plus', email: 'orders@autopartsplus.com' },
        { id: 2, name: 'Speed Parts International', email: 'orders@speedparts.com' },
        { id: 3, name: 'Car Components Corp', email: 'orders@carcomponents.com' }
    ];
    
    const products = [
        { id: 1, name: 'Toyota Corolla 2022', category: 'Sedan', price: 22000 },
        { id: 2, name: 'Honda CR-V 2023', category: 'SUV', price: 28000 },
        { id: 3, name: 'Ford F-150', category: 'Truck', price: 35000 },
        { id: 4, name: 'BMW 3 Series', category: 'Sedan', price: 41000 }
    ];
    
    // Order type selection handler
    document.getElementById('order_type').addEventListener('change', function() {
        const orderType = this.value;
        const partnerSelection = document.getElementById('partner_selection');
        const partnerSelect = document.getElementById('partner_id');
        
        // Clear previous options
        partnerSelect.innerHTML = '<option value="">Select Partner</option>';
        
        if (orderType === 'manufacturer') {
            partnerSelection.style.display = 'block';
            manufacturers.forEach(manufacturer => {
                const option = document.createElement('option');
                option.value = manufacturer.id;
                option.textContent = manufacturer.name;
                partnerSelect.appendChild(option);
            });
        } else if (orderType === 'vendor') {
            partnerSelection.style.display = 'block';
            vendors.forEach(vendor => {
                const option = document.createElement('option');
                option.value = vendor.id;
                option.textContent = vendor.name;
                partnerSelect.appendChild(option);
            });
        } else {
            partnerSelection.style.display = 'none';
            hideAllFormFields();
        }
    });
    
    // Partner selection handler
    document.getElementById('partner_id').addEventListener('change', function() {
        const partnerId = this.value;
        const productSelection = document.getElementById('product_selection');
        const productSelect = document.getElementById('product_id');
        
        // Clear previous options
        productSelect.innerHTML = '<option value="">Select Product</option>';
        
        if (partnerId) {
            productSelection.style.display = 'block';
            products.forEach(product => {
                const option = document.createElement('option');
                option.value = product.id;
                option.textContent = `${product.name} (${product.category}) - $${product.price.toLocaleString()}`;
                productSelect.appendChild(option);
            });
        } else {
            productSelection.style.display = 'none';
            hideFormFieldsAfterProduct();
        }
    });
    
    // Product selection handler
    document.getElementById('product_id').addEventListener('change', function() {
        const productId = this.value;
        
        if (productId) {
            document.getElementById('quantity_input').style.display = 'block';
            document.getElementById('delivery_date_input').style.display = 'block';
            document.getElementById('submitOrder').style.display = 'inline-block';
        } else {
            hideFormFieldsAfterProduct();
        }
    });
    
    // Cancel button handler
    document.getElementById('cancelOrder').addEventListener('click', function() {
        resetOrderForm();
    });
    
    // Form submission handler
    document.getElementById('newOrderForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);
        const submitBtn = document.getElementById('submitOrder');
        const messageDiv = document.getElementById('orderFormMessage');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Creating...';
        messageDiv.textContent = '';
        messageDiv.style.color = '';

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(async res => {
            const data = await res.json();
            if (res.ok && data.success) {
                messageDiv.textContent = data.message;
                messageDiv.style.color = 'green';
                form.reset();
                hideAllFormFields();
            } else if (data.errors) {
                // Laravel validation errors
                messageDiv.textContent = Object.values(data.errors).flat().join(' ');
                messageDiv.style.color = 'red';
            } else {
                messageDiv.textContent = data.message || 'Failed to create order.';
                messageDiv.style.color = 'red';
            }
        })
        .catch(() => {
            messageDiv.textContent = 'Server error. Please try again.';
            messageDiv.style.color = 'red';
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Create Order';
        });
    });
    
    // Enable/disable Create Order button based on required fields
    function checkOrderFormValidity() {
        const orderType = document.getElementById('order_type').value;
        const partnerId = document.getElementById('partner_id').value;
        const productId = document.getElementById('product_id').value;
        const quantity = document.getElementById('quantity').value;
        const deliveryDate = document.getElementById('delivery_date').value;
        const submitBtn = document.getElementById('submitOrder');
        if (
            orderType &&
            partnerId &&
            productId &&
            quantity &&
            deliveryDate
        ) {
            submitBtn.disabled = false;
            submitBtn.style.opacity = 1;
            submitBtn.style.cursor = 'pointer';
        } else {
            submitBtn.disabled = true;
            submitBtn.style.opacity = 0.6;
            submitBtn.style.cursor = 'not-allowed';
        }
    }

    // Attach input listeners after DOM is loaded
    ['order_type','partner_id','product_id','quantity','delivery_date'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.addEventListener('input', checkOrderFormValidity);
    });
    checkOrderFormValidity();

    function hideAllFormFields() {
        document.getElementById('partner_selection').style.display = 'none';
        document.getElementById('product_selection').style.display = 'none';
        hideFormFieldsAfterProduct();
        // Always show Create and Cancel buttons
        document.getElementById('submitOrder').style.display = 'inline-block';
        document.getElementById('cancelOrder').style.display = 'inline-block';
        checkOrderFormValidity();
    }

    function hideFormFieldsAfterProduct() {
        document.getElementById('quantity_input').style.display = 'none';
        document.getElementById('delivery_date_input').style.display = 'none';
        // Always show Create and Cancel buttons
        document.getElementById('submitOrder').style.display = 'inline-block';
        document.getElementById('cancelOrder').style.display = 'inline-block';
    }

    function resetOrderForm() {
        document.getElementById('newOrderForm').reset();
        hideAllFormFields();
        // Always show Create and Cancel buttons
        document.getElementById('submitOrder').style.display = 'inline-block';
        document.getElementById('cancelOrder').style.display = 'inline-block';
        checkOrderFormValidity();
    }

    // Set minimum date for delivery date input
    const deliveryDateInput = document.getElementById('delivery_date');
    const today = new Date().toISOString().split('T')[0];
    deliveryDateInput.min = today;

    // Track Order Modal close
    document.body.addEventListener('click', function(e) {
        if (e.target && e.target.id === 'closeTrackOrderModal') {
            document.getElementById('trackOrderModal').style.display = 'none';
            if (window.orderMapInstance) {
                window.orderMapInstance.remove();
                window.orderMapInstance = null;
            }
        }
        if (e.target && e.target.id === 'closeEditOrderModal') {
            document.getElementById('editOrderModal').style.display = 'none';
        }
    });
    var confirmBtn = document.getElementById('confirmDeleteOrderBtn');
    var cancelBtn = document.getElementById('cancelDeleteOrderBtn');
    if (confirmBtn && cancelBtn) {
      confirmBtn.onclick = function() {
        if (!orderIdToDelete) return;
        const isRetailer = document.getElementById('retailerOrdersBtn').classList.contains('active');
        let arr = isRetailer ? retailerOrders : manufacturerOrders;
        const idx = arr.findIndex(o => o.id === orderIdToDelete);
        if (idx !== -1) {
          arr.splice(idx, 1);
          renderOrders(isRetailer ? 'retailer' : 'manufacturer');
        }
        document.getElementById('deleteOrderModal').style.display = 'none';
        orderIdToDelete = null;
      };
      cancelBtn.onclick = function() {
        document.getElementById('deleteOrderModal').style.display = 'none';
        orderIdToDelete = null;
      };
    }
});

// Enable/disable Create Order button based on required fields
function checkOrderFormValidity() {
    const orderType = document.getElementById('order_type').value;
    const partnerId = document.getElementById('partner_id').value;
    const productId = document.getElementById('product_id').value;
    const quantity = document.getElementById('quantity').value;
    const deliveryDate = document.getElementById('delivery_date').value;
    const submitBtn = document.getElementById('submitOrder');
    if (
        orderType &&
        partnerId &&
        productId &&
        quantity &&
        deliveryDate
    ) {
        submitBtn.disabled = false;
        submitBtn.style.opacity = 1;
        submitBtn.style.cursor = 'pointer';
    } else {
        submitBtn.disabled = true;
        submitBtn.style.opacity = 0.6;
        submitBtn.style.cursor = 'not-allowed';
    }
}
['order_type','partner_id','product_id','quantity','delivery_date'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('input', checkOrderFormValidity);
});
checkOrderFormValidity();
</script>
<!-- jsPDF CDN for PDF generation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<!-- Add Leaflet CSS in the head section if not already present -->
@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
@endpush
<!-- Add Leaflet JS before closing body if not already present -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<div id="trackOrderModal" style="display:none;position:fixed;z-index:9999;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.25);align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:14px;max-width:540px;width:95vw;padding:2rem 1.5rem;box-shadow:0 8px 32px rgba(0,0,0,0.18);position:relative;">
    <button id="closeTrackOrderModal" style="position:absolute;top:1rem;right:1rem;background:none;border:none;font-size:1.5rem;color:var(--primary);cursor:pointer;">&times;</button>
    <div id="trackOrderModalContent"></div>
  </div>
</div>
<div id="editOrderModal" style="display:none;position:fixed;z-index:9999;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.25);align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:14px;max-width:540px;width:95vw;padding:2rem 1.5rem;box-shadow:0 8px 32px rgba(0,0,0,0.18);position:relative;">
    <button id="closeEditOrderModal" style="position:absolute;top:1rem;right:1rem;background:none;border:none;font-size:1.5rem;color:var(--primary);cursor:pointer;">&times;</button>
    <div id="editOrderModalContent"></div>
  </div>
</div>
<div id="deleteOrderModal" style="display:none;position:fixed;z-index:10000;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.25);align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:14px;max-width:350px;width:90vw;padding:2rem 1.5rem;box-shadow:0 8px 32px rgba(0,0,0,0.18);position:relative;display:flex;flex-direction:column;align-items:center;">
    <div style="font-size:1.2rem;font-weight:700;color:var(--danger);margin-bottom:1rem;"><i class="fas fa-exclamation-triangle"></i> Confirm Delete</div>
    <div id="deleteOrderModalText" style="color:var(--text-dark);margin-bottom:1.5rem;text-align:center;"></div>
    <div style="display:flex;gap:1.2rem;">
      <button id="confirmDeleteOrderBtn" style="background:var(--danger);color:#fff;border:none;border-radius:8px;padding:0.5rem 1.5rem;font-weight:700;cursor:pointer;">Yes</button>
      <button id="cancelDeleteOrderBtn" style="background:#eee;color:#333;border:none;border-radius:8px;padding:0.5rem 1.5rem;font-weight:700;cursor:pointer;">No</button>
    </div>
  </div>
</div>
@endpush
@endsection