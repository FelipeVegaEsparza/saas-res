<?php $__env->startSection('title', 'Configuración'); ?>

<?php $__env->startSection('content'); ?>
<?php
use Illuminate\Support\Facades\Storage;
?>

<div class="mb-4">
    <h1>Configuración del Restaurante</h1>
</div>

<form action="<?php echo e(route('tenant.path.settings.update', ['tenant' => request()->route('tenant')])); ?>" method="POST" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>

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
                        <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="name" name="name" value="<?php echo e(old('name', $restaurant->name)); ?>" required>
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="rut" class="form-label">RUT</label>
                        <input type="text" class="form-control <?php $__errorArgs = ['rut'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="rut" name="rut" value="<?php echo e(old('rut', $restaurant->rut)); ?>" placeholder="12.345.678-9">
                        <?php $__errorArgs = ['rut'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="phone" class="form-label">Número de Contacto</label>
                        <input type="text" class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="phone" name="phone" value="<?php echo e(old('phone', $restaurant->phone)); ?>" placeholder="+56 9 1234 5678">
                        <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="address" class="form-label">Dirección</label>
                        <input type="text" class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="address" name="address" value="<?php echo e(old('address', $restaurant->address)); ?>">
                        <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descripción</label>
                <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                          id="description" name="description" rows="3"><?php echo e(old('description', $restaurant->description)); ?></textarea>
                <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <small class="text-muted">Breve descripción de tu restaurante</small>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="logo_horizontal" class="form-label">Logo Horizontal</label>
                        <?php if($restaurant->logo_horizontal): ?>
                            <div class="mb-2">
                                <img src="<?php echo e(Storage::url($restaurant->logo_horizontal)); ?>" alt="Logo Horizontal" class="img-thumbnail" style="max-height: 100px;">
                            </div>
                        <?php endif; ?>
                        <input type="file" class="form-control <?php $__errorArgs = ['logo_horizontal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="logo_horizontal" name="logo_horizontal" accept="image/*">
                        <?php $__errorArgs = ['logo_horizontal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <small class="text-muted">Formato: JPG, PNG, SVG. Máx: 2MB</small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="logo_square" class="form-label">Logo Cuadrado</label>
                        <?php if($restaurant->logo_square): ?>
                            <div class="mb-2">
                                <img src="<?php echo e(Storage::url($restaurant->logo_square)); ?>" alt="Logo Cuadrado" class="img-thumbnail" style="max-height: 100px;">
                            </div>
                        <?php endif; ?>
                        <input type="file" class="form-control <?php $__errorArgs = ['logo_square'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="logo_square" name="logo_square" accept="image/*">
                        <?php $__errorArgs = ['logo_square'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                        <select class="form-select <?php $__errorArgs = ['country'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="country" name="country" required>
                            <option value="">Seleccionar país</option>
                            <option value="AR" data-currency="ARS" data-symbol="$" <?php echo e(old('country', $restaurant->country) == 'AR' ? 'selected' : ''); ?>>Argentina (ARS - Peso Argentino)</option>
                            <option value="BO" data-currency="BOB" data-symbol="Bs" <?php echo e(old('country', $restaurant->country) == 'BO' ? 'selected' : ''); ?>>Bolivia (BOB - Boliviano)</option>
                            <option value="BR" data-currency="BRL" data-symbol="R$" <?php echo e(old('country', $restaurant->country) == 'BR' ? 'selected' : ''); ?>>Brasil (BRL - Real)</option>
                            <option value="CL" data-currency="CLP" data-symbol="$" <?php echo e(old('country', $restaurant->country) == 'CL' ? 'selected' : ''); ?>>Chile (CLP - Peso Chileno)</option>
                            <option value="CO" data-currency="COP" data-symbol="$" <?php echo e(old('country', $restaurant->country) == 'CO' ? 'selected' : ''); ?>>Colombia (COP - Peso Colombiano)</option>
                            <option value="CR" data-currency="CRC" data-symbol="₡" <?php echo e(old('country', $restaurant->country) == 'CR' ? 'selected' : ''); ?>>Costa Rica (CRC - Colón)</option>
                            <option value="CU" data-currency="CUP" data-symbol="$" <?php echo e(old('country', $restaurant->country) == 'CU' ? 'selected' : ''); ?>>Cuba (CUP - Peso Cubano)</option>
                            <option value="EC" data-currency="USD" data-symbol="$" <?php echo e(old('country', $restaurant->country) == 'EC' ? 'selected' : ''); ?>>Ecuador (USD - Dólar)</option>
                            <option value="SV" data-currency="USD" data-symbol="$" <?php echo e(old('country', $restaurant->country) == 'SV' ? 'selected' : ''); ?>>El Salvador (USD - Dólar)</option>
                            <option value="GT" data-currency="GTQ" data-symbol="Q" <?php echo e(old('country', $restaurant->country) == 'GT' ? 'selected' : ''); ?>>Guatemala (GTQ - Quetzal)</option>
                            <option value="HN" data-currency="HNL" data-symbol="L" <?php echo e(old('country', $restaurant->country) == 'HN' ? 'selected' : ''); ?>>Honduras (HNL - Lempira)</option>
                            <option value="MX" data-currency="MXN" data-symbol="$" <?php echo e(old('country', $restaurant->country) == 'MX' ? 'selected' : ''); ?>>México (MXN - Peso Mexicano)</option>
                            <option value="NI" data-currency="NIO" data-symbol="C$" <?php echo e(old('country', $restaurant->country) == 'NI' ? 'selected' : ''); ?>>Nicaragua (NIO - Córdoba)</option>
                            <option value="PA" data-currency="PAB" data-symbol="B/." <?php echo e(old('country', $restaurant->country) == 'PA' ? 'selected' : ''); ?>>Panamá (PAB - Balboa)</option>
                            <option value="PY" data-currency="PYG" data-symbol="₲" <?php echo e(old('country', $restaurant->country) == 'PY' ? 'selected' : ''); ?>>Paraguay (PYG - Guaraní)</option>
                            <option value="PE" data-currency="PEN" data-symbol="S/" <?php echo e(old('country', $restaurant->country) == 'PE' ? 'selected' : ''); ?>>Perú (PEN - Sol)</option>
                            <option value="DO" data-currency="DOP" data-symbol="RD$" <?php echo e(old('country', $restaurant->country) == 'DO' ? 'selected' : ''); ?>>República Dominicana (DOP - Peso Dominicano)</option>
                            <option value="UY" data-currency="UYU" data-symbol="$U" <?php echo e(old('country', $restaurant->country) == 'UY' ? 'selected' : ''); ?>>Uruguay (UYU - Peso Uruguayo)</option>
                            <option value="VE" data-currency="VES" data-symbol="Bs.S" <?php echo e(old('country', $restaurant->country) == 'VE' ? 'selected' : ''); ?>>Venezuela (VES - Bolívar Soberano)</option>
                        </select>
                        <?php $__errorArgs = ['country'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <small class="text-muted">Selecciona tu país para configurar la moneda automáticamente</small>
                    </div>
                </div>
            </div>

            <input type="hidden" id="currency" name="currency" value="<?php echo e(old('currency', $restaurant->currency)); ?>">
            <input type="hidden" id="currency_symbol" name="currency_symbol" value="<?php echo e(old('currency_symbol', $restaurant->currency_symbol)); ?>">

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
                        <input type="url" class="form-control <?php $__errorArgs = ['facebook'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="facebook" name="facebook" value="<?php echo e(old('facebook', $restaurant->facebook)); ?>"
                               placeholder="https://facebook.com/tu-restaurante">
                        <?php $__errorArgs = ['facebook'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="instagram" class="form-label">
                            <i class="ri ri-instagram-fill"></i> Instagram
                        </label>
                        <input type="url" class="form-control <?php $__errorArgs = ['instagram'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="instagram" name="instagram" value="<?php echo e(old('instagram', $restaurant->instagram)); ?>"
                               placeholder="https://instagram.com/tu-restaurante">
                        <?php $__errorArgs = ['instagram'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="tiktok" class="form-label">
                            <i class="ri ri-tiktok-fill"></i> TikTok
                        </label>
                        <input type="url" class="form-control <?php $__errorArgs = ['tiktok'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="tiktok" name="tiktok" value="<?php echo e(old('tiktok', $restaurant->tiktok)); ?>"
                               placeholder="https://tiktok.com/@tu-restaurante">
                        <?php $__errorArgs = ['tiktok'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="twitter" class="form-label">
                            <i class="ri ri-twitter-x-fill"></i> X (Twitter)
                        </label>
                        <input type="url" class="form-control <?php $__errorArgs = ['twitter'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="twitter" name="twitter" value="<?php echo e(old('twitter', $restaurant->twitter)); ?>"
                               placeholder="https://x.com/tu-restaurante">
                        <?php $__errorArgs = ['twitter'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                <?php if($restaurant->menu_background_image): ?>
                    <div class="mb-2">
                        <img src="<?php echo e(Storage::url($restaurant->menu_background_image)); ?>" alt="Fondo Menú" class="img-thumbnail" style="max-height: 150px;">
                    </div>
                <?php endif; ?>
                <input type="file" class="form-control <?php $__errorArgs = ['menu_background_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       id="menu_background_image" name="menu_background_image" accept="image/*">
                <?php $__errorArgs = ['menu_background_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <small class="text-muted">Formato: JPG, PNG. Máx: 4MB</small>
            </div>

            <div class="mb-4">
                <label class="form-label">Esquema de Color del Menú</label>
                <div class="row g-3">
                    <?php $__currentLoopData = $colorSchemes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $scheme): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-4">
                            <div class="form-check custom-option custom-option-basic <?php echo e(old('menu_color_scheme', $restaurant->menu_color_scheme) === $key ? 'checked' : ''); ?>">
                                <label class="form-check-label custom-option-content" for="color_<?php echo e($key); ?>">
                                    <input class="form-check-input" type="radio" name="menu_color_scheme"
                                           id="color_<?php echo e($key); ?>" value="<?php echo e($key); ?>"
                                           <?php echo e(old('menu_color_scheme', $restaurant->menu_color_scheme) === $key ? 'checked' : ''); ?>>
                                    <span class="custom-option-header">
                                        <span class="h6 mb-0"><?php echo e($scheme['name']); ?></span>
                                    </span>
                                    <span class="custom-option-body">
                                        <div class="d-flex gap-2 mt-2">
                                            <div style="width: 30px; height: 30px; background-color: <?php echo e($scheme['primary']); ?>; border-radius: 4px;"></div>
                                            <div style="width: 30px; height: 30px; background-color: <?php echo e($scheme['secondary']); ?>; border-radius: 4px;"></div>
                                            <div style="width: 30px; height: 30px; background-color: <?php echo e($scheme['accent']); ?>; border-radius: 4px;"></div>
                                        </div>
                                    </span>
                                </label>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <div class="alert alert-info">
                <div class="d-flex align-items-center">
                    <i class="ri ri-qr-code-line ri-24px me-3"></i>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">Código QR de tu Menú</h6>
                        <p class="mb-2">Descarga el código QR para imprimirlo en tus mesas</p>
                        <a href="<?php echo e(route('tenant.path.settings.download-qr', ['tenant' => request()->route('tenant')])); ?>"
                           class="btn btn-sm btn-primary" target="_blank">
                            <i class="ri ri-download-line me-1"></i> Descargar QR
                        </a>
                        <a href="<?php echo e(url('/' . request()->route('tenant') . '/menu')); ?>"
                           class="btn btn-sm btn-outline-primary" target="_blank">
                            <i class="ri ri-external-link-line me-1"></i> Ver Menú
                        </a>
                    </div>
                </div>
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
                           <?php echo e(old('accepts_online_orders', $restaurant->accepts_online_orders) ? 'checked' : ''); ?>>
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
                            <input type="number" class="form-control <?php $__errorArgs = ['delivery_fee'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="delivery_fee" name="delivery_fee"
                                   value="<?php echo e(old('delivery_fee', $restaurant->delivery_fee ?? 0)); ?>"
                                   min="0" step="0.01">
                        </div>
                        <?php $__errorArgs = ['delivery_fee'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <small class="text-muted">Costo fijo por envío a domicilio</small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="min_order_amount" class="form-label">Pedido Mínimo</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control <?php $__errorArgs = ['min_order_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="min_order_amount" name="min_order_amount"
                                   value="<?php echo e(old('min_order_amount', $restaurant->min_order_amount ?? 0)); ?>"
                                   min="0" step="0.01">
                        </div>
                        <?php $__errorArgs = ['min_order_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                                   value="<?php echo e(url('/' . request()->route('tenant') . '/order')); ?>"
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

<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('tenant.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\saasres\resources\views/tenant/settings/index.blade.php ENDPATH**/ ?>