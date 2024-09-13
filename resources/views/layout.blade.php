<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>Klinik PKU Muhammadiyah</title>
</head>

<body>
    <div class="min-h-screen flex flex-col">
        @include('navbar')
        @yield('content')
    </div>
    @yield('script')
</body>

</html>
