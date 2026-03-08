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

<!-- Custom Menu Styles -->
<style>
/**
 * Custom Menu Sidebar Styles
 * Diferenciación visual del menú lateral
 */

/* Modo Claro - Menú con tono más oscuro */
[data-theme="light"] .layout-menu {
  background-color: #f8f9fa !important;
  border-right: 1px solid #e7e7e7;
}

[data-theme="light"] .layout-menu .menu-inner {
  background-color: #f8f9fa !important;
}

[data-theme="light"] .layout-menu .app-brand {
  background-color: #f8f9fa !important;
}

[data-theme="light"] .layout-menu .menu-item .menu-link {
  color: #566a7f;
}

[data-theme="light"] .layout-menu .menu-item.active > .menu-link {
  background-color: rgba(105, 108, 255, 0.08) !important;
  color: #696cff;
}

[data-theme="light"] .layout-menu .menu-item .menu-link:hover {
  background-color: rgba(105, 108, 255, 0.04);
}

[data-theme="light"] .layout-menu .menu-header {
  color: #a1acb8;
}

/* Modo Oscuro - Menú con tono más claro/diferenciado */
[data-theme="dark"] .layout-menu {
  background-color: #2b2c40 !important;
  border-right: 1px solid #3a3b54;
}

[data-theme="dark"] .layout-menu .menu-inner {
  background-color: #2b2c40 !important;
}

[data-theme="dark"] .layout-menu .app-brand {
  background-color: #2b2c40 !important;
}

[data-theme="dark"] .layout-menu .menu-item .menu-link {
  color: #b4b7bd;
}

[data-theme="dark"] .layout-menu .menu-item.active > .menu-link {
  background-color: rgba(105, 108, 255, 0.16) !important;
  color: #8b8dff;
}

[data-theme="dark"] .layout-menu .menu-item .menu-link:hover {
  background-color: rgba(105, 108, 255, 0.08);
}

[data-theme="dark"] .layout-menu .menu-header {
  color: #7071a0;
}

/* Sombra sutil para mayor diferenciación */
.layout-menu {
  box-shadow: 2px 0 8px rgba(0, 0, 0, 0.05);
}

/* Transiciones suaves */
.layout-menu,
.layout-menu .menu-item .menu-link {
  transition: all 0.2s ease-in-out;
}
</style>
