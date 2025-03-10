/*=========================================================================================
    File Name: app-ecommerce.js
    Description: Ecommerce pages js
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy  - Vuejs, HTML & Laravel Admin Dashboard Template
    Author: PIXINVENT
    Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

'use strict';

$(function () {
  // RTL Support
  var direction = 'ltr';
  if ($('html').data('textdirection') == 'rtl') {
    direction = 'rtl';
  }

  var sidebarShop = $('.sidebar-shop'),
    btnCart = $('.btn-cart'),
    overlay = $('.body-content-overlay'),
    sidebarToggler = $('.shop-sidebar-toggler'),
    gridViewBtn = $('.grid-view-btn'),
    listViewBtn = $('.list-view-btn'),
    priceSlider = document.getElementById('price-slider'),
    ecommerceProducts = $('#ecommerce-products'),
    sortingDropdown = $('.dropdown-sort .dropdown-item'),
    sortingText = $('.dropdown-toggle .active-sorting'),
    wishlist = $('.btn-wishlist'),
    checkout = 'app-ecommerce-checkout.html';

  if ($('body').attr('data-framework') === 'laravel') {
    var url = $('body').attr('data-asset-path');
    checkout = url + 'app/ecommerce/checkout';
  }

  // On sorting dropdown change
  if (sortingDropdown.length) {
    sortingDropdown.on('click', function () {
      var $this = $(this);
      var selectedLang = $this.text();
      sortingText.text(selectedLang);
    });
  }

  // Show sidebar
  if (sidebarToggler.length) {
    sidebarToggler.on('click', function () {
      sidebarShop.toggleClass('show');
      overlay.toggleClass('show');
      $('body').addClass('modal-open');
    });
  }

  // Overlay Click
  if (overlay.length) {
    overlay.on('click', function (e) {
      sidebarShop.removeClass('show');
      overlay.removeClass('show');
      $('body').removeClass('modal-open');
    });
  }

  // Init Price slider
  if (typeof priceSlider !== undefined && priceSlider !== null) {
    noUiSlider.create(priceSlider, {
      start: [1500, 3500],
      direction: direction,
      connect: true,
      tooltips: [true, true],
      format: wNumb({
        decimals: 0
      }),
      range: {
        min: 51,
        max: 5000
      }
    });
  }

  // Grid View
  if (gridViewBtn.length) {
    gridViewBtn.on('click', function () {
      ecommerceProducts.removeClass('list-view').addClass('grid-view');
      listViewBtn.removeClass('active');
      gridViewBtn.addClass('active');
    });
  }

  // List View
  if (listViewBtn.length) {
    listViewBtn.on('click', function () {
      ecommerceProducts.removeClass('grid-view').addClass('list-view');
      gridViewBtn.removeClass('active');
      listViewBtn.addClass('active');
    });
  }

  // On cart & view cart btn click to cart
  if (btnCart.length) {
    btnCart.on('click', function (e) {
      var $this = $(this),
        addToCart = $this.find('.add-to-cart');
      if (addToCart.length > 0) {
        e.preventDefault();
      }
      addToCart.text('View In Cart').removeClass('add-to-cart').addClass('view-in-cart');
      $this.attr('href', checkout);
      toastr['success']('', 'Added Item In Your Cart 🛒', {
        closeButton: true,
        tapToDismiss: false,
        rtl: direction
      });
    });
  }

  // For Wishlist Icon
  if (wishlist.length) {
    wishlist.on('click', function () {
      var $this = $(this);
      $this.find('svg').toggleClass('text-danger');
      if ($this.find('svg').hasClass('text-danger')) {
        toastr['success']('', 'Added to wishlist ❤️', {
          closeButton: true,
          tapToDismiss: false,
          rtl: direction
        });
      }
    });
  }
});

// on window resize hide sidebar
$(window).on('resize', function () {
  if ($(window).outerWidth() >= 991) {
    $('.sidebar-shop').removeClass('show');
    $('.body-content-overlay').removeClass('show');
  }
});

$(document).ready(function () {
    $(document).on("click", "#show-ticket-info", function() {
        var ticket_id = $(this).data('ticket_id');
        var owner_name = $(this).data('owner_name');
        var id_number = $(this).data('id_number');
        var owner_phone_number = $(this).data('owner_phone_number');
        var owner_email_address = $(this).data('owner_email_address');
        var owner_address = $(this).data('owner_address');
        $("#ticket-info #ticket_id").val(ticket_id);
        $("#ticket-info #name").val(owner_name);
        $("#ticket-info #id_number").val(id_number);
        $("#ticket-info #phone").val(owner_phone_number);
        $("#ticket-info #email").val(owner_email_address);
        $("#ticket-info #address").val(owner_address);
    });
});

