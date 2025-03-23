<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="description" content="Vuexy admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Vuexy admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title>User Dashboard Area - GoTick</title>
    <link rel="apple-touch-icon" href="{{ asset('theme/app-assets/images/ico/apple-icon-120.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('theme/app-assets/images/ico/favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/vendors/css/vendors.min.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/vendors/css/forms/wizard/bs-stepper.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/vendors/css/forms/spinner/jquery.bootstrap-touchspin.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/vendors/css/charts/apexcharts.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/vendors/css/extensions/toastr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/css/bootstrap-extended.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/css/colors.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/css/components.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/css/themes/dark-layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/css/themes/bordered-layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/css/themes/semi-dark-layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/css/pages/dashboard-ecommerce.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/css/plugins/charts/chart-apex.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/css/plugins/extensions/ext-component-toastr.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/vendors/css/animate/animate.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/vendors/css/tables/datatable/rowGroup.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/vendors/css/editors/quill/katex.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/vendors/css/editors/quill/monokai-sublime.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/vendors/css/editors/quill/quill.snow.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/vendors/css/editors/quill/quill.bubble.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/vendors/css/forms/select/select2.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/css/plugins/forms/form-validation.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/css/plugins/forms/pickers/form-pickadate.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/css/plugins/forms/form-quill-editor.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/vendors/css/file-uploaders/dropzone.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/css/plugins/forms/form-file-uploader.css') }}">

    @if (Auth::check() && Auth::user()->role == "playground_operator")
        @if(Route::is('operator.transaction') || Route::is('operator.transaction.checkout') || Route::is('operator.transaction.invoice.ticket') || Route::is('operator.transaction.invoice.checkout.pay'))
            <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/css/plugins/extensions/ext-component-sliders.css') }}">
            <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/css/pages/app-ecommerce.css') }}">
            <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/css/plugins/forms/form-wizard.css') }}">
            <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/css/plugins/forms/form-number-input.css') }}">
        @endif
    @endif

    <link rel="stylesheet" type="text/css" href="{{ asset('theme/assets/css/style.css') }}">
