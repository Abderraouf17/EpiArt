@extends('admin.layout')

@section('title', 'قواعد الشحن')
@section('header', 'إدارة قواعد الشحن')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h3>قواعد الشحن حسب الولاية</h3>
        <a href="/admin/shipping/create" class="btn btn-primary">+ إضافة ولاية</a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>الولاية</th>
                <th>كود الولاية</th>
                <th>توصيل منزلي</th>
                <th>توصيل مكتب</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($shippingRules as $rule)
                <tr>
                    <td>{{ $rule->wilaya }}</td>
                    <td>{{ $rule->wilaya_code }}</td>
                    <td>{{ number_format($rule->home_delivery_fee, 2) }} DA</td>
                    <td>{{ number_format($rule->desk_delivery_fee, 2) }} DA</td>
                    <td>
                        <a href="/admin/shipping/{{ $rule->id }}/edit" class="btn btn-secondary" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;">تعديل</a>
                        <form method="POST" action="/admin/shipping/{{ $rule->id }}" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;" onclick="return confirm('هل أنت متأكد؟')">حذف</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 1.5rem;">
        {{ $shippingRules->links() }}
    </div>
</div>
@endsection
