@extends('layouts.dashboard')

@section('title', 'Vendor Dashboard')

@section('sidebar-content')
    @include('dashboards.vendor.sidebar')
@endsection

@section('content')
<div class="content-card">
    <h2 style="color: var(--primary); margin-bottom: 1.5rem; font-size: 1.8rem;">
        Products Overview
    </h2>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.2rem; flex-wrap: wrap; gap: 1rem;">
        <div style="display: flex; gap: 1rem; align-items: center;">
            <input id="productSearch" type="text" placeholder="Search products..." style="padding: 0.5rem 1rem; border-radius: 8px; border: 1px solid #ddd; font-size: 1rem;">
            <select id="productFilter" style="padding: 0.5rem 1rem; border-radius: 8px; border: 1px solid #ddd; font-size: 1rem;">
                <option value="">All Categories</option>
                <option value="Sedan">Sedan</option>
                <option value="SUV">SUV</option>
                <option value="Truck">Truck</option>
                <option value="Coupe">Coupe</option>
            </select>
        </div>
        <div style="display: flex; gap: 1rem; align-items: center;">
            <button id="toggleView" style="background: var(--primary); color: #fff; border: none; border-radius: 8px; padding: 0.5rem 1.2rem; font-weight: 600;">
                <i class="fas fa-th-large"></i> Grid View
            </button>
            <button style="background: var(--accent); color: #fff; border: none; border-radius: 8px; padding: 0.5rem 1.2rem; font-weight: 600;">
                <i class="fas fa-plus"></i> Add Product
            </button>
        </div>
    </div>
    <div id="productsTableView">
        <div class="table-responsive">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="productsTableBody">
                    <!-- JS will render rows here -->
                </tbody>
            </table>
        </div>
    </div>
    <div id="productsGridView" style="display: none;">
        <div class="products-grid">
            <!-- JS will render cards here -->
        </div>
    </div>
</div>
<!-- Add Product Modal -->
<div id="addProductModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.3); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:12px; padding:2rem; min-width:320px; max-width:90vw; box-shadow:0 8px 32px rgba(0,0,0,0.15);">
        <h3 style="color:var(--primary); font-size:1.2rem; font-weight:700; margin-bottom:1rem;">Add Product</h3>
        <form id="addProductForm" style="display:flex; flex-direction:column; gap:1rem;">
            <input id="addProductName" type="text" placeholder="Product Name" required style="padding:0.5rem; border-radius:8px; border:1px solid #ddd;">
            <select id="addProductCategory" required style="padding:0.5rem; border-radius:8px; border:1px solid #ddd;">
                <option value="">Select Category</option>
                <option value="Sedan">Sedan</option>
                <option value="SUV">SUV</option>
                <option value="Truck">Truck</option>
                <option value="Coupe">Coupe</option>
            </select>
            <input id="addProductPrice" type="number" placeholder="Price" required style="padding:0.5rem; border-radius:8px; border:1px solid #ddd;">
            <input id="addProductStock" type="number" placeholder="Stock" required style="padding:0.5rem; border-radius:8px; border:1px solid #ddd;">
            <input id="addProductImage" type="text" placeholder="Image URL (optional)" style="padding:0.5rem; border-radius:8px; border:1px solid #ddd;">
            <div style="display:flex; gap:1rem; justify-content:flex-end;">
                <button type="button" id="closeAddProductModal" style="background:#eee; color:#333; border:none; border-radius:8px; padding:0.5rem 1.2rem;">Cancel</button>
                <button type="submit" style="background:var(--primary); color:#fff; border:none; border-radius:8px; padding:0.5rem 1.2rem; font-weight:600;">Add</button>
            </div>
        </form>
    </div>
</div>
<!-- Edit Product Modal -->
<div id="editProductModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.3); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:12px; padding:2rem; min-width:320px; max-width:90vw; box-shadow:0 8px 32px rgba(0,0,0,0.15);">
        <h3 style="color:var(--primary); font-size:1.2rem; font-weight:700; margin-bottom:1rem;">Edit Product</h3>
        <form id="editProductForm" style="display:flex; flex-direction:column; gap:1rem;">
            <input id="editProductName" type="text" placeholder="Product Name" required style="padding:0.5rem; border-radius:8px; border:1px solid #ddd;">
            <select id="editProductCategory" required style="padding:0.5rem; border-radius:8px; border:1px solid #ddd;">
                <option value="">Select Category</option>
                <option value="Sedan">Sedan</option>
                <option value="SUV">SUV</option>
                <option value="Truck">Truck</option>
                <option value="Coupe">Coupe</option>
            </select>
            <input id="editProductPrice" type="number" placeholder="Price" required style="padding:0.5rem; border-radius:8px; border:1px solid #ddd;">
            <input id="editProductStock" type="number" placeholder="Stock" required style="padding:0.5rem; border-radius:8px; border:1px solid #ddd;">
            <input id="editProductImage" type="text" placeholder="Image URL (optional)" style="padding:0.5rem; border-radius:8px; border:1px solid #ddd;">
            <div style="display:flex; gap:1rem; justify-content:flex-end;">
                <button type="button" id="closeEditProductModal" style="background:#eee; color:#333; border:none; border-radius:8px; padding:0.5rem 1.2rem;">Cancel</button>
                <button type="submit" style="background:var(--primary); color:#fff; border:none; border-radius:8px; padding:0.5rem 1.2rem; font-weight:600;">Save</button>
            </div>
        </form>
    </div>
</div>
<style>
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 1.5rem;
    margin-top: 1rem;
}
.product-card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    padding: 1.2rem 1rem 1rem 1rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
}
.product-card img {
    width: 90px;
    height: 90px;
    object-fit: cover;
    border-radius: 10px;
    margin-bottom: 0.7rem;
}
.product-card .product-name {
    font-weight: 700;
    color: var(--primary);
    font-size: 1.1rem;
    margin-bottom: 0.2rem;
    text-align: center;
}
.product-card .product-category {
    color: var(--text-light);
    font-size: 0.98rem;
    margin-bottom: 0.3rem;
}
.product-card .product-price {
    color: var(--accent);
    font-weight: 600;
    font-size: 1.05rem;
    margin-bottom: 0.2rem;
}
.product-card .product-stock {
    color: var(--primary-light);
    font-size: 0.97rem;
    margin-bottom: 0.7rem;
}
.product-card .product-actions {
    display: flex;
    gap: 0.7rem;
}
.product-card .product-actions button {
    background: #f5f5f5;
    border: none;
    border-radius: 8px;
    padding: 0.4rem 0.9rem;
    cursor: pointer;
    color: var(--primary);
    font-weight: 600;
    font-size: 1rem;
    transition: background 0.2s;
}
.product-card .product-actions button.edit {
    color: var(--primary);
}
.product-card .product-actions button.delete {
    color: var(--danger);
}
</style>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
// Demo product data
const products = [
  { id: 1, name: 'Toyota Corolla 2022', category: 'Sedan', price: 22000, stock: 12, image: '/images/car1.png' },
  { id: 2, name: 'Honda CR-V 2023', category: 'SUV', price: 28000, stock: 7, image: '/images/car2.png' },
  { id: 3, name: 'Ford F-150', category: 'Truck', price: 35000, stock: 5, image: '/images/car3.png' },
  { id: 4, name: 'BMW 3 Series', category: 'Sedan', price: 41000, stock: 3, image: '/images/car4.png' },
  { id: 5, name: 'Chevrolet Camaro', category: 'Coupe', price: 37000, stock: 2, image: '/images/car5.png' },
  { id: 6, name: 'Kia Seltos', category: 'SUV', price: 25000, stock: 8, image: '/images/car6.png' },
];

function renderProductsTable(list) {
  document.getElementById('productsTableBody').innerHTML = list.map(p => `
    <tr data-idx="${products.findIndex(prod => prod.id === p.id)}">
      <td><img src="${p.image}" alt="${p.name}" style="width:48px;height:48px;object-fit:cover;border-radius:8px;"></td>
      <td>${p.name}</td>
      <td>${p.category}</td>
      <td>$${p.price.toLocaleString()}</td>
      <td>${p.stock}</td>
      <td>
        <button class="edit" style="background:var(--primary-light);color:var(--primary);border:none;border-radius:6px;padding:0.3rem 0.8rem;margin-right:0.3rem;cursor:pointer;"><i class="fas fa-edit"></i></button>
        <button class="delete" style="background:var(--danger-light);color:var(--danger);border:none;border-radius:6px;padding:0.3rem 0.8rem;cursor:pointer;"><i class="fas fa-trash"></i></button>
      </td>
    </tr>
  `).join('');
  attachProductActions();
}

function renderProductsGrid(list) {
  document.querySelector('.products-grid').innerHTML = list.map(p => `
    <div class="product-card" data-idx="${products.findIndex(prod => prod.id === p.id)}">
      <img src="${p.image}" alt="${p.name}">
      <div class="product-name">${p.name}</div>
      <div class="product-category">${p.category}</div>
      <div class="product-price">$${p.price.toLocaleString()}</div>
      <div class="product-stock">Stock: ${p.stock}</div>
      <div class="product-actions">
        <button class="edit"><i class="fas fa-edit"></i></button>
        <button class="delete"><i class="fas fa-trash"></i></button>
      </div>
    </div>
  `).join('');
  attachProductActions();
}

