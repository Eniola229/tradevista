<!DOCTYPE html>
<html lang="zxx">
@php use Illuminate\Support\Str; @endphp
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Where Buyers and Sellers Connect">
    <meta name="keywords" content="tradevista, ecommerce,">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @php
        $isProductPage = Str::contains(request()->url(), 'product-details');
    @endphp

    @if($isProductPage && isset($product))
        <title>{{ $product->product_name }} | TradeVista Hub</title>
        <link rel="icon" type="image/png" href="{{ asset($product->image_url) }}">
    @else
        <title>TradeVista Hub | Connecting Buyers and Sellers</title>
        <link rel="icon" type="image/png" href="https://tradevista.biz/img/logo.png">
    @endif

        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Cookie&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Css Styles -->
        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" type="text/css">
        <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}" type="text/css">
        <link rel="stylesheet" href="{{ asset('css/elegant-icons.css') }}" type="text/css">
        <link rel="stylesheet" href="{{ asset('css/jquery-ui.min.css') }}" type="text/css">
        <link rel="stylesheet" href="{{ asset('css/magnific-popup.css') }}" type="text/css">
        <link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css') }}" type="text/css">
        <link rel="stylesheet" href="{{ asset('css/slicknav.min.css') }}" type="text/css">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}" type="text/css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        

        
</head>
