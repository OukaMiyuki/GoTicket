$(document).ready(function () {
    let table_user_list = $('#locations-table').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        searching: false,
        ajax: {
            url: "/tenant/lokasi-data",
            data: function (d) {
                d.name = $('#name').val();
                d.address = $('#address').val();
                d.phone = $('#phone').val();
            }
        },
        columns: [
            { data: 'location_name', name: 'location_name' },
            { data: 'max_ticket_quota', name: 'max_ticket_quota' },
            { data: 'address', name: 'address' },
            { data: 'phone', name: 'phone' },
            { data: 'location_types', name: 'location_types', orderable: false, searchable: false },
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        columnDefs: [
            { width: "200px", targets: 0 },
            { width: "250px", targets: 1 },
            { width: "200px", targets: 2 },
            { width: "350px", targets: 3 },
        ],
        autoWidth: false,
        order: [[0, 'desc']],
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
    $('div.head-label').html('<h6 class="mb-0">DataTable Tabel User</h6>');
    $('.dt_adv_search input, .dt_adv_search select').on('change keyup', function () {
        table_user_list.draw();
    });

    $('#modals-slide-in form').on('submit', function (e) {
        e.preventDefault();
        $('.form-group').removeClass('has-error');
        $('.form-group .text-danger').remove();

        var formData = new FormData(this);

        var jenisLokasiValues = $('#jenis_lokasi').val();
        if (jenisLokasiValues) {
            jenisLokasiValues.forEach(value => {
                formData.append('jenis_lokasi[]', value);
            });
        }

        $.ajax({
            url: '/tenant/lokasi/insert',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    $('#modals-slide-in').modal('hide');
                    alert('User added successfully!');
                    window.location.href = '/tenant/lokasi';
                }
            },
            error: function (xhr) {
                var errors = xhr.responseJSON.errors;
                $.each(errors, function (field, messages) {
                    var inputField = $('#basic-icon-default-' + field);
                    var errorMessage = messages[0];
                    inputField.closest('.form-group').addClass('has-error');
                    inputField.after('<div class="text-danger">' + errorMessage + '</div>');
                });
            }
        });
    });

    $('#modals-edit-slide-in form').on('submit', function (e) {
        e.preventDefault();
        $('.form-group').removeClass('has-error');
        $('.form-group .text-danger').remove();
        var formData = new FormData(this);

        var jenisLokasiValues = $('#jenis_lokasi_edit').val();
        if (jenisLokasiValues) {
            jenisLokasiValues.forEach(value => {
                formData.append('jenis_lokasi[]', value);
            });
        }

        $.ajax({
            url: '/tenant/lokasi/update',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    $('#modals-edit-slide-in').modal('hide');
                    alert('User data updated successfully!');
                    window.location.href = '/tenant/lokasi';
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

    $(document).on("click", "#edit-location-data", function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var max_quota = $(this).data('max_quota');
        var address = $(this).data('address');
        var phone = $(this).data('phone');
        var jenisLokasi = $(this).data('jenis-lokasi');
        $("#edit-lokasi #id").val(id);
        $("#edit-lokasi #name").val(name);
        $("#edit-lokasi #max_quota").val(max_quota);
        $("#edit-lokasi #address").val(address);
        $("#edit-lokasi #phone").val(phone);
        $("#jenis_lokasi_edit").val(jenisLokasi).trigger("change");
    });


});
