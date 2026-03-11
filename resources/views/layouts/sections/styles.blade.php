<!-- BEGIN: Theme CSS-->
<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap" rel="stylesheet" />

<!-- Fonts Icons -->
@vite(['resources/assets/vendor/fonts/iconify/iconify.css'])

<!-- BEGIN: Vendor CSS-->
@vite(['resources/assets/vendor/libs/node-waves/node-waves.scss', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'])

@if ($configData['hasCustomizer'])
  @vite(['resources/assets/vendor/libs/pickr/pickr-themes.scss'])
@endif

<!-- Core CSS -->
@vite(['resources/assets/vendor/scss/core.scss', 'resources/assets/css/demo.css', 'resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.scss'])

<!-- Vendor Styles -->
@vite(['resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.scss', 'resources/assets/vendor/libs/typeahead-js/typeahead.scss'])
@yield('vendor-style')

<!-- Page Styles -->
@yield('page-style')

<!-- app CSS -->
@vite(['resources/css/app.css'])
<!-- END: app CSS-->

<!-- Hide page titles in dashboard -->
<style>
/* Ocultar títulos h1 en el contenido del dashboard */
.layout-page .content-wrapper h1.mb-1 {
  display: none;
}

/* También ocultar el párrafo descriptivo que suele acompañar al título */
.layout-page .content-wrapper h1.mb-1 + p.text-muted {
  display: none;
}

/* Ocultar títulos h1 sin clase específica (settings, users, statistics, products, categories) */
.layout-page .content-wrapper > div > h1,
.layout-page .content-wrapper > div.mb-4 > h1,
.layout-page .content-wrapper > div.d-flex > h1 {
  display: none;
}

/* Ocultar párrafos descriptivos después de h1 en settings */
.layout-page .content-wrapper > div.mb-4 > h1 + p.text-muted {
  display: none;
}

/* ===== SOLUCIÓN DEFINITIVA PARA FLECHAS GIGANTES ===== */

/* 1. OCULTAR COMPLETAMENTE ELEMENTOS SWIPER QUE CAUSAN LAS FLECHAS GIGANTES */
.swiper-button-prev,
.swiper-button-next,
[class*="swiper-button"] {
  display: none !important;
  visibility: hidden !important;
  opacity: 0 !important;
  width: 0 !important;
  height: 0 !important;
  position: absolute !important;
  left: -9999px !important;
  top: -9999px !important;
  z-index: -9999 !important;
  pointer-events: none !important;
}

/* 2. Asegurar que la paginación funcione correctamente */
.pagination .page-link {
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  min-width: 38px !important;
  height: 38px !important;
  padding: 0.5rem 0.75rem !important;
  position: relative !important;
  overflow: hidden !important;
  text-align: center !important;
  line-height: 1 !important;
}

/* 3. Ocultar elementos problemáticos SOLO en paginación */
.pagination .page-link svg,
.pagination .page-link i:not(.pagination-icon),
.pagination .page-link [class*="ri-"],
.pagination .page-link [class*="bx-"],
.pagination .page-link [class*="fa-"],
.page-item .page-link svg,
.page-item .page-link i:not(.pagination-icon) {
  display: none !important;
  visibility: hidden !important;
  opacity: 0 !important;
  width: 0 !important;
  height: 0 !important;
}

/* 4. Agregar flechas simples a la paginación */
.pagination .page-item:first-child .page-link {
  font-size: 0 !important;
}

.pagination .page-item:first-child .page-link::before {
  content: '‹' !important;
  font-size: 1.2rem !important;
  line-height: 1 !important;
  font-weight: 400 !important;
  position: absolute !important;
  top: 50% !important;
  left: 50% !important;
  transform: translate(-50%, -50%) !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
}

.pagination .page-item:last-child .page-link {
  font-size: 0 !important;
}

.pagination .page-item:last-child .page-link::before {
  content: '›' !important;
  font-size: 1.2rem !important;
  line-height: 1 !important;
  font-weight: 400 !important;
  position: absolute !important;
  top: 50% !important;
  left: 50% !important;
  transform: translate(-50%, -50%) !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
}

<script>
// Script para eliminar dinámicamente elementos swiper problemáticos
(function() {
    function removeSwiperButtons() {
        const swiperButtons = document.querySelectorAll('.swiper-button-prev, .swiper-button-next, [class*="swiper-button"]');
        swiperButtons.forEach(function(button) {
            if (button && button.parentNode) {
                button.style.display = 'none';
                button.style.visibility = 'hidden';
                button.style.opacity = '0';
                button.style.position = 'absolute';
                button.style.left = '-9999px';
                button.style.zIndex = '-9999';
            }
        });
    }

    // Ejecutar múltiples veces
    removeSwiperButtons();
    setTimeout(removeSwiperButtons, 100);
    setTimeout(removeSwiperButtons, 500);
    setTimeout(removeSwiperButtons, 1000);

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', removeSwiperButtons);
    }

    window.addEventListener('load', removeSwiperButtons);

    // Observador de mutaciones
    if (typeof MutationObserver !== 'undefined') {
        const observer = new MutationObserver(removeSwiperButtons);
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }
})();
</script>
</style>

<!-- Custom Menu Styles -->
<style>
/**
 * Custom Menu Sidebar Styles - Diseño Profesional
 * Diferenciación visual del menú lateral con mejoras modernas
 */

/* Modo Claro - Menú con tono más oscuro */
[data-theme="light"] .layout-menu {
  background: linear-gradient(180deg, #f8f9fa 0%, #f5f6f8 100%) !important;
  border-right: 1px solid #e7e7e7;
}

[data-theme="light"] .layout-menu .menu-inner {
  background-color: transparent !important;
}

[data-theme="light"] .layout-menu .app-brand {
  background-color: transparent !important;
  padding: 1.5rem 1.25rem;
  border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

[data-theme="light"] .layout-menu .menu-item .menu-link {
  color: #566a7f;
  border-radius: 8px;
  margin: 4px 12px;
  padding: 10px 16px;
  font-weight: 500;
  transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}

[data-theme="light"] .layout-menu .menu-item.active > .menu-link {
  background: linear-gradient(135deg, rgba(105, 108, 255, 0.12) 0%, rgba(105, 108, 255, 0.08) 100%) !important;
  color: #696cff;
  box-shadow: 0 2px 8px rgba(105, 108, 255, 0.15);
  transform: translateX(4px);
}

[data-theme="light"] .layout-menu .menu-item .menu-link:hover:not(.active) {
  background-color: rgba(105, 108, 255, 0.04);
  transform: translateX(2px);
  color: #696cff;
}

[data-theme="light"] .layout-menu .menu-header {
  color: #a1acb8;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  padding: 1rem 1.5rem 0.5rem;
}

[data-theme="light"] .layout-menu .menu-item .menu-link i {
  font-size: 1.25rem;
  margin-right: 0.75rem;
  transition: transform 0.25s ease;
}

[data-theme="light"] .layout-menu .menu-item.active > .menu-link i {
  transform: scale(1.1);
}

/* Modo Oscuro - Menú con tono más claro/diferenciado */
[data-theme="dark"] .layout-menu {
  background: linear-gradient(180deg, #2b2c40 0%, #25273a 100%) !important;
  border-right: 1px solid #3a3b54;
}

[data-theme="dark"] .layout-menu .menu-inner {
  background-color: transparent !important;
}

[data-theme="dark"] .layout-menu .app-brand {
  background-color: transparent !important;
  padding: 1.5rem 1.25rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

[data-theme="dark"] .layout-menu .menu-item .menu-link {
  color: #b4b7bd;
  border-radius: 8px;
  margin: 4px 12px;
  padding: 10px 16px;
  font-weight: 500;
  transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}

[data-theme="dark"] .layout-menu .menu-item.active > .menu-link {
  background: linear-gradient(135deg, rgba(105, 108, 255, 0.2) 0%, rgba(105, 108, 255, 0.12) 100%) !important;
  color: #8b8dff;
  box-shadow: 0 2px 12px rgba(105, 108, 255, 0.25);
  transform: translateX(4px);
}

[data-theme="dark"] .layout-menu .menu-item .menu-link:hover:not(.active) {
  background-color: rgba(105, 108, 255, 0.08);
  transform: translateX(2px);
  color: #8b8dff;
}

[data-theme="dark"] .layout-menu .menu-header {
  color: #7071a0;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  padding: 1rem 1.5rem 0.5rem;
}

[data-theme="dark"] .layout-menu .menu-item .menu-link i {
  font-size: 1.25rem;
  margin-right: 0.75rem;
  transition: transform 0.25s ease;
}

[data-theme="dark"] .layout-menu .menu-item.active > .menu-link i {
  transform: scale(1.1);
}

/* Sombra profesional para mayor diferenciación */
.layout-menu {
  box-shadow: 2px 0 16px rgba(0, 0, 0, 0.08);
}

/* Transiciones suaves y profesionales */
.layout-menu .menu-item .menu-link {
  position: relative;
  overflow: hidden;
}

/* Efecto de brillo sutil en hover */
.layout-menu .menu-item .menu-link::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
  transition: left 0.5s ease;
}

.layout-menu .menu-item .menu-link:hover::before {
  left: 100%;
}

/* Indicador visual para item activo */
.layout-menu .menu-item.active > .menu-link::after {
  content: '';
  position: absolute;
  left: 0;
  top: 50%;
  transform: translateY(-50%);
  width: 3px;
  height: 60%;
  background: linear-gradient(180deg, #696cff 0%, #5f61e6 100%);
  border-radius: 0 4px 4px 0;
}

/* Mejora del logo en el app-brand */
.layout-menu .app-brand-logo {
  transition: transform 0.3s ease;
}

.layout-menu .app-brand:hover .app-brand-logo {
  transform: scale(1.05);
}

/* Espaciado mejorado para submenús */
.layout-menu .menu-sub {
  padding-left: 1rem;
}

.layout-menu .menu-sub .menu-item .menu-link {
  font-size: 0.9rem;
  padding: 8px 16px;
}

/* Scroll suave en el menú */
.layout-menu .menu-inner {
  scrollbar-width: thin;
  scrollbar-color: rgba(105, 108, 255, 0.3) transparent;
}

.layout-menu .menu-inner::-webkit-scrollbar {
  width: 6px;
}

.layout-menu .menu-inner::-webkit-scrollbar-track {
  background: transparent;
}

.layout-menu .menu-inner::-webkit-scrollbar-thumb {
  background-color: rgba(105, 108, 255, 0.3);
  border-radius: 3px;
}

.layout-menu .menu-inner::-webkit-scrollbar-thumb:hover {
  background-color: rgba(105, 108, 255, 0.5);
}

/* Animación de entrada para items del menú */
@keyframes slideInLeft {
  from {
    opacity: 0;
    transform: translateX(-10px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

.layout-menu .menu-item {
  animation: slideInLeft 0.3s ease forwards;
}

.layout-menu .menu-item:nth-child(1) { animation-delay: 0.05s; }
.layout-menu .menu-item:nth-child(2) { animation-delay: 0.1s; }
.layout-menu .menu-item:nth-child(3) { animation-delay: 0.15s; }
.layout-menu .menu-item:nth-child(4) { animation-delay: 0.2s; }
.layout-menu .menu-item:nth-child(5) { animation-delay: 0.25s; }
.layout-menu .menu-item:nth-child(6) { animation-delay: 0.3s; }
.layout-menu .menu-item:nth-child(7) { animation-delay: 0.35s; }
.layout-menu .menu-item:nth-child(8) { animation-delay: 0.4s; }
</style>
