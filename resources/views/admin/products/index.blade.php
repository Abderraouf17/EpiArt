@extends('admin.layout')

@section('title', 'المنتجات')
@section('header', 'إدارة المنتجات')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h3>قائمة المنتجات</h3>
        <button class="btn btn-primary add-product-btn">+ إضافة منتج جديد</button>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>الصورة</th>
                <th>الاسم</th>
                <th>الفئة</th>
                <th>السعر</th>
                <th>مميز</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>
                        @if($product->images->first())
                            <img src="{{ $product->images->first()->image_url }}" alt="{{ $product->name }}" style="width: 50px; height: 50px; border-radius: 4px; object-fit: cover;">
                        @else
                            <div style="width: 50px; height: 50px; background: #e5e7eb; border-radius: 4px;"></div>
                        @endif
                    </td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td>{{ number_format($product->price, 0) }} DA</td>
                    <td>
                        @if($product->is_featured)
                            <span class="badge badge-success">نعم</span>
                        @else
                            <span class="badge">لا</span>
                        @endif
                    </td>
                    <td>
                        <button data-product-id="{{ $product->id }}" class="btn btn-secondary edit-product-btn" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;">تعديل</button>
                        <form method="POST" action="/admin/products/{{ $product->id }}" style="display: inline;" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger delete-btn" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;" data-message="هل أنت متأكد من حذف هذا المنتج؟">حذف</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 1.5rem;">
        {{ $products->links() }}
    </div>
</div>

<!-- Product Modal -->
<div id="productModal" class="modal" style="display: none;">
    <div class="modal-content" style="max-width: 900px; max-height: 90vh; overflow-y: auto;">
        <div class="modal-header">
            <h3 id="modalTitle">إضافة منتج جديد</h3>
            <button onclick="closeProductModal()" class="close-btn">&times;</button>
        </div>
        <div class="modal-body">
            <form id="productForm" method="POST" action="/admin/products" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="id" id="productId">
                
                <div class="form-row">
                    <div class="form-group">
                        <label>اسم المنتج *</label>
                        <input type="text" name="name" id="productName" required>
                    </div>
                    <div class="form-group">
                        <label>الفئة *</label>
                        <select name="category_id" id="productCategory" required style="background-position: left 0.5rem center; padding-right: 0.75rem; padding-left: 2.5rem; direction: rtl;">
                            <option value="">اختر الفئة</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>السعر (DA) *</label>
                        <input type="number" name="price" id="productPrice" step="0.01" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>الوصف</label>
                    <textarea name="description" id="productDescription" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label>صور المنتج</label>
                    <input type="file" name="image_files[]" multiple accept="image/*" id="productImages" onchange="previewProductImages(event)" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 6px;" />
                    <div id="product-images-preview" style="display: flex; gap: 0.5rem; margin-top: 0.5rem; flex-wrap: wrap;"></div>
                </div>

                <div class="form-group">
                    <label style="font-weight: 600; margin-bottom: 0.5rem; display: block;">التنويعات (حتى 5 تنويعات)</label>
                    <div id="variantsContainer" style="display: flex; flex-direction: column; gap: 1rem; border: 1px solid #e5e7eb; padding: 1rem; border-radius: 6px; background: #f9fafb;">
                        <!-- Variants will be added here -->
                    </div>
                    <button type="button" onclick="addVariant()" class="btn" style="margin-top: 0.5rem; background: var(--primary); color: white; padding: 0.5rem 1rem; font-size: 0.875rem;">+ إضافة تنويع</button>
                    <small style="display: block; margin-top: 0.5rem; color: #6b7280;">يمكنك إضافة حتى 5 تنويعات (حجم، لون، قطع)</small>
                </div>

                <div class="form-group">
                    <div style="background: #fef3c7; border: 2px solid #fbbf24; border-radius: 8px; padding: 1rem; margin-top: 1rem;">
                        <label style="display: flex; align-items: start; gap: 0.75rem; cursor: pointer;">
                            <input type="checkbox" name="is_featured" id="productFeatured" value="1" style="width: 20px; height: 20px; margin-top: 2px; cursor: pointer;">
                            <div>
                                <span style="font-weight: 600; color: #92400e; display: block; margin-bottom: 0.25rem;">⭐ منتج مميز</span>
                                <small style="color: #78350f; display: block; line-height: 1.5;">سيظهر هذا المنتج في قسم "المنتجات المميزة" على الصفحة الرئيسية للموقع، مما يجذب انتباه العملاء ويزيد من فرص البيع.</small>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="closeProductModal()" class="btn btn-secondary">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}
