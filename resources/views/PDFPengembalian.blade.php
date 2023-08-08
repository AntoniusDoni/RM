<!DOCTYPE html>
<html>

<head>
    <title>Berkas RM</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    
    <header>
        <div style="text-align: center; font-size: 16px;font-weight: bold;">
        <img src="images/Logo RSD Tanpa BG.png" width="90" height="100" style="float: left; padding: 0; margin: auto;"/>
        RUMAH SAKIT DIRGAHAYU SAMARINDA</div> <br>
        <div style="text-align: center;">
             <u style="font-size: 10px; font-weight:bold;">Jl. Gunung Merbabu No.62, Jawa, Kec. Samarinda Ulu, Kota Samarinda, Kalimantan Timur 75243</u>
        </div>
        <br>
        <hr>
    </header>
    <h3 style="font-size: 14px; text-align: center; font-weight: bold;">{{ $title }}</h3>
    <?php if(!empty($datestart)&&!empty($dateend)){?>
    <p style="font-size: 10px;">Periode {{$datestart}} Sampai dengan {{$dateend}}</p>
   <?php }
    ?>
    <table class="table table-bordered" style="font-size: 12px;">
        <thead className="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" className="py-3 px-6">
                    No.
                </th>
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
                    Kode Pengembali
                </th>
                <th scope="col" className="py-3 px-6">
                    Tanggal Kembali
                </th>

            </tr>
        </thead>
        <?php $i=0;?>
        @foreach ($datas as $data)
        <?php $i++;?>
            <tr>
                <td><?=$i;?></td>
                <td>{{ $data?->pasien?->no_rm }}</td>
                <td>{{ $data?->pasien?->nama }}</td>
                <td>{{ $data?->petugasKembali?->ruang?->nama }}</td>
                <td>{{ $data?->petugasKembali?->kode_petugas }}
                    {{ $data?->petugasKembali?->nama }}
                </td>
                <td>{{$data->tanggal_kembali}}</td>
            </tr>
        @endforeach
    </table>
    <p style="font-size: 12px";>Samarinda, {{ $date }} </p>
    <br>
    <p style="font-size: 12px;"><u>{{$creator}}</u></p>
</body>

</html>
