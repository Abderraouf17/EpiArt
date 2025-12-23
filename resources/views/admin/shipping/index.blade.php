@extends('admin.layout')

@section('title', 'قواعد الشحن')
@section('header', 'إدارة قواعد الشحن')

@section('content')
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3>قواعد الشحن حسب الولاية</h3>
            <button class="btn btn-primary add-shipping-btn">+ إضافة ولاية</button>
        </div>

        <!-- Mobile View (Cards) -->
        <div class="md:hidden space-y-4">
            @foreach($shippingRules as $rule)
                <div class="bg-white border rounded-lg p-4 shadow-sm">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h4 class="font-bold text-gray-900">{{ $rule->wilaya }}</h4>
                            <span class="text-sm text-gray-500 block">كود: {{ $rule->wilaya_code }}</span>
                        </div>
                    </div>

                    <div class="space-y-2 mb-3 bg-gray-50 p-3 rounded">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">توصيل منزلي:</span>
                            <span class="font-bold text-[#8B3A3A]">{{ number_format($rule->home_delivery_fee, 2) }} DA</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">توصيل مكتب:</span>
                            <span class="font-bold text-[#8B3A3A]">{{ number_format($rule->desk_delivery_fee, 2) }} DA</span>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-2 border-t mt-2">
                        <button data-id="{{ $rule->id }}"
                            class="btn btn-secondary edit-shipping-btn text-sm py-1 px-3">تعديل</button>
                        <form method="POST" action="/admin/shipping/{{ $rule->id }}" class="delete-form inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger delete-btn text-sm py-1 px-3"
                                data-message="هل أنت متأكد من حذف هذه القاعدة؟">حذف</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Desktop View (Table) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="table w-full">
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
                                <button data-id="{{ $rule->id }}" class="btn btn-secondary edit-shipping-btn"
                                    style="padding: 0.25rem 0.75rem; font-size: 0.875rem;">تعديل</button>
                                <form method="POST" action="/admin/shipping/{{ $rule->id }}" style="display: inline;"
                                    class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger delete-btn"
                                        style="padding: 0.25rem 0.75rem; font-size: 0.875rem;"
                                        data-message="هل أنت متأكد من حذف هذه القاعدة؟">حذف</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1.5rem;">
            {{ $shippingRules->links() }}
        </div>
    </div>

    <!-- Shipping Rule Modal -->
    <div id="shippingModal" class="modal" style="display: none;">
        <div class="modal-content" style="max-width: 600px; max-height: 90vh; overflow-y: auto;">
            <div class="modal-header">
                <h3 id="shippingModalTitle">إضافة قاعدة شحن</h3>
                <button onclick="closeShippingModal()" class="close-btn">&times;</button>
            </div>
            <div class="modal-body p-6">
                <form id="shippingForm" method="POST" action="/admin/shipping">
                    @csrf
                    <input type="hidden" name="_method" id="shippingFormMethod" value="POST">

                    <div class="form-group">
                        <label>اسم الولاية *</label>
                        <input type="text" name="wilaya" id="shippingWilaya" required>
                    </div>

                    <div class="form-group">
                        <label>كود الولاية *</label>
                        <input type="text" name="wilaya_code" id="shippingWilayaCode" required maxlength="10">
                    </div>

                    <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label>توصيل منزلي (DA) *</label>
                            <input type="number" name="home_delivery_fee" id="shippingHomeFee" step="0.01" required>
                        </div>

                        <div class="form-group">
                            <label>توصيل مكتب (DA) *</label>
                            <input type="number" name="desk_delivery_fee" id="shippingDeskFee" step="0.01" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" onclick="closeShippingModal()" class="btn btn-secondary">إلغاء</button>
                        <button type="submit" class="btn btn-primary">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('add-shipping-btn')) {
                openShippingModal();
            }

            if (e.target.classList.contains('edit-shipping-btn') || e.target.closest('.edit-shipping-btn')) {
                const btn = e.target.classList.contains('edit-shipping-btn') ? e.target : e.target.closest('.edit-shipping-btn');
                const id = btn.dataset.id;
                openShippingModal(id);
            }

            if (e.target.classList.contains('delete-btn')) {
                e.preventDefault();
                const message = e.target.dataset.message || 'هل أنت متأكد من الحذف؟';
                confirmDelete(message).then((confirmed) => {
                    if (confirmed) {
                        e.target.closest('.delete-form').submit();
                    }
                });
            }
        });

        function openShippingModal(id = null) {
            const modal = document.getElementById('shippingModal');
            const form = document.getElementById('shippingForm');
            const modalTitle = document.getElementById('shippingModalTitle');
            const formMethod = document.getElementById('shippingFormMethod');

            if (id) {
                modalTitle.textContent = 'تعديل قاعدة شحن';
                formMethod.value = 'PUT';
                form.action = `/admin/shipping/${id}`;

                fetch(`/admin/shipping/${id}/edit`, {
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('shippingWilaya').value = data.wilaya || '';
                        document.getElementById('shippingWilayaCode').value = data.wilaya_code || '';
                        document.getElementById('shippingHomeFee').value = data.home_delivery_fee || '';
                        document.getElementById('shippingDeskFee').value = data.desk_delivery_fee || '';
                    });
            } else {
                modalTitle.textContent = 'إضافة قاعدة شحن';
                formMethod.value = 'POST';
                form.action = '/admin/shipping';
                form.reset();
            }

            modal.style.display = 'flex';
        }

        function closeShippingModal() {
            document.getElementById('shippingModal').style.display = 'none';
        }

        document.getElementById('shippingModal')?.addEventListener('click', function (e) {
            if (e.target === this) {
                closeShippingModal();
            }
        });
    </script>
@endsection