<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ranking Sales | {{ $tanggalAwal }} - {{ $tanggalAkhir }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Sales</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sales as $sale)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $sale->sales_name }}</td>
                <td>Rp {{ number_format($sale->total_omset,0,',','.') }}</td>
            </tr>
            @endforeach
            <tr>
                <th colspan="2">Total</th>
                <th>Rp {{ number_format($totalOmset,0,',','.') }}</th>
            </tr>
        </tbody>
    </table>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            window.print();
        });
    </script>
</body>
</html>
