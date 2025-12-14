@extends('admin.layout')

@section('title', 'تعديل قاعدة الشحن')
@section('header', 'تعديل قاعدة الشحن')

@section('content')
<div class="card">
    <form method="POST" action="/admin/shipping/{{ $shipping->id }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>اسم الولاية</label>
            <input type="text" name="wilaya" value="{{ $shipping->wilaya }}" required>
        </div>

        <div class="form-group">
            <label>كود الولاية</label>
            <input type="text" name="wilaya_code" value="{{ $shipping->wilaya_code }}" required maxlength="10">
        </div>

        <div class="form-group">
            <label>رسم التوصيل المنزلي (DA)</label>
            <input type="number" name="home_delivery_fee" step="0.01" value="{{ $shipping->home_delivery_fee }}" required>
        </div>

        <div class="form-group">
            <label>رسم التوصيل للمكتب (DA)</label>
            <input type="number" name="desk_delivery_fee" step="0.01" value="{{ $shipping->desk_delivery_fee }}" required>
        </div>

        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
            <a href="/admin/shipping" class="btn btn-secondary">إلغاء</a>
        </div>
    </form>
</div>
@endsection
