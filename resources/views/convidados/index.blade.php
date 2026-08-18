@extends('layouts.app')

@section('content')
    <x-navbar />

    <main class="page-main">

        <x-flash-messages />

        <div class="page-header">
            <img src="{{ asset('images/doodles/convite.png') }}" alt="Convite" class="page-header-doodle">
            <div>
                <h1 class="titulo">Lista de Convidados</h1>
                <p class="text-text-muted">
                    Visualize quem estará no <strong class="text-primary">{{ $casamento->nomeCasamento }}</strong>
                </p>
            </div>
        </div>

    </main>

@endsection