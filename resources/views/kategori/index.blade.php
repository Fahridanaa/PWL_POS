@extends('layout.app')

@section('subtitle', 'Kategori')
@section('content_header_title', 'Home')
@section('content_header_subtitle', 'Kategori')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Manage Kategori</div>
            <div class="card-body">
                <a href="{{ route('/kategori/create') }}" class="btn rounded-lg px-2 btn-xs mb-3 z-1" id="rand-button">+ Tambah Kategori</a>
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let button = document.getElementById("rand-button");
            let card = document.querySelector(".card-body");

            function randomColor() {
                let r = Math.floor(Math.random() * 256);
                let g = Math.floor(Math.random() * 256);
                let b = Math.floor(Math.random() * 256);
                return `rgb(${r},${g},${b})`;
            }

            function animateButton() {
                button.style.backgroundColor = randomColor();
                button.style.left = Math.random() * (card.offsetWidth - button.offsetWidth) + 'px';
                button.style.top = Math.random() * (card.offsetHeight - button.offsetHeight) + 'px';
            }

            setInterval(animateButton, 500);
        });
    </script>
@endpush

<style>
    .btn {
        position: absolute;
        transition: all 2s;
    }
</style>

@push('scripts') {{ $dataTable->scripts() }} @endpush