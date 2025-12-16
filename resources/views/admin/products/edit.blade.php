@extends('admin.layout')

@section('title', 'تعديل المنتج')
@section('header', 'تعديل المنتج')

@section('content')
    <div class="card">
        <form method="POST" action="/admin/products/{{ $product->id }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>اسم المنتج</label>
                <input type="text" name="name" value="{{ $product->name }}" required>
            </div>

            <div class="form-group">
                <label>الفئة</label>
                <select name="category_id" required
                    style="background-position: left 0.5rem center; padding-right: 0.75rem; padding-left: 2.5rem; direction: rtl;">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @if($product->category_id == $cat->id) selected @endif>{{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>السعر (DA)</label>
                <input type="number" name="price" step="0.01" value="{{ $product->price }}" required>
            </div>

            <div class="form-group">
                <label>الوصف</label>
                <textarea name="description" rows="4">{{ $product->description }}</textarea>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_featured" value="1" @if($product->is_featured) checked @endif>
                    منتج مميز
                </label>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_new" value="1" @if($product->is_new) checked @endif>
                    منتج جديد
                </label>
            </div>

            <div class="form-group">
                <label>صور المنتج (حتى 3 صور)</label>

                <div style="display: flex; gap: 0.5rem; margin-bottom: 1rem; flex-wrap: wrap;" id="all-images-container">
                    @foreach($product->images as $image)
                        <div style="position: relative; width: 80px; height: 80px;" class="existing-image"
                            data-image-id="{{ $image->id }}">
                            <img src="{{ $image->image_url }}" alt="{{ $product->name }}"
                                style="width: 100%; height: 100%; object-fit: cover; border-radius: 6px; border: 2px solid #8B3A3A;">
                            <button type="button" class="delete-image-btn"
                                style="position: absolute; top: -5px; right: -5px; width: 20px; height: 20px; border-radius: 50%; background: #dc2626; color: white; border: none; cursor: pointer; font-size: 12px; line-height: 1; padding: 0; display: flex; align-items: center; justify-content: center;">×</button>
                        </div>
                    @endforeach
                </div>

                <input type="file" name="image_files[]" multiple accept="image/*" id="imageUpload"
                    onchange="previewNewImages(event)"
                    style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 6px;" />
                <small style="display: block; margin-top: 0.5rem; color: #6b7280;">يمكنك رفع حتى 3 صور للمنتج</small>
            </div>

            <script>
                const maxImages = 3;

                function countCurrentImages() {
                    const existing = document.querySelectorAll('.existing-image').length;
                    const newImages = document.querySelectorAll('.new-image-preview').length;
                    return existing + newImages;
                }

                // Handle delete button clicks
                document.addEventListener('click', function (e) {
                    if (e.target.classList.contains('delete-image-btn')) {
                        const imageDiv = e.target.closest('.existing-image');
                        const imageId = imageDiv.dataset.imageId;

                        if (confirm('هل أنت متأكد من حذف هذه الصورة؟')) {
                            fetch(`/admin/product-images/${imageId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            })
                                .then(res => res.json())
                                .then(data => {
                                    imageDiv.remove();
                                    showNotification('تم حذف الصورة بنجاح');
                                })
                                .catch(err => {
                                    console.error('Error:', err);
                                    alert('حدث خطأ أثناء حذف الصورة');
                                });
                        }
                    }
                });

                function previewNewImages(event) {
                    const container = document.getElementById('all-images-container');
                    const files = event.target.files;

                    // Remove old previews
                    document.querySelectorAll('.new-image-preview').forEach(el => el.remove());

                    const currentCount = countCurrentImages();
                    const availableSlots = maxImages - document.querySelectorAll('.existing-image').length;

                    if (files.length > availableSlots) {
                        alert(`يمكنك رفع ${availableSlots} صورة فقط (الحد الأقصى 3 صور)`);
                        event.target.value = '';
                        return;
                    }

                    for (let i = 0; i < files.length && i < availableSlots; i++) {
                        const file = files[i];
                        const reader = new FileReader();

                        reader.onload = function (e) {
                            const div = document.createElement('div');
                            div.className = 'new-image-preview';
                            div.style.cssText = 'position: relative; width: 80px; height: 80px;';
                            div.innerHTML = `
                            <img src="${e.target.result}" alt="Preview ${i + 1}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 6px; border: 2px solid #10b981;">
                            <span style="position: absolute; top: 2px; left: 2px; background: rgba(16, 185, 129, 0.9); color: white; padding: 2px 6px; border-radius: 3px; font-size: 0.7rem;">جديد</span>
                        `;
                            container.appendChild(div);
                        }

                        reader.readAsDataURL(file);
                    }
                }

                function showNotification(message) {
                    // Use existing notification system if available
                    if (typeof window.showNotification === 'function') {
                        window.showNotification(message);
                    } else {
                        alert(message);
                    }
                }
            </script>

            <h3 style="margin-top: 2rem; margin-bottom: 1rem;">المتغيرات</h3>
            <div id="variations">
                @foreach($product->variations as $index => $variation)
                    <div class="variation-item"
                        style="background: #f9fafb; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 1rem; align-items: end;">
                            <div class="form-group" style="margin: 0;">
                                <label>نوع المتغير</label>
                                <select name="variations[{{ $index }}][type]"
                                    style="background-position: left 0.5rem center; padding-right: 0.75rem; padding-left: 2.5rem; direction: rtl;">
                                    <option value="">اختر</option>
                                    @foreach($variationTypes as $type)
                                        <option value="{{ $type }}" @if($variation->type == $type) selected @endif>
                                            {{ ucfirst($type) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" style="margin: 0;">
                                <label>القيمة</label>
                                <input type="text" name="variations[{{ $index }}][value]" value="{{ $variation->value }}">
                            </div>
                            <div class="form-group" style="margin: 0;">
                                <label>السعر الإضافي</label>
                                <input type="number" name="variations[{{ $index }}][additional_price]" step="0.01"
                                    value="{{ $variation->additional_price }}">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <button type="button" class="btn btn-secondary" onclick="addVariation()">+ إضافة متغير</button>

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                <a href="/admin/products" class="btn btn-secondary">إلغاء</a>
            </div>
        </form>
    </div>

    @php
        $variationCountValue = $product->variations->count();
    @endphp

    <div id="js-data" data-variation-count="{{ $variationCountValue }}" style="display:none;"></div>

    <script type="text/javascript">
        'use strict';
        let variationCount = parseInt(document.getElementById('js-data').dataset.variationCount);

        function addVariation() {
            const html = `
                <div class="variation-item" style="background: #f9fafb; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 1rem; align-items: end;">
                        <div class="form-group" style="margin: 0;">
                            <label>نوع المتغير</label>
                            <select name="variations[${variationCount}][type]">
                                <option value="">اختر</option>
                                <option value="size">Size</option>
                                <option value="color">Color</option>
                                <option value="weight">Weight</option>
                                <option value="volume">Volume</option>
                                <option value="quantity">Quantity</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin: 0;">
                            <label>القيمة</label>
                            <input type="text" name="variations[${variationCount}][value]">
                        </div>
                        <div class="form-group" style="margin: 0;">
                            <label>السعر الإضافي</label>
                            <input type="number" name="variations[${variationCount}][additional_price]" step="0.01" value="0">
                        </div>
                        <button type="button" class="btn btn-danger" onclick="removeVariation(this)">حذف</button>
                    </div>
                </div>
            `;
            document.getElementById('variations').insertAdjacentHTML('beforeend', html);
            variationCount++;
        }

        function removeVariation(btn) {
            btn.closest('.variation-item').remove();
        }
    </script>
@endsection