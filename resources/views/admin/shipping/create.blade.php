@extends('admin.layout')

@section('title', 'إضافة ولاية جديدة')
@section('header', 'إضافة قاعدة شحن جديدة')

@section('content')
<div class="card">
    <form method="POST" action="/admin/shipping">
        @csrf

        <div class="form-group">
            <label>اسم الولاية</label>
            <input type="text" name="wilaya" value="{{ old('wilaya') }}" required>
        </div>

        <div class="form-group">
            <label>كود الولاية</label>
            <input type="text" name="wilaya_code" value="{{ old('wilaya_code') }}" required maxlength="10">
        </div>

        <div class="form-group">
            <label>رسم التوصيل المنزلي (DA)</label>
            <input type="number" name="home_delivery_fee" step="0.01" value="{{ old('home_delivery_fee') }}" required>
        </div>

        <div class="form-group">
            <label>رسم التوصيل للمكتب (DA)</label>
            <input type="number" name="desk_delivery_fee" step="0.01" value="{{ old('desk_delivery_fee') }}" required>
        </div>

        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">إضافة</button>
            <a href="/admin/shipping" class="btn btn-secondary">إلغاء</a>
        </div>
    </form>
</div>
@endsection
