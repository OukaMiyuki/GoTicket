<x-dashboard-layout>
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">Voucher Data List</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('tenant.dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item active">Voucher</li>
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
                                                        <label>Tipe Diskon:</label>
                                                        <select id="discount_type" class="form-control dt-input" data-column="0">
                                                            <option value="">- Pilih Tipe Diskon -</option>
                                                            <option value="percentage">Percentage</option>
                                                            <option value="fixed">Fixed</option>
                                                        </select>
                                                    </div>
                                                    {{-- <div class="col-lg-2">
                                                        <label>Start Date:</label>
                                                        <input type="date" id="start_date" class="form-control dt-input" data-column="0" placeholder="" />
                                                    </div> --}}
                                                    {{-- <div class="col-lg-2">
                                                        <label>End Date:</label>
                                                        <input type="date" id="end_date" class="form-control dt-input" data-column="0" placeholder="" />
                                                    </div> --}}
                                                    <div class="col-lg-3">
                                                        <label>Voucher Date Range:</label>
                                                        <input type="text" id="date_range" class="form-control dt-input flatpickr-range" data-column="1" placeholder="YYYY-MM-DD to YYYY-MM-DD" />
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <label>Status:</label>
                                                        <select id="status" class="form-control dt-input" data-column="2">
                                                            <option value="">- Pilih Status -</option>
                                                            <option value="active">Active</option>
                                                            <option value="inactive">Tidak Aktif</option>
                                                            <option value="expired">Exppired</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <label>Lokasi:</label>
                                                        <select class="select2 form-control" id="lokasi" name="lokasi[]" multiple data-column="3">
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
                                    <table id="voucher-table" class="table w-100">
                                        <thead>
                                            <tr>
                                                <th>Voucher Code</th>
                                                <th>Discount Type</th>
                                                <th>Discount Value</th>
                                                <th>Min. Spend</th>
                                                <th>Max. Discount</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Usage Limit</th>
                                                <th>Per-User Limit</th>
                                                <th>Plot Lokasi</th>
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
                                    <h5 class="modal-title" id="exampleModalLabel">Tambah Voucher</h5>
                                </div>
                                <div class="modal-body flex-grow-1">
                                    <div class="form-group">
                                        <label class="form-label" for="code">Kode Voucher</label>
                                        <input name="code" required type="text" class="form-control" id="code" placeholder="kodevocher123" aria-label="kodevocher123" />
                                        <small class="text-danger"></small>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="discount_type">Jenis Diskon</label>
                                        <select class="form-control" id="discount_type" name="discount_type" required>
                                            <option value="">- Pilih Jenis Diskon -</option>
                                            <option value="percentage">Percentage</option>
                                            <option value="fixed">Fixed</option>
                                        </select>
                                        <small class="text-danger"></small>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="discount_value">Nominal Diskon (Persen/Nominal)</label>
                                        <input name="discount_value" required type="number" class="form-control" id="discount_value" placeholder="Input nominal atau presentase diskon" aria-label="discount_value" />
                                        <small class="text-danger"></small>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="min_spend">Min. Spend</label>
                                        <input name="min_spend" required type="number" class="form-control" id="min_spend" placeholder="1xxxx" aria-label="1xxxx" />
                                        <small class="text-danger"></small>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="max_discount">Max. Discount (Opsional)</label>
                                        <input name="max_discount" type="number" class="form-control" id="max_discount" placeholder="1xxxx" aria-label="1xxxx" />
                                        <small class="text-danger"></small>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="range_date">Discount Date Range Validity</label>
                                        <input type="text" id="range_date" name="range_date" class="form-control dt-input flatpickr-range" data-column="1" placeholder="YYYY-MM-DD to YYYY-MM-DD" />
                                        <small class="text-danger"></small>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="usage_limit">Limit Penggunaan</label>
                                        <input name="usage_limit" required type="number" class="form-control" id="usage_limit" placeholder="1xx" aria-label="1xx" />
                                        <small class="text-danger"></small>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="per_user_limit">Limit Penggunaan Per-Member</label>
                                        <input name="per_user_limit" required type="number" class="form-control" id="per_user_limit" placeholder="1xx" aria-label="1xx" />
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
                    <div class="modal modal-slide-in fade" id="modals-edit-slide-in">
                        <div class="modal-dialog sidebar-sm">
                            <form class="edit-new-record modal-content pt-0">
                                @csrf
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                                <div class="modal-header mb-1">
                                    <h5 class="modal-title" id="exampleModalLabel">Edit Data Lokasi</h5>
                                </div>
                                <div class="modal-body flex-grow-1" id="edit-voucher">
                                    <div class="form-group">
                                        <label class="form-label" for="code">Kode Voucher</label>
                                        <input name="id" required type="text" id="id"/>
                                        <input name="code" required type="text" class="form-control" id="edit-code" placeholder="kodevocher123" aria-label="kodevocher123" />
                                        <small class="text-danger"></small>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="discount_type">Jenis Diskon</label>
                                        <select class="form-control" id="edit_discount_type" name="discount_type" required>
                                            <option value="">- Pilih Jenis Diskon -</option>
                                            <option value="percentage">Percentage</option>
                                            <option value="fixed">Fixed</option>
                                        </select>
                                        <small class="text-danger"></small>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="discount_value">Nominal Diskon (Persen/Nominal)</label>
                                        <input name="discount_value" required type="number" class="form-control" id="edit_discount_value" placeholder="Input nominal atau presentase diskon" aria-label="discount_value" />
                                        <small class="text-danger"></small>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="min_spend">Min. Spend</label>
                                        <input name="min_spend" required type="number" class="form-control" id="edit_min_spend" placeholder="1xxxx" aria-label="1xxxx" />
                                        <small class="text-danger"></small>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="max_discount">Max. Discount (Opsional)</label>
                                        <input name="max_discount" type="number" class="form-control" id="edit_max_discount" placeholder="1xxxx" aria-label="1xxxx" />
                                        <small class="text-danger"></small>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="range_date">Discount Date Range Validity</label>
                                        <input type="text" id="edit_range_date" name="range_date" class="form-control dt-input flatpickr-range" data-column="1" placeholder="YYYY-MM-DD to YYYY-MM-DD" />
                                        <small class="text-danger"></small>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="usage_limit">Limit Penggunaan</label>
                                        <input name="usage_limit" required type="number" class="form-control" id="edit_usage_limit" placeholder="1xx" aria-label="1xx" />
                                        <small class="text-danger"></small>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="per_user_limit">Limit Penggunaan Per-Member</label>
                                        <input name="per_user_limit" required type="number" class="form-control" id="edit_per_user_limit" placeholder="1xx" aria-label="1xx" />
                                        <small class="text-danger"></small>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="plot_lokasi">Plot Lokasi</label>
                                        <select class="select2 form-control" id="edit_plot_lokasi" name="plot_lokasi[]" multiple required>
                                            @foreach (App\Models\Location::where('userId', Auth::user()->id)->orderBy('location_name', 'asc')->get() as $lokasi)
                                                <option value="{{ $lokasi->id }}">{{ $lokasi->location_name }}</option>
                                            @endforeach
                                        </select>
                                        <small class="text-danger"></small>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="edit_status">Status</label>
                                        <select id="edit_status" class="form-control" name="status">
                                            <option value="">- Pilih Status -</option>
                                            <option value="active">Aktif</option>
                                            <option value="inactive">Tidak Aktif</option>
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
