@extends('layouts.dashboard')

@section('title', 'Vendor Orders')

@section('sidebar-content')
    @include('dashboards.vendor.sidebar')
@endsection

@section('content')
<div class="content-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="color: var(--primary); font-size: 1.8rem; margin: 0;">
            <i class="fas fa-file-invoice"></i> Orders to Manufacturers
    </h2>
        <a href="{{ route('vendor.retailer-orders.index') }}" class="button" style="background: #007bff; color: white; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; font-weight: 600;">
            <i class="fas fa-list"></i> View Retailer Orders
        </a>
    </div>
    
    <!-- Order Form -->
    <div class="order-form-container" style="background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h3 style="color: var(--primary); margin-bottom: 1.5rem; font-size: 1.4rem; font-weight: 700;">
            <i class="fas fa-plus-circle"></i> Create New Order to Manufacturer
        </h3>
        
        <form id="newOrderForm" method="POST" action="{{ route('vendor.orders.create') }}" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            @csrf
            
            <!-- Manufacturer Selection -->
            <div class="form-group">
                <label for="partner_id" style="font-weight: 600; color: #374151; margin-bottom: 0.5rem; display: block;">Select Manufacturer</label>
                <select id="partner_id" name="partner_id" required style="width: 100%; padding: 0.8rem 1rem; border-radius: 8px; border: 1px solid #d1d5db; font-size: 1rem; background: #f9fafb;">
                    <option value="">Select Manufacturer</option>
                    @foreach($manufacturers as $manufacturer)
                        <option value="{{ $manufacturer->id }}">{{ $manufacturer->name }}{{ $manufacturer->company ? ' - ' . $manufacturer->company : '' }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Product Selection -->
            <div class="form-group" id="product_selection" style="display: none;">
                <label for="product_id" style="font-weight: 600; color: #374151; margin-bottom: 0.5rem; display: block;">Select Product</label>
                <select id="product_id" name="product_id" required style="width: 100%; padding: 0.8rem 1rem; border-radius: 8px; border: 1px solid #d1d5db; font-size: 1rem; background: #f9fafb;" disabled>
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
    
    <!-- Orders Table -->
    <h3 style="margin-top:2rem; color:var(--primary); font-size:1.3rem;">Your Orders to Manufacturers</h3>
    <table class="order-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Manufacturer</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Status</th>
                <th>Ordered At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->manufacturer->name ?? 'Unknown' }}</td>
                    <td>{{ $order->product_name ?? $order->product }}</td>
                    <td>{{ $order->quantity }}</td>
                    <td>{{ ucfirst($order->status) }}</td>
                    <td>{{ $order->ordered_at ? \Carbon\Carbon::parse($order->ordered_at)->format('Y-m-d') : '' }}</td>
                    <td>
                        <form method="POST" action="{{ route('vendor.orders.destroy', $order->id) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="button button-red" onclick="return confirm('Delete this order?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center">No orders found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<style>
.order-table {
    width: 100%; border-collapse: collapse; margin-bottom: 1.2rem; background: #fff; border-radius: 10px; overflow: hidden;
}
.order-table th, .order-table td {
    padding: 0.7rem 0.8rem; text-align: left; border-bottom: 1px solid #f0f0f0;
}
.order-table th { background: #f8f8f8; color: var(--primary); font-weight: 700; }
.order-table tr:last-child td { border-bottom: none; }
.button { padding: 0.4rem 0.8rem; border: none; border-radius: 6px; cursor: pointer; font-size: 0.9rem; }
.button-red { background: #dc3545; color: white; }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing order form...');
    
    // Sample data for products
    const products = [
        { id: 1, name: 'Toyota Corolla 2022', category: 'Sedan', price: 22000 },
        { id: 2, name: 'Honda CR-V 2023', category: 'SUV', price: 28000 },
        { id: 3, name: 'Ford F-150', category: 'Truck', price: 35000 },
        { id: 4, name: 'BMW 3 Series', category: 'Sedan', price: 41000 }
    ];
    
    // Get form elements
        const partnerSelect = document.getElementById('partner_id');
    const productSelection = document.getElementById('product_selection');
    const productSelect = document.getElementById('product_id');
    const quantityInput = document.getElementById('quantity_input');
    const deliveryDateInput = document.getElementById('delivery_date_input');
    const submitOrderBtn = document.getElementById('submitOrder');
    const cancelOrderBtn = document.getElementById('cancelOrder');
    
    console.log('Form elements found:', {
        partnerSelect: !!partnerSelect,
        productSelection: !!productSelection,
        productSelect: !!productSelect,
        quantityInput: !!quantityInput,
        deliveryDateInput: !!deliveryDateInput,
        submitOrderBtn: !!submitOrderBtn,
        cancelOrderBtn: !!cancelOrderBtn
    });
    
    // Manufacturer selection handler
    if (partnerSelect) {
        partnerSelect.addEventListener('change', function() {
        const partnerId = this.value;
        // Clear previous options
            if (productSelect) {
        productSelect.innerHTML = '<option value="">Select Product</option>';
                productSelect.disabled = true;
            }
        if (partnerId) {
                if (productSelection) productSelection.style.display = 'block';
                // Fetch products for this manufacturer
                fetch(`/vendor/products/by-manufacturer/${partnerId}`)
                    .then(res => res.json())
                    .then(products => {
                        if (products.length > 0) {
                            productSelect.disabled = false;
            products.forEach(product => {
                const option = document.createElement('option');
                option.value = product.id;
                                option.textContent = `${product.name} (${product.category}) - $${product.price}`;
                productSelect.appendChild(option);
            });
        } else {
                            productSelect.disabled = true;
                        }
                    });
            } else {
                if (productSelection) productSelection.style.display = 'none';
                productSelect.disabled = true;
            hideFormFieldsAfterProduct();
        }
            checkOrderFormValidity();
    });
    }
    
    // Product selection handler
    if (productSelect) {
        productSelect.addEventListener('change', function() {
            console.log('Product changed to:', this.value);
        const productId = this.value;
        
        if (productId) {
                console.log('Showing quantity and delivery date inputs');
                if (quantityInput) quantityInput.style.display = 'block';
                if (deliveryDateInput) deliveryDateInput.style.display = 'block';
                if (submitOrderBtn) submitOrderBtn.style.display = 'inline-block';
        } else {
                console.log('Hiding quantity and delivery date inputs');
            hideFormFieldsAfterProduct();
        }
            checkOrderFormValidity();
    });
    }
    
    // Cancel button handler
    if (cancelOrderBtn) {
        cancelOrderBtn.addEventListener('click', function() {
            console.log('Cancel button clicked');
        resetOrderForm();
    });
    }
    
    // Form submission handler
    const newOrderForm = document.getElementById('newOrderForm');
    if (newOrderForm) {
        newOrderForm.addEventListener('submit', function(e) {
        e.preventDefault();
            console.log('Form submitted');
        const form = this;
        const formData = new FormData(form);
        const messageDiv = document.getElementById('orderFormMessage');
            
            // Add order_type to form data
            formData.append('order_type', 'manufacturer');
            
            if (submitOrderBtn) {
                submitOrderBtn.disabled = true;
                submitOrderBtn.textContent = 'Creating...';
            }
            if (messageDiv) {
        messageDiv.textContent = '';
        messageDiv.style.color = '';
            }

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
                    if (messageDiv) {
                messageDiv.textContent = data.message;
                messageDiv.style.color = 'green';
                    }
                form.reset();
                hideAllFormFields();
                    // Reload the page to show the new order
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
            } else if (data.errors) {
                // Laravel validation errors
                    if (messageDiv) {
                messageDiv.textContent = Object.values(data.errors).flat().join(' ');
                messageDiv.style.color = 'red';
                    }
            } else {
                    if (messageDiv) {
                messageDiv.textContent = data.message || 'Failed to create order.';
                messageDiv.style.color = 'red';
                    }
            }
        })
            .catch((error) => {
                console.error('Form submission error:', error);
                if (messageDiv) {
            messageDiv.textContent = 'Server error. Please try again.';
            messageDiv.style.color = 'red';
                }
        })
        .finally(() => {
                if (submitOrderBtn) {
                    submitOrderBtn.disabled = false;
                    submitOrderBtn.textContent = 'Create Order';
                }
            });
        });
    }
    
    // Enable/disable Create Order button based on required fields
    function checkOrderFormValidity() {
        const partnerId = partnerSelect ? partnerSelect.value : '';
        const productId = productSelect ? productSelect.value : '';
        const quantity = quantityInput ? quantityInput.querySelector('input')?.value : '';
        const deliveryDate = deliveryDateInput ? deliveryDateInput.querySelector('input')?.value : '';
        
        console.log('Form validity check:', { partnerId, productId, quantity, deliveryDate });
        
        if (submitOrderBtn) {
            if (partnerId && productId && quantity && deliveryDate) {
                submitOrderBtn.disabled = false;
                submitOrderBtn.style.opacity = 1;
                submitOrderBtn.style.cursor = 'pointer';
                console.log('Form is valid, enabling submit button');
        } else {
                submitOrderBtn.disabled = true;
                submitOrderBtn.style.opacity = 0.6;
                submitOrderBtn.style.cursor = 'not-allowed';
                console.log('Form is invalid, disabling submit button');
            }
        }
    }

    // Attach input listeners
    const inputIds = ['partner_id', 'product_id', 'quantity', 'delivery_date'];
    inputIds.forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('input', checkOrderFormValidity);
            console.log('Added input listener for:', id);
        } else {
            console.log('Element not found:', id);
        }
    });
    
    // Initial form validity check
    checkOrderFormValidity();

    function hideAllFormFields() {
        console.log('Hiding all form fields');
        if (productSelection) productSelection.style.display = 'none';
        hideFormFieldsAfterProduct();
        // Always show Create and Cancel buttons
        if (submitOrderBtn) submitOrderBtn.style.display = 'inline-block';
        if (cancelOrderBtn) cancelOrderBtn.style.display = 'inline-block';
        checkOrderFormValidity();
    }

    function hideFormFieldsAfterProduct() {
        console.log('Hiding fields after product');
        if (quantityInput) quantityInput.style.display = 'none';
        if (deliveryDateInput) deliveryDateInput.style.display = 'none';
        // Always show Create and Cancel buttons
        if (submitOrderBtn) submitOrderBtn.style.display = 'inline-block';
        if (cancelOrderBtn) cancelOrderBtn.style.display = 'inline-block';
    }

    function resetOrderForm() {
        console.log('Resetting order form');
        if (newOrderForm) newOrderForm.reset();
        hideAllFormFields();
        // Always show Create and Cancel buttons
        if (submitOrderBtn) submitOrderBtn.style.display = 'inline-block';
        if (cancelOrderBtn) cancelOrderBtn.style.display = 'inline-block';
        checkOrderFormValidity();
    }

    // Set minimum date for delivery date input
    const deliveryDateInputElement = deliveryDateInput ? deliveryDateInput.querySelector('input') : null;
    if (deliveryDateInputElement) {
    const today = new Date().toISOString().split('T')[0];
        deliveryDateInputElement.min = today;
        console.log('Set minimum delivery date to:', today);
    }

    console.log('Order form initialization complete');
});
</script>
@endpush
@endsection