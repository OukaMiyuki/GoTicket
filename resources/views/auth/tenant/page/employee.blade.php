<x-dashboard-layout>
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">Employee Data List</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('tenant.dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item active">Employee</li>
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
                                                    <div class="col-lg-2">
                                                        <label>Name:</label>
                                                        <input type="text" id="name" class="form-control dt-input dt-full-name" data-column="0" placeholder="John Doe" />
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <label>Email:</label>
                                                        <input type="text" id="email" class="form-control dt-input" data-column="1" placeholder="example@email.com" />
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <label>Phone:</label>
                                                        <input type="text" id="phone" class="form-control dt-input" data-column="2" placeholder="08-XXX-XXXX-XXX" />
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <label>Jenis Kelamin:</label>
                                                        <select id="jenis_kelamin" class="form-control dt-input" data-column="3">
                                                            <option value="">- Pilih Jenis Lokasi -</option>
                                                            <option value="Laki-laki">Laki-laki</option>
                                                            <option value="Perempuan">Perempuan</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <label>Jabatan:</label>
                                                        <select id="jabatan" class="form-control dt-input" data-column="4">
                                                            <option value="">- Pilih Jenis Jabatan -</option>
                                                            <option value="playground_supervisor">Supervisor</option>
                                                            <option value="playground_operator">Operator</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-2">
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
                                <div class="card-datatable"">
                                    <table id="user-table" class="table w-100">
                                        <thead>
                                            <tr>
                                                <th>Nama</th>
                                                <th>Email</th>
                                                <th>No. Telp/WA</th>
                                                <th>Jenis Kelamin</th>
                                                <th>Jabatan</th>
                                                <th>Lokasi</th>
                                                <th>Status</th>
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
                                    <h5 class="modal-title" id="exampleModalLabel">Tambah Karyawan</h5>
                                </div>
                                <div class="modal-body flex-grow-1">
                                    <div class="form-group">
                                        <label class="form-label" for="name">Nama Lengkap</label>
                                        <input name="name" required type="text" class="form-control" id="name" placeholder="John Doe" aria-label="John Doe" />
                                        <small class="text-danger"></small>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="email">Email</label>
                                        <input name="email" required type="email" class="form-control" id="email" placeholder="email@example.com" aria-label="email@example.com" />
                                        <small class="text-danger"></small>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="phone">No. Telp/WA</label>
                                        <input name="phone" required type="text" class="form-control" id="phone" placeholder="08XXXXXXXXXX" aria-label="08XXXXXXXXXX" />
                                        <small class="text-danger"></small>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="jenis_kelamin">Jenis Kelamin</label>
                                        <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                                            <option value="">- Pilih Jenis Kelamin -</option>
                                            <option value="Laki-laki">Laki-laki</option>
                                            <option value="Perempuan">Perempuan</option>
                                        </select>
                                        <small class="text-danger"></small>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="jabatan">Jabatan</label>
                                        <select class="form-control" id="jabatan" name="jabatan" required>
                                            <option value="">- Pilih Jabatan -</option>
                                            <option value="playground_supervisor">Supervisor</option>
                                            <option value="playground_operator">Operator</option>
                                        </select>
                                        <small class="text-danger"></small>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="plot_lokasi">Plot Lokasi</label>
                                        <select class="select2 form-control" id="plot_lokasi" name="plot_lokasi[]" multiple required>
                                            @foreach (App\Models\Location::where('userId', Auth::user()->id)->orderBy('location_name', 'asc')->get() as $lokasi)
                                                <option value="{{ $lokasi->id }}">{{ $lokasi->location_name }}</option>
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
            </div>
        </div>
    </div>
</x-dashboard-layout>
