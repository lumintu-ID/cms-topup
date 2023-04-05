<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>      <html class="no-js"> <!--<![endif]-->
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="token" content="{{ csrf_token() }}" />
  <meta name="key-genarate" content="{{ env("APP_KEY") }}" />
  <title>ESIPAYMENT</title>
</head>
<body data-payment="{{$dataRedirect}}">
  
  <h1>Codapay Checkout</h1>

  <iframe id="idIframe" src="about:blank" frameborder="0" style="display: none" ></iframe>

  @vite(['resources/js/libpayment.js'])
  
</body>
</html>