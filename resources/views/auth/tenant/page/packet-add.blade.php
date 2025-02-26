<x-dashboard-layout>
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">Tambah Paket Baru</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('tenant.dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('tenant.dashboard.packet') }}">Ticket Packet</a></li>
                                    <li class="breadcrumb-item active">Tambah Paket</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
                    <div class="form-group breadcrumb-right">
                        <div class="dropdown">
                            <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i data-feather="grid"></i></button>
                            <div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item" href="app-todo.html"><i class="mr-1" data-feather="check-square"></i><span class="align-middle">Todo</span></a><a class="dropdown-item" href="app-chat.html"><i class="mr-1" data-feather="message-square"></i><span class="align-middle">Chat</span></a><a class="dropdown-item" href="app-email.html"><i class="mr-1" data-feather="mail"></i><span class="align-middle">Email</span></a><a class="dropdown-item" href="app-calendar.html"><i class="mr-1" data-feather="calendar"></i><span class="align-middle">Calendar</span></a></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section class="bs-validation">
                    <div class="row">
                        <div class="col-md-12 col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Form Tambah Paket Baru</h4>
                                </div>
                                <div class="card-body">
                                    <form class="needs-validation" novalidate id="package-form" action="{{ route('tenant.dashboard.packet.insert') }}" method="POST" enctype="multipart/form-data" onsubmit="return false;">
                                        <div class="row mt-1">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="packet_name">Nama Paket</label>
                                                    <input type="text" id="packet_name" class="form-control" name="packet_name" placeholder="Input nama paket" aria-label="packet_name" aria-describedby="packet_name" required />
                                                    <div class="valid-feedback">Looks good!</div>
                                                    <div class="invalid-feedback">Harap masukkan nama paket.</div>
                                                </div>
                                            </div>
                                            <div class="col-4 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="harga">Harga Paket</label>
                                                    <input type="text" id="harga" name="harga" class="form-control" placeholder="1xxxx" aria-label="harga" required />
                                                    <div class="valid-feedback">Looks good!</div>
                                                    <div class="invalid-feedback">Harap masukkan harga paket.</div>
                                                </div>
                                            </div>
                                            <div class="col-4 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="currency">Currency</label>
                                                    <select class="form-control" id="currency" name="currency" required>
                                                        <option value="">- Pilih Currency -</option>
                                                        <option value="IDR">IDR</option>
                                                        <option value="USD">USD</option>
                                                        <option value="GBP">GBP</option>
                                                    </select>
                                                    <div class="valid-feedback">Looks good!</div>
                                                    <div class="invalid-feedback">Harap pilih currency.</div>
                                                </div>
                                            </div>
                                            {{-- <div class="col-4 col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="max_ppl">Max People</label>
                                                    <input type="number" id="max_ppl" class="form-control" name="max_ppl" placeholder="1xx" required />
                                                    <div class="valid-feedback">Looks good!</div>
                                                    <div class="invalid-feedback">Harap masukkan maksimum jumlah peserta.</div>
                                                </div>
                                            </div> --}}
                                            <div class="col-4 col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="duration">Durasi Tiket</label>
                                                    <input type="number" id="duration" class="form-control" name="duration" placeholder="1xx" required />
                                                    <div class="valid-feedback">Looks good!</div>
                                                    <div class="invalid-feedback">Harap masukkan lama durasi paket.</div>
                                                </div>
                                            </div>
                                            <div class="col-4 col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    <label for="currency">Validity Period</label>
                                                    <select class="form-control" id="validity" name="validity" required>
                                                        <option value="">- Pilih Validitas Paket -</option>
                                                        <option value="daily">Harian</option>
                                                        <option value="weekly">Mingguan</option>
                                                        <option value="monthly">Bulanan</option>
                                                        <option value="yearly">Tahunan</option>
                                                    </select>
                                                    <div class="valid-feedback">Looks good!</div>
                                                    <div class="invalid-feedback">Harap pilih validitas periode paket.</div>
                                                </div>
                                            </div>
                                            <div class="col-4 col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    <label for="lokasi">Lokasi Paket</label>
                                                    <select class="form-control" id="lokasi" name="lokasi" required>
                                                        <option value="">- Pilih Lokasi Paket -</option>
                                                        @foreach (App\Models\Location::where('userId', Auth::user()->id)->orderBy('location_name', 'asc')->get() as $lokasi)
                                                            <option value="{{ $lokasi->id }}">{{ $lokasi->location_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="valid-feedback">Looks good!</div>
                                                    <div class="invalid-feedback">Harap pilih validitas periode paket.</div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-12 col-lg-12">
                                                <div class="form-group">
                                                    <label for="detail">Detail Paket</label>
                                                    <div id="detail-editor" style="height: 200px;"></div>
                                                    <input type="hidden" name="detail" id="detail">
                                                    <div class="valid-feedback">Looks good!</div>
                                                    <div class="invalid-feedback">Harap masukkan detail paket.</div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-12 col-lg-12">
                                                <div class="form-group">
                                                    <label for="gallery_files">Upload Gallery</label>
                                                    <div class="dropzone" id="gallery-dropzone">
                                                        <div class="dz-message">Drop files here or click to upload.</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="gallery_files" id="gallery-files" value="">
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-dashboard-layout>
