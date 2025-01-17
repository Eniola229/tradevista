/*  ---------------------------------------------------
Template Name: Ashion
Description: Ashion ecommerce template
Author: Colorib
Author URI: https://colorlib.com/
Version: 1.0
Created: Colorib
---------------------------------------------------------  */

//Ajax for adding product

$(document).ready(function () {
  function showToast(message, type) {
    Swal.fire({
      text: message,
      icon: type,
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
    });
  }

  $("#addCart").on("click", function (e) {
    e.preventDefault();

    let form = $(this).closest('form');
    let productId = $(this).data("product-id");
    let quantity = form.find('input[name="quantity"]').val();

    $.ajax({
      url: form.attr('action'),
      type: 'GET',
      data: form.serialize() + '&product_id=' + productId + '&quantity=' + quantity,
      success: function (response) {
        showToast('Product added to cart successfully!', 'success');
        window.location.reload();
      },
      error: function (xhr) {
        // Parse the response JSON to get the error message
        let errorMessage = 'Error adding product to cart!';

        if (xhr.responseJSON && xhr.responseJSON.message) {
          errorMessage = xhr.responseJSON.message;
        }

        // Show the error message using your toast function
        showToast(errorMessage, 'error');
      }
    });
  });
  $(".product-btn").on("click", function (e) {
    e.preventDefault();

    let form = $(this).closest('form');
    let productId = $(this).data("product-id");
    let quantity = form.find('input[name="quantity"]').val();

    $.ajax({
      url: form.attr('action'),
      type: 'GET',
      data: form.serialize() + '&product_id=' + productId + '&quantity=' + quantity,
      success: function (response) {
        showToast('Product added to cart successfully!', 'success');
        window.location.reload();
      },
      error: function (xhr) {
        // Parse the response JSON to get the error message
        let errorMessage = 'Error adding product to cart!';

        if (xhr.responseJSON && xhr.responseJSON.message) {
          errorMessage = xhr.responseJSON.message;
        }

        // Show the error message using your toast function
        showToast(errorMessage, 'error');
      }
    });
  });

  //Wishlist 
  function showToast(message, type) {
    Swal.fire({
      text: message,
      icon: type,
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
    });
  }

  $(".addWishlistBtn").on("click", function (e) {
    e.preventDefault();

    let form = $(this).closest('form');
    let productId = $(this).data("product-id");

    $.ajax({
      url: form.attr('action'),
      type: 'GET',
      data: form.serialize() + '&product_id=' + productId,
      success: function (response) {
        showToast(response.message, 'success');
        window.location.reload();
      },
      error: function (xhr) {
        let errorMessage = xhr.responseJSON.message || 'Error adding product to wishlist!';
        showToast(errorMessage, 'error');
      }
    });
  });

  $(".remove-item-wishlist").on("click", function (e) {
    e.preventDefault();

    let productId = $(this).data("product-id");

    $.ajax({
      url: 'wishlist/removeWishlist',
      type: 'GET',
      data: {
        product_id: productId
      },
      success: function (response) {
        showToast(response.message, 'success');
        window.location.reload();
      },
      error: function (xhr) {
        let errorMessage = xhr.responseJSON.message || 'Error removing item from wishlist!';
        showToast(errorMessage, 'error');
      }
    });
  });


});




'use strict';

