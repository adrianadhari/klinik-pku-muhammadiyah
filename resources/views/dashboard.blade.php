@extends('layout')
@section('content')
    <div class="flex-grow container  mx-auto flex space-x-36 justify-center text-center items-center">

        <div class="w-1/3">
            <h1 class="text-5xl font-bold mb-10">IMPORT FILE</h1>
            <a class="cursor-pointer" onclick="my_modal_1.showModal()">
                <div class="flex items-center flex-col justify-center p-32 bg-[#F6F9F8] space-y-6 border-dashed border">
                    <img src="{{ asset('upload.png') }}" alt="Upload">
                    <p class="text-2xl font-semibold">Upload files <span class="text-[#483EA8] underline">here</span></p>
                </div>
            </a>
            <dialog id="my_modal_1" class="modal">
                <div class="modal-box w-full max-w-5xl">
                    <form method="dialog">
                        <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                    </form>
                    <div class="flex flex-col space-y-4 px-8">
                        <h3 class="text-4xl font-medium">Upload files</h3>
                        <p class="text-2xl">Select relevant documents you want to upload</p>
                        <div class="w-full flex flex-col space-y-6 items-center justify-center p-12">
                            <img src="{{ asset('upload-2.png') }}" alt="Upload">
                            <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="file" name="file" class="file-input file-input-bordered w-full max-w-xs"
                                    required />
                                <button
                                    class="bg-[#4A8E7F] mt-6 px-5 py-2 rounded-md text-white font-semibold text-md w-1/3 hover:opacity-90"
                                    type="submit">UPLOAD</button>
                            </form>
                        </div>
                    </div>
                </div>
            </dialog>
        </div>

        <div class="w-1/3">
            <h1 class="text-5xl font-bold mb-10">CREATE REPORT</h1>
            <a class="cursor-pointer" onclick="my_modal_2.showModal()">
                <div class="flex items-center flex-col justify-center p-32 bg-[#F6F9F8] space-y-6 border-dashed border">
                    <img src="{{ asset('report.png') }}" alt="Upload">
                    <p class="text-2xl font-semibold">Create report <span class="text-[#483EA8] underline">here</span></p>
                </div>
            </a>
            <dialog id="my_modal_2" class="modal">
                <div class="modal-box w-full max-w-5xl">
                    <form method="dialog">
                        <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                    </form>
                    <div class="flex flex-col space-y-4 px-8">
                        <h1 class="text-4xl font-bold">Create Daily Report</h1>
                        <div class="p-6 flex space-x-14">
                            <div class="w-1/2">
                                <div class="flex flex-col space-y-4">
                                    <label class="form-control w-full max-w-xs">
                                        <div class="label">
                                            <span class="label-text text-lg font-bold">Status</span>
                                        </div>
                                        <select class="select border-[#A6C3BA] border-2">
                                            <option>Lunas</option>
                                            <option>Belum Lunas</option>
                                        </select>
                                    </label>
                                    <div class="flex items-center text-start space-x-4">
                                        <div>
                                            <label for="tanggal_mulai" class="text-lg font-bold">Tanggal Mulai</label>
                                            <input type="date" name="tanggal_mulai"
                                                class="border-2 mt-2 rounded-lg p-3 border-[#A6C3BA] w-full"
                                                id="tanggal_mulai">
                                        </div>
                                        <div>
                                            <label for="tanggal_berakhir" class="text-lg font-bold">Tanggal Berakhir</label>
                                            <input type="date" name="tanggal_berakhir" id="tanggal_berakhir"
                                                class="border-2 mt-2 rounded-lg p-3 border-[#A6C3BA] w-full">
                                        </div>
                                    </div>
                                    <div class="flex items-center text-start space-x-4">
                                        <div>
                                            <label for="jam_mulai" class="text-lg font-bold">Jam Mulai</label>
                                            <input type="time" name="jam_mulai" id="jam_mulai"
                                                class="border-2 mt-2 rounded-lg p-3 border-[#A6C3BA] w-full">
                                        </div>
                                        <div>
                                            <label for="jam_selesai" class="text-lg font-bold">Jam Selesai</label>
                                            <input type="time" name="jam_selesai" id="jam_selesai"
                                                class="border-2 mt-2 rounded-lg p-3 border-[#A6C3BA] w-full">
                                        </div>
                                    </div>
                                    <div class="flex items-center text-start space-x-4">
                                        <label class="form-control w-full max-w-xs">
                                            <div class="label">
                                                <span class="label-text text-lg font-bold">Poli</span>
                                            </div>
                                            <select class="select border-[#A6C3BA] border-2">
                                                <option>Semua</option>
                                                <option>Belum Lunas</option>
                                            </select>
                                        </label>
                                        <label class="form-control w-full max-w-xs">
                                            <div class="label">
                                                <span class="label-text text-lg font-bold">Tenaga Medis</span>
                                            </div>
                                            <select class="select border-[#A6C3BA] border-2">
                                                <option>Semua</option>
                                                <option>Belum Lunas</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="w-1/2">
                                <div class="flex flex-col space-y-4">
                                    <div class="flex items-center space-x-4 text-start">
                                        <div>
                                            <p class="text-lg font-bold">Jenis Laporan</p>
                                            <div class="p-6 rounded-lg border-2 mt-2 border-[#A6C3BA]">
                                                <div>
                                                    <input type="radio" name="jenis_laporan">
                                                    <label for="jenis_laporan" class="me-4">Rinci</label>
                                                    <input type="radio" name="jenis_laporan">
                                                    <label for="jenis_laporan">Rekap</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-lg font-bold">Accounting</p>
                                            <div class="p-6 rounded-lg border-2 mt-2 border-[#A6C3BA]">
                                                <div>
                                                    <input type="radio" name="jenis_laporan">
                                                    <label for="jenis_laporan" class="me-4">Rinci</label>
                                                    <input type="radio" name="jenis_laporan">
                                                    <label for="jenis_laporan">Rekap</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col space-y-2 text-start">
                                        <p class="text-lg font-bold">Metode Pembayaran</p>
                                        <div>
                                            <input type="radio" name="metode_pembayaran">
                                            <label for="cash">Cash</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="metode_pembayaran">
                                            <label for="cash">Kredit</label>
                                        </div>
                                    </div>
                                    <label class="form-control w-full max-w-xs">
                                        <div class="label">
                                            <span class="label-text text-lg font-bold">Penanggung Jawab</span>
                                        </div>
                                        <select class="select border-[#A6C3BA] border-2">
                                            <option>Semua</option>
                                            <option>Belum Lunas</option>
                                        </select>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-center space-x-8">
                            <button type="submit" class="px-9 py-4 rounded-md shadow border text-[#21A366]">
                                <div class="flex space-x-2 items-center">
                                    <img src="{{ asset('excel.png') }}" alt="">
                                    <span>Create Excel</span>
                                </div>
                            </button>
                            <button type="submit" class="px-9 py-4 rounded-md shadow border text-[#DD2025]">
                                <div class="flex space-x-2 items-center">
                                    <img src="{{ asset('pdf.png') }}" alt="">
                                    <span>Create PDF</span>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </dialog>
        </div>

    </div>
@endsection
