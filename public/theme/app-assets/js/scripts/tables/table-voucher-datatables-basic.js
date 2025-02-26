$(document).ready(function () {
    let table_voucher_list = $('#voucher-table').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        searching: false,
        ajax: {
            url: "/tenant/voucher-data",
            data: function (d) {
                d.discount_type = $('#discount_type').val();
                d.date_range = $('#date_range').val();
                d.status = $('#status').val();
                d.lokasi = $('#lokasi').val();
            },
            error: function (xhr, error, thrown) {
                console.log("AJAX Error: " + error);
                console.log("Thrown Error: " + thrown);
            }
        },
        columns: [
            { data: 'code', name: 'voucher_code' },
            { data: 'discount_type', name: 'discount_type' },
            { data: 'discount_value', name: 'discount_value' },
            { data: 'min_spend', name: 'min_spend' },
            { data: 'max_discount', name: 'max_discount' },
            { data: 'start_date', name: 'start_date' },
            { data: 'end_date', name: 'end_date' },
            { data: 'usage_limit', name: 'usage_limit' },
            { data: 'per_user_limit', name: 'per_user_limit' },
            { data: 'location', name: 'location' },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        columnDefs: [
            { width: "100px", targets: 0 },
            { width: "200px", targets: 9 },
        ],
        order: [[2, 'desc']],
        dom: '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-right"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        displayLength: 7,
        lengthMenu: [7, 10, 25, 50, 75, 100],
        buttons: [
            {
                extend: 'collection',
                className: 'btn btn-outline-secondary dropdown-toggle mr-2',
                text: feather.icons['share'].toSvg({ class: 'font-small-4 mr-50' }) + 'Export',
                buttons: [
                    {
                        extend: 'print',
                        text: feather.icons['printer'].toSvg({ class: 'font-small-4 mr-50' }) + 'Print',
                        className: 'dropdown-item',
                        exportOptions: { columns: [0, 1, 2, 3] }
                    },
                    {
                        extend: 'csv',
                        text: feather.icons['file-text'].toSvg({ class: 'font-small-4 mr-50' }) + 'Csv',
                        className: 'dropdown-item',
                        exportOptions: { columns: [0, 1, 2, 3] }
                    },
                    {
                        extend: 'excel',
                        text: feather.icons['file'].toSvg({ class: 'font-small-4 mr-50' }) + 'Excel',
                        className: 'dropdown-item',
                        exportOptions: { columns: [0, 1, 2, 3] }
                    },
                    {
                        extend: 'pdf',
                        text: feather.icons['clipboard'].toSvg({ class: 'font-small-4 mr-50' }) + 'Pdf',
                        className: 'dropdown-item',
                        exportOptions: { columns: [0, 1, 2, 3] }
                    },
                    {
                        extend: 'copy',
                        text: feather.icons['copy'].toSvg({ class: 'font-small-4 mr-50' }) + 'Copy',
                        className: 'dropdown-item',
                        exportOptions: { columns: [0, 1, 2, 3] }
                    }
                ],
                init: function (api, node, config) {
                    $(node).removeClass('btn-secondary');
                    $(node).parent().removeClass('btn-group');
                    setTimeout(function () {
                        $(node).closest('.dt-buttons').removeClass('btn-group').addClass('d-inline-flex');
                    }, 50);
                }
            },
            {
                text: feather.icons['plus'].toSvg({ class: 'mr-50 font-small-4' }) + 'Add',
                className: 'create-new btn btn-primary',
                attr: {
                    'data-toggle': 'modal',
                    'data-target': '#modals-slide-in'
                },
                init: function (api, node, config) {
                    $(node).removeClass('btn-secondary');
                }
            }
        ],
        language: {
            paginate: {
                previous: '&nbsp;',
                next: '&nbsp;'
            }
        }
    });

    $('div.head-label').html('<h6 class="mb-0">Voucher Data List</h6>');
    $('.dt_adv_search input, .dt_adv_search select').on('change keyup', function () {
        table_voucher_list.draw();
    });

    $('#modals-slide-in form').on('submit', function (e) {
        e.preventDefault();
        $('.form-group').removeClass('has-error');
        $('.form-group .text-danger').remove();

        var formData = new FormData(this);

        var plotLokasiValues = $('#plot_lokasi').val();
        if (plotLokasiValues) {
            plotLokasiValues.forEach(value => {
                formData.append('plot_lokasi[]', value);
            });
        }

        $.ajax({
            url: '/tenant/voucher/insert',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    $('#modals-slide-in').modal('hide');

                    toastr.success(response.message, "✅ Success", {
                        closeButton: true,
                        tapToDismiss: false,
                        progressBar: true,
                        positionClass: "toast-top-right",
                        timeOut: 1000
                    });

                    setTimeout(function () {
                        window.location.href = "/tenant/voucher";
                    }, 2000);
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessage = "Please correct the following errors:<br>";
                    $.each(errors, function (field, messages) {
                        errorMessage += `• ${messages[0]}<br>`;
                    });

                    toastr.error(errorMessage, "❌ Validation Error", {
                        closeButton: true,
                        tapToDismiss: false,
                        progressBar: true,
                        positionClass: "toast-top-right",
                        timeOut: 5000
                    });

                } else {
                    let errorMessage = xhr.responseJSON?.message || "Something went wrong. Please try again.";
                    toastr.error(errorMessage, "❌ Error", {
                        closeButton: true,
                        tapToDismiss: false,
                        progressBar: true,
                        positionClass: "toast-top-right",
                        timeOut: 3000
                    });
                }
            }
        });
    });

    $('#modals-edit-slide-in form').on('submit', function (e) {
        e.preventDefault();
        $('.form-group').removeClass('has-error');
        $('.form-group .text-danger').remove();
        var formData = new FormData(this);

        var jenisLokasiValues = $('#edit_plot_lokasi').val();
        if (jenisLokasiValues) {
            jenisLokasiValues.forEach(value => {
                formData.append('plot_lokasi[]', value);
            });
        }

        $.ajax({
            url: '/tenant/voucher/update',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    $('#modals-edit-slide-in').modal('hide');
                    alert('User data updated successfully!');
                    window.location.href = '/tenant/voucher';
                }
            },
            error: function (xhr) {
                var errors = xhr.responseJSON.errors;
                $.each(errors, function (field, messages) {
                    var inputField = $('#edit-' + field);
                    var errorMessage = messages[0];
                    inputField.closest('.form-group').addClass('has-error');
                    inputField.after('<div class="text-danger">' + errorMessage + '</div>');
                });
            }
        });
    });

    $(document).on("click", "#edit-voucher-data", function() {
        var id = $(this).data('id');
        var code = $(this).data('code');
        var discount_type = $(this).data('discount_type');
        var discount_value = $(this).data('discount_value');
        var min_spend = $(this).data('min_spend');
        var max_discount = $(this).data('max_discount');
        var dateRange = $(this).data('daterange');
        var usage_limit = $(this).data('usage_limit');
        var per_user_limit = $(this).data('per_user_limit');
        var lokasi = $(this).data('lokasi');
        var status = $(this).data('status');

        $("#edit-voucher #id").val(id);
        $("#edit-voucher #edit-code").val(code);
        $("#edit-voucher #edit_discount_type").val(discount_type);
        $("#edit-voucher #edit_discount_value").val(discount_value);
        $("#edit-voucher #edit_min_spend").val(min_spend);
        $("#edit-voucher #edit_max_discount").val(max_discount);
        $("#edit-voucher #edit_usage_limit").val(usage_limit);
        $("#edit-voucher #edit_per_user_limit").val(per_user_limit);
        $("#edit-voucher #edit_status").val(status);
        $("#edit_plot_lokasi").val(lokasi).trigger("change");
    
        var datePicker = $("#edit_range_date").get(0)._flatpickr;
        if (datePicker) {
            datePicker.setDate(dateRange, true);
        } else {
            $("#edit_range_date").flatpickr({
                mode: "range",
                defaultDate: dateRange
            });
        }
    });
    
});
