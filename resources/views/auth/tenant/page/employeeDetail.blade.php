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
                                    <li class="breadcrumb-item"><a href="{{ route('tenant.dashboard.employee') }}">Employee</a></li>
                                    <li class="breadcrumb-item active">{{ $user->name }}</li>
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
                <!-- users edit start -->
                <section class="app-user-edit">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-pills" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center active" id="account-tab" data-toggle="tab" href="#account" aria-controls="account" role="tab" aria-selected="true">
                                        <i data-feather="user"></i><span class="d-none d-sm-block">Account</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center" id="information-tab" data-toggle="tab" href="#information" aria-controls="information" role="tab" aria-selected="false">
                                        <i data-feather="info"></i><span class="d-none d-sm-block">Information</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center" id="transaction-tab" data-toggle="tab" href="#transaction" aria-controls="transaction" role="tab" aria-selected="false">
                                        <i data-feather='book-open'></i><span class="d-none d-sm-block">Transaksi</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <!-- Account Tab starts -->
                                <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">
                                    <!-- users edit media object start -->
                                    <div class="media mb-2">
                                        <img src="{{ !empty($user->userDetail->photo) ? Storage::url('images\users\profile_pict'.$user->userDetail->photo) : asset('theme/assets/images/user.png') }}" alt="users avatar" class="user-avatar users-avatar-shadow rounded mr-2 my-25 cursor-pointer" height="90" width="90" />
                                        <div class="media-body mt-50">
                                            <h4>{{ $user->name }}</h4>
                                            <div class="col-12 d-flex mt-1 px-0">
                                                <label class="btn btn-primary mr-75 mb-0" for="change-picture">
                                                    <span class="d-none d-sm-block">
                                                        @if ($user->role == "playground_supervisor")
                                                            Supervisor
                                                        @elseif ($user->role == "playground_operator")
                                                            Operator
                                                        @endif
                                                    </span>
                                                </span>
                                                </label>
                                                <button class="btn
                                                    @if ($user->status == 1)
                                                        btn-outline-success
                                                    @else
                                                        btn-outline-danger
                                                    @endif
                                                d-none d-sm-block">
                                                    @if ($user->status == 1)
                                                        Aktif
                                                    @else
                                                        Tidak Aktif
                                                    @endif
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- users edit media object ends -->
                                    <!-- users edit account form start -->
                                    <form class="form-validate" action="{{ route('tenant.dashboard.employee.user.update') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="full_name">Nama Lengkap</label>
                                                    <input type="hidden" class="d-none" name="id" id="id" value="{{ $user->id }}" required>
                                                    <input type="text" class="form-control" placeholder="John Doe" value="{{ $user->name }}" name="full_name" id="full_name" required />
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input type="email" class="form-control" placeholder="johndoe@example.com" value="{{ $user->email }}" name="email" id="email" required />
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="phone">No Telp./WA</label>
                                                    <input type="text" class="form-control" placeholder="08XXXXXXXXXXX" value="{{ $user->phone }}" name="phone" id="phone" required />
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="jabatan">Jabatan</label>
                                                    <select class="form-control" id="jabatan" name="jabatan" required>
                                                        <option value="">- Pilih Jabatan  -</option>
                                                        <option value="playground_supervisor" @if($user->role == "playground_supervisor") selected @endif>Supervisor</option>
                                                        <option value="playground_operator" @if($user->role == "playground_operator") selected @endif>Operator</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="status">Status</label>
                                                    <select class="form-control" id="status" name="status" required>
                                                        <option value="">- Pilih status karyawan -</option>
                                                        <option value="1" @if($user->status == 1) selected @endif>Aktif</option>
                                                        <option value="0" @if($user->status == 0) selected @endif>Tidak Aktif</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="plot_lokasi">Plot Lokasi</label>
                                                    <select class="select2 form-control" id="plot_lokasi" name="plot_lokasi[]" multiple required>
                                                        @foreach (App\Models\Location::where('userId', Auth::user()->id)->orderBy('location_name', 'asc')->get() as $lokasi)
                                                            <option value="{{ $lokasi->id }}"
                                                                @if(in_array($lokasi->id, $selectedLocations)) selected @endif>
                                                                {{ $lokasi->location_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <small class="text-danger"></small>
                                                </div>
                                            </div>
                                            {{-- <div class="col-12">
                                                <div class="table-responsive border rounded mt-1">
                                                    <h6 class="py-1 mx-1 mb-0 font-medium-2">
                                                        <i data-feather="lock" class="font-medium-3 mr-25"></i>
                                                        <span class="align-middle">Permission</span>
                                                    </h6>
                                                    <table class="table table-striped table-borderless">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th>Module</th>
                                                                <th>Read</th>
                                                                <th>Write</th>
                                                                <th>Create</th>
                                                                <th>Delete</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>Admin</td>
                                                                <td>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox" class="custom-control-input" id="admin-read" checked />
                                                                        <label class="custom-control-label" for="admin-read"></label>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox" class="custom-control-input" id="admin-write" />
                                                                        <label class="custom-control-label" for="admin-write"></label>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox" class="custom-control-input" id="admin-create" />
                                                                        <label class="custom-control-label" for="admin-create"></label>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox" class="custom-control-input" id="admin-delete" />
                                                                        <label class="custom-control-label" for="admin-delete"></label>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Staff</td>
                                                                <td>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox" class="custom-control-input" id="staff-read" />
                                                                        <label class="custom-control-label" for="staff-read"></label>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox" class="custom-control-input" id="staff-write" checked />
                                                                        <label class="custom-control-label" for="staff-write"></label>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox" class="custom-control-input" id="staff-create" />
                                                                        <label class="custom-control-label" for="staff-create"></label>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox" class="custom-control-input" id="staff-delete" />
                                                                        <label class="custom-control-label" for="staff-delete"></label>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Author</td>
                                                                <td>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox" class="custom-control-input" id="author-read" checked />
                                                                        <label class="custom-control-label" for="author-read"></label>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox" class="custom-control-input" id="author-write" />
                                                                        <label class="custom-control-label" for="author-write"></label>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox" class="custom-control-input" id="author-create" checked />
                                                                        <label class="custom-control-label" for="author-create"></label>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox" class="custom-control-input" id="author-delete" />
                                                                        <label class="custom-control-label" for="author-delete"></label>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Contributor</td>
                                                                <td>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox" class="custom-control-input" id="contributor-read" />
                                                                        <label class="custom-control-label" for="contributor-read"></label>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox" class="custom-control-input" id="contributor-write" />
                                                                        <label class="custom-control-label" for="contributor-write"></label>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox" class="custom-control-input" id="contributor-create" />
                                                                        <label class="custom-control-label" for="contributor-create"></label>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox" class="custom-control-input" id="contributor-delete" />
                                                                        <label class="custom-control-label" for="contributor-delete"></label>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>User</td>
                                                                <td>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox" class="custom-control-input" id="user-read" />
                                                                        <label class="custom-control-label" for="user-read"></label>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox" class="custom-control-input" id="user-create" />
                                                                        <label class="custom-control-label" for="user-create"></label>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox" class="custom-control-input" id="user-write" />
                                                                        <label class="custom-control-label" for="user-write"></label>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox" class="custom-control-input" id="user-delete" checked />
                                                                        <label class="custom-control-label" for="user-delete"></label>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div> --}}
                                            <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                                <button type="submit" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1"><i data-feather='save'></i>&nbsp;Save Changes</button>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- users edit account form ends -->
                                </div>
                                <!-- Account Tab ends -->
                                <!-- Information Tab starts -->
                                <div class="tab-pane" id="information" aria-labelledby="information-tab" role="tabpanel">
                                    <!-- users edit Info form start -->
                                    <form class="form-validate" method="POST" action="{{ route('tenant.dashboard.employee.userDetail.update') }}">
                                        @csrf
                                        <div class="row mt-1">
                                            <div class="col-12">
                                                <h4 class="mb-1">
                                                    <i data-feather="user" class="font-medium-4 mr-25"></i>
                                                    <span class="align-middle">Personal Information</span>
                                                </h4>
                                            </div>
                                            <div class="col-lg-3 col-md-3">
                                                <div class="form-group">
                                                    <label for="birth">Birth date</label>
                                                    <input type="hidden" class="d-none" id="id" name="id" value="{{ $user->id }}">
                                                    <input id="birth" type="date" class="form-control birthdate-picker" name="birth" value="{{ $user->userDetail->tanggal_lahir }}" placeholder="YYYY-MM-DD" required />
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3">
                                                <div class="form-group">
                                                    <label for="tmpt_lahir">Tempat Lahir</label>
                                                    <input id="tmpt_lahir" type="text" class="form-control" value="{{ $user->userDetail->tempat_lahir }}" name="tmpt_lahir" required placeholder="Input tempat lahir" />
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3">
                                                <div class="form-group">
                                                    <label for="jenis_kelamin">Jenis Kelamin</label>
                                                    <select id="jenis_kelamin" name="jenis_kelamin" required class="form-control">
                                                        <option value="">- Pilih Jenis Kelamin -</option>
                                                        <option value="Laki-laki" @if($user->userDetail->jenis_kelamin == "Laki-laki") selected @endif>Laki-laki</option>
                                                        <option value="Perempuan" @if($user->userDetail->jenis_kelamin == "Perempuan") selected @endif>Perempuan</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3">
                                                <div class="form-group">
                                                    <label for="ktp">No. KTP</label>
                                                    <input id="ktp" type="text" class="form-control" value="{{ $user->userDetail->no_ktp }}" name="ktp" required placeholder="Input nomor KTP" />
                                                </div>
                                            </div>

                                            {{-- <div class="col-lg-4 col-md-6">
                                                <div class="form-group">
                                                    <label class="d-block mb-1">Contact Options</label>
                                                    <div class="custom-control custom-checkbox custom-control-inline">
                                                        <input type="checkbox" class="custom-control-input" id="email-cb" checked />
                                                        <label class="custom-control-label" for="email-cb">Email</label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox custom-control-inline">
                                                        <input type="checkbox" class="custom-control-input" id="message" checked />
                                                        <label class="custom-control-label" for="message">Message</label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox custom-control-inline">
                                                        <input type="checkbox" class="custom-control-input" id="phone" />
                                                        <label class="custom-control-label" for="phone">Phone</label>
                                                    </div>
                                                </div>
                                            </div> --}}
                                            <div class="col-12">
                                                <h4 class="mb-1 mt-2">
                                                    <i data-feather="map-pin" class="font-medium-4 mr-25"></i>
                                                    <span class="align-middle">Address</span>
                                                </h4>
                                            </div>
                                            <div class="col-lg-12 col-md-12">
                                                <div class="form-group">
                                                    <label for="address">Alamat Lengkap</label>
                                                    <textarea class="form-control" id="address" rows="3" placeholder="Input alamat lengkap" name="address" required>{{ $user->userDetail->alamat }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                                <button type="submit" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1"><i data-feather='save'></i>&nbsp;Save Changes</button>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- users edit Info form ends -->
                                </div>
                                <!-- Information Tab ends -->
                                <!-- Social Tab starts -->
                                <div class="tab-pane" id="transaction" aria-labelledby="transaction-tab" role="tabpanel">
                                    <!-- users edit social form start -->
                                    <form class="form-validate">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6 form-group">
                                                <label for="twitter-input">Twitter</label>
                                                <div class="input-group input-group-merge">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon3">
                                                        <i data-feather="twitter" class="font-medium-2"></i>
                                                        </span>
                                                    </div>
                                                    <input id="twitter-input" type="text" class="form-control" value="https://www.twitter.com/adoptionism744" placeholder="https://www.twitter.com/" aria-describedby="basic-addon3" />
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6 form-group">
                                                <label for="facebook-input">Facebook</label>
                                                <div class="input-group input-group-merge">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon4">
                                                        <i data-feather="facebook" class="font-medium-2"></i>
                                                        </span>
                                                    </div>
                                                    <input id="facebook-input" type="text" class="form-control" value="https://www.facebook.com/adoptionism664" placeholder="https://www.facebook.com/" aria-describedby="basic-addon4" />
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6 form-group">
                                                <label for="instagram-input">Instagram</label>
                                                <div class="input-group input-group-merge">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon5">
                                                        <i data-feather="instagram" class="font-medium-2"></i>
                                                        </span>
                                                    </div>
                                                    <input id="instagram-input" type="text" class="form-control" value="https://www.instagram.com/adopt-ionism744" placeholder="https://www.instagram.com/" aria-describedby="basic-addon5" />
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6 form-group">
                                                <label for="github-input">Github</label>
                                                <div class="input-group input-group-merge">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon9">
                                                        <i data-feather="github" class="font-medium-2"></i>
                                                        </span>
                                                    </div>
                                                    <input id="github-input" type="text" class="form-control" value="https://www.github.com/madop818" placeholder="https://www.github.com/" aria-describedby="basic-addon9" />
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6 form-group">
                                                <label for="codepen-input">Codepen</label>
                                                <div class="input-group input-group-merge">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon12">
                                                        <i data-feather="codepen" class="font-medium-2"></i>
                                                        </span>
                                                    </div>
                                                    <input id="codepen-input" type="text" class="form-control" value="https://www.codepen.com/adoptism243" placeholder="https://www.codepen.com/" aria-describedby="basic-addon12" />
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6 form-group">
                                                <label for="slack-input">Slack</label>
                                                <div class="input-group input-group-merge">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon11">
                                                        <i data-feather="slack" class="font-medium-2"></i>
                                                        </span>
                                                    </div>
                                                    <input id="slack-input" type="text" class="form-control" value="@adoptionism744" placeholder="https://www.slack.com/" aria-describedby="basic-addon11" />
                                                </div>
                                            </div>
                                            <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                                <button type="submit" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1">Save Changes</button>
                                                <button type="reset" class="btn btn-outline-secondary">Reset</button>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- users edit social form ends -->
                                </div>
                                <!-- Social Tab ends -->
                            </div>
                        </div>
                    </div>
                </section>
                <!-- users edit ends -->
            </div>
        </div>
    </div>
</x-dashboard-layout>
