@extends('layout.app')
@section('subtitle', 'User')
@section('content_header_title', 'Create')
@section('content_header_subtitle', 'User')
@section('content')
    <div class="container">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Buat User baru</h3>
            </div>
            <div class="m-3">
                <a class="btn btn-secondary" href="{{ url('/m_user') }}">Kembali</a>
            </div>
            <form method="post" action="../m_user">
                {{ csrf_field() }}
                <div class="card-body">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class=" form-control" id="username" name="username"
                               placeholder="Contoh : Adm1">
                        @error('username')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password"
                               placeholder="Contoh : *******">
                        @error('password')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama"
                               placeholder="Contoh : Administrator 1">
                        @error('nama')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="level_id">Level id</label>
                        <input type="number" class="form-control" id="level_id" name="level_id" placeholder="Contoh : 1">
                        @error('level_id')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
        </div>
        </form>
    </div>
@endsection