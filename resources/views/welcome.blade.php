@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">PWL POS Jobsheet 7</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            <h2>Selamat datang semua, ini adalah halaman utama dari aplikasi ini.</h2>
            <img src="{{ $item['images']['original']['url'] }}" alt="{{ $item['title'] }}">
        </div>
    </div>
@endsection