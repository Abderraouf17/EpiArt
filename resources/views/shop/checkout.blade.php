@extends('layouts.app')

@section('content')
@php
    $shippingJson = $shippingRules->toJson();
    $subtotalValue = (float)($subtotal ?? 0);
@endphp

<div id="checkout-data" data-shipping="{{ htmlspecialchars($shippingJson) }}" data-subtotal="{{ $subtotalValue }}" style="display:none;"></div>

<div style="max-width: 800px; margin: 0 auto; padding: 2rem 1rem;">
    <h1 style="font-size: 2rem; font-weight: bold; margin-bottom: 2rem;">إتمام الشراء</h1>

    @if(empty($cart))
        <div style="text-align: center; padding: 2rem; background: #fef3c7; border-radius: 8px; margin-bottom: 2rem;">
            <p style="color: #92400e;">السلة فارغة. يرجى إضافة منتجات قبل الشراء.</p>
        </div>
    @else
        <form method="POST" action="/checkout/process">
            @csrf

            <div style="background: white; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem; border: 1px solid #e5e7eb;">
                <h2 style="font-weight: 600; margin-bottom: 1.5rem;">معلومات الشحن</h2>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">الاسم الأول</label>
                        <input type="text" name="first_name" required style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 6px;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">الاسم الأخير</label>
                        <input type="text" name="last_name" required style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 6px;">
                    </div>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">البريد الإلكتروني</label>
                    <input type="email" name="email" value="{{ auth()->user()->email }}" required style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 6px;">
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">رقم الهاتف</label>
                    <input type="tel" name="phone" required style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 6px;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">الولاية</label>
                        <select name="wilaya" required style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 6px;">
                            <option value="">اختر الولاية</option>
                            @foreach($shippingRules as $rule)
                                <option value="{{ $rule->wilaya }}">{{ $rule->wilaya }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">نوع التوصيل</label>
                        <select name="delivery_type" required style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 6px;">
                            <option value="home">إلى المنزل</option>
                            <option value="desk">إلى المكتب</option>
                        </select>
                    </div>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">العنوان (اختياري)</label>
                    <textarea name="address" style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 6px; min-height: 100px;"></textarea>
                </div>
            </div>

            <div style="background: white; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem; border: 1px solid #e5e7eb;">
                <h2 style="font-weight: 600; margin-bottom: 1.5rem;">ملخص الطلب</h2>
                
                @php
                    $subtotal = 0;
                @endphp
                
                @foreach($cart as $item)
                    @php
                        $product = \App\Models\Product::find($item['product_id']);
                        $itemTotal = $item['price'] * $item['quantity'];
                        $subtotal += $itemTotal;
                    @endphp
                    <div style="display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid #f3f4f6;">
                        <div>
                            <p>{{ $product->name }}</p>
                            @if($item['variation_value'])
                                <small style="color: #6b7280;">{{ $item['variation_type'] }}: {{ $item['variation_value'] }} × {{ $item['quantity'] }}</small>
                            @else
                                <small style="color: #6b7280;">× {{ $item['quantity'] }}</small>
                            @endif
                        </div>
                        <span style="font-weight: 600;">{{ number_format($itemTotal, 2) }} DA</span>
                    </div>
                @endforeach

                <div style="margin-top: 1rem; padding-top: 1rem; border-top: 2px solid #e5e7eb;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span>المجموع:</span>
                        <span>{{ number_format($subtotal, 2) }} DA</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                        <span>رسم التوصيل:</span>
                        <span id="delivery-fee">0 DA</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 1.25rem; font-weight: bold; color: #8B3A3A;">
                        <span>الإجمالي:</span>
                        <span id="total">{{ number_format($subtotal, 2) }} DA</span>
                    </div>
                </div>
            </div>

            <button type="submit" style="width: 100%; padding: 1rem; background: #8B3A3A; color: white; border: none; border-radius: 6px; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.3s;">إكمال الطلب</button>
            <a href="/cart" style="display: block; text-align: center; padding: 0.75rem; margin-top: 1rem; color: #8B3A3A; text-decoration: none;">العودة للسلة</a>
        </form>
    @endif
</div>

<script type="text/javascript">
    'use strict';
    const checkoutData = document.getElementById('checkout-data');
    const shippingRules = JSON.parse(checkoutData.dataset.shipping);
    const subtotal = parseFloat(checkoutData.dataset.subtotal);

    document.querySelector('select[name="wilaya"]').addEventListener('change', updateDeliveryFee);
    document.querySelector('select[name="delivery_type"]').addEventListener('change', updateDeliveryFee);

    function updateDeliveryFee() {
        const wilaya = document.querySelector('select[name="wilaya"]').value;
        const deliveryType = document.querySelector('select[name="delivery_type"]').value;

        const rule = shippingRules.find(r => r.wilaya === wilaya);
        if (rule) {
            const fee = deliveryType === 'home' ? rule.home_delivery_fee : rule.desk_delivery_fee;
            const total = subtotal + parseFloat(fee);
            document.getElementById('delivery-fee').textContent = fee.toFixed(2) + ' DA';
            document.getElementById('total').textContent = total.toFixed(2) + ' DA';
        }
    }
</script>
@endsection
