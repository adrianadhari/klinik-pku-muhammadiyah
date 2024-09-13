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
    <div class="min-h-screen flex items-center justify-center">
        <div class="shadow-xl rounded-2xl border border-[#D6D6D6] px-7 py-10 w-[450px]">
            <h1 class="text-4xl text-center font-bold">Login</h1>
            @if (session()->has('failed'))
                <div role="alert" class="alert alert-error mt-7">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('failed') }}</span>
                </div>
            @endif
            <form action="/" method="POST">
                @csrf
                <div class="flex flex-col space-y-6 mt-7">
                    <input type="text" name="username" id="username" class="p-3 rounded-xl border outline-none"
                        placeholder="Username">
                    <input type="password" name="password" id="password" class="p-3 rounded-xl border outline-none"
                        placeholder="Password">
                    <button type="submit"
                        class="bg-[#4B8D7F] text-white font-medium text-center w-full p-3 rounded-xl hover:opacity-90">LOGIN</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
