<!doctype html>
<html lang="id">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>KYC Verifikasi Satu Sehat</title>
</head>

<body class="antialiased">
    <div class="d-flex align-items-center flex-column min-vh-100">
        <div class="w-100 align-self-center">
            <div class="d-flex flex-row" style="height: 10vh">
                <div class="mx-auto">
                    <h1 class="text-center">VERIFIKASI SATU SEHAT</h1>
                    {{-- <h2 class="text-center">RS BHAYANGKARA NGANJUK</h2> --}}
                </div>
            </div>
        </div>
        <div class="w-100 mt-auto">
            <div class="d-flex flex-row">
                <div class="mx-auto mb-5">
                    <a class="btn btn-lg btn-primary" href="/">Refresh</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script>
        $(document).ready(function() {
            const y = window.top.outerHeight / 2 + window.top.screenY - ( 1200 / 2);
            const x = window.top.outerWidth / 2 + window.top.screenX - ( 700 / 2);
            window.open("{{$url}}", 'Satu Sehat', 'width=1200,height=700,scrollbars=yes,menubar=yes,status=no,location=no,toolbar=yes');
        });
    </script>
</body>

</html>