@if (session('sucesso'))
    <div class="alert-success flex items-center justify-between gap-3 p-3" x-data="{ mostrar: true }" x-show="mostrar" x-cloak>
        <span>{{ session('sucesso') }}</span>
        <button type="button" @click="mostrar = false" class="text-lg leading-none cursor-pointer">&times;</button>
    </div>
@endif

@if (session('erro'))
    <div class="alert-danger flex items-center justify-between gap-3" x-data="{ mostrar: true }" x-show="mostrar"
        x-cloak>
        <span>{{ session('erro') }}</span>
        <button type="button" @click="mostrar = false" class="text-lg leading-none cursor-pointer">&times;</button>
    </div>
@endif