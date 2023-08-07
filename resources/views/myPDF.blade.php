<!DOCTYPE html>
<html>

<head>
    <title>Berkas RM</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <h1>{{ $title }}</h1>



    <table class="table table-bordered">
        <thead className="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" className="py-3 px-6">
                    No RM
                </th>
                <th scope="col" className="py-3 px-6">
                    Nama Pasien
                </th>
                <th scope="col" className="py-3 px-6">
                    Kode Ruangan
                </th>
                <th scope="col" className="py-3 px-6">
                    Kode peminjam
                </th>
                <th scope="col" className="py-3 px-6">
                    Tanggal Pinjam
                </th>

            </tr>
        </thead>
        @foreach ($datas as $data)
            <tr>
                <td>{{ $data?->pasien?->no_rm }}</td>
                <td>{{ $data?->pasien?->nama }}</td>
                <td>{{ $data?->petugasPinjam?->ruang?->nama }}</td>
                <td>{{ $data?->petugasPinjam?->kode_petugas }}
                    {{ $data?->petugasPinjam?->nama }}
                </td>
                <td>{{$data->tgl_pinjam}}</td>
            </tr>
        @endforeach
    </table>
    <p>Samarinda, {{ $date }} </p>
    <p>Petugas {{$datas?->creator?->name}}</p>
</body>

</html>
