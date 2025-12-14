@extends('admin.layout')

@section('title', 'الفئات')
@section('header', 'إدارة الفئات')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h3>قائمة الفئات</h3>
        <button class="btn btn-primary add-category-btn">+ إضافة فئة جديدة</button>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>الصورة</th>
                <th>الاسم</th>
                <th>عدد المنتجات</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
                <tr>
                    <td>
                        @if($category->image_url)
                            <img src="{{ $category->image_url }}" alt="{{ $category->name }}" style="width: 50px; height: 50px; border-radius: 4px; object-fit: cover;">
                        @else
                            <div style="width: 50px; height: 50px; background: #e5e7eb; border-radius: 4px;"></div>
                        @endif
                    </td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->products->count() }}</td>
                    <td>
                        <button data-category-id="{{ $category->id }}" class="btn btn-secondary edit-category-btn" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;">تعديل</button>
                        <form method="POST" action="/admin/categories/{{ $category->id }}" style="display: inline;" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger delete-btn" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;" data-message="هل أنت متأكد من حذف هذه الفئة؟">حذف</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 1.5rem;">
        {{ $categories->links() }}
    </div>
</div>

<!-- Category Modal -->
<div id="categoryModal" class="modal" style="display: none;">
    <div class="modal-content" style="max-width: 700px; max-height: 90vh; overflow-y: auto;">
        <div class="modal-header">
            <h3 id="categoryModalTitle">إضافة فئة جديدة</h3>
            <button onclick="closeCategoryModal()" class="close-btn">&times;</button>
        </div>
        <div class="modal-body">
            <form id="categoryForm" method="POST" action="/admin/categories">
                @csrf
                <input type="hidden" name="_method" id="categoryFormMethod" value="POST">
                
                <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>اسم الفئة *</label>
                        <input type="text" name="name" id="categoryName" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 6px;">
                    </div>
                    
                    <div class="form-group">
                        <label>الرقم</label>
                        <input type="text" name="number" id="categoryNumber" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 6px;" placeholder="مثال: CAT-001">
                    </div>
                </div>

                <div class="form-group">
                    <label>الوصف</label>
                    <textarea name="description" id="categoryDescription" rows="3" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 6px;"></textarea>
                </div>

                <div class="form-group">
                    <label>صورة الفئة</label>
                    <input type="file" name="image" accept="image/*" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 6px;" />
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="closeCategoryModal()" class="btn btn-secondary">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Event delegation for category buttons
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('add-category-btn')) {
        openCategoryModal();
    }
    
    if (e.target.classList.contains('edit-category-btn') || e.target.closest('.edit-category-btn')) {
        const btn = e.target.classList.contains('edit-category-btn') ? e.target : e.target.closest('.edit-category-btn');
        const categoryId = btn.dataset.categoryId;
        openCategoryModal(categoryId);
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

function addCategoryImageInput() {
    const container = document.getElementById('categoryImageInputs');
    const newRow = document.createElement('div');
    newRow.className = 'category-image-row';
    newRow.style.cssText = 'display: flex; gap: 0.5rem; align-items: center; margin-top: 0.5rem;';
    newRow.innerHTML = `
        <select class="category-image-type" style="width: 120px; padding: 0.5rem; border: 1px solid #ddd; border-radius: 6px;" onchange="toggleCategoryImageInput(this)">
            <option value="url">رابط URL</option>
            <option value="upload">رفع ملف</option>
        </select>
        <input type="text" name="category_image_urls[]" class="category-image-url" placeholder="https://example.com/image.jpg" style="flex: 1; padding: 0.5rem; border: 1px solid #ddd; border-radius: 6px;">
        <input type="file" name="category_image_files[]" class="category-image-file" accept="image/*" style="display: none; flex: 1;">
        <button type="button" onclick="removeCategoryImageInput(this)" class="btn btn-danger" style="padding: 0.5rem 0.75rem; font-size: 0.875rem;">×</button>
    `;
    container.appendChild(newRow);
}

function removeCategoryImageInput(btn) {
    btn.closest('.category-image-row').remove();
}

function toggleCategoryImageInput(select) {
    const row = select.closest('.category-image-row');
    const urlInput = row.querySelector('.category-image-url');
    const fileInput = row.querySelector('.category-image-file');
    
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

function openCategoryModal(categoryId = null) {
    const modal = document.getElementById('categoryModal');
    const form = document.getElementById('categoryForm');
    const modalTitle = document.getElementById('categoryModalTitle');
    const formMethod = document.getElementById('categoryFormMethod');
    
    if (categoryId) {
        modalTitle.textContent = 'تعديل الفئة';
        formMethod.value = 'PUT';
        form.action = `/admin/categories/${categoryId}`;
        
        fetch(`/admin/categories/${categoryId}/edit`, {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
            .then(res => res.json())
            .then(data => {
                document.getElementById('categoryName').value = data.name || '';
                document.getElementById('categoryNumber').value = data.number || '';
                document.getElementById('categoryDescription').value = data.description || '';
                
                // Set up image inputs
                const imageContainer = document.getElementById('categoryImageInputs');
                imageContainer.innerHTML = '';
                
                if (data.image_url) {
                    const row = document.createElement('div');
                    row.className = 'category-image-row';
                    row.style.cssText = 'display: flex; gap: 0.5rem; align-items: center;';
                    row.innerHTML = `
                        <select class="category-image-type" style="width: 120px; padding: 0.5rem; border: 1px solid #ddd; border-radius: 6px;" onchange="toggleCategoryImageInput(this)">
                            <option value="url">رابط URL</option>
                            <option value="upload">رفع ملف</option>
                        </select>
                        <input type="text" name="category_image_urls[]" class="category-image-url" value="${data.image_url}" style="flex: 1; padding: 0.5rem; border: 1px solid #ddd; border-radius: 6px;">
                        <input type="file" name="category_image_files[]" class="category-image-file" accept="image/*" style="display: none; flex: 1;">
                    `;
                    imageContainer.appendChild(row);
                } else {
                    // Add default empty row
                    addDefaultCategoryImageRow();
                }
            });
    } else {
        modalTitle.textContent = 'إضافة فئة جديدة';
        formMethod.value = 'POST';
        form.action = '/admin/categories';
        form.reset();
        
        // Reset to one default image input
        const imageContainer = document.getElementById('categoryImageInputs');
        imageContainer.innerHTML = '';
        addDefaultCategoryImageRow();
    }
    
    modal.style.display = 'flex';
}

function addDefaultCategoryImageRow() {
    const imageContainer = document.getElementById('categoryImageInputs');
    const row = document.createElement('div');
    row.className = 'category-image-row';
    row.style.cssText = 'display: flex; gap: 0.5rem; align-items: center;';
    row.innerHTML = `
        <select class="category-image-type" style="width: 120px; padding: 0.5rem; border: 1px solid #ddd; border-radius: 6px;" onchange="toggleCategoryImageInput(this)">
            <option value="url">رابط URL</option>
            <option value="upload">رفع ملف</option>
        </select>
        <input type="text" name="category_image_urls[]" class="category-image-url" placeholder="https://example.com/image.jpg" style="flex: 1; padding: 0.5rem; border: 1px solid #ddd; border-radius: 6px;">
        <input type="file" name="category_image_files[]" class="category-image-file" accept="image/*" style="display: none; flex: 1;">
    `;
    imageContainer.appendChild(row);
}

function closeCategoryModal() {
    document.getElementById('categoryModal').style.display = 'none';
}

document.getElementById('categoryModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeCategoryModal();
    }
});
</script>
@endsection
