<x-dashboard-layout>
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">Location Data List</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('tenant.dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item active">Location</li>
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
                                                    <div class="col-lg-4">
                                                        <label>Name:</label>
                                                        <input type="text" id="name" class="form-control dt-input dt-full-name" data-column="0" placeholder="Input nama lokasi" />
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label>Address:</label>
                                                        <input type="text" id="address" class="form-control dt-input" data-column="1" placeholder="Input alamat lokasi" />
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label>Phone:</label>
                                                        <input type="text" id="phone" class="form-control dt-input" data-column="2" placeholder="08-XXX-XXXX-XXX" />
                                                    </div>
                                                    {{-- <div class="col-lg-3">
                                                        <label>Jenis Lokasi:</label>
                                                        <select id="lokasi" class="select2 form-control dt-input" data-column="3">
                                                            <option value="">- Pilih Jenis Lokasi -</option>
                                                            @foreach (App\Models\LocationType::orderBy('name', 'asc')->get() as $lokasi)
                                                                <option value="{{ $lokasi->id }}"">{{ $lokasi->name }} - {{ $lokasi->detail }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <hr class="my-0" />
                                <div class="card-datatable">
                                    <table id="locations-table" class="table w-100">
                                        <thead>
                                            <tr>
                                                <th>Nama Lokasi</th>
                                                <th>Ticket Quota Per-day</th>
                                                <th>Alamat</th>
                                                <th>No. Telp</th>
                                                <th>Jenis Lokasi</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal to add new record -->
                    <div class="modal modal-slide-in fade" id="modals-slide-in">
                        <div class="modal-dialog sidebar-sm">
                            <form class="add-new-record modal-content pt-0">
                                @csrf
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                                <div class="modal-header mb-1">
                                    <h5 class="modal-title" id="exampleModalLabel">Tambah Lokasi Baru</h5>
                                </div>
                                <div class="modal-body flex-grow-1">
                                    <div class="form-group">
                                        <label class="form-label" for="name">Nama Lokasi</label>
                                        <input name="name" required type="text" class="form-control" id="name" placeholder="Input nama lokasi" aria-label="Input nama lokasi" />
                                        <small class="text-danger"></small>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="name">Max Ticket Quota</label>
                                        <input name="max_quota" required type="number" class="form-control" id="max_quota" placeholder="Input max ticket quota" aria-label="Input max ticket quota" />
                                        <small class="text-danger"></small>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="address">Address</label>
                                        <textarea class="form-control" name="address" id="address" rows="3" placeholder="Input alamat lokasi" required></textarea>
                                        <small class="text-danger"></small>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="phone">Phone Number</label>
                                        <input name="phone" required type="text" class="form-control" id="phone" placeholder="Input nomor telepon lokasi" aria-label="Input nomor telepon lokasi" />
                                        <small class="text-danger"></small>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="jenis_lokasi">Jenis Lokasi</label>
                                        <select class="select2 form-control" id="jenis_lokasi" name="jenis_lokasi[]" multiple>
                                            @foreach (App\Models\LocationType::orderBy('name', 'asc')->get() as $lokasi)
                                                <option value="{{ $lokasi->id }}"">{{ $lokasi->name }}</option>
                                            @endforeach
                                        </select>
                                        <small class="text-danger"></small>
                                    </div>
                                    <button type="submit" class="btn btn-primary data-submit mr-1">Submit</button>
                                    <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">Reset</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="modal modal-slide-in fade" id="modals-edit-slide-in">
                        <div class="modal-dialog sidebar-sm">
                            <form class="edit-new-record modal-content pt-0">
                                @csrf
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                                <div class="modal-header mb-1">
                                    <h5 class="modal-title" id="exampleModalLabel">Edit Data Lokasi</h5>
                                </div>
                                <div class="modal-body flex-grow-1" id="edit-lokasi">
                                    <div class="form-group">
                                        <label class="form-label" for="name">Nama Lokasi</label>
                                        <input type="hidden" class="d-none" name="id" id="id">
                                        <input name="name" required type="text" class="form-control" id="name" placeholder="Input nama lokasi" aria-label="Input nama lokasi" />
                                        <small class="text-danger"></small>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="name">Max Ticket Quota</label>
                                        <input name="max_quota" required type="number" class="form-control" id="max_quota" placeholder="Input max ticket quota" aria-label="Input max ticket quota" />
                                        <small class="text-danger"></small>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="address">Address</label>
                                        <textarea class="form-control" name="address" id="address" rows="3" placeholder="Input alamat lokasi" required></textarea>
                                        <small class="text-danger"></small>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="phone">Phone Number</label>
                                        <input name="phone" required type="text" class="form-control" id="phone" placeholder="Input nomor telepon lokasi" aria-label="Input nomor telepon lokasi" />
                                        <small class="text-danger"></small>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="jenis_lokasi">Jenis Lokasi</label>
                                        <select class="select2 form-control" id="jenis_lokasi_edit" name="jenis_lokasi[]" multiple>
                                            @foreach (App\Models\LocationType::orderBy('name', 'asc')->get() as $lokasi)
                                                <option value="{{ $lokasi->id }}"">{{ $lokasi->name }}</option>
                                            @endforeach
                                        </select>
                                        <small class="text-danger"></small>
                                    </div>
                                    <button type="submit" class="btn btn-primary data-submit mr-1">Submit</button>
                                    <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">Reset</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
                <!--/ Basic table -->
            </div>
        </div>
    </div>
</x-dashboard-layout>
