@if (session('sucesso'))
    <div class="alert-success">
        {{ session('sucesso') }}
    </div>
@endif

@if (session('erro'))
    <div class="alert-danger">
        {{ session('erro') }}
    </div>
@endif
