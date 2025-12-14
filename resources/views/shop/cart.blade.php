@extends('layouts.app')

@section('content')
<div style="max-width: 1000px; margin: 0 auto; padding: 2rem 1rem;">
    <h1 style="font-size: 2rem; font-weight: bold; margin-bottom: 2rem;">السلة</h1>

    @if(empty($cart))
        <div style="text-align: center; padding: 3rem;">
            <p style="color: #6b7280; margin-bottom: 1.5rem;">السلة فارغة</p>
            <a href="/shop" style="display: inline-block; padding: 0.75rem 1.5rem; background: #8B3A3A; color: white; border-radius: 6px; text-decoration: none; font-weight: 600;">تسوق الآن</a>
        </div>
    @else
        <div style="display: grid; grid-template-columns: 1fr 300px; gap: 2rem;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #e5e7eb;">
                        <th style="padding: 1rem; text-align: right;">المنتج</th>
                        <th style="padding: 1rem; text-align: right;">الكمية</th>
                        <th style="padding: 1rem; text-align: right;">السعر</th>
                        <th style="padding: 1rem;">الإجراء</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total = 0;
                    @endphp
                    @foreach($cart as $key => $item)
                        @php
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                        @endphp
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 1rem;">
                                <div>
                                    <p style="font-weight: 600;">{{ \App\Models\Product::find($item['product_id'])->name }}</p>
                                    @if($item['variation_value'])
                                        <small style="color: #6b7280;">{{ $item['variation_type'] }}: {{ $item['variation_value'] }}</small>
                                    @endif
                                </div>
                            </td>
                            <td style="padding: 1rem;">{{ $item['quantity'] }}</td>
                            <td style="padding: 1rem;">{{ number_format($subtotal, 2) }} DA</td>
                            <td style="padding: 1rem;">
                                <form method="POST" action="/cart/remove" style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="key" value="{{ $key }}">
                                    <button type="submit" style="background: #dc2626; color: white; padding: 0.25rem 0.75rem; border: none; border-radius: 4px; cursor: pointer;">حذف</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div>
                <div style="background: #f9fafb; padding: 1.5rem; border-radius: 8px;">
                    <h3 style="font-weight: 600; margin-bottom: 1rem;">ملخص الطلب</h3>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #e5e7eb;">
                        <span>المجموع:</span>
                        <span style="font-weight: bold;">{{ number_format($total, 2) }} DA</span>
                    </div>
                    <a href="/checkout" style="display: block; text-align: center; padding: 0.75rem; background: #8B3A3A; color: white; border-radius: 6px; text-decoration: none; font-weight: 600; margin-bottom: 1rem;">متابعة الشراء</a>
                    <a href="/shop" style="display: block; text-align: center; padding: 0.75rem; background: white; color: #8B3A3A; border: 1px solid #8B3A3A; border-radius: 6px; text-decoration: none; font-weight: 600;">مواصلة التسوق</a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