.modal-content {
    background: white;
    border-radius: 8px;
    width: 90%;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
}
.modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.modal-header h3 {
    margin: 0;
    color: var(--primary);
}
.modal-body {
    padding: 1.5rem;
}
.modal-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid #e5e7eb;
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
}
.close-btn {
    background: none;
    border: none;
    font-size: 2rem;
    cursor: pointer;
    color: #6b7280;
    line-height: 1;
    padding: 0;
}
.close-btn:hover {
    color: #000;
}
.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}
@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
let variantCount = 0;

// Event delegation for product buttons
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('add-product-btn')) {
        openProductModal();
    }
    
    if (e.target.classList.contains('edit-product-btn') || e.target.closest('.edit-product-btn')) {
        const btn = e.target.classList.contains('edit-product-btn') ? e.target : e.target.closest('.edit-product-btn');
        const productId = btn.dataset.productId;
        openProductModal(productId);
    }
    
    if (e.target.classList.contains('delete-btn')) {
        e.preventDefault();
        const message = e.target.dataset.message || 'هل أنت متأكد من الحذف؟';
        confirmDelete(message).then((confirmed) => {
            if (confirmed) {
                e.target.closest('.delete-form').submit();
            }
        });
    }
});

function previewProductImages(event) {
    const previewContainer = document.getElementById('product-images-preview');
    previewContainer.innerHTML = '';
    
    const files = event.target.files;
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.style.cssText = 'position: relative; width: 80px; height: 80px;';
            div.innerHTML = `
                <img src="${e.target.result}" alt="Preview ${i+1}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 6px; border: 2px solid #8B3A3A;">
                <span style="position: absolute; top: 2px; left: 2px; background: rgba(139, 58, 58, 0.9); color: white; padding: 2px 6px; border-radius: 3px; font-size: 0.7rem;">${i+1}</span>
            `;
            previewContainer.appendChild(div);
        }
        
        reader.readAsDataURL(file);
    }
}

function previewImage(input) {
    const row = input.closest('.image-input-row');
    const preview = row.querySelector('.image-preview');
    const img = preview.querySelector('img');
    
    if (input.value) {
        img.src = input.value;
        preview.style.display = 'block';
    } else {
        preview.style.display = 'none';
    }
}

function previewImageFile(input) {
    const row = input.closest('.image-input-row');
    const preview = row.querySelector('.image-preview');
    const img = preview.querySelector('img');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
    }
}

function addImageInput() {
    const container = document.getElementById('imageInputs');
    const newRow = document.createElement('div');
    newRow.className = 'image-input-row';
    newRow.style.cssText = 'display: flex; gap: 0.5rem; align-items: center; margin-top: 0.5rem;';
    newRow.innerHTML = `
        <select class="image-type" style="width: 120px; padding: 0.5rem; border: 1px solid #ddd; border-radius: 6px;" onchange="toggleImageInput(this)">
            <option value="url">رابط URL</option>
            <option value="upload">رفع ملف</option>
        </select>
        <input type="text" name="image_urls[]" class="image-url-input" placeholder="https://example.com/image.jpg" style="flex: 1; padding: 0.5rem; border: 1px solid #ddd; border-radius: 6px;" onchange="previewImage(this)">
        <input type="file" name="image_files[]" class="image-file-input" accept="image/*" style="display: none; flex: 1;" onchange="previewImageFile(this)">
        <div class="image-preview" style="width: 50px; height: 50px; border: 1px solid #ddd; border-radius: 4px; overflow: hidden; display: none;">
            <img src="" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        <button type="button" onclick="removeImageInput(this)" class="btn btn-danger" style="padding: 0.5rem 0.75rem; font-size: 0.875rem;">×</button>
    `;
    container.appendChild(newRow);
}

function removeImageInput(btn) {
    btn.closest('.image-input-row').remove();
}

function toggleImageInput(select) {
    const row = select.closest('.image-input-row');
    const urlInput = row.querySelector('.image-url-input');
    const fileInput = row.querySelector('.image-file-input');
    
    if (select.value === 'url') {
        urlInput.style.display = 'block';
        fileInput.style.display = 'none';
        fileInput.value = '';
    } else {
        urlInput.style.display = 'none';
        fileInput.style.display = 'block';
        urlInput.value = '';
    }
}

