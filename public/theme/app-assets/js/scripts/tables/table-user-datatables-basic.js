$(document).ready(function () {
    let table_user_list = $('#user-table').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        searching: false,
        ajax: {
            url: "/tenant/employee-data",
            data: function (d) {
                d.name = $('#name').val();
                d.email = $('#email').val();
                d.phone = $('#phone').val();
                d.lokasi = $('#lokasi').val(); 
                d.jenis_kelamin = $('#jenis_kelamin').val();
                d.jabatan = $('#jabatan').val();
            },
            error: function (xhr, error, thrown) {
                console.log("AJAX Error: " + error);
                console.log("Thrown Error: " + thrown);
            }
        },
        columns: [
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'phone', name: 'phone' },
            { data: 'jenis_kelamin', name: 'jenis_kelamin' },
            { data: 'role', name: 'role' },
            { data: 'location', name: 'location', orderable: false, searchable: false },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        columnDefs: [
            {
                className: 'control',
                orderable: false,
            }
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


    $('div.head-label').html('<h6 class="mb-0">DataTable Tabel User</h6>');
    $('.dt_adv_search input, .dt_adv_search select').on('change keyup', function () {
        table_user_list.draw();
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
            url: '/tenant/employee/insert',
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
        
                    // Delay the redirect to allow message display
                    setTimeout(function () {
                        window.location.href = "/tenant/employee";
                    }, 2000);
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    // Handle validation errors
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
                    // Handle other errors
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
});
