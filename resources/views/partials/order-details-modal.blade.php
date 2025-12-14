<div id="orderDetailsModal" class="fixed inset-0 z-50 flex items-center justify-center hidden" style="background-color: rgba(0,0,0,0.5);">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-hidden flex flex-col transform transition-all scale-95 opacity-0 duration-300" id="orderDetailsContent">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="text-xl font-bold text-gray-800" id="modalOrderTitle">تفاصيل الطلب</h3>
            <button onclick="closeOrderDetailsModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        
        <div class="p-6 overflow-y-auto" id="modalOrderBody">
            <!-- Content loaded via JS -->
        </div>
    </div>
</div>

<script>
    function openOrderDetails(orderId) {
        const modal = document.getElementById('orderDetailsModal');
        const content = document.getElementById('orderDetailsContent');
        const body = document.getElementById('modalOrderBody');
        const title = document.getElementById('modalOrderTitle');
        
        title.textContent = `تفاصيل الطلب #${orderId}`;
        
        modal.classList.remove('hidden');
        // Trigger reflow
        void modal.offsetWidth;
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');

        const order = window.ordersData[orderId];
        
        let html = `
            <div class="mb-6">
                <div class="flex justify-between items-center mb-4">
                    <span class="px-3 py-1 rounded-full text-sm font-semibold ${getStatusColor(order.status)}">${getStatusLabel(order.status)}</span>
                    <span class="text-gray-500">${new Date(order.created_at).toLocaleDateString('ar-DZ')}</span>
                </div>
                <div class="grid grid-cols-2 gap-4 text-sm text-gray-600 mb-4">
                    <div>
                        <p class="font-bold text-gray-800">العنوان:</p>
                        <p>${order.wilaya} - ${order.delivery_type === 'home' ? 'توصيل للمنزل' : 'توصيل للمكتب'}</p>
                        <p>${order.address || ''}</p>
                    </div>
                    <div class="text-left">
                        <p class="font-bold text-gray-800">المجموع:</p>
                        <p class="text-lg font-bold text-[#8B3A3A]">${parseFloat(order.total_price).toFixed(2)} DA</p>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-100 pt-4">
                <h4 class="font-bold text-gray-800 mb-3">المنتجات</h4>
                <div class="space-y-3">
        `;
        
        if (order.items && order.items.length > 0) {
            order.items.forEach(item => {
                const imgUrl = (item.product && item.product.images && item.product.images.length > 0) 
                    ? item.product.images[0].image_url 
                    : null;
                    
                html += `
                    <div class="flex gap-4 items-center bg-gray-50 p-3 rounded-lg">
                        <div class="w-12 h-12 bg-white rounded border border-gray-200 overflow-hidden flex-shrink-0">
                            ${imgUrl ? 
                                `<img src="${imgUrl}" class="w-full h-full object-cover">` : 
                                '<div class="w-full h-full bg-gray-100 flex items-center justify-center text-xs text-gray-400">No Img</div>'}
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800">${item.product ? item.product.name : 'منتج محذوف'}</p>
                            <p class="text-sm text-gray-500">${item.quantity} x ${parseFloat(item.price).toFixed(2)} DA</p>
                        </div>
                        <span class="font-bold text-gray-700">${(item.quantity * item.price).toFixed(2)} DA</span>
                    </div>
                `;
            });
        } else {
            html += '<p class="text-gray-500 text-sm">لا توجد منتجات</p>';
        }
        
        html += '</div></div>';
        
        if (order.status === 'pending') {
            html += `
                <div class="mt-6 pt-4 border-t border-gray-100 text-center">
                    <form action="/orders/${order.id}/cancel" method="POST" onsubmit="return confirm('هل أنت متأكد من إلغاء هذا الطلب؟');">
                        <input type="hidden" name="_token" value="${document.querySelector('meta[name=csrf-token]').content}">
                        <button type="submit" class="text-red-500 hover:text-red-700 font-semibold text-sm underline decoration-red-500/30 hover:decoration-red-700">إلغاء الطلب</button>
                    </form>
                </div>
            `;
        }

        body.innerHTML = html;
    }

    function closeOrderDetailsModal() {
        const modal = document.getElementById('orderDetailsModal');
        const content = document.getElementById('orderDetailsContent');
        
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function getStatusLabel(status) {
        const labels = {
            'pending': 'قيد الانتظار',
            'confirmed': 'مؤكدة',
            'shipped': 'تم الشحن',
            'delivered': 'تم التوصيل',
            'cancelled': 'ملغاة'
        };
        return labels[status] || status;
    }

    function getStatusColor(status) {
        const colors = {
            'pending': 'bg-yellow-100 text-yellow-800',
            'confirmed': 'bg-blue-100 text-blue-800',
            'shipped': 'bg-purple-100 text-purple-800',
            'delivered': 'bg-green-100 text-green-800',
            'cancelled': 'bg-red-100 text-red-800'
        };
        return colors[status] || 'bg-gray-100 text-gray-800';
    }
</script>
