<?php $__env->startSection('title', 'Configuración - Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4">
    <h1 class="mb-1">Configuración del Sistema</h1>
    <p class="text-muted">Personaliza la información de tu empresa y configuraciones generales</p>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <ul class="nav nav-tabs nav-fill" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="company-tab" data-bs-toggle="tab" data-bs-target="#company" type="button" role="tab">
                    <i class="ri ri-building-line me-2"></i>
                    <span class="d-none d-sm-inline">Información de la Empresa</span>
                    <span class="d-inline d-sm-none">Empresa</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="social-tab" data-bs-toggle="tab" data-bs-target="#social" type="button" role="tab">
                    <i class="ri ri-share-line me-2"></i>
                    <span class="d-none d-sm-inline">Redes Sociales</span>
                    <span class="d-inline d-sm-none">Redes</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="email-tab" data-bs-toggle="tab" data-bs-target="#email" type="button" role="tab">
                    <i class="ri ri-mail-line me-2"></i>
                    <span class="d-none d-sm-inline">Configuración de Emails</span>
                    <span class="d-inline d-sm-none">Emails</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">
                    <i class="ri ri-settings-3-line me-2"></i>
                    <span class="d-none d-sm-inline">Configuración General</span>
                    <span class="d-inline d-sm-none">General</span>
                </button>
            </li>
        </ul>

        <form action="<?php echo e(route('admin.settings.update')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="tab-content pt-4">

                <div class="tab-pane fade show active" id="company" role="tabpanel">
                    <?php
                        $companySettings = $settings['company'] ?? [];
                    ?>

                    <div class="alert alert-info mb-4">
                        <i class="ri ri-information-line me-2"></i>
                        Esta información se mostrará en el sitio web público (landing page).
                    </div>

                    <div class="row">
                        <?php $__currentLoopData = $companySettings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($setting->key === 'company_logo'): ?>
                                <div class="col-12 mb-4">
                                    <label for="company_logo" class="form-label">
                                        <strong>Logo de la Empresa</strong>
                                    </label>
                                    <?php if($setting->value): ?>
                                        <div class="mb-3">
                                            <img src="<?php echo e(asset('storage/' . $setting->value)); ?>" alt="Logo" style="max-height: 100px;" class="img-thumbnail">
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" class="form-control" id="company_logo" name="settings[company_logo]" accept="image/*">
                                    <small class="text-muted">JPG, PNG, SVG. Máximo 2MB. Recomendado: 200x50px</small>
                                </div>
                            <?php else: ?>
                                <div class="col-md-6 mb-4">
                                    <label for="<?php echo e($setting->key); ?>" class="form-label">
                                        <strong><?php echo e(ucwords(str_replace('_', ' ', str_replace('company_', '', $setting->key)))); ?></strong>
                                    </label>
                                    <?php if($setting->type === 'textarea'): ?>
                                        <textarea class="form-control" id="<?php echo e($setting->key); ?>" name="settings[<?php echo e($setting->key); ?>]" rows="3"><?php echo e(old('settings.' . $setting->key, $setting->value)); ?></textarea>
                                    <?php else: ?>
                                        <input type="text" class="form-control" id="<?php echo e($setting->key); ?>" name="settings[<?php echo e($setting->key); ?>]" value="<?php echo e(old('settings.' . $setting->key, $setting->value)); ?>">
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <div class="tab-pane fade" id="social" role="tabpanel">
                    <?php
                        $socialSettings = $settings['social'] ?? [];
                    ?>

                    <div class="alert alert-info mb-4">
                        <i class="ri ri-information-line me-2"></i>
                        Las redes sociales aparecerán en el footer. Solo se muestran las que tengan URL.
                    </div>

                    <div class="row">
                        <?php $__currentLoopData = $socialSettings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-6 mb-4">
                                <label for="<?php echo e($setting->key); ?>" class="form-label">
                                    <strong>
                                        <?php if(str_contains($setting->key, 'facebook')): ?>
                                            <i class="ri ri-facebook-fill text-primary me-1"></i>Facebook
                                        <?php elseif(str_contains($setting->key, 'instagram')): ?>
                                            <i class="ri ri-instagram-line text-danger me-1"></i>Instagram
                                        <?php elseif(str_contains($setting->key, 'twitter')): ?>
                                            <i class="ri ri-twitter-fill text-info me-1"></i>Twitter
                                        <?php elseif(str_contains($setting->key, 'linkedin')): ?>
                                            <i class="ri ri-linkedin-fill text-primary me-1"></i>LinkedIn
                                        <?php elseif(str_contains($setting->key, 'youtube')): ?>
                                            <i class="ri ri-youtube-fill text-danger me-1"></i>YouTube
                                        <?php endif; ?>
                                    </strong>
                                </label>
                                <input type="url" class="form-control" id="<?php echo e($setting->key); ?>" name="settings[<?php echo e($setting->key); ?>]" value="<?php echo e(old('settings.' . $setting->key, $setting->value)); ?>" placeholder="https://ejemplo.com/tuperfil">
                                <small class="text-muted">URL completa (opcional)</small>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <div class="tab-pane fade" id="email" role="tabpanel">
                    <?php
                        $emailSettings = $settings['email'] ?? [];
                    ?>

                    <div class="alert alert-info mb-4">
                        <i class="ri ri-information-line me-2"></i>
                        Textos para los emails de bienvenida automáticos.
                    </div>

                    <?php $__currentLoopData = $emailSettings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="mb-4">
                            <label for="<?php echo e($setting->key); ?>" class="form-label">
                                <strong><?php echo e(ucwords(str_replace('_', ' ', str_replace('email_', '', $setting->key)))); ?></strong>
                            </label>
                            <?php if($setting->type === 'textarea'): ?>
                                <textarea class="form-control" id="<?php echo e($setting->key); ?>" name="settings[<?php echo e($setting->key); ?>]" rows="4"><?php echo e(old('settings.' . $setting->key, $setting->value)); ?></textarea>
                            <?php else: ?>
                                <input type="text" class="form-control" id="<?php echo e($setting->key); ?>" name="settings[<?php echo e($setting->key); ?>]" value="<?php echo e(old('settings.' . $setting->key, $setting->value)); ?>">
                            <?php endif; ?>
                            <?php if($setting->key === 'email_welcome_subject'): ?>
                                <small class="text-muted">Asunto del email. Se agregará el nombre del restaurante.</small>
                            <?php elseif($setting->key === 'email_welcome_message'): ?>
                                <small class="text-muted">Mensaje principal después del saludo.</small>
                            <?php elseif($setting->key === 'email_footer_text'): ?>
                                <small class="text-muted">Texto al final antes de la firma.</small>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="tab-pane fade" id="general" role="tabpanel">
                    <?php
                        $generalSettings = $settings['general'] ?? [];
                    ?>

                    <?php $__currentLoopData = $generalSettings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="mb-4">
                            <label for="<?php echo e($setting->key); ?>" class="form-label">
                                <strong><?php echo e(ucwords(str_replace('_', ' ', $setting->key))); ?></strong>
                            </label>
                            <?php if($setting->type === 'textarea'): ?>
                                <textarea class="form-control" id="<?php echo e($setting->key); ?>" name="settings[<?php echo e($setting->key); ?>]" rows="4"><?php echo e(old('settings.' . $setting->key, $setting->value)); ?></textarea>
                            <?php else: ?>
                                <input type="text" class="form-control" id="<?php echo e($setting->key); ?>" name="settings[<?php echo e($setting->key); ?>]" value="<?php echo e(old('settings.' . $setting->key, $setting->value)); ?>">
                            <?php endif; ?>
                            <?php if($setting->key === 'support_email'): ?>
                                <small class="text-muted">Email de soporte técnico.</small>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <div class="mt-4 pt-3 border-top">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">
                            <i class="ri ri-information-line me-1"></i>
                            Los cambios se aplicarán inmediatamente
                        </small>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-label-secondary">
                            <i class="ri ri-close-line me-1"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri ri-save-line me-1"></i>Guardar Cambios
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\saasres\resources\views/admin/settings/index.blade.php ENDPATH**/ ?>