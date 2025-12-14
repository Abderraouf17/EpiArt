<?php

namespace App\Http\Controllers\Admin;

use App\Models\ShippingRule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShippingController extends Controller
{
    public function index()
    {
        $shippingRules = ShippingRule::paginate(15);
        return view('admin.shipping.index', compact('shippingRules'));
    }

    public function create()
    {
        return view('admin.shipping.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'wilaya' => 'required|string|max:255|unique:shipping_rules',
            'wilaya_code' => 'required|string|max:10|unique:shipping_rules',
            'home_delivery_fee' => 'required|numeric|min:0',
            'desk_delivery_fee' => 'required|numeric|min:0',
        ]);

        ShippingRule::create($validated);

        return redirect()->route('admin.shipping.index')
            ->with('success', 'تم إضافة قاعدة الشحن بنجاح');
    }

    public function edit(ShippingRule $shipping)
    {
        return view('admin.shipping.edit', compact('shipping'));
    }

    public function update(Request $request, ShippingRule $shipping)
    {
        $validated = $request->validate([
            'wilaya' => 'required|string|max:255|unique:shipping_rules,wilaya,' . $shipping->id,
            'wilaya_code' => 'required|string|max:10|unique:shipping_rules,wilaya_code,' . $shipping->id,
            'home_delivery_fee' => 'required|numeric|min:0',
            'desk_delivery_fee' => 'required|numeric|min:0',
        ]);

        $shipping->update($validated);

        return redirect()->route('admin.shipping.index')
            ->with('success', 'تم تحديث قاعدة الشحن بنجاح');
    }

    public function destroy(ShippingRule $shipping)
    {
        $shipping->delete();
        return redirect()->route('admin.shipping.index')
            ->with('success', 'تم حذف قاعدة الشحن بنجاح');
    }
}
