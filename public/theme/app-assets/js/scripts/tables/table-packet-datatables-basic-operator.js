$(document).ready(function () {

    let table_packet_list = $('#packet-table').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        searching: false,
        ajax: {
            url: "/operator/data/packet-data",
            data: function (d) {
                d.packet_name = $('#packet_name').val();
                d.price = $('#price').data('raw-value') || '';
                d.status = $('#status').val();
                d.lokasi = $('#lokasi').val();
            },
            error: function (xhr, error, thrown) {
                console.log("AJAX Error: " + error);
                console.log("Thrown Error: " + thrown);
            }
        },
        columns: [
            { data: 'packet_name', name: 'packet_name' },
            { data: 'location', name: 'location' },
            { data: 'price', name: 'price' },
            { data: 'currency', name: 'currency' },
            // { data: 'max_people', name: 'max_people' },
            { data: 'duration', name: 'duration' },
            { data: 'validity_type', name: 'validity_type' },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'gallery_button', name: 'gallery_button', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        columnDefs: [
            // { width: "400px", targets: 0 },
            // { width: "50px", targets: 1 },
            // { width: "50px", targets: 2 },
            // { width: "400px", targets: 9 }
        ],
        autoWidth: false,
        order: [[0, 'asc']],
        dom: '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-right"B>>' +
             '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t' +
             '<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        displayLength: 10,
        lengthMenu: [10, 25, 50, 75, 100],
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
                        exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
                    },
                    {
                        extend: 'csv',
                        text: feather.icons['file-text'].toSvg({ class: 'font-small-4 mr-50' }) + 'CSV',
                        className: 'dropdown-item',
                        exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
                    },
                    {
                        extend: 'excel',
                        text: feather.icons['file'].toSvg({ class: 'font-small-4 mr-50' }) + 'Excel',
                        className: 'dropdown-item',
                        exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
                    },
                    {
                        extend: 'pdf',
                        text: feather.icons['clipboard'].toSvg({ class: 'font-small-4 mr-50' }) + 'PDF',
                        className: 'dropdown-item',
                        exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
                    },
                    {
                        extend: 'copy',
                        text: feather.icons['copy'].toSvg({ class: 'font-small-4 mr-50' }) + 'Copy',
                        className: 'dropdown-item',
                        exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
                    }
                ]
            },
        ],
        language: {
            paginate: {
                previous: '&nbsp;',
                next: '&nbsp;'
            }
        }
    });

    $('div.head-label').html('<h6 class="mb-0">Packet Data List</h6>');

    function formatCurrency(input) {
        let value = input.value.replace(/\D/g, '');
        if (value) {
            value = new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2 }).format(value / 100); // Format as currency
        }
        input.value = value;
    }

    function getRawPrice(value) {
        return value.replace(/\./g, '').replace(',', '.');
    }

    $('#price').on('input', function () {
        formatCurrency(this);
        $(this).data('raw-value', this.value.replace(/\./g, '').replace(',', '.'));
    });

    $('.dt_adv_search input, .dt_adv_search select').on('change keyup', function () {
        table_packet_list.draw();
    });

    $('#carouselModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var imagesData = button.attr('data-images');

        if (imagesData) {
            try {
                var images = JSON.parse(imagesData);

                if (Array.isArray(images) && images.length > 0) {
                    var carouselInner = $('#carouselImages');
                    carouselInner.empty();
                    images.forEach(function(image, index) {
                        var isActive = (index === 0) ? ' active' : '';
                        carouselInner.append(`
                            <div class="carousel-item${isActive}">
                                <img src="${image}" class="d-block w-100" alt="image-${index}">
                            </div>
                        `);
                    });
                } else {
                    console.log("No images found in the data.");
                }
            } catch (error) {
                console.error("Error parsing JSON:", error);
            }
        } else {
            console.log("No data-images found or data is empty.");
        }
    });
});
