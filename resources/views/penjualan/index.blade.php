@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('penjualan/create') }}">Tambah</a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <table class="table table-bordered table-striped table-hover table-sm w-100" id="table_penjualan">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Pembeli</th>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>aksi</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
    <script>
        $(document).ready(function() {
            $('#table_penjualan').DataTable({
                serverSide: true, // serverSide: true, jika ingin menggunakan server side processing
                ajax: {
                    "url": "{{ url('penjualan/list') }}",
                    "dataType": "json",
                    "type": "POST",
                },
                columns: [
                    {
                        data: "DT_RowIndex", // nomor urut dari laravel datatable addIndexColumn()
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },{
                        data: "penjualan.user.name",
                        className: "",
                        orderable: true, // orderable: true, jika ingin kolom ini bisadiurutkan
                        searchable: true // searchable: true, jika ingin kolom ini bisadicari
                    },{
                        data: "penjualan.pembeli",
                        className: "",
                        orderable: true, // orderable: true, jika ingin kolom ini bisadiurutkan
                        searchable: true // searchable: true, jika ingin kolom ini bisadicari
                    },{
                        data: "barang.barang_name",
                        className: "",
                        orderable: true, // orderable: true, jika ingin kolom ini bisadiurutkan
                        searchable: true // searchable: true, jika ingin kolom ini bisadicari
                    },{
                        data: "harga",
                        className: "",
                        orderable: true, // orderable: true, jika ingin kolom ini bisadiurutkan
                        searchable: true // searchable: true, jika ingin kolom ini bisadicari
                    },{
                        data: "jumlah",
                        className: "",
                        orderable: true, // orderable: true, jika ingin kolom ini bisadiurutkan
                        searchable: true // searchable: true, jika ingin kolom ini bisadicari
                    },{
                        data: "aksi",
                        className: "",
                        orderable: false, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: false // searchable: true, jika ingin kolom ini bisa dicari
                    }
                ]
            });
        });
    </script>
@endpush