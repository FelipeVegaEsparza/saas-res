@php
use Illuminate\Support\Facades\Storage;
@endphp

@extends('tenant.layouts.app')

@section('title', $restaurant->name . ' - Pedidos No Disponibles')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="avatar avatar-xl mx-auto mb-4">
                <span class="avatar-initial rounded-circle bg-label-warning">
                    <i class="ri ri-error-warning-line ri-48px"></i>
                </span>
            </div>

            @if($restaurant->logo_horizontal)
                <img src="{{ Storage::url($restaurant->logo_horizontal) }}"
                     alt="{{ $restaurant->name }}"
                     style="max-height: 80px; margin-bottom: 1rem;">
            @endif

            <h2 class="mb-3">Pedidos Online No Disponibles</h2>
            <p class="text-muted mb-4">
                Lo sentimos, actualmente no estamos aceptando pedidos online.
                Por favor contáctanos directamente o visítanos en nuestro local.
            </p>

            @if($restaurant->phone)
            <div class="mb-3">
                <a href="tel:{{ $restaurant->phone }}" class="btn btn-primary">
                    <i class="ri ri-phone-line me-2"></i>Llamar: {{ $restaurant->phone }}
                </a>
            </div>
            @endif

            <a href="{{ route('tenant.path.menu.index', ['tenant' => request()->route('tenant')]) }}" class="btn btn-outline-primary">
                <i class="ri ri-restaurant-line me-2"></i>Ver Menú
            </a>
        </div>
    </div>
</div>
@endsection
