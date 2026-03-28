<div
    x-data="{ 
        show: false, 
        message: '', 
        type: 'success',
        timer: null 
    }"
    x-on:notify.window="
        show = true; 
        message = $event.detail.message; 
        type = $event.detail.type || 'success';
        clearTimeout(timer);
        timer = setTimeout(() => show = false, 5000);
    "
    x-show="show"
    x-cloak
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-y-2"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform translate-y-2"
    class="fixed bottom-5 right-5 z-[100] min-w-[300px] max-w-md"
>
    <div 
        :class="{
            'bg-green-600': type === 'success',
            'bg-red-600': type === 'error',
            'bg-blue-600': type === 'info',
            'bg-yellow-600': type === 'warning'
        }"
        class="flex items-center p-4 rounded-lg shadow-2xl text-white space-x-3 rtl:space-x-reverse"
    >
        <template x-if="type === 'success'">
            <x-heroicon-s-check-circle class="w-6 h-6" />
        </template>
        <template x-if="type === 'error'">
            <x-heroicon-s-x-circle class="w-6 h-6" />
        </template>

        <div class="flex-1 text-sm font-medium" x-text="message"></div>

        <button @click="show = false" class="text-white/80 hover:text-white">
            <x-heroicon-s-x-mark class="w-5 h-5" />
        </button>
    </div>
</div>
