@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @empty($detail)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
            @else
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th>ID</th>
                        <td>{{ $detail->detail_id }}</td>
                    </tr>
                    <tr>
                        <th>Penjualan Kode</th>
                        <td>{{ $detail->penjualan->penjualan_kode }}</td>
                    </tr>
                    <tr>
                        <th>User</th>
                        <td>{{ $detail->penjualan->user->name }}</td>
                    </tr>
                    <tr>
                        <th>Pembeli</th>
                        <td>{{ $detail->penjualan->pembeli }}</td>
                    </tr>
                    <tr>
                        <th>Harga</th>
                        <td>{{ $detail->harga }}</td>
                    </tr>
                    <tr>
                        <th>Jumlah</th>
                        <td>{{ $detail->jumlah }}</td>
                    </tr>
                    <tr>
                        <th>penjualan_tanggal</th>
                        <td>{{ $detail->penjualan->penjualan_tanggal }}</td>
                    </tr>
                </table>
            @endempty
            <a href="{{ url('penjualan') }}" class="btn btn-sm btn-default mx-2">Kembali</a>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
@endpush