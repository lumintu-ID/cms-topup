<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>      <html class="no-js"> <!--<![endif]-->
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ESIPAYMENT</title>
    <meta name="description" content="esipayment">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- <meta name="referrer" content="no-referrer-when-downgrade" /> --}}
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    @yield('css-utilities')
    @vite(['resources/js/app.js'])
  </head>
  <body>
    @include('frontend.partials.loader-page')
    @include('frontend.partials.header')
    <main class="container-fluid container-lg">
      @yield('content')
      @include('frontend.partials.section-support')
    </main>
    @include('frontend.partials.footer')
    <!--[if lt IE 7]>
      <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
    @yield('js-utilities')
  </body>
</html>