<x-dashboard-layout>
    <div class="app-content content ecommerce-application">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">Tickets</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('operator.dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('operator.transaction') }}">Transaction</a></li>
                                    <li class="breadcrumb-item"><a href="">Invoice</a></li>
                                    <li class="breadcrumb-item active">Ticket</li>
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
                @foreach ($ticket->tickets as $ticket)
                    <div class="list-view product-checkout checkout">
                        <div class="checkout-items">
                            <div class="card ecommerce-card">
                                <div class="item-img">
                                    <a href="#">
                                        <img src="{{ $ticket->packet->galleries->isNotEmpty() ? Storage::url($ticket->packet->galleries->first()->image) : asset('theme/assets/images/photo-gallery.png') }}" alt="img-placeholder" />
                                    </a>
                                </div>
                                <div class="card-body">
                                    <div class="item-name">
                                        <h6 class="mb-0">
                                            <a href="#" class="text-body">
                                                {{ $ticket->packet->packet_name }}
                                                @if ($ticket->redeemed == 0)
                                                    <span class="text-success"><div class="badge badge-light-warning">Unredeemed</div></span>
                                                @elseif($ticket->redeemed == 1)
                                                    <span class="text-success"><div class="badge badge-light-success">Redeemed</div></span>
                                                @endif
                                            </a>
                                        </h6>
                                        <span class="item-company">By <a href="javascript:void(0)" class="company-name">{{ $ticket->packet->location->location_name }}</a></span>
                                        <div class="item-rating">

                                        </div>
                                    </div>
                                    <div class="">
                                        <span class="quantity-title">Ticket Information:</span>
                                        <ul class="list-unstyled">
                                            <li>Nomor Tiket : <strong>{{ $ticket->ticketDetail->ticket_unique_id }}</strong></li>
                                            <li>Harga Tiket : <strong>@currency($ticket->packet->price)</strong></li>
                                        </ul>
                                        {{-- <div class="input-group quantity-counter-wrapper">
                                            <input type="text" class="quantity-counter" value="1" />
                                        </div> --}}
                                        <span class="delivery-date text-muted">Purcashed on, {{ \Carbon\Carbon::parse($ticket->transaction_timestamp)->format('l, d M Y') }}</span>
                                    </div>
                                    {{-- <span class="text-success">17% off 4 offers Available</span> --}}
                                </div>
                                <div class="item-options text-center">
                                    {{-- <div class="item-wrapper">
                                        <div class="item-cost">
                                            <h4 class="item-price">@currency($ticket->packet->price)</h4>
                                        </div>
                                    </div> --}}
                                    {{-- <button type="button" class="btn btn-light mt-1 remove-wishlist">
                                        <i data-feather="x" class="align-middle mr-25"></i>
                                        <span>Remove</span>
                                    </button> --}}
                                    @if (is_null($ticket->ticketDetail->owner_name))
                                        <button type="button" class="btn btn-primary"  data-toggle="modal" data-target="#{{$ticket->ticketDetail->ticket_unique_id}}">
                                            <i data-feather="edit" class="align-middle mr-25"></i>
                                            <span class="text-truncate">Tambahkan Informasi</span>
                                        </button>
                                    @else
                                        <button id="show-ticket-info"
                                            data-ticket_id="{{$ticket->ticketDetail->ticket_unique_id}}"
                                            data-owner_name="{{$ticket->ticketDetail->owner_name}}"
                                            data-id_number="{{$ticket->ticketDetail->id_number}}"
                                            data-owner_phone_number="{{$ticket->ticketDetail->owner_phone_number}}"
                                            data-owner_email_address="{{$ticket->ticketDetail->owner_email_address}}"
                                            data-owner_address="{{$ticket->ticketDetail->owner_address}}"
                                            type="button" class="btn btn-primary"
                                            data-toggle="modal"
                                            data-target="#{{$ticket->ticketDetail->ticket_unique_id}}">
                                            <i data-feather="eye" class="align-middle mr-25"></i>
                                            <span class="text-truncate">Lihat Informasi</span>
                                        </button>
                                    @endif
                                    @if (!is_null($ticket->ticketDetail->owner_email_address))
                                        <button type="button" class="btn btn-outline-danger mt-1">
                                            <i data-feather="mail" class="align-middle mr-25"></i>
                                            <span class="text-truncate">Kirim ke Email</span>
                                        </button>
                                    @endif
                                    @if (!is_null($ticket->ticketDetail->owner_phone_number))
                                        <button type="button" class="btn btn-outline-success mt-1">
                                            <i data-feather="phone" class="align-middle mr-25"></i>
                                            <span class="text-truncate">Kirim ke Whatsapp</span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if (is_null($ticket->ticketDetail->owner_name))
                            <div class="modal modal-slide-in fade" id="{{$ticket->ticketDetail->ticket_unique_id}}">
                                <div class="modal-dialog sidebar-sm">
                                    <form class="add-new-record modal-content pt-0" method="POST" action="{{ route('operator.transaction.invoice.ticket.info.insert') }}">
                                        @csrf
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                                        <div class="modal-header mb-1">
                                            <h5 class="modal-title" id="exampleModalLabel">Tambah Informasi Ke Tiket (Opsional)</h5>
                                        </div>
                                        <div class="modal-body flex-grow-1" id="ticket-info">
                                            <div class="form-group">
                                                <label class="form-label" for="name">Nama Pemilik Tiket</label>
                                                <input type="hidden" class="d-none" name="id" id="id" value="{{$ticket->id}}" required readonly>
                                                <input type="hidden" class="d-none" name="ticket_id" value="{{$ticket->ticketDetail->ticket_unique_id}}" id="ticket_id" required readonly>
                                                <input name="name" type="text" class="form-control" id="name" placeholder="Input nama lokasi" aria-label="Input nama lokasi" />
                                                <small class="text-danger"></small>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="id_number">ID Number (KTP)</label>
                                                <input name="id_number" type="text" class="form-control" id="id_number" placeholder="Masukkan nomor identitas" aria-label="Masukkan nomor identitas" />
                                                <small class="text-danger"></small>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="phone">Phone Number/Whatsapp</label>
                                                <input name="phone" type="number" class="form-control" id="phone" placeholder="08xxxxxxxxxxxx" aria-label="08xxxxxxxxxxxx" />
                                                <small class="text-danger"></small>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="email">Email</label>
                                                <input name="email" type="email" class="form-control" id="email" placeholder="johndoe@example.com" aria-label="johndoe@example.com" />
                                                <small class="text-danger"></small>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="address">Alamat</label>
                                                <textarea class="form-control" name="address" id="address" rows="3" placeholder="Masukkan alamat pemilik tiket"></textarea>
                                                <small class="text-danger"></small>
                                            </div>
                                            <button type="submit" class="btn btn-primary data-submit mr-1">Submit</button>
                                            <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">Reset</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="modal modal-slide-in fade" id="{{$ticket->ticketDetail->ticket_unique_id}}">
                                <div class="modal-dialog sidebar-sm">
                                    <form class="add-new-record modal-content pt-0">
                                        @csrf
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                                        <div class="modal-header mb-1">
                                            <h5 class="modal-title" id="exampleModalLabel">Informasi Tiket</h5>
                                        </div>
                                        <div class="modal-body flex-grow-1" id="ticket-info">
                                            <div class="form-group">
                                                <label class="form-label" for="ticket_id">Ticket ID</label>
                                                <input name="ticket_id" type="text" class="form-control" id="ticket_id" placeholder="Input ticket id" aria-label="Input ticket id" readonly />
                                                <small class="text-danger"></small>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="name">Nama Pemilik Tiket</label>
                                                <input name="name" type="text" class="form-control" id="name" placeholder="Input nama lokasi" aria-label="Input nama lokasi" readonly />
                                                <small class="text-danger"></small>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="id_number">ID Number (KTP)</label>
                                                <input name="id_number" type="text" class="form-control" id="id_number" placeholder="Masukkan nomor identitas" readonly aria-label="Masukkan nomor identitas" />
                                                <small class="text-danger"></small>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="phone">Phone Number/Whatsapp</label>
                                                <input name="phone" type="number" class="form-control" id="phone" placeholder="08xxxxxxxxxxxx" readonly aria-label="08xxxxxxxxxxxx" />
                                                <small class="text-danger"></small>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="email">Email</label>
                                                <input name="email" type="email" class="form-control" id="email" placeholder="johndoe@example.com" readonly aria-label="johndoe@example.com" />
                                                <small class="text-danger"></small>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="address">Alamat</label>
                                                <textarea class="form-control" name="address" id="address" rows="3" placeholder="Masukkan alamat pemilik tiket" readonly></textarea>
                                                <small class="text-danger"></small>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-dashboard-layout>
