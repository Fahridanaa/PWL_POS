@extends('layout.app')

@section('subtitle', 'Level')
@section('content_header_title', 'Home')
@section('content_header_subtitle', 'Level')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Manage Level</div>
            <div class="card-body">
                <a href="{{ route('/level/create') }}" class="btn btn-primary rounded-lg px-2 btn-xs mb-3 z-1" id="rand-button">Tambah level</a>
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    {{ $dataTable->scripts() }}
@endpush