</head>
<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="">
    @include('body.nav-header')
    @include('body.menu')
    {{ $slot }}
    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>
    @include('body.footer')
    <button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
    @vite('resources/js/app.js')
    <script src="{{ asset('theme/app-assets/vendors/js/vendors.min.js') }}"></script>
    <script src="{{ asset('theme/app-assets/vendors/js/charts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('theme/app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
    <script src="{{ asset('theme/app-assets/js/core/app-menu.js') }}"></script>
    <script src="{{ asset('theme/app-assets/js/core/theme.js') }}"></script>
    <script src="{{ asset('theme/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('theme/app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('theme/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('theme/app-assets/vendors/js/tables/datatable/responsive.bootstrap4.js') }}"></script>
    <script src="{{ asset('theme/app-assets/vendors/js/tables/datatable/datatables.checkboxes.min.js') }}"></script>
    <script src="{{ asset('theme/app-assets/vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>
    <script src="{{ asset('theme/app-assets/vendors/js/tables/datatable/jszip.min.js') }}"></script>
    <script src="{{ asset('theme/app-assets/vendors/js/tables/datatable/pdfmake.min.js') }}"></script>
    <script src="{{ asset('theme/app-assets/vendors/js/tables/datatable/vfs_fonts.js') }}"></script>
    <script src="{{ asset('theme/app-assets/vendors/js/tables/datatable/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('theme/app-assets/vendors/js/tables/datatable/buttons.print.min.js') }}"></script>
    <script src="{{ asset('theme/app-assets/vendors/js/tables/datatable/dataTables.rowGroup.min.js') }}"></script>
    <script src="{{ asset('theme/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('theme/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('theme/app-assets/js/scripts/components/components-dropdowns.js') }}"></script>

    <script src="{{ asset('theme/app-assets/vendors/js/editors/quill/katex.min.js') }}"></script>
    <script src="{{ asset('theme/app-assets/vendors/js/editors/quill/highlight.min.js') }}"></script>
    <script src="{{ asset('theme/app-assets/vendors/js/editors/quill/quill.min.js') }}"></script>

    <script src="{{ asset('theme/app-assets/vendors/js/extensions/dropzone.min.js') }}"></script>

    <script src="{{ asset('theme/app-assets/js/scripts/forms/form-select2.js') }}"></script>

    <script src="{{ asset('theme/app-assets/js/scripts/components/components-modals.js') }}"></script>

    <script src="{{ asset('theme/app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>

    <script src="{{ asset('theme/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('theme/app-assets/vendors/js/extensions/polyfill.min.js') }}"></script>
    <script src="{{ asset('theme/app-assets/js/scripts/extensions/ext-component-sweet-alerts.js') }}"></script>

    @if (Auth::check() && Auth::user()->role == "playground_owner")
        @if(Route::is('tenant.dashboard'))
            <script src="{{ asset('theme/app-assets/js/scripts/pages/dashboard-ecommerce.js') }}"></script>
        @elseif(Route::is('tenant.dashboard.location'))
            <script src="{{ asset('theme/app-assets/js/scripts/tables/table-location-datatables-basic.js') }}"></script>
        @elseif(Route::is('tenant.dashboard.employee'))
            <script src="{{ asset('theme/app-assets/js/scripts/tables/table-user-datatables-basic.js') }}"></script>
        @elseif(Route::is('tenant.dashboard.packet'))
            <script src="{{ asset('theme/app-assets/js/scripts/tables/table-packet-datatables-basic-tenant.js') }}"></script>
        @elseif(Route::is('tenant.dashboard.packet.add'))
            <script src="{{ asset('theme/app-assets/js/scripts/forms/form-validation.js') }}"></script>
            <script src="{{ asset('theme/app-assets/js/scripts/forms/form-quill-editor.js') }}"></script>
            <script src="{{ asset('theme/app-assets/js/scripts/forms/form-file-uploader.js') }}"></script>
        @elseif(Route::is('tenant.dashboard.packet.edit'))
            <script src="{{ asset('theme/app-assets/js/scripts/forms/form-validation.js') }}"></script>
            <script src="{{ asset('theme/app-assets/js/scripts/forms/form-quill-edit-data.js') }}"></script>
            <script src="{{ asset('theme/app-assets/js/scripts/forms/form-file-uploader-edit.js') }}"></script>
        @elseif(Route::is('tenant.dashboard.voucher'))
            <script src="{{ asset('theme/app-assets/js/scripts/tables/table-voucher-datatables-basic.js') }}"></script>
        @endif
    @endif
    @if (Auth::check() && Auth::user()->role == "playground_operator")
        @if(Route::is('operator.transaction') || Route::is('operator.transaction.checkout') || Route::is('operator.transaction.invoice.ticket') || Route::is('operator.transaction.invoice.checkout.pay'))
            <script src="{{ asset('theme/app-assets/vendors/js/extensions/wNumb.min.js') }}"></script>
            <script src="{{ asset('theme/app-assets/vendors/js/extensions/nouislider.min.js') }}"></script>
            <script src="{{ asset('theme/app-assets/js/scripts/pages/app-ecommerce.js') }}"></script>
            <script src="{{ asset('theme/app-assets/vendors/js/forms/wizard/bs-stepper.min.js') }}"></script>
            <script src="{{ asset('theme/app-assets/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js') }}"></script>
            <script src="{{ asset('theme/app-assets/js/scripts/pages/app-ecommerce-checkout.js') }}"></script>
        @elseif(Route::is('operator.dashboard.packet'))
            <script src="{{ asset('theme/app-assets/js/scripts/tables/table-packet-datatables-basic-operator.js') }}"></script>
        @endif
    @endif
    <script>
        $(document).ready(function () {
            @if(session('success'))
                toastr.success("{{ session('success') }}", "✅ Success", {
                    closeButton: true,
                    tapToDismiss: false,
                    progressBar: true,
                    positionClass: "toast-top-right",
                    timeOut: 3000
                });
            @endif

            @if(session('error'))
                toastr.error("{{ session('error') }}", "❌ Error", {
                    closeButton: true,
                    tapToDismiss: false,
                    progressBar: true,
                    positionClass: "toast-top-right",
                    timeOut: 3000
                });
            @endif
        });
    </script>
    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })
    </script>
    <script>
        // Wait for the DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('download-qr').addEventListener('click', function () {
                // Get the SVG QR code element
                var qrCodeElement = document.getElementById('qr-code-container').querySelector('svg');

                if (qrCodeElement) {
                    // Create a new <a> element to trigger the download
                    var link = document.createElement('a');

                    // Convert the SVG to a PNG using the Canvas API
                    var canvas = document.createElement('canvas');
                    var ctx = canvas.getContext('2d');

                    // Get the original SVG data
                    var svgData = qrCodeElement.outerHTML;

                    // Set the scaling factor (e.g., 2x the display size for download)
                    var scaleFactor = 2; // Increase the download size (200px * 2 = 400px)

                    // Scale the canvas to the larger size for download
                    var svgBlob = new Blob([svgData], {type: 'image/svg+xml'});
                    var reader = new FileReader();

                    reader.onload = function () {
                        var img = new Image();
                        img.onload = function () {
                            // Set the canvas size based on the scaling factor
                            canvas.width = img.width * scaleFactor;  // Scale width
                            canvas.height = img.height * scaleFactor;  // Scale height

                            // Draw the image on the canvas
                            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

                            // Convert canvas to PNG image data URL
                            var dataUrl = canvas.toDataURL('image/png');

                            // Set up the download link
                            link.href = dataUrl;
                            link.download = 'qr-code.png';  // Name for the downloaded file
                            link.click();  // Trigger the download
                        };
                        img.src = reader.result;  // Convert the SVG to a data URL
                    };

                    reader.readAsDataURL(svgBlob);  // Convert SVG to a data URL
                } else {
                    console.error("QR code SVG element not found.");
                }
            });
        });

        window.Laravel = {
            role: @json(auth()->check() ? auth()->user()->role : null)
        };

        document.getElementById("check-payment").addEventListener("click", function() {
            location.reload();
        });
    </script>
</body>
</html>
