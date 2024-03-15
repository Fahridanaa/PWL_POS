<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ubah User</title>
</head>
<body>
<h1>Form Ubah Data User</h1>
<a href="/PWL_POS/public/user">Kembali</a>
<br><br>
<form method="post" action="/PWL_POS/public/user/ubah_simpan/{{$data->user_id}}">
    {{csrf_field()}}
    {{method_field('PUT')}}

    <label>Username</label>
    <input type="text" name="username" placeholder="Masukkan Username" value="{{$data->username}}">
    <br>
    <label>Nama</label>
    <input type="text" name="name" placeholder="Masukkan Nama" value="{{$data->name}}">
    <label>Password</label>
    <input type="password" name="password" placeholder="Masukkan Password" value="{{$data->password}}">
    <br>
    <label>Level ID</label>
    <input type="number" name="level_id" placeholder="Masukkan ID Level" value="{{$data->level_id}}">
    <br><br>
    <input type="submit" class="btn btn-success" value="Simpan">
</form>
</body>
</html>