function filterProducts() {
  const search = document.getElementById('productSearch').value.toLowerCase();
  const filter = document.getElementById('productFilter').value;
  return products.filter(p =>
    (p.name.toLowerCase().includes(search) || p.category.toLowerCase().includes(search)) &&
    (!filter || p.category === filter)
  );
}

document.getElementById('productSearch').addEventListener('input', () => {
  const filtered = filterProducts();
  if (document.getElementById('productsTableView').style.display !== 'none') {
    renderProductsTable(filtered);
  } else {
    renderProductsGrid(filtered);
  }
});
document.getElementById('productFilter').addEventListener('change', () => {
  const filtered = filterProducts();
  if (document.getElementById('productsTableView').style.display !== 'none') {
    renderProductsTable(filtered);
  } else {
    renderProductsGrid(filtered);
  }
});
document.getElementById('toggleView').addEventListener('click', function() {
  const tableView = document.getElementById('productsTableView');
  const gridView = document.getElementById('productsGridView');
  if (tableView.style.display === 'none') {
    tableView.style.display = '';
    gridView.style.display = 'none';
    this.innerHTML = '<i class="fas fa-th-large"></i> Grid View';
  } else {
    tableView.style.display = 'none';
    gridView.style.display = '';
    this.innerHTML = '<i class="fas fa-table"></i> Table View';
  }
  const filtered = filterProducts();
  if (tableView.style.display === 'none') {
    renderProductsGrid(filtered);
  } else {
    renderProductsTable(filtered);
  }
});

// Add Product Modal logic
const addProductBtn = document.querySelector('button[style*="accent"]');
const addProductModal = document.getElementById('addProductModal');
const closeAddProductModal = document.getElementById('closeAddProductModal');
const addProductForm = document.getElementById('addProductForm');
addProductBtn.addEventListener('click', function() {
    addProductModal.style.display = 'flex';
});
closeAddProductModal.addEventListener('click', function() {
    addProductModal.style.display = 'none';
});
addProductModal.addEventListener('click', function(e) {
    if (e.target === addProductModal) addProductModal.style.display = 'none';
});
addProductForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const name = document.getElementById('addProductName').value;
    const category = document.getElementById('addProductCategory').value;
    const price = parseFloat(document.getElementById('addProductPrice').value);
    const stock = parseInt(document.getElementById('addProductStock').value);
    const image = document.getElementById('addProductImage').value || '/images/car1.png';
    products.push({ id: products.length+1, name, category, price, stock, image });
    addProductModal.style.display = 'none';
    // Re-render current view
    const filtered = filterProducts();
    if (document.getElementById('productsTableView').style.display !== 'none') {
        renderProductsTable(filtered);
    } else {
        renderProductsGrid(filtered);
    }
    addProductForm.reset();
});

// Edit Product Modal logic
let editProductId = null;

function attachProductActions() {
  // Edit buttons
  document.querySelectorAll('.edit').forEach(btn => {
    btn.onclick = function(e) {
      e.stopPropagation();
      const parent = btn.closest('tr') || btn.closest('.product-card');
      if (!parent) return;
      const idx = parseInt(parent.getAttribute('data-idx'));
      const filtered = filterProducts();
      const prod = filtered[idx];
      editProductId = prod.id; // Use the unique product id
      document.getElementById('editProductName').value = prod.name;
      document.getElementById('editProductCategory').value = prod.category;
      document.getElementById('editProductPrice').value = prod.price;
      document.getElementById('editProductStock').value = prod.stock;
      document.getElementById('editProductImage').value = prod.image;
      document.getElementById('editProductModal').style.display = 'flex';
    };
  });
  // Delete buttons
  document.querySelectorAll('.delete').forEach(btn => {
    btn.onclick = function(e) {
      e.stopPropagation();
      const parent = btn.closest('tr') || btn.closest('.product-card');
      if (!parent) return;
      const idx = parseInt(parent.getAttribute('data-idx'));
      const filtered = filterProducts();
      const prod = filtered[idx];
      const realIdx = products.findIndex(p => p.id === prod.id);
      if (confirm('Are you sure you want to delete this product?')) {
        products.splice(realIdx, 1);
        // Re-render current view
        const filteredNow = filterProducts();
        if (document.getElementById('productsTableView').style.display !== 'none') {
          renderProductsTable(filteredNow);
        } else {
          renderProductsGrid(filteredNow);
        }
      }
    };
  });
}

// Initial render
renderProductsTable(products);
});
</script>
@endpush
@endsection