@extends('tenant.layouts.app')

@section('title', 'QR - ' . $table->number)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bx bx-qr"></i> Código QR - {{ $table->number }}
                    </h4>
                </div>
                <div class="card-body text-center p-5">
                    <img src="{{ $qrUrl }}" alt="QR Code" class="img-fluid mb-4" style="max-width: 300px;">

                    <h5 class="mb-3">{{ $restaurant->name }}</h5>
                    <p class="text-muted mb-4">{{ $table->number }}</p>

                    <div class="alert alert-info">
                        <small>
                            <i class="bx bx-info-circle"></i>
                            Escanea este código para ver el menú digital
                        </small>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ $menuUrl }}" class="btn btn-primary" target="_blank">
                            <i class="bx bx-link-external"></i> Abrir Menú
                        </a>
                        <a href="{{ route('tenant.qr.download', $table->id) }}" class="btn btn-outline-secondary" download>
                            <i class="bx bx-download"></i> Descargar QR
                        </a>
                        <a href="{{ route('tenant.dashboard') }}" class="btn btn-outline-dark">
                            <i class="bx bx-arrow-back"></i> Volver al Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
