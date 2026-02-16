@php
$configData = Helper::appClasses();
$customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/commonMaster')

@section('layoutContent')
<!-- Content -->
@yield('content')
<!--/ Content -->
@endsection
