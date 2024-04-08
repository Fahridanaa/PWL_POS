@extends('layout.app')
@section('subtitle', 'User')
@section('content_header_title', 'Edit')
@section('content_header_subtitle', 'User')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Edit Level</div>
            <div class="card-body">
                <a class="btn btn-secondary" href="{{ url('/m_user') }}">Kembali</a>
                <form method="post" action="{{ route('m_user.update', $useri->user_id) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group mt-2">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username"
                               value="{{ $useri->username }}">
                        @error('username')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password"
                               value="{{ $useri->password }}">
                        @error('password')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" class="form-control" id="name" name="name"
                               value="{{ $useri->name }}">
                        @error('nama')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="level_id">Level Id</label>
                        <input type="text" class="form-control" id="level_id" name="level_id"
                               value="{{ $useri->level_id }}">
                        @error('level_id')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection