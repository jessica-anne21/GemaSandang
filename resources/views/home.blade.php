@extends('layouts.app')

@section('content')

<nav class="navbar navbar-expand-lg" style="background-color: #f3f1ea;">
  <div class="container">
    <a class="navbar-brand" href="#" style="font-family: 'Playfair Display', serif;">Gema Sandang</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav mx-auto">
        <li class="nav-item"><a class="nav-link" href="#">BERANDA</a></li>
        <li class="nav-item"><a class="nav-link" href="#">BELANJA</a></li>
        <li class="nav-item"><a class="nav-link" href="#">TENTANG</a></li>
      </ul>
      <div class="d-flex">
          <a href="#" class="nav-link me-3">Keranjang</a>
          <a href="#" class="nav-link">Masuk</a>
      </div>
    </div>
  </div>
</nav>

<div class="container my-5 text-center">
    <h1 class="display-3 text-burgundy" style="font-family: 'Playfair Display', serif;">Gema Sandang</h1>
    <p class="lead text-charcoal" style="font-family: 'Lato', sans-serif;">Pakaian 2<sup>nd</sup> Berkualitas</p>
    <a href="#" class="btn btn-burgundy btn-lg mt-3">SHOP NOW</a>
</div>

@endsection