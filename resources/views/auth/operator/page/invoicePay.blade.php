<x-dashboard-layout>
    <div class="app-content content ecommerce-application">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">Product Payment</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('operator.dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('operator.transaction') }}">Transaction</a></li>
                                    <li class="breadcrumb-item active">Payment Process</li>
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
                <div class="checkout-tab-steps">
                    <div class="bs-stepper-content">
                        {{-- <div id="step-address" class="content"> --}}
                            <form id="checkout-address" class="list-view product-checkout">
                                <!-- Checkout Customer Address Left starts -->
                                <div class="card">
                                    <div class="card-header flex-column align-items-start">
                                        <h4 class="card-title">Payment Information</h4>
                                        <p class="card-text text-muted mt-25">Silakan ikuti langkah-langkah berikut untuk menyelesaikan proses pembayaran.</p>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                @if ($invoice->payment_method === "Qris")
                                                    <div class="row justify-content-center">
                                                        <div class="col-12 text-center">
                                                            <div class="form-group mb-1">
                                                                <div class="qr-container" id="qr-code-container">
                                                                    {!! QrCode::size(200)->generate($invoice->payment_reference) !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row justify-content-center">
                                                        <div class="col-12 text-center">
                                                            <div class="form-group mb-2">
                                                                <button type="button" class="btn btn-primary download-btn" id="download-qr">Download QR Code</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @elseif($invoice->payment_method === "VA_NOBU")
                                                @endif
                                            </div>
                                            <div class="col-md-1">
                                                <hr class="vertical-hr">
                                            </div>
                                            <div class="col-md-7">
                                                @if ($invoice->payment_method === "Qris")
                                                    <ol>
                                                        <li>Unduh QR Code dari invoice (bisa di-screenshot dan dipotong).</li>
                                                        <li>Buka aplikasi dompet digital yang mendukung QRIS (GoPay, OVO, Dana, dll.).</li>
                                                        <li>Unggah QR Code yang telah diunduh.</li>
                                                        <li>Periksa detail transaksi dan pastikan sudah sesuai.</li>
                                                        <li>Lakukan pembayaran dan simpan bukti transaksi.</li>
                                                    </ol>
                                                @endif
                                            </div>
                                        </div>
                                        <div id="operator-payment-update">
                                            <payment-update :invoice-id="{{ $invoice->id }}"></payment-update>
                                        </div>
                                    </div>
                                </div>
                                <!-- Checkout Customer Address Left ends -->

                                <!-- Checkout Customer Address Right starts -->
                                <div class="checkout-options">
                                    <div class="card">
                                        <div class="card-body">
                                            {{-- <label class="section-label mb-1">Options</label> --}}
                                            {{-- <div class="coupons input-group input-group-merge">
                                                <input type="text" class="form-control" placeholder="Coupons" aria-label="Coupons" aria-describedby="input-coupons" />
                                                <div class="input-group-append">
                                                    <span class="input-group-text text-primary" id="input-coupons">Apply</span>
                                                </div>
                                            </div>
                                            <hr /> --}}
                                            <div class="price-details">
                                                <h6 class="price-title">Price Details</h6>
                                                <ul class="list-unstyled">
                                                    <li class="price-detail">
                                                        <div class="detail-title">Sub Total</div>
                                                        <div class="detail-amt">@currency($invoice->price)</div>
                                                    </li>
                                                    <li class="price-detail">
                                                        <div class="detail-title">Diskon</div>
                                                        <div class="detail-amt text-success"><strong>-</strong></div>
                                                    </li>
                                                    <li class="price-detail">
                                                        <div class="detail-title">Nominal Diskon</div>
                                                        <div class="detail-amt discount-amt text-success">@currency($invoice->discount_value)</div>
                                                    </li>
                                                    <li class="price-detail">
                                                        <div class="detail-title">Pajak PPN</div>
                                                        <a href="javascript:void(0)" class="detail-amt text-primary"><strong>{{ $invoice->tax }}%</strong></a>
                                                    </li>
                                                    <li class="price-detail">
                                                        <div class="detail-title">Nominal Pajak</div>
                                                        <div class="detail-amt discount-amt">@currency($invoice->tax_value)</div>
                                                    </li>
                                                </ul>
                                                <hr />
                                                <ul class="list-unstyled">
                                                    <li class="price-detail">
                                                        <div class="detail-title detail-total">Total</div>
                                                        <div class="detail-amt font-weight-bolder">@currency($invoice->total_payment_amount)</div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Checkout Place Order Right ends -->
                                </div>
                                <!-- Checkout Customer Address Right ends -->
                            </form>
                        {{-- </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