function addVariant() {
    const container = document.getElementById('variantsContainer');
    if (variantCount >= 5) {
        alert('لا يمكن إضافة أكثر من 5 تنويعات');
        return;
    }
    
    variantCount++;
    const variantDiv = document.createElement('div');
    variantDiv.className = 'variant-row';
    variantDiv.style.cssText = 'display: grid; grid-template-columns: 150px 1fr 150px auto; gap: 0.5rem; align-items: center; padding: 0.75rem; background: white; border-radius: 6px;';
    variantDiv.innerHTML = `
        <select name="variants[${variantCount}][type]" style="padding: 0.5rem; border: 1px solid #ddd; border-radius: 6px;" onchange="updateVariantValuePlaceholder(this)">
            <option value="">نوع التنويع</option>
            <option value="size">الحجم</option>
            <option value="color">اللون</option>
            <option value="quantity">القطع</option>
        </select>
        <input type="text" name="variants[${variantCount}][value]" class="variant-value" placeholder="القيمة" style="padding: 0.5rem; border: 1px solid #ddd; border-radius: 6px;">
        <input type="number" name="variants[${variantCount}][price]" placeholder="السعر" step="0.01" style="padding: 0.5rem; border: 1px solid #ddd; border-radius: 6px;">
        <button type="button" onclick="removeVariant(this)" class="btn btn-danger" style="padding: 0.5rem 0.75rem; font-size: 0.875rem;">×</button>
    `;
    container.appendChild(variantDiv);
}

function removeVariant(btn) {
    btn.closest('.variant-row').remove();
    variantCount--;
}

function updateVariantValuePlaceholder(select) {
    const row = select.closest('.variant-row');
    const valueInput = row.querySelector('.variant-value');
    
    const placeholders = {
        'size': 'مثال: 100g, 250ml, 1kg, 500ml',
        'color': 'مثال: أحمر, أخضر, أصفر',
        'quantity': 'مثال: 5 قطع, 10 قطع'
    };
    
    valueInput.placeholder = placeholders[select.value] || 'القيمة';
}

function openProductModal(productId = null) {
    const modal = document.getElementById('productModal');
    const form = document.getElementById('productForm');
    const modalTitle = document.getElementById('modalTitle');
    const formMethod = document.getElementById('formMethod');
    
    // Reset variants
    document.getElementById('variantsContainer').innerHTML = '';
    variantCount = 0;
    
    if (productId) {
        modalTitle.textContent = 'تعديل المنتج';
        formMethod.value = 'PUT';
        form.action = `/admin/products/${productId}`;
        
        // Fetch product data and populate form
        fetch(`/admin/products/${productId}/edit`, {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
            .then(res => res.json())
            .then(data => {
                document.getElementById('productId').value = data.id || '';
                document.getElementById('productName').value = data.name || '';
                document.getElementById('productCategory').value = data.category_id || '';
                document.getElementById('productPrice').value = data.price || '';
                document.getElementById('productDescription').value = data.description || '';
                document.getElementById('productFeatured').checked = data.is_featured || false;
                
                // Load variants
                if (data.variations && data.variations.length > 0) {
                    data.variations.forEach(variant => {
                        variantCount++;
                        const variantDiv = document.createElement('div');
                        variantDiv.className = 'variant-row';
                        variantDiv.style.cssText = 'display: grid; grid-template-columns: 150px 1fr 150px auto; gap: 0.5rem; align-items: center; padding: 0.75rem; background: white; border-radius: 6px;';
                        variantDiv.innerHTML = `
                            <input type="hidden" name="variants[${variantCount}][id]" value="${variant.id}">
                            <select name="variants[${variantCount}][type]" style="padding: 0.5rem; border: 1px solid #ddd; border-radius: 6px;" onchange="updateVariantValuePlaceholder(this)">
                                <option value="">نوع التنويع</option>
                                <option value="size" ${variant.type === 'size' ? 'selected' : ''}>الحجم</option>
                                <option value="color" ${variant.type === 'color' ? 'selected' : ''}>اللون</option>
                                <option value="quantity" ${variant.type === 'quantity' ? 'selected' : ''}>القطع</option>
                            </select>
                            <input type="text" name="variants[${variantCount}][value]" value="${variant.value}" class="variant-value" placeholder="القيمة" style="padding: 0.5rem; border: 1px solid #ddd; border-radius: 6px;">
                            <input type="number" name="variants[${variantCount}][price]" value="${variant.additional_price}" placeholder="السعر" step="0.01" style="padding: 0.5rem; border: 1px solid #ddd; border-radius: 6px;">
                            <button type="button" onclick="removeVariant(this)" class="btn btn-danger" style="padding: 0.5rem 0.75rem; font-size: 0.875rem;">×</button>
                        `;
                        document.getElementById('variantsContainer').appendChild(variantDiv);
                    });
                }
            });
    } else {
        modalTitle.textContent = 'إضافة منتج جديد';
        formMethod.value = 'POST';
        form.action = '/admin/products';
        form.reset();
    }
    
    modal.style.display = 'flex';
}

function closeProductModal() {
    document.getElementById('productModal').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('productModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeProductModal();
    }
});

// Add event listener to all initial image type selects
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.image-type').forEach(select => {
        select.addEventListener('change', function() {
            toggleImageInput(this);
        });
    });
});
</script>
@endsection
