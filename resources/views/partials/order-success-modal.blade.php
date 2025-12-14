<div id="orderSuccessModal" class="fixed inset-0 z-50 flex items-center justify-center hidden" style="background-color: rgba(0,0,0,0.5);">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 transform transition-all scale-95 opacity-0 duration-300" id="orderSuccessContent">
        <div class="text-center">
            <!-- Animated Checkmark -->
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-6">
                <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            
            <h2 class="text-3xl font-bold text-gray-900 mb-2">تم الطلب بنجاح!</h2>
            <p class="text-gray-500 mb-6">شكراً لطلبك. سنقوم بتوصيل طلبيتك في أقرب وقت ممكن.</p>
            
            <div class="bg-gray-50 rounded-xl p-4 mb-6 text-right">
                <h3 class="text-sm font-semibold text-gray-900 mb-2">معلومات التواصل</h3>
                <div class="space-y-2 text-sm text-gray-600">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#8B3A3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span>0550 12 34 56</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#8B3A3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>contact@epiart.com</span>
                    </div>
                </div>
            </div>

            <button onclick="closeOrderSuccessModal()" class="w-full bg-[#8B3A3A] text-white font-bold py-3 px-4 rounded-xl hover:bg-[#722F37] transition duration-300 transform hover:scale-[1.02]">
                متابعة التسوق
            </button>
        </div>
    </div>
</div>

<script>
    function showOrderSuccessModal() {
        const modal = document.getElementById('orderSuccessModal');
        const content = document.getElementById('orderSuccessContent');
        
        modal.classList.remove('hidden');
        // Trigger reflow
        void modal.offsetWidth;
        
        // Add animation classes
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }

    function closeOrderSuccessModal() {
        const modal = document.getElementById('orderSuccessModal');
        const content = document.getElementById('orderSuccessContent');
        
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // Check if session has success message specifically for order
    @if(session('success') && str_contains(session('success'), 'تم إنشاء الطلب بنجاح'))
        document.addEventListener('DOMContentLoaded', () => {
            showOrderSuccessModal();
        });
    @endif
</script>
