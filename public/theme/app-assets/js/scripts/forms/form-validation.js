$(function () {
    'use strict';
  
    var packageForm = document.getElementById('package-form');
  
    packageForm.addEventListener('submit', function (event) {
      if (!packageForm.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      } else {
        event.preventDefault();
        if (myDropzone.getQueuedFiles().length > 0) {
          myDropzone.processQueue();
        } else {
          packageForm.submit();
        }
      }
  
      packageForm.classList.add('was-validated');
    }, false);
  });
  