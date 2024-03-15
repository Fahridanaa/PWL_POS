<!DOCTYPE html>
<html>
    <head>
        <title>Data User</title>
    </head>
    <body>
        <h1>Data User</h1>
        <a href="user/tambah">+ Tambah User</a>
        <table border="1" cellpadding="1" cellspacing="1">
            <tr>
{{--                <th>Jumlah Pengguna</th>--}}
                <th>ID</th>
                <th>Username</th>
                <th>Nama</th>
                <th>ID Level Pengguna</th>
                <th>Aksi</th>
            </tr>
            @foreach($data as $d)
                <tr>
    {{--                <td>{{$data}}</td>--}}
    {{--                <td>{{$data->user_id}}</td>--}}
    {{--                <td>{{$data->username}}</td>--}}
    {{--                <td>{{$data->name}}</td>--}}
    {{--                <td>{{$data->level_id}}</td>--}}
                    <td>{{$d->user_id}}</td>
                    <td>{{$d->username}}</td>
                    <td>{{$d->name}}</td>
                    <td>{{$d->level_id}}</td>
                    <td><a href="user/ubah/{{$d->user_id}}">Ubah</a> | <a href="user/hapus/{{$d->user_id}}">Hapus</a></td>
                </tr>
            @endforeach
        </table>
    </body>
</html>