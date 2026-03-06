@extends('tenant.layouts.admin')

@section('title', 'Configuración')

@section('content')
@php
use Illuminate\Support\Facades\Storage;
@endphp

<div class="mb-4">
    <h1>Configuración del Restaurante</h1>
</div>

<form action="{{ route('tenant.path.settings.update', ['tenant' => request()->route('tenant')]) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <!-- Información Básica -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Información Básica</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre del Restaurante *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name', $restaurant->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="rut" class="form-label">RUT</label>
                        <input type="text" class="form-control @error('rut') is-invalid @enderror"
                               id="rut" name="rut" value="{{ old('rut', $restaurant->rut) }}" placeholder="12.345.678-9">
                        @error('rut')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="phone" class="form-label">Número de Contacto</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                               id="phone" name="phone" value="{{ old('phone', $restaurant->phone) }}" placeholder="+56 9 1234 5678">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="address" class="form-label">Dirección</label>
                        <input type="text" class="form-control @error('address') is-invalid @enderror"
                               id="address" name="address" value="{{ old('address', $restaurant->address) }}">
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descripción</label>
                <textarea class="form-control @error('description') is-invalid @enderror"
                          id="description" name="description" rows="3">{{ old('description', $restaurant->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Breve descripción de tu restaurante</small>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="logo_horizontal" class="form-label">Logo Horizontal</label>
                        @if($restaurant->logo_horizontal)
                            <div class="mb-2">
                                <img src="{{ Storage::url($restaurant->logo_horizontal) }}" alt="Logo Horizontal" class="img-thumbnail" style="max-height: 100px;">
                            </div>
                        @endif
                        <input type="file" class="form-control @error('logo_horizontal') is-invalid @enderror"
                               id="logo_horizontal" name="logo_horizontal" accept="image/*">
                        @error('logo_horizontal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Formato: JPG, PNG, SVG. Máx: 2MB</small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="logo_square" class="form-label">Logo Cuadrado</label>
                        @if($restaurant->logo_square)
                            <div class="mb-2">
                                <img src="{{ Storage::url($restaurant->logo_square) }}" alt="Logo Cuadrado" class="img-thumbnail" style="max-height: 100px;">
                            </div>
                        @endif
                        <input type="file" class="form-control @error('logo_square') is-invalid @enderror"
                               id="logo_square" name="logo_square" accept="image/*">
                        @error('logo_square')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Formato: JPG, PNG, SVG. Máx: 2MB</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Configuración Regional -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Configuración Regional</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="country" class="form-label">País / Moneda *</label>
                        <select class="form-select @error('country') is-invalid @enderror" id="country" name="country" required>
                            <option value="">Seleccionar país</option>
                            <option value="AR" data-currency="ARS" data-symbol="$" {{ old('country', $restaurant->country) == 'AR' ? 'selected' : '' }}>Argentina (ARS - Peso Argentino)</option>
                            <option value="BO" data-currency="BOB" data-symbol="Bs" {{ old('country', $restaurant->country) == 'BO' ? 'selected' : '' }}>Bolivia (BOB - Boliviano)</option>
                            <option value="BR" data-currency="BRL" data-symbol="R$" {{ old('country', $restaurant->country) == 'BR' ? 'selected' : '' }}>Brasil (BRL - Real)</option>
                            <option value="CL" data-currency="CLP" data-symbol="$" {{ old('country', $restaurant->country) == 'CL' ? 'selected' : '' }}>Chile (CLP - Peso Chileno)</option>
                            <option value="CO" data-currency="COP" data-symbol="$" {{ old('country', $restaurant->country) == 'CO' ? 'selected' : '' }}>Colombia (COP - Peso Colombiano)</option>
                            <option value="CR" data-currency="CRC" data-symbol="₡" {{ old('country', $restaurant->country) == 'CR' ? 'selected' : '' }}>Costa Rica (CRC - Colón)</option>
                            <option value="CU" data-currency="CUP" data-symbol="$" {{ old('country', $restaurant->country) == 'CU' ? 'selected' : '' }}>Cuba (CUP - Peso Cubano)</option>
                            <option value="EC" data-currency="USD" data-symbol="$" {{ old('country', $restaurant->country) == 'EC' ? 'selected' : '' }}>Ecuador (USD - Dólar)</option>
                            <option value="SV" data-currency="USD" data-symbol="$" {{ old('country', $restaurant->country) == 'SV' ? 'selected' : '' }}>El Salvador (USD - Dólar)</option>
                            <option value="GT" data-currency="GTQ" data-symbol="Q" {{ old('country', $restaurant->country) == 'GT' ? 'selected' : '' }}>Guatemala (GTQ - Quetzal)</option>
                            <option value="HN" data-currency="HNL" data-symbol="L" {{ old('country', $restaurant->country) == 'HN' ? 'selected' : '' }}>Honduras (HNL - Lempira)</option>
                            <option value="MX" data-currency="MXN" data-symbol="$" {{ old('country', $restaurant->country) == 'MX' ? 'selected' : '' }}>México (MXN - Peso Mexicano)</option>
                            <option value="NI" data-currency="NIO" data-symbol="C$" {{ old('country', $restaurant->country) == 'NI' ? 'selected' : '' }}>Nicaragua (NIO - Córdoba)</option>
                            <option value="PA" data-currency="PAB" data-symbol="B/." {{ old('country', $restaurant->country) == 'PA' ? 'selected' : '' }}>Panamá (PAB - Balboa)</option>
                            <option value="PY" data-currency="PYG" data-symbol="₲" {{ old('country', $restaurant->country) == 'PY' ? 'selected' : '' }}>Paraguay (PYG - Guaraní)</option>
                            <option value="PE" data-currency="PEN" data-symbol="S/" {{ old('country', $restaurant->country) == 'PE' ? 'selected' : '' }}>Perú (PEN - Sol)</option>
                            <option value="DO" data-currency="DOP" data-symbol="RD$" {{ old('country', $restaurant->country) == 'DO' ? 'selected' : '' }}>República Dominicana (DOP - Peso Dominicano)</option>
                            <option value="UY" data-currency="UYU" data-symbol="$U" {{ old('country', $restaurant->country) == 'UY' ? 'selected' : '' }}>Uruguay (UYU - Peso Uruguayo)</option>
                            <option value="VE" data-currency="VES" data-symbol="Bs.S" {{ old('country', $restaurant->country) == 'VE' ? 'selected' : '' }}>Venezuela (VES - Bolívar Soberano)</option>
                        </select>
                        @error('country')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Selecciona tu país para configurar la moneda automáticamente</small>
                    </div>
                </div>
            </div>

            <input type="hidden" id="currency" name="currency" value="{{ old('currency', $restaurant->currency) }}">
            <input type="hidden" id="currency_symbol" name="currency_symbol" value="{{ old('currency_symbol', $restaurant->currency_symbol) }}">

            <div class="alert alert-info mb-0">
                <i class="ri ri-information-line me-2"></i>
                Los precios se mostrarán sin decimales según la moneda seleccionada
            </div>
        </div>
    </div>

    <!-- Redes Sociales -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Redes Sociales</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="facebook" class="form-label">
                            <i class="ri ri-facebook-fill"></i> Facebook
                        </label>
                        <input type="url" class="form-control @error('facebook') is-invalid @enderror"
                               id="facebook" name="facebook" value="{{ old('facebook', $restaurant->facebook) }}"
                               placeholder="https://facebook.com/tu-restaurante">
                        @error('facebook')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="instagram" class="form-label">
                            <i class="ri ri-instagram-fill"></i> Instagram
                        </label>
                        <input type="url" class="form-control @error('instagram') is-invalid @enderror"
                               id="instagram" name="instagram" value="{{ old('instagram', $restaurant->instagram) }}"
                               placeholder="https://instagram.com/tu-restaurante">
                        @error('instagram')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="tiktok" class="form-label">
                            <i class="ri ri-tiktok-fill"></i> TikTok
                        </label>
                        <input type="url" class="form-control @error('tiktok') is-invalid @enderror"
                               id="tiktok" name="tiktok" value="{{ old('tiktok', $restaurant->tiktok) }}"
                               placeholder="https://tiktok.com/@tu-restaurante">
                        @error('tiktok')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="twitter" class="form-label">
                            <i class="ri ri-twitter-x-fill"></i> X (Twitter)
                        </label>
                        <input type="url" class="form-control @error('twitter') is-invalid @enderror"
                               id="twitter" name="twitter" value="{{ old('twitter', $restaurant->twitter) }}"
                               placeholder="https://x.com/tu-restaurante">
                        @error('twitter')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Carta QR -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Carta QR / Menú Digital</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="menu_background_image" class="form-label">Imagen de Fondo del Menú</label>
                @if($restaurant->menu_background_image)
                    <div class="mb-2">
                        <img src="{{ Storage::url($restaurant->menu_background_image) }}" alt="Fondo Menú" class="img-thumbnail" style="max-height: 150px;">
                    </div>
                @endif
                <input type="file" class="form-control @error('menu_background_image') is-invalid @enderror"
                       id="menu_background_image" name="menu_background_image" accept="image/*">
                @error('menu_background_image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Formato: JPG, PNG. Máx: 4MB</small>
            </div>

            <div class="mb-4">
                <label class="form-label">Esquema de Color del Menú</label>
                <div class="row g-3">
                    @foreach($colorSchemes as $key => $scheme)
                        <div class="col-md-4">
                            <div class="form-check custom-option custom-option-basic {{ old('menu_color_scheme', $restaurant->menu_color_scheme) === $key ? 'checked' : '' }}">
                                <label class="form-check-label custom-option-content" for="color_{{ $key }}">
                                    <input class="form-check-input" type="radio" name="menu_color_scheme"
                                           id="color_{{ $key }}" value="{{ $key }}"
                                           {{ old('menu_color_scheme', $restaurant->menu_color_scheme) === $key ? 'checked' : '' }}>
                                    <span class="custom-option-header">
                                        <span class="h6 mb-0">{{ $scheme['name'] }}</span>
                                    </span>
                                    <span class="custom-option-body">
                                        <div class="d-flex gap-2 mt-2">
                                            <div style="width: 30px; height: 30px; background-color: {{ $scheme['primary'] }}; border-radius: 4px;"></div>
                                            <div style="width: 30px; height: 30px; background-color: {{ $scheme['secondary'] }}; border-radius: 4px;"></div>
                                            <div style="width: 30px; height: 30px; background-color: {{ $scheme['accent'] }}; border-radius: 4px;"></div>
                                        </div>
                                    </span>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="alert alert-info">
                <div class="d-flex align-items-center">
                    <i class="ri ri-qr-code-line ri-24px me-3"></i>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">Código QR de tu Menú</h6>
                        <p class="mb-2">Descarga el código QR para imprimirlo en tus mesas</p>
                        <a href="{{ route('tenant.path.settings.download-qr', ['tenant' => request()->route('tenant')]) }}"
                           class="btn btn-sm btn-primary" target="_blank">
                            <i class="ri ri-download-line me-1"></i> Descargar QR
                        </a>
                        <a href="{{ url('/' . request()->route('tenant') . '/menu') }}"
                           class="btn btn-sm btn-outline-primary" target="_blank">
                            <i class="ri ri-external-link-line me-1"></i> Ver Menú
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Módulos del Sistema -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="ri ri-apps-line me-2"></i>Módulos del Sistema</h5>
        </div>
        <div class="card-body">
            <p class="text-muted mb-4">Activa o desactiva los módulos según las necesidades de tu negocio. Los módulos desactivados no aparecerán en el menú lateral.</p>

            <div class="row">
                <div class="col-md-6">
                    <div class="card border mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h6 class="mb-1"><i class="ri ri-table-line me-2 text-primary"></i>Módulo de Mesas</h6>
                                    <small class="text-muted">Gestión de mesas y pedidos en local</small>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="module_tables_enabled"
                                           name="module_tables_enabled" value="1"
                                           {{ old('module_tables_enabled', $restaurant->module_tables_enabled ?? true) ? 'checked' : '' }}>
                                </div>
                            </div>
                            <ul class="list-unstyled mb-0 small text-muted">
                                <li><i class="ri ri-check-line me-1"></i> Gestión de mesas</li>
                                <li><i class="ri ri-check-line me-1"></i> Toma de pedidos</li>
                                <li><i class="ri ri-check-line me-1"></i> Control de cuentas</li>
                                <li><i class="ri ri-check-line me-1"></i> Impresión de comandas</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h6 class="mb-1"><i class="ri ri-e-bike-2-line me-2 text-info"></i>Módulo de Delivery</h6>
                                    <small class="text-muted">Gestión de pedidos para llevar y delivery</small>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="module_delivery_enabled"
                                           name="module_delivery_enabled" value="1"
                                           {{ old('module_delivery_enabled', $restaurant->module_delivery_enabled ?? true) ? 'checked' : '' }}>
                                </div>
                            </div>
                            <ul class="list-unstyled mb-0 small text-muted">
                                <li><i class="ri ri-check-line me-1"></i> Pedidos para llevar</li>
                                <li><i class="ri ri-check-line me-1"></i> Pedidos a domicilio</li>
                                <li><i class="ri ri-check-line me-1"></i> Seguimiento de estados</li>
                                <li><i class="ri ri-check-line me-1"></i> Impresión de comandas</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="alert alert-warning mb-0">
                <i class="ri ri-information-line me-2"></i>
                <strong>Importante:</strong> Al desactivar un módulo, este dejará de aparecer en el menú lateral y no podrás acceder a sus funciones. Puedes reactivarlo en cualquier momento.
            </div>
        </div>
    </div>

    <!-- Pedidos Online -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Pedidos Online</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="accepts_online_orders"
                           name="accepts_online_orders" value="1"
                           {{ old('accepts_online_orders', $restaurant->accepts_online_orders) ? 'checked' : '' }}>
                    <label class="form-check-label" for="accepts_online_orders">
                        Aceptar Pedidos Online
                    </label>
                </div>
                <small class="text-muted">Permite que los clientes hagan pedidos desde tu sitio web</small>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="delivery_fee" class="form-label">Costo de Delivery</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control @error('delivery_fee') is-invalid @enderror"
                                   id="delivery_fee" name="delivery_fee"
                                   value="{{ old('delivery_fee', $restaurant->delivery_fee ?? 0) }}"
                                   min="0" step="0.01">
                        </div>
                        @error('delivery_fee')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Costo fijo por envío a domicilio</small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="min_order_amount" class="form-label">Pedido Mínimo</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control @error('min_order_amount') is-invalid @enderror"
                                   id="min_order_amount" name="min_order_amount"
                                   value="{{ old('min_order_amount', $restaurant->min_order_amount ?? 0) }}"
                                   min="0" step="0.01">
                        </div>
                        @error('min_order_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Monto mínimo para realizar un pedido</small>
                    </div>
                </div>
            </div>

            <div class="alert alert-info mb-0">
                <div class="d-flex align-items-start">
                    <i class="ri ri-information-line ri-22px me-2"></i>
                    <div>
                        <h6 class="mb-1">Enlace de Pedidos Online</h6>
                        <p class="mb-2">Comparte este enlace con tus clientes para que puedan hacer pedidos:</p>
                        <div class="input-group">
                            <input type="text" class="form-control"
                                   value="{{ url('/' . request()->route('tenant') . '/order') }}"
                                   readonly id="onlineOrderUrl">
                            <button class="btn btn-outline-primary" type="button" onclick="copyOrderUrl()">
                                <i class="ri ri-file-copy-line"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="ri ri-save-line me-1"></i> Guardar Cambios
        </button>
    </div>
</form>

@push('scripts')
<script>
function copyOrderUrl() {
    const input = document.getElementById('onlineOrderUrl');
    input.select();
    document.execCommand('copy');

    // Mostrar feedback
    const btn = event.target.closest('button');
    const originalHTML = btn.innerHTML;
    btn.innerHTML = '<i class="ri ri-check-line"></i>';
    btn.classList.remove('btn-outline-primary');
    btn.classList.add('btn-success');

    setTimeout(() => {
        btn.innerHTML = originalHTML;
        btn.classList.remove('btn-success');
        btn.classList.add('btn-outline-primary');
    }, 2000);
}

// Update currency fields when country changes
document.getElementById('country').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const currency = selectedOption.getAttribute('data-currency');
    const symbol = selectedOption.getAttribute('data-symbol');

    document.getElementById('currency').value = currency || '';
    document.getElementById('currency_symbol').value = symbol || '';
});
</script>
@endpush
@endsection
