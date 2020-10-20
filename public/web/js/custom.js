$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


$(document).ready(function() {
    "use strict";

    // ===========Featured Owl Carousel============
    var objowlcarousel = $(".owl-carousel-featured");
    if (objowlcarousel.length > 0) {
        objowlcarousel.owlCarousel({
            responsive: {
                0: {
                    items: 2,
                },
                600: {
                    items: 2,
                    nav: false
                },
                1000: {
                    items: 5,
                },
                1200: {
                    items: 5,
                },
            },
            lazyLoad: true,
            pagination: false,
            loop: true,
			dots: false,
            autoPlay: false,
            navigation: true,
            stopOnHover: true,
            nav: true,
            navigationText: ["<i class='mdi mdi-chevron-left'></i>", "<i class='mdi mdi-chevron-right'></i>"]
        });
    }

    // ===========Category Owl Carousel============
    var objowlcarousel = $(".owl-carousel-category");
    if (objowlcarousel.length > 0) {
        objowlcarousel.owlCarousel({
            responsive: {
                0: {
                    items: 3,
                },
                600: {
                    items: 5,
                    nav: false
                },
                1000: {
                    items: 8,
                },
                1200: {
                    items: 8,
                },
            },
            items: 8,
            lazyLoad: true,
            pagination: false,
            loop: true,
			dots: false,
            autoPlay: 2000,
            navigation: true,
            stopOnHover: true,
            nav: true,
            navigationText: ["<i class='mdi mdi-chevron-left'></i>", "<i class='mdi mdi-chevron-right'></i>"]
        });
    }

    // ===========Right Sidebar============
    $('[data-toggle="offcanvas"]').on('click', function() {
        $('body').toggleClass('toggled');
    });

    // ===========Slider============
    var mainslider = $(".owl-carousel-slider");
    if (mainslider.length > 0) {
        mainslider.owlCarousel({
            items: 1,
            dots: false,
            lazyLoad: true,
            pagination: true,
            autoPlay: 4000,
            loop: true,
            singleItem: true,
            navigation: true,
            stopOnHover: true,
            nav: true,
            navigationText: ["<i class='mdi mdi-chevron-left'></i>", "<i class='mdi mdi-chevron-right'></i>"]
        });
    }

    // ===========Select2============
    $('select').select2();

    // ===========Tooltip============
    $('[data-toggle="tooltip"]').tooltip()

    // ===========Single Items Slider============   
    var sync1 = $("#sync1");
    var sync2 = $("#sync2");
    sync1.owlCarousel({
        singleItem: true,
        items: 1,
        slideSpeed: 1000,
        pagination: false,
        navigation: true,
        autoPlay: 2500,
		dots: false,
        nav: true,
        navigationText: ["<i class='mdi mdi-chevron-left'></i>", "<i class='mdi mdi-chevron-right'></i>"],
        afterAction: syncPosition,
        responsiveRefreshRate: 200,
    });
    sync2.owlCarousel({
        items: 5,
        navigation: true,
        dots: false,
        pagination: false,
        nav: true,
        navigationText: ["<i class='mdi mdi-chevron-left'></i>", "<i class='mdi mdi-chevron-right'></i>"],
        responsiveRefreshRate: 100,
        afterInit: function(el) {
            el.find(".owl-item").eq(0).addClass("synced");
        }
    });

    function syncPosition(el) {
        var current = this.currentItem;
        $("#sync2")
            .find(".owl-item")
            .removeClass("synced")
            .eq(current)
            .addClass("synced")
        if ($("#sync2").data("owlCarousel") !== undefined) {
            center(current)
        }
    }
    $("#sync2").on("click", ".owl-item", function(e) {
        e.preventDefault();
        var number = $(this).data("owlItem");
        sync1.trigger("owl.goTo", number);
    });

    function center(number) {
        var sync2visible = sync2.data("owlCarousel").owl.visibleItems;
        var num = number;
        var found = false;
        for (var i in sync2visible) {
            if (num === sync2visible[i]) {
                var found = true;
            }
        }
        if (found === false) {
            if (num > sync2visible[sync2visible.length - 1]) {
                sync2.trigger("owl.goTo", num - sync2visible.length + 2)
            } else {
                if (num - 1 === -1) {
                    num = 0;
                }
                sync2.trigger("owl.goTo", num);
            }
        } else if (num === sync2visible[sync2visible.length - 1]) {
            sync2.trigger("owl.goTo", sync2visible[1])
        } else if (num === sync2visible[0]) {
            sync2.trigger("owl.goTo", num - 1)
        }
    }
    
    //
// $("body").on("contextmenu",function(e){
//         return false;
//     });
//     $(document).keydown(function(e){
//          if (e.ctrlKey && (e.keyCode === 67 || e.keyCode === 86 || e.keyCode === 85 || e.keyCode === 117)){
//            return false;
//          }
//          if(e.which === 123){
//              return false;
//          }
//          if(e.metaKey){
//              return false;
//          }
//          //document.onkeydown = function(e) {
//          // "I" key
//          if (e.ctrlKey && e.shiftKey && e.keyCode == 73) {
//              return false;
//          }
//          // "J" key
//          if (e.ctrlKey && e.shiftKey && e.keyCode == 74) {
//              return false;
//          }
//          // "S" key + macOS
//          if (e.keyCode == 83 && (navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey)) {
//              return false;
//          }
//          if (e.keyCode == 224 && (navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey)) {
//              return false;
//          }
//          // "U" key
//          if (e.ctrlKey && e.keyCode == 85) {
//             return false;
//          }
//          // "F12" key
//          if (event.keyCode == 123) {
//             return false;
//          }
//     });
	


    
    

});

function orderCheck(){

    let target         = $("#target").val();
    var user_id        = $("#user_id").val();
    var quantity       = $('#quantity').val();
    var farmer_id      = $('#farmer_id').val();
    var customer_id    = $('#customer_id').val();
    var upazila_id     = $('#upazila_id').val();
    var product_id     = $('#product_id').val();
    var union_id       = $('#union_id').val();
    var total_amount   = $('#total_amount').val();

    if(user_id < 0 ){

        $('#authentication_modal').modal('show');

    }else{

        if(quantity!='' && total_amount!=''){
        $.ajax ({
            type: 'POST',
            url: target+"order/check",
            dataType: "JSON",
            data: { 
                user_id: user_id, 
                quantity:quantity, 
                total_amount:total_amount,
                farmer_id:farmer_id,
                customer_id:customer_id,
                upazila_id:upazila_id,
                union_id:union_id,
                product_id:product_id,
                 },
            success : function(response) {

                $('#authentication_modal').modal('hide');
                 $(".alert-success").css("display", "block");
                 $(".alert-success").append("<P>অর্ডার সম্পূর্ণ হয়েছে");
                
            }
        });
    }
        
    }
}

jQuery(document).ready(function($) {
    $("#quantity").on('keyup', function() {
    
        var quantity        = parseInt($(this).val());
        var rate            = parseInt($('#rate').val());
        var total_quantity  = parseInt($('#total_quantity').val());

        if(quantity > total_quantity){
             $('#msg').show();
             $('#total_amount_show').html('');

        }else{
           $('#msg').hide();
           var total_amount = (quantity*rate);
           $('#total_amount').val(total_amount);
           $('#total_amount_show').html(total_amount);
        }
       // console.log(total_amountShow);
    
    });
});  

$('.product_find').click(function(){
    // alert('hello');
});