(function ($) {

    /*------------------
        Preloader
    --------------------*/
    $(window).on('load', function () {
        $(".loader").fadeOut();
        $("#preloder").delay(200).fadeOut("slow");

        /*------------------
            Product filter
        --------------------*/
        $('.filter__controls li').on('click', function () {
            $('.filter__controls li').removeClass('active');
            $(this).addClass('active');
        });
        if ($('.property__gallery').length > 0) {
            var containerEl = document.querySelector('.property__gallery');
            var mixer = mixitup(containerEl);
        }
    });

    /*------------------
        Background Set
    --------------------*/
    $('.set-bg').each(function () {
        var bg = $(this).data('setbg');
        $(this).css('background-image', 'url(' + bg + ')');
    });

    //Search Switch
    $('.search-switch').on('click', function () {
        $('.search-model').fadeIn(400);
    });

    $('.search-close-switch').on('click', function () {
        $('.search-model').fadeOut(400, function () {
            $('#search-input').val('');
        });
    });

    //Canvas Menu
    $(".canvas__open").on('click', function () {
        $(".offcanvas-menu-wrapper").addClass("active");
        $(".offcanvas-menu-overlay").addClass("active");
    });

    $(".offcanvas-menu-overlay, .offcanvas__close").on('click', function () {
        $(".offcanvas-menu-wrapper").removeClass("active");
        $(".offcanvas-menu-overlay").removeClass("active");
    });

    /*------------------
		Navigation
	--------------------*/
    $(".header__menu").slicknav({
        prependTo: '#mobile-menu-wrap',
        allowParentLinks: true
    });

    /*------------------
        Accordin Active
    --------------------*/
    $('.collapse').on('shown.bs.collapse', function () {
        $(this).prev().addClass('active');
    });

    $('.collapse').on('hidden.bs.collapse', function () {
        $(this).prev().removeClass('active');
    });

    /*--------------------------
        Banner Slider
    ----------------------------*/
    $(".banner__slider").owlCarousel({
        loop: true,
        margin: 0,
        items: 1,
        dots: true,
        smartSpeed: 1200,
        autoHeight: false,
        autoplay: true
    });

    /*--------------------------
        Product Details Slider
    ----------------------------*/
    $(".product__details__pic__slider").owlCarousel({
        loop: false,
        margin: 0,
        items: 1,
        dots: false,
        nav: true,
        navText: ["<i class='arrow_carrot-left'></i>","<i class='arrow_carrot-right'></i>"],
        smartSpeed: 1200,
        autoHeight: false,
        autoplay: false,
        mouseDrag: false,
        startPosition: 'URLHash'
    }).on('changed.owl.carousel', function(event) {
        var indexNum = event.item.index + 1;
        product_thumbs(indexNum);
    });

    function product_thumbs (num) {
        var thumbs = document.querySelectorAll('.product__thumb a');
        thumbs.forEach(function (e) {
            e.classList.remove("active");
            if(e.hash.split("-")[1] == num) {
                e.classList.add("active");
            }
        })
    }


    /*------------------
		Magnific
    --------------------*/
    $('.image-popup').magnificPopup({
        type: 'image'
    });


    $(".nice-scroll").niceScroll({
        cursorborder:"",
        cursorcolor:"#dddddd",
        boxzoom:false,
        cursorwidth: 5,
        background: 'rgba(0, 0, 0, 0.2)',
        cursorborderradius:50,
        horizrailenabled: false
    });

    /*------------------
        CountDown
    --------------------*/
    // For demo preview start
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();

    if(mm == 12) {
        mm = '01';
        yyyy = yyyy + 1;
    } else {
        mm = parseInt(mm) + 1;
        mm = String(mm).padStart(2, '0');
    }
    var timerdate = mm + '/' + dd + '/' + yyyy;
    // For demo preview end


    // Uncomment below and use your date //

    /* var timerdate = "2020/12/30" */

	$("#countdown-time").countdown(timerdate, function(event) {
        $(this).html(event.strftime("<div class='countdown__item'><span>%D</span> <p>Day</p> </div>" + "<div class='countdown__item'><span>%H</span> <p>Hour</p> </div>" + "<div class='countdown__item'><span>%M</span> <p>Min</p> </div>" + "<div class='countdown__item'><span>%S</span> <p>Sec</p> </div>"));
    });

    /*-------------------
		Range Slider
	--------------------- */
	var rangeSlider = $(".price-range"),
    minamount = $("#minamount"),
    maxamount = $("#maxamount"),
    minPrice = rangeSlider.data('min'),
    maxPrice = rangeSlider.data('max');
    rangeSlider.slider({
    range: true,
    min: minPrice,
    max: maxPrice,
    values: [minPrice, maxPrice],
    slide: function (event, ui) {
        minamount.val('$' + ui.values[0]);
        maxamount.val('$' + ui.values[1]);
        }
    });
    minamount.val('$' + rangeSlider.slider("values", 0));
    maxamount.val('$' + rangeSlider.slider("values", 1));

    /*------------------
		Single Product
	--------------------*/
	$('.product__thumb .pt').on('click', function(){
		var imgurl = $(this).data('imgbigurl');
		var bigImg = $('.product__big__img').attr('src');
		if(imgurl != bigImg) {
			$('.product__big__img').attr({src: imgurl});
		}
    });
    
    /*-------------------
		Quantity change
	--------------------- */
// Quantity Increment and Decrement
var proQty = $('.pro-qty');

proQty.each(function () {
    var productId = $(this).data('product-id');
    var index = $(this).data('index');

    $(this).prepend("<span class='dec qtybtn minus' data-product-id='" + productId + "' data-index='" + index + "'>-</span>");
    $(this).append("<span class='inc qtybtn plus' data-product-id='" + productId + "' data-index='" + index + "'>+</span>");
});

// Event listener for quantity change
proQty.on('click', '.qtybtn', function () {
    var $button = $(this);
    var $input = $button.parent().find('input');
    var oldValue = parseFloat($input.val());

    // Determine new value
    var newVal;
    if ($button.hasClass('inc')) {
        newVal = oldValue + 1; // Increment
    } else {
        newVal = oldValue > 0 ? oldValue - 1 : 0; // Decrement with minimum value of 0
    }

    // Update input value
    $input.val(newVal);
});

// Radio Button Activation
$(".size__btn label").on('click', function () {
    $(".size__btn label").removeClass('active'); // Remove 'active' from all labels
    $(this).addClass('active'); // Add 'active' to the clicked label
});


})(jQuery);