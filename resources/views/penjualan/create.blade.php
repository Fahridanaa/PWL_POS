@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ url('penjualan') }}" class="form-horizontal">
                @csrf
                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Kode Penjualan</label>
                    <div class="col-11">
                        <input readonly type="text" class="form-control" id="penjualan_kode" name="penjualan_kode"
                               value="{{ old('penjualan_kode', $kode_penjualan) }}" required>
                        @error('penjualan_kode')
                        <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Pembeli</label>
                    <div class="col-11">
                        <input type="text" class="form-control" id="pembeli" name="pembeli"
                               value="{{ old('pembeli') }}" required>
                        @error('pembeli')
                        <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="detail">Detail Penjualan:</label>
                    <table class="table table-bordered" id="detail">
                        <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Total Harga</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <select name="barang_id[]" class="form-control barang" required>
                                    <option value="">Pilih Barang</option>
                                    @foreach ($barang as $item)
                                        <option value="{{ $item->barang_id }}">{{ $item->barang_name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" class="form-control harga" name="harga[]" readonly></td>
                            <td><input type="number" class="form-control jumlah" name="jumlah[]" required></td>
                            <td>
                                <input type="number" class="form-control total_harga" name="total_harga[]" readonly>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-primary btn-sm" id="tambahBarang">Tambah Barang</button>
                </div>
                <div class="form-group row">
                    <label class="col-1 control-label col-form-label"></label>
                    <div class="col-11">
                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                        <a class="btn btn-sm btn-default ml-1" href="{{ url('penjualan')}}">Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('css')
@endpush
@push('js')
    <script>
        $(document).ready(function() {
            // Ketika tombol "Tambah Barang" diklik
            $('#tambahBarang').click(function() {
                // Tambahkan baris baru ke tabel detail penjualan
                $('#detail tbody').append('<tr>' +
                    '<td><select name="barang_id[]" class="form-control barang" required><option value="">Pilih Barang</option>@foreach ($barang as $item)<option value="{{ $item->barang_id }}">{{ $item->barang_name }}</option>@endforeach</select></td>' +
                    '<td><input type="text" class="form-control harga" name="harga[]" readonly></td>' +
                    '<td><input type="number" class="form-control jumlah" name="jumlah[]" required></td>' +
                    '<td><input type="number" class="form-control total_harga" name="total_harga[]" readonly></td>' +
                    '</tr>');
            });

            let detailTable = $('#detail');
            // Ketika dropdown barang dipilih, perbarui harga secara otomatis
            detailTable.on('change', 'select[name="barang_id[]"]', function() {
                var selectedId = $(this).val();
                var hargaInput = $(this).closest('tr').find('.harga');
                // Melakukan AJAX request untuk mendapatkan harga barang berdasarkan ID yang dipilih
                $.ajax({
                    url: '{{ url("penjualan/get-harga") }}/' + selectedId,
                    type: 'GET',
                    success: function(response) {
                        hargaInput.val(response.harga_jual);
                    },
                    error: function() {
                        hargaInput.val('');
                    }
                });
            });

            detailTable.on('input', 'input[name="jumlah[]"]', function() {
                let jumlah = $(this).val() || 0;
                let harga = $(this).closest('tr').find('.harga').val();
                let totalHargaInput = $(this).closest('tr').find('.total_harga');

                let totalHarga = harga * jumlah;
                totalHargaInput.val(totalHarga.toFixed(2));
            });
        });
    </script>
@endpush