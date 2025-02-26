<x-dashboard-layout>
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">Packet Data List</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('tenant.dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item active">Ticket Packet</li>
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
                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body mt-2">
                                    <form class="dt_adv_search" method="POST">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-row mb-1">
                                                    <div class="col-lg-3">
                                                        <label>Name:</label>
                                                        <input type="text" id="packet_name" class="form-control dt-input dt-full-name" data-column="0" placeholder="Input nama paket" />
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <label>Price (Rp.):</label>
                                                        <input type="text" id="price" class="form-control dt-input" data-column="1" placeholder="1xxxxx" />
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <label>Status:</label>
                                                        <select id="status" class="form-control dt-input" data-column="2">
                                                            <option value="">- Pilih Status -</option>
                                                            <option value="1">Aktif</option>
                                                            <option value="0">Tidak Aktif</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <label>Lokasi:</label>
                                                        <select class="select2 form-control" id="lokasi" name="lokasi[]" multiple>
                                                            @foreach (App\Models\Location::where('userId', Auth::user()->id)->orderBy('location_name', 'asc')->get() as $lokasi)
                                                                <option value="{{ $lokasi->id }}"">{{ $lokasi->location_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <hr class="my-0" />
                                <div class="card-datatable">
                                    <table id="packet-table" class="table w-100">
                                        <thead>
                                            <tr>
                                                <th>Nama Paket</th>
                                                <th>Lokasi Paket</th>
                                                <th>Harga (Rp.)</th>
                                                <th>Currency</th>
                                                {{-- <th>Max People</th> --}}
                                                <th>Durasi</th>
                                                <th>Tipe Durasi</th>
                                                <th>Status</th>
                                                <th>Photo</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <div class="modal fade text-left" id="carouselModal" tabindex="-1" role="dialog" aria-labelledby="carouselModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="carouselModalLabel">Packet Gallery</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="carousel-keyboard" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner" id="carouselImages">

                        </div>
                        <a class="carousel-control-prev" href="#carousel-keyboard" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carousel-keyboard" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
