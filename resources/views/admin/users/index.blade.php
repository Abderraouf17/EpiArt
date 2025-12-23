@extends('admin.layout')

@section('title', 'العملاء')
@section('header', 'إدارة العملاء')

@section('content')
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3>قائمة العملاء</h3>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden space-y-4">
            @foreach($users as $user)
                <div class="bg-white border rounded-lg p-4 shadow-sm">
                    <div class="flex items-center gap-4 mb-3">
                        <div
                            class="w-12 h-12 border-radius-50 bg-gradient-to-br from-[#8B3A3A] to-[#722F37] flex items-center justify-center text-white font-bold rounded-full">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-900">{{ $user->name }}</h4>
                            <span class="text-sm text-gray-500 block">{{ $user->email }}</span>
                            @if($user->phone)
                                <span class="text-xs text-gray-400 block">{{ $user->phone }}</span>
                            @endif
                        </div>
                        <span class="badge {{ $user->is_admin ? 'badge-danger' : 'badge-success' }} text-xs">
                            {{ $user->is_admin ? 'مسؤول' : 'عميل' }}
                        </span>
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t mt-2">
                        @if(!$user->is_admin)
                            <button data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}"
                                class="btn view-orders-btn text-sm py-1 px-3 bg-blue-500 text-white">الطلبات</button>
                        @endif
                        <button data-user-id="{{ $user->id }}"
                            class="btn btn-secondary edit-user-btn text-sm py-1 px-3">تعديل</button>
                        @if($user->id !== auth()->id())
                            <form method="POST" action="/admin/users/{{ $user->id }}" class="delete-form inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger delete-btn text-sm py-1 px-3"
                                    data-message="هل أنت متأكد من حذف هذا المستخدم؟">حذف</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>رقم الهاتف</th>
                        <th>الدور</th>
                        <th>تاريخ التسجيل</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div
                                        style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #8B3A3A, #722F37); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 600;">{{ $user->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?? 'غير محدد' }}</td>
                            <td>
                                <span class="badge @if($user->is_admin) badge-danger @else badge-success @endif">
                                    @if($user->is_admin) مسؤول @else عميل @endif
                                </span>
                            </td>
                            <td>{{ $user->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div style="display: flex; gap: 0.5rem;">
                                    @if(!$user->is_admin)
                                        <button data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}"
                                            class="btn view-orders-btn"
                                            style="padding: 0.25rem 0.75rem; font-size: 0.875rem; background: #3b82f6; color: white;">
                                            الطلبات
                                        </button>
                                    @endif
                                    <button data-user-id="{{ $user->id }}" class="btn btn-secondary edit-user-btn"
                                        style="padding: 0.25rem 0.75rem; font-size: 0.875rem;">
                                        تعديل
                                    </button>
                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="/admin/users/{{ $user->id }}" style="display: inline;"
                                            class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger delete-btn"
                                                style="padding: 0.25rem 0.75rem; font-size: 0.875rem;"
                                                data-message="هل أنت متأكد من حذف هذا المستخدم؟">حذف</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1.5rem;">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="userModal" class="modal" style="display: none;">
        <div class="modal-content" style="max-width: 600px; max-height: 90vh; overflow-y: auto;">
            <div class="modal-header">
                <h3 id="modalTitle">تعديل العميل</h3>
                <button onclick="closeUserModal()" class="close-btn">&times;</button>
            </div>
            <div class="modal-body p-6">
                <form id="userForm" method="POST" action="">
                    @csrf
                    <input type="hidden" id="userMethod" name="_method" value="PUT">

                    <div style="margin-bottom: 1.5rem;">
                        <label
                            style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #374151;">الاسم</label>
                        <input type="text" name="name" id="userName" required class="form-input"
                            style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem; transition: all 0.3s;"
                            onfocus="this.style.borderColor='#8B3A3A'" onblur="this.style.borderColor='#e5e7eb'">
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #374151;">البريد
                            الإلكتروني</label>
                        <input type="email" name="email" id="userEmail" required class="form-input"
                            style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem; transition: all 0.3s;"
                            onfocus="this.style.borderColor='#8B3A3A'" onblur="this.style.borderColor='#e5e7eb'">
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #374151;">رقم
                            الهاتف</label>
                        <input type="text" name="phone" id="userPhone" class="form-input"
                            style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem; transition: all 0.3s;"
                            onfocus="this.style.borderColor='#8B3A3A'" onblur="this.style.borderColor='#e5e7eb'">
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label
                            style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer; padding: 1rem; background: #f9fafb; border-radius: 8px; transition: all 0.3s;"
                            onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='#f9fafb'">
                            <input type="checkbox" name="is_admin" id="userIsAdmin" value="1"
                                style="width: 20px; height: 20px; cursor: pointer;">
                            <span style="font-weight: 600; color: #374151;">مسؤول</span>
                        </label>
                    </div>

                    <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem;">
                        <button type="button" onclick="closeUserModal()" class="btn"
                            style="background: #6b7280; color: white; padding: 0.75rem 2rem; border-radius: 8px; font-weight: 600; transition: all 0.3s;"
                            onmouseover="this.style.background='#4b5563'"
                            onmouseout="this.style.background='#6b7280'">إلغاء</button>
                        <button type="submit" class="btn btn-primary"
                            style="padding: 0.75rem 2rem; border-radius: 8px; font-weight: 600;">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Customer Orders Modal -->
    <div id="ordersModal" class="modal" style="display: none;">
        <div class="modal-content" style="max-width: 1000px; max-height: 90vh; overflow-y: auto;">
            <div class="modal-header" style="background: linear-gradient(135deg, #3b82f6, #2563eb); color: white;">
                <h3 id="ordersModalTitle" style="margin: 0; color: white;">طلبات العميل</h3>
                <button onclick="closeOrdersModal()"
                    style="background: rgba(255,255,255,0.2); border: none; font-size: 1.5rem; cursor: pointer; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.3s;"
                    onmouseover="this.style.background='rgba(255,255,255,0.3)'"
                    onmouseout="this.style.background='rgba(255,255,255,0.2)'">&times;</button>
            </div>
            <div class="modal-body p-6" id="ordersContent">
                <div style="text-align: center; padding: 3rem;">
                    <div class="spinner"
                        style="width: 48px; height: 48px; border: 4px solid #e5e7eb; border-top-color: #3b82f6; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 1rem;">
                    </div>
                    <div style="font-size: 1.125rem; color: #6b7280;">جاري التحميل...</div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>

    <script>
        // Event delegation for buttons
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('view-orders-btn') || e.target.closest('.view-orders-btn')) {
                const btn = e.target.classList.contains('view-orders-btn') ? e.target : e.target.closest('.view-orders-btn');
                const userId = btn.dataset.userId;
                const userName = btn.dataset.userName;
                viewCustomerOrders(userId, userName);
            }

            if (e.target.classList.contains('edit-user-btn') || e.target.closest('.edit-user-btn')) {
                const btn = e.target.classList.contains('edit-user-btn') ? e.target : e.target.closest('.edit-user-btn');
                const userId = btn.dataset.userId;
                openUserModal(userId);
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

        function openUserModal(userId = null) {
            const modal = document.getElementById('userModal');
            const form = document.getElementById('userForm');
            const title = document.getElementById('modalTitle');

            if (userId) {
                title.textContent = 'تعديل العميل';
                form.action = `/admin/users/${userId}`;
                document.getElementById('userMethod').value = 'PUT';

                // Fetch user data
                fetch(`/admin/users/${userId}/edit`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(user => {
                        document.getElementById('userName').value = user.name;
                        document.getElementById('userEmail').value = user.email;
                        document.getElementById('userPhone').value = user.phone || '';
                        document.getElementById('userIsAdmin').checked = user.is_admin;
                    });
            }

            modal.style.display = 'flex';
        }

        function closeUserModal() {
            document.getElementById('userModal').style.display = 'none';
        }

        function viewCustomerOrders(userId, userName) {
            const modal = document.getElementById('ordersModal');
            const title = document.getElementById('ordersModalTitle');
            const content = document.getElementById('ordersContent');

            title.textContent = `طلبات ${userName}`;
            content.innerHTML = '<div style="text-align: center; padding: 2rem;"><div style="font-size: 1.125rem; color: #6b7280;">جاري التحميل...</div></div>';

            modal.style.display = 'flex';

            // Fetch customer orders
            fetch(`/admin/users/${userId}/orders`)
                .then(response => response.json())
                .then(orders => {
                    if (orders.length === 0) {
                        content.innerHTML = `
                        <div style="text-align: center; padding: 3rem;">
                            <svg style="width: 64px; height: 64px; margin: 0 auto 1rem; color: #d1d5db;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            <div style="font-size: 1.125rem; color: #6b7280;">لا توجد طلبات لهذا العميل</div>
                        </div>
                    `;
                    } else {
                        let html = '<table class="table"><thead><tr><th>رقم الطلب</th><th>المجموع</th><th>الحالة</th><th>التاريخ</th><th>الإجراءات</th></tr></thead><tbody>';

                        orders.forEach(order => {
                            const statusColors = {
                                'pending': 'badge-warning',
                                'confirmed': 'badge-info',
                                'processing': 'badge-primary',
                                'shipped': 'badge-secondary',
                                'delivered': 'badge-success',
                                'cancelled': 'badge-danger'
                            };

                            const statusLabels = {
                                'pending': 'قيد الانتظار',
                                'confirmed': 'مؤكد',
                                'processing': 'قيد المعالجة',
                                'shipped': 'تم الشحن',
                                'delivered': 'تم التوصيل',
                                'cancelled': 'ملغى'
                            };

                            html += `
                            <tr>
                                <td>#${order.id}</td>
                                <td>${order.total_amount} DA</td>
                                <td><span class="badge ${statusColors[order.status]}">${statusLabels[order.status]}</span></td>
                                <td>${new Date(order.created_at).toLocaleDateString('ar-DZ')}</td>
                                <td><a href="/admin/orders/${order.id}" class="btn btn-secondary" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;">عرض</a></td>
                            </tr>
                        `;
                        });

                        html += '</tbody></table>';
                        content.innerHTML = html;
                    }
                })
                .catch(error => {
                    content.innerHTML = '<div style="text-align: center; padding: 2rem; color: #ef4444;">حدث خطأ في تحميل الطلبات</div>';
                });
        }

        function closeOrdersModal() {
            document.getElementById('ordersModal').style.display = 'none';
        }

        // Close modals when clicking outside
        window.onclick = function (event) {
            const userModal = document.getElementById('userModal');
            const ordersModal = document.getElementById('ordersModal');

            if (event.target === userModal) {
                closeUserModal();
            }
            if (event.target === ordersModal) {
                closeOrdersModal();
            }
        }
    </script>
@endsection