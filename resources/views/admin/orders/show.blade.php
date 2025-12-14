@extends('admin.layout')

@section('title', 'تفاصيل الطلب')
@section('header', 'تفاصيل الطلب #' . $order->id)

@section('content')
<div class="card">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
        <div>
            <h3 style="margin-bottom: 1rem;">معلومات العميل</h3>
            <p><strong>الاسم:</strong> {{ $order->first_name }} {{ $order->last_name }}</p>
            <p><strong>البريد الإلكتروني:</strong> {{ $order->email }}</p>
            <p><strong>الهاتف:</strong> {{ $order->phone }}</p>
            <p><strong>الولاية:</strong> {{ $order->wilaya }}</p>
            <p><strong>نوع التوصيل:</strong> @if($order->delivery_type === 'home') إلى المنزل @else إلى المكتب @endif</p>
            @if($order->address)
                <p><strong>العنوان:</strong> {{ $order->address }}</p>
            @endif
        </div>
        <div>
            <h3 style="margin-bottom: 1rem;">حالة الطلب</h3>
            <form method="POST" action="/admin/orders/{{ $order->id }}/status">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label>الحالة</label>
                    <select name="status">
                        <option value="pending" @if($order->status === 'pending') selected @endif>قيد الانتظار</option>
                        <option value="confirmed" @if($order->status === 'confirmed') selected @endif>مؤكدة</option>
                        <option value="shipped" @if($order->status === 'shipped') selected @endif>مرسلة</option>
                        <option value="delivered" @if($order->status === 'delivered') selected @endif>مسلمة</option>
                        <option value="cancelled" @if($order->status === 'cancelled') selected @endif>ملغاة</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">تحديث الحالة</button>
            </form>
        </div>
    </div>

    <h3 style="margin-bottom: 1rem;">العناصر المطلوبة</h3>
    <table class="table">
        <thead>
            <tr>
                <th>المنتج</th>
                <th>الكمية</th>
                <th>السعر</th>
                <th>الإجمالي</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>
                        {{ $item->product->name }}
                        @if($item->variation_value)
                            <br><small>({{ $item->variation_type }}: {{ $item->variation_value }})</small>
                        @endif
                    </td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price, 2) }} DA</td>
                    <td>{{ number_format($item->subtotal, 2) }} DA</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="display: grid; grid-template-columns: 1fr 300px; gap: 2rem; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #e5e7eb;">
        <div></div>
        <div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <strong>المجموع:</strong>
                <span>{{ number_format($order->total_price - $order->delivery_fee, 2) }} DA</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #e5e7eb;">
                <strong>رسوم التوصيل:</strong>
                <span>{{ number_format($order->delivery_fee, 2) }} DA</span>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 1.25rem; font-weight: bold;">
                <strong>الإجمالي:</strong>
                <span>{{ number_format($order->total_price, 2) }} DA</span>
            </div>
        </div>
    </div>

    <div style="margin-top: 2rem;">
        <a href="/admin/orders" class="btn btn-secondary">العودة إلى الطلبات</a>
    </div>
</div>
@endsection
