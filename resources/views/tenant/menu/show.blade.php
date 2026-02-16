@extends('tenant.layouts.app')

@section('title', $product->name . ' - ' . $restaurant->name)

@section('content')
@php
use Illuminate\Support\Facades\Storage;
@endphp

<div class="menu-header">
    <div class="container">
        <div class="d-flex align-items-center">
            <a href="{{ route('tenant.menu.index') }}" class="btn btn-light btn-sm me-3">
                <i class="bx bx-arrow-back"></i> Volver
            </a>
            <div>
                <h1 class="mb-0">{{ $product->name }}</h1>
                <p class="mb-0">{{ $product->category->name }}</p>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-6 mb-4">
            @if($product->image)
                <img src="{{ Storage::url($product->image) }}" class="img-fluid rounded shadow" alt="{{ $product->name }}">
            @else
                <div class="bg-light rounded shadow d-flex align-items-center justify-content-center" style="height: 400px;">
                    <i class="bx bx-dish" style="font-size: 8rem; color: var(--secondary-color);"></i>
                </div>
            @endif

            @if($product->images && count($product->images) > 0)
                <div class="row g-2 mt-3">
                    @foreach($product->images as $image)
                        <div class="col-4">
                            <img src="{{ Storage::url($image) }}" class="img-fluid rounded" alt="{{ $product->name }}">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h2 class="mb-0">{{ $product->name }}</h2>
                        @if($product->featured)
                            <span class="badge bg-warning">
                                <i class="bx bx-star"></i> Destacado
                            </span>
                        @endif
                    </div>

                    <div class="mb-4">
                        <span class="price-tag">${{ number_format($product->price, 2) }}</span>
                    </div>

                    @if($product->description)
                        <div class="mb-4">
                            <h5>Descripción</h5>
                            <p class="text-muted">{{ $product->description }}</p>
                        </div>
                    @endif

                    @if($product->preparation_time)
                        <div class="mb-3">
                            <i class="bx bx-time text-primary"></i>
                            <strong>Tiempo de preparación:</strong> {{ $product->preparation_time }} minutos
                        </div>
                    @endif

                    @if($product->tags && count($product->tags) > 0)
                        <div class="mb-3">
                            <strong class="d-block mb-2">Características:</strong>
                            @foreach($product->tags as $tag)
                                <span class="badge badge-tag bg-info me-1">{{ $tag }}</span>
                            @endforeach
                        </div>
                    @endif

                    @if($product->allergens && count($product->allergens) > 0)
                        <div class="alert alert-warning">
                            <i class="bx bx-error-circle"></i>
                            <strong>Alérgenos:</strong>
                            {{ implode(', ', $product->allergens) }}
                        </div>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('tenant.menu.index') }}" class="btn btn-primary btn-lg w-100">
                            <i class="bx bx-arrow-back"></i> Volver al Menú
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-white border-top py-4 mt-5">
    <div class="container text-center">
        <p class="text-muted mb-0">
            <small>{{ $restaurant->name }} - Menú Digital</small>
        </p>
    </div>
</footer>
@endsection
