@props(['show', 'onClose'])

<div x-show="{{ $show }}" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40" @click="{{ $onClose }}"></div>

    <div class="relative bg-surface border-2 border-primary rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto p-6">
        <button type="button" @click="{{ $onClose }}"
            class="absolute top-4 right-4 text-primary text-xl cursor-pointer">
            <i class="fa-solid fa-xmark"></i>
        </button>

        {{ $slot }}
    </div>
</div>