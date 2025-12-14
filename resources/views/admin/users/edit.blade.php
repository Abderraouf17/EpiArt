@extends('admin.layout')

@section('title', 'تعديل المستخدم')
@section('header', 'تعديل المستخدم')

@section('content')
<div class="card">
    <form method="POST" action="/admin/users/{{ $user->id }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>الاسم</label>
            <input type="text" name="name" value="{{ $user->name }}" required>
        </div>

        <div class="form-group">
            <label>البريد الإلكتروني</label>
            <input type="email" name="email" value="{{ $user->email }}" required>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="is_admin" value="1" @if($user->is_admin) checked @endif>
                هذا المستخدم هو مسؤول
            </label>
        </div>

        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
            <a href="/admin/users" class="btn btn-secondary">إلغاء</a>
        </div>
    </form>
</div>
@endsection
