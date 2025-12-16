@extends('admin.layout')

@section('title', 'إنشاء منتج جديد')
@section('header', 'إنشاء منتج جديد')

@section('content')
    <div class="card">
        <form method="POST" action="/admin/products" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>اسم المنتج</label>
                <input type="text" name="name" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label>الفئة</label>
                <select name="category_id" required>
                    <option value="">اختر الفئة</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @if(old('category_id') == $cat->id) selected @endif>{{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>السعر (DA)</label>
                <input type="number" name="price" step="0.01" value="{{ old('price') }}" required>
            </div>

            <div class="form-group">
                <label>الوصف</label>
                <textarea name="description" rows="4">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_featured" value="1" @if(old('is_featured')) checked @endif>
                    منتج مميز
                </label>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_new" value="1" @if(old('is_new')) checked @endif>
                    منتج جديد
                </label>
            </div>

            <div class="form-group">
                <label>صور المنتج (حتى 3 صور)</label>
                <input type="file" name="images[]" multiple accept="image/*" />
                <small>أو أدخل روابط الصور أدناه</small>
            </div>

            <div id="image-urls">
                @for ($i = 0; $i < 3; $i++)
                    <div class="form-group">
                        <label>رابط الصورة {{ $i + 1 }}</label>
                        <input type="url" name="image_urls[]" placeholder="https://...">
                    </div>
                @endfor
            </div>

            <h3 style="margin-top: 2rem; margin-bottom: 1rem;">المتغيرات (أحجام، ألوان، إلخ)</h3>
            <div id="variations">
                <div class="variation-item"
                    style="background: #f9fafb; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 1rem; align-items: end;">
                        <div class="form-group" style="margin: 0;">
                            <label>نوع المتغير</label>
                            <select name="variations[0][type]">
                                <option value="">اختر</option>
                                @foreach($variationTypes as $type)
                                    <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" style="margin: 0;">
                            <label>القيمة</label>
                            <input type="text" name="variations[0][value]" placeholder="مثال: S, M, L">
                        </div>
                        <div class="form-group" style="margin: 0;">
                            <label>السعر الإضافي (اختياري)</label>
                            <input type="number" name="variations[0][additional_price]" step="0.01" value="0">
                        </div>
                        <button type="button" class="btn btn-danger" onclick="removeVariation(this)">حذف</button>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-secondary" onclick="addVariation()">+ إضافة متغير</button>

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary">إنشاء المنتج</button>
                <a href="/admin/products" class="btn btn-secondary">إلغاء</a>
            </div>
        </form>
    </div>

    <script>
        let variationCount = 1;

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
                            <input type="text" name="variations[${variationCount}][value]" placeholder="مثال: S, M, L">
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