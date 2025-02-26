Dropzone.autoDiscover = false;

$(function() {
    "use strict";

    var myDropzone = new Dropzone("#gallery-dropzone", {
        url: "#",
        autoProcessQueue: false,
        paramName: "gallery_files",
        maxFilesize: 2,
        addRemoveLinks: true,
        dictRemoveFile: "Remove",
        parallelUploads: 10,
        acceptedFiles: "image/*",
    });

    // $("#package-form").on("submit", function(e) {
    //     e.preventDefault();
    //     $('.form-group .text-danger').remove();
    //     $('.is-invalid').removeClass('is-invalid');

    //     let formData = new FormData(this);
    //     formData.append("detail", $("#detail").val());

    //     myDropzone.getAcceptedFiles().forEach((file, index) => {
    //         formData.append(`gallery_files[${index}]`, file);
    //     });

    //     let csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    //     $.ajax({
    //         url: "/tenant/packet/insert",
    //         type: "POST",
    //         data: formData,
    //         processData: false,
    //         contentType: false,
    //         headers: {
    //             "X-CSRF-TOKEN": csrfToken
    //         },
    //         success: function(response) {
    //             if (response.success) {
    //                 toastr.success(response.message, "✅ Success", {
    //                     closeButton: true,
    //                     tapToDismiss: false,
    //                     progressBar: true,
    //                     positionClass: "toast-top-right",
    //                     timeOut: 2000
    //                 });

    //                 setTimeout(function() {
    //                     window.location.href = "/tenant/packet";
    //                 }, 2000);
    //             }
    //         },
    //         error: function(xhr) {
    //             if (xhr.status === 422) {
    //                 let errors = xhr.responseJSON.errors;
    //                 let errorMessage = "Please correct the following errors:<br>";

    //                 $.each(errors, function(field, messages) {
    //                     let inputField = $("#" + field);
    //                     inputField.addClass("is-invalid");
    //                     inputField.after('<div class="text-danger">' + messages[0] + "</div>");

    //                     errorMessage += `• ${messages[0]}<br>`;
    //                 });

    //                 toastr.error(errorMessage, "❌ Validation Error", {
    //                     closeButton: true,
    //                     tapToDismiss: false,
    //                     progressBar: true,
    //                     positionClass: "toast-top-right",
    //                     timeOut: 5000
    //                 });

    //             } else {
    //                 let errorMessage = xhr.responseJSON?.message || "Something went wrong. Please try again.";
    //                 toastr.error(errorMessage, "❌ Error", {
    //                     closeButton: true,
    //                     tapToDismiss: false,
    //                     progressBar: true,
    //                     positionClass: "toast-top-right",
    //                     timeOut: 3000
    //                 });
    //             }
    //         }
    //     });
    // });

    $("#package-form").on("submit", function(e) {
        e.preventDefault();
        $('.form-group .text-danger').remove();
        $('.is-invalid').removeClass('is-invalid');

        let formData = new FormData(this);
        formData.append("detail", $("#detail").val());

        myDropzone.getAcceptedFiles().forEach((file, index) => {
            formData.append(`gallery_files[${index}]`, file);
        });

        let csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        let loadingToast = toastr.info("Uploading files... Please wait.", "⏳ Processing", {
            closeButton: false,
            tapToDismiss: false,
            progressBar: true,
            positionClass: "toast-top-right",
            timeOut: 0,
            extendedTimeOut: 0
        });

        let submitButton = $("#package-form button[type='submit']");
        submitButton.prop("disabled", true).text("Uploading...");

        $.ajax({
            url: "/tenant/packet/insert",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": csrfToken
            },
            success: function(response) {
                toastr.clear(loadingToast);

                if (response.success) {
                    toastr.success(response.message, "✅ Success", {
                        closeButton: true,
                        tapToDismiss: false,
                        progressBar: true,
                        positionClass: "toast-top-right",
                        timeOut: 2000
                    });

                    setTimeout(function() {
                        window.location.href = "/tenant/packet";
                    }, 2000);
                }
            },
            error: function(xhr) {
                toastr.clear(loadingToast);

                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessage = "Please correct the following errors:<br>";

                    $.each(errors, function(field, messages) {
                        let inputField = $("#" + field);
                        inputField.addClass("is-invalid");
                        inputField.after('<div class="text-danger">' + messages[0] + "</div>");
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
            },
            complete: function() {
                submitButton.prop("disabled", false).text("Submit");
            }
        });
    });

    myDropzone.on("removedfile", function(file) {
        console.log("File removed: ", file.name);
    });

});
