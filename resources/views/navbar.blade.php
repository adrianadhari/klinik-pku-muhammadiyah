<div class="navbar bg-[#4A8E7F]">
    <div class="container mx-auto">
        <div class="flex-1">
            <a class="btn btn-ghost text-xl" href="/dashboard">
                <img src="{{ asset('logo.png') }}" alt="Logo" class="w-11">
            </a>
        </div>
        <div class="flex-none gap-2">
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                    <div class="w-10 rounded-full">
                        <img alt="Tailwind CSS Navbar component" src="{{ asset('profile.png') }}" />
                    </div>
                </div>
                <ul tabindex="0"
                    class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <li><button type="submit">Logout</button></li>
                    </form>
                </ul>
            </div>
        </div>
    </div>
</div>
