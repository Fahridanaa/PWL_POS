@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @empty($penjualan)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
            @else
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th>ID</th>
                        <td>{{ $penjualan->penjualan_id }}</td>
                    </tr>
                    <tr>
                        <th>Penjualan Kode</th>
                        <td>{{ $penjualan->penjualan_kode }}</td>
                    </tr>
                    <tr>
                        <th>User</th>
                        <td>{{ $penjualan->user->name }}</td>
                    </tr>
                    <tr>
                        <th>Pembeli</th>
                        <td>{{ $penjualan->pembeli }}</td>
                    </tr>
                    <tr>
                        <th>penjualan_tanggal</th>
                        <td>{{ $penjualan->penjualan_tanggal }}</td>
                    </tr>
                </table>
                <h5 class="mt-4">Detail Penjualan</h5>
                <table class="table table-bordered table-striped table-hover table-sm">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Harga Jual</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $total = 0;
                    @endphp
                    @foreach($detail as $item)
                        @php
                            $subtotal = $item->harga * $item->jumlah;
                            $total += $subtotal;
                        @endphp
                        <tr>
                            <td>{{ $count++ }}</td>
                            <td>{{ $item->barang->barang_kode }}</td>
                            <td>{{ $item->barang->barang_name }}</td>
                            <td>{{ $item->harga }}</td>
                            <td>{{ $item->jumlah }}</td>
                            <td>{{ "Rp. ". $subtotal }}</td>
                        </tr>
                    @endforeach
                    <tr style="background-color: white;">
                        <td colspan="5"><b>Total Penjualan</b></td>
                        <td>{{ "Rp. ". $total }}</td>
                    </tr>
                    </tbody>
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