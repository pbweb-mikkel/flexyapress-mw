//Video Popup
!function(e){e.fn.VideoPopUp=function(i){var o=this.attr("id"),s=e.extend({},{backgroundColor:"#000000",opener:"video",maxweight:"640",pausevideo:!1,idvideo:""},i),d=document.getElementById(s.idvideo);return e("#"+o).css("display","none"),e("#"+o).append('<div id="opct"></div>'),e("#opct").css("background",s.backgroundColor),e("#"+o).css("z-index","100001"),e("#"+o).css("position","fixed"),e("#"+o).css("top","0"),e("#"+o).css("bottom","0"),e("#"+o).css("right","0"),e("#"+o).css("left","0"),e("#"+o).css("padding","auto"),e("#"+o).css("text-align","center"),e("#"+o).css("background","none"),e("#"+o).css("vertical-align","vertical-align"),e("#videCont").css("z-index","100002"),e("#"+o).append('<div id="closer_videopopup">&otimes;</div>'),e("#"+s.opener).on("click",function(){e("#"+o).show(),e("#"+s.idvideo).trigger("play")}),e("#closer_videopopup").on("click",function(){1==s.pausevideo?e("#"+s.idvideo).trigger("pause"):(d.pause(),d.currentTime=0),e("#"+o).hide()}),this.css({})}}(jQuery);

(function($){
    var resizeSliderTimeout = null;

    AOS.init({
        once:true,
    });

    if($('.property-search-form form').length){
        $('#search-button').on('click', function(e){
            e.preventDefault();

            window.location.href = window.location.href+"?q="+encodeURI($('#form-field-search_value').val());

        });
    }

    if($('.flexyapress-property-slider').length){

        $('.flexyapress-property-slider').each(function(){
           $(this).slick({
               prevArrow: "<button type=\"button\" class=\"slick-prev\"></button>",
               nextArrow: "<button type=\"button\" class=\"slick-next\"></button>",
               focusOnSelect: true,
               lazyLoad: 'ondemand',
               rows:0,
               fade:true
           })
        });

    }

    lightbox.option({
        'wrapAround':true,
        'albumLabel': "%1/%2",
        resizeDuration: 300,
        imageFadeDuration: 300,
        fadeDuration: 300,
    })

    $('#open-images').on('click', function(){

    });

    $('#open-drawings').on('click', function(){

    });

    if($('#set-search-agent-form').length){
        initSearchAgentForm();
    }

    $('input[name="contactAccepted"]').on('change', function(){
        var form = $(this).closest('form');
        var checked = $(this).prop('checked');
        $('.consent span', form).hide();

        if(checked){
            $('#consent_with_contact', form).show();
        }else{
            $('#consent_without_contact', form).show();
        }

    });

    $('#vidBox').VideoPopUp({
        // trigger element
        opener:"open-videos",
        // video ID
        idvideo:"property-video"
    });

    if($("#price-range", '#property-search-form').length){
        $( "#price-range", '#property-search-form' ).slider({
            range: true,
            step:1000,
            min: $('#price-range').data('price-min'),
            max: $('#price-range').data('price-max'),
            values: [$('#price-range').data('price-min-selected') , $('#price-range').data('price-max-selected') ],
            slide: function( event, ui ) {
                var min = ui.values[ 0 ];
                var max = ui.values[ 1 ];
                $( "#price-min" ).html(prettyPrice(min));
                $( "#price-max" ).html(prettyPrice(max));
                $('input[name="minPrice"]').val(min);
                $('input[name="maxPrice"]').val(max);
            },
            stop: function(){
                $('#property-search-form').submit();
            }
        });
        $( "#price-min" ).html(prettyPrice($( "#price-range" ).slider( "values", 0 )));
        $( "#price-max" ).html(prettyPrice($( "#price-range" ).slider( "values", 1 )));
    }

    function prettyPrice(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+'kr';
    }

    function setHeroWidth(){
        $('.hero-image').each(function(){
            if($(window).innerWidth() > 991) {
                var p = $(this).closest('.elementor-section');
                var pw = $(p).width();
                $(this).css('width', pw / 2);
            }else{
                $(this).attr('style', '');
            }
        });
    }
    setHeroWidth();

    if($('body').hasClass('single-sag')){
        initPropertySlider();
        //initPropertySliderNav();
        //setSliderHeight();
    }



    function initPropertySlider(){

        var slider = $('#property-images').slick({
            //asNavFor: '#property-slider-navigation',
            prevArrow: "<button type=\"button\" class=\"slick-prev\"></button>",
            nextArrow: "<button type=\"button\" class=\"slick-next\"></button>",
            focusOnSelect: true,
            lazyLoad: 'ondemand',
            rows:0,
            fade:true
        });

        $(document).keydown(function(e) {

            switch(e.which) {
                case 37: // left
                    slider.slick('slickPrev');
                case 39: // right
                    slider.slick('slickNext');
                default: return; // exit this handler for other keys
            }
            e.preventDefault(); // prevent the default action (scroll / move caret)
        });

        $('#go-to-drawings').click(function(){
            $('#property-images').slick('slickGoTo', $('.drawing-1').data('slick-index'), false);
        });

        $('#go-to-images').click(function(){
            $('#property-images').slick('slickGoTo', $('.image-1').data('slick-index'), false);
        });

        $('#go-to-map').click(function(){
            $('#property-images').slick('slickGoTo', $('.slide-map').data('slick-index'), false);
        });

        $('.fullscreen').on('click',function(){
            toggleFullscreen();

        });

        slider.on('beforeChange', function(event, slick, currentSlide, nextSlide){
            if(nextSlide !== 0){
                $('.property-images-container .flags').fadeOut();
            }else{
                $('.property-images-container .flags').fadeIn();
            }

            var iframe = $('#property-images .slick-slide[data-slick-index="'+nextSlide+'"] iframe');

            if(iframe.length && !iframe.attr('src')){
                iframe.attr('src', iframe.attr('data-src'));
            }

        });

    }

    function toggleFullscreen() {
        if (!$('body').hasClass('fullscreen')) {
            openFullscreen();
        } else if ($('body').hasClass('fullscreen')) {
            closeFullscreen();
        }
    }

    function fullscreenchanged (event) {
        // document.fullscreenElement will point to the element that
        // is in fullscreen mode if there is one. If there isn't one,
        // the value of the property is null.
        if (!document.fullscreenElement) {
            closeFullscreen();
        }
    }

    document.addEventListener('fullscreenchange', fullscreenchanged);

    /* View in fullscreen */
    function openFullscreen() {
        var elem = document.documentElement;
        $('body').addClass('fullscreen');
        if (elem.requestFullscreen) {
            elem.requestFullscreen();
        } else if (elem.webkitRequestFullscreen) { /* Safari */
            elem.webkitRequestFullscreen();
        } else if (elem.msRequestFullscreen) { /* IE11 */
            elem.msRequestFullscreen();
        }
        $(window).trigger('resize');
    }

    /* Close fullscreen */
    function closeFullscreen() {
        $('body').removeClass('fullscreen');
        if(!document.fullscreenElement){
            $(window).trigger('resize');
            return;
        }
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.webkitExitFullscreen) { /* Safari */
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) { /* IE11 */
            document.msExitFullscreen();
        }
        $(window).trigger('resize');
    }

    function initPropertySliderNav(count, fullscreen){

        if(!count){
            count = 5;
        }

        var args = {
            asNavFor: '#property-slider',
            prevArrow: "<button type=\"button\" class=\"slick-prev\"><i class=\"fas fa-chevron-left\"></i></button>",
            nextArrow: "<button type=\"button\" class=\"slick-next\"><i class=\"fas fa-chevron-right\"></i></button>",
            centerMode: false,
            slidesToShow: count,
            slidesToScroll: 1,
            focusOnSelect: true,
            swipe: true,
            swipeToSlide: true,
            variableWidth:true,
            arrows:true,
            lazyLoad: 'progressive',
            responsive: [
                {
                    breakpoint: 1199,
                    settings:{
                        slidesToShow: 3,
                    }
                },
                {
                    breakpoint: 991,
                    settings:{
                        slidesToShow: 4,
                    }
                },
                {
                    breakpoint: 767,
                    settings:{
                        slidesToShow: 4,
                    }
                }
            ]
        };

        if(fullscreen){
            args.responsive = [];


        }

        var sliderNav = $('#property-slider-navigation').slick(args);
    }

    function initRecentPropertiesSlider(){
        var slider = $('.recent-properties').slick({
            prevArrow: "<button type=\"button\" class=\"slick-prev\"><i class=\"fas fa-chevron-left\"></i></button>",
            nextArrow: "<button type=\"button\" class=\"slick-next\"><i class=\"fas fa-chevron-right\"></i></button>",
            focusOnSelect: true,
            lazyLoad: 'ondemand',
            slidesToShow:3,
            slidesToScroll: 3,
            arrows:true,
            responsive: [
                {
                    breakpoint:1199,
                    settings:{
                        slidesToShow:2,
                        slidesToScroll:2,
                    }
                },
                {
                    breakpoint:767,
                    settings:{
                        slidesToShow:1,
                        slidesToScroll:1,
                    }
                }
            ]
        });
    }

    initRecentPropertiesSlider();


    function setSliderHeight(){
        var w = $('#property-slider').width();
        var h = w / 1.77;
        clearTimeout(resizeSliderTimeout);
        resizeSliderTimeout = setTimeout(function(){
            $('#property-slider').css('height', h);
        }, 200);
    }


    $(window).resize(function(){
        setSliderHeight();
        setHeroWidth();
    });

    $('.flexya-order-form').on('submit', submitFlexyaForm);
    $('#property-search-form').on('submit', submitSearchForm);

    $( ".datepicker" ).datepicker({
        minDate: 1,
        dayNamesMin: ["Søn", "Man", "Tir", "Ons", "Tor", "Fre", "Lør" ],
        dayNames: ["Søndag", "Mandag", "Tirsdag", "Onsdag", "Torsdag", "Fredag", "Lørdag" ],
        monthNames: [ "Januar", "Februar", "Marts", "April", "Maj", "Juni", "Juli", "August", "September", "Oktober", "November", "December" ],
        firstDay: 1,
        onSelect: function(dateText, obj){
            var con = $('#'+obj.id);
            var form = con.closest('form');
            var input = form.find('input[name="date"]');
            if(input.length > 0){
                input.val(dateText);
            }

        }
    });



    function submitFlexyaForm(e){
        e.preventDefault();
        var data = $(this).serializeArray();
        var form = $(this);
        var token;

        for(var i = 0; i < data.length; i++){

            if(data[i].name == 'makeABid'){
                var regex = new RegExp('^[0-9]+$');
                if(!regex.test(data[i].value)){
                    alert('Du har indtastet ugyldige tegn i feltet "Bud". Vi beder dig om kun at bruge tal');
                    $('input[name="makeABid"]').css('background', 'tomato').focus();
                    return false;
                }
            }

        }

        var arrangedData = {};
        for(var key in data){
            arrangedData[data[key].name] = data[key].value;
        }

        if(arrangedData.date == ""){
            $('.date-error', this).removeClass('d-none');
        }

        arrangedData.action = 'submit_flexya_form';

        if(typeof grecaptcha !== 'undefined'  && ajax_object.google_captcha_site_key){
            grecaptcha.execute(ajax_object.google_captcha_site_key, {action: 'submit_flexya_form'}).then(function(resp_token){
                token = resp_token;

                arrangedData.token = token;

                $.ajax({
                    url: ajax_object.ajaxurl,
                    method: 'post',
                    data:arrangedData,
                    beforeSend: function(){
                        $('.loading-spinner', form).show();
                        $('input[type="submit"]',form).hide();
                    },
                    success: function(resp){
                        //console.log(resp);
                        var data = resp.data;
                        console.log(data);
                        if(data.type == 'error'){
                            $('.status-message', form).text(data.message).fadeIn();
                            $('input[type="submit"]',form).show();
                        }else if(data.type == 'success'){
                            $(form).animate({
                                opacity: 0
                            }, 500, 'swing', function(){
                                form.hide();
                                form.siblings('.success-message').fadeIn();
                            })
                        }

                        //console.log(data);

                    },
                    complete: function(){
                        $('.loading-spinner', form).hide();
                    }
                });

            });
        }else{

            $.ajax({
                url: ajax_object.ajaxurl,
                method: 'post',
                data:arrangedData,
                beforeSend: function(){
                    $('.loading-spinner', form).show();
                    $('input[type="submit"]',form).hide();
                },
                success: function(resp){
                    var data = resp.data;
                    if(data.type == 'error'){
                        $('.status-message', form).text(data.message).fadeIn();
                        $('input[type="submit"]',form).show();
                    }else if(data.type == 'success'){
                        $(form).animate({
                            opacity: 0
                        }, 500, 'swing', function(){
                            form.hide();
                            form.siblings('.success-message').fadeIn();
                        })
                    }else{
                        $('.status-message', form).text('Der er sket en fejl og vi har ikke modtaget din forespørgsel. Prøv venligst igen eller kontakt os på mail.').fadeIn();
                        $('input[type="submit"]',form).show();
                    }

                    //console.log(data);

                },
                complete: function(){
                    $('.loading-spinner', form).hide();
                }
            });

        }


        return false;

    }

    function submitSearchForm(e){
        e.preventDefault();
        var data = $(this).serializeArray();
        var form = $(this);
        var propertiesWrapper = $(form).closest('#property-search').siblings('.property-list').find('.properties');
        var propertyList = $(form).closest('#property-search').siblings('.property-list');

        var arrangedData = {};
        for(var key in data){
            arrangedData[data[key].name] = data[key].value;
        }


        $.ajax({
            url: ajax_object.ajaxurl,
            method: 'get',
            data: {
                'data': arrangedData,
                'action': 'search_properties'
            },
            beforeSend: function(){
                propertyList.addClass('loading');
            },
            success: function(resp){
                propertiesWrapper.html(resp);
                updateSearchCount();
                AOS.refresh();
            },
            complete: function(){
                propertyList.removeClass('loading');
            }
        })


        return false;

    }

    if($('.property-list').length){
        updateSearchCount();
    }

    function updateSearchCount(){
        $('.search-count').text($('.property', $('.search-count').closest('.property-list-container')).length);
    }

    $('.popup-btn').on('click', openPopup);
    $('.pb-popup .close').on('click', closePopups);
    $('.pb-popup-wrapper').click(function(e){
        if(!$(e.target).closest('.pb-popup').length && !$(e.target).hasClass('ui-icon') && !$(e.target).hasClass('ui-datepicker-next') && !$(e.target).hasClass('ui-datepicker-prev')){
            closePopups();
        }
    });

    function openPopup(e){
        e.preventDefault();
        var popup = $(e.target).data('target');
        $('body').addClass('popup-open');
        $('#'+popup).fadeIn().addClass('shown');
    }

    function closePopups(){
        $('body').removeClass('popup-open');
        $('.pb-popup-container.shown').fadeOut().removeClass('shown');
    }

    $('.sortbox .selected').on('click', toggleSortBox);
    $('.sortbox .select').on('click', selectSort);

    function toggleSortBox(){
        $('.sortbox').toggleClass('open');
    }

    function closeSortBox(){
        $('.sortbox').removeClass('open');
    }

    function selectSort(){
        var sortBy = $(this).data('sort-by');
        var sort = $(this).data('sort');
        var text = $(this).text();
        $('.sortbox .selected-text').text(text);
        $('#property-search-form input[name="sort"]').val(sort);
        $('#property-search-form input[name="sortBy"]').val(sortBy);
        $('#property-search-form').submit();
        closeSortBox();
    }

    $('#toggle-search').on('click', toggleSearch);

    function toggleSearch(){
        $('#property-search-form').stop().slideToggle();
        $('#toggle-search').toggleClass('toggled');
    }

    $('.burger-menu, #mobile-menu .close').on('click', toggleMobileMenu);

    function toggleMobileMenu(){
        $('body').toggleClass('menu-shown');
    }

    function initSearchAgentForm(){
        $( "#price-range", '#set-search-agent-form' ).slider({
            range: true,
            values : [1000000, 10000000],
            step: 50000,
            min:50000,
            max:10000000,
            slide: function( event, ui ) {
                $( "#price-show" ).text( prettyPrice(ui.values[ 0 ]) + "kr - " + prettyPrice(ui.values[ 1 ])+"kr" );
            },
            stop:function(event, ui){
                $('#min-price').val($( "#price-range" ).slider( "values", 0 ));
                $('#max-price').val($( "#price-range" ).slider( "values", 1 ));
            }
        });

        $( "#price-show" ).text( prettyPrice($( "#price-range" ).slider( "values", 0 )) +
            "kr - " + prettyPrice($( "#price-range" ).slider( "values", 1 ))+" kr" );

        $('#min-price').val($( "#price-range" ).slider( "values", 0 ));
        $('#max-price').val($( "#price-range" ).slider( "values", 1 ));

        $( "#property-size" ).slider({
            range: true,
            values : [50, 500],
            step: 5,
            min:10,
            max:500,
            slide: function( event, ui ) {
                $( "#area-show" ).html( ui.values[ 0 ] + "m<sup>2</sup> - " + ui.values[ 1 ]+"m<sup>2</sup>" );
            },
            stop:function(event, ui){
                $('#min-size').val($( "#property-size" ).slider( "values", 0 ));
                $('#max-size').val($( "#property-size" ).slider( "values", 1 ));
            }
        });

        $( "#area-show" ).html( $( "#property-size" ).slider( "values", 0 ) +
            "m<sup>2</sup> - " + $( "#property-size" ).slider( "values", 1 )+" m<sup>2</sup>" );

        $('#min-size').val($( "#property-size" ).slider( "values", 0 ));
        $('#max-size').val($( "#property-size" ).slider( "values", 1 ));


        $('#min-rooms-range').slider({
            value: 5,
            step: 1,
            min:1,
            max:10,
            slide: function( event, ui ) {
                $( "#min-rooms-show" ).html( ui.value);
            },
            stop:function(event, ui){
                $('#min-rooms').val($( "#min-rooms-range" ).slider("value"));
            }
        });

        $( "#min-rooms-show" ).html( $( "#min-rooms-range" ).slider("value"));
        $('#min-rooms').val($( "#min-rooms-range" ).slider("value"));

        $('#land-size-range').slider({
            range: true,
            values: [0, 3000],
            step: 5,
            min:0,
            max:3000,
            slide: function( event, ui ) {
                $( "#land-show" ).html(ui.values[ 0 ] + "m<sup>2</sup> - " + ui.values[ 1 ]+"m<sup>2</sup>" );
            },
            stop:function(event, ui){
                $('#min-land-size').val($( "#land-size-range" ).slider( "values", 0 ));
                $('#max-land-size').val($( "#land-size-range" ).slider( "values", 1 ));
            }

        });

        $('#min-land-size').val($( "#land-size-range" ).slider( "values", 0 ));
        $('#max-land-size').val($( "#land-size-range" ).slider( "values", 1 ));


        $( "#land-show" ).html( $( "#land-size-range" ).slider("values",0)+" m<sup>2</sup> - "+$( "#land-size-range" ).slider("values",1)+" m<sup>2</sup>");

        $('#location-select').dropdown({
            multipleMode: 'label'
        });

        $("#set-search-agent-form").on("submit", submitSetSearchAgent);

    }

    function submitSetSearchAgent(e){
        e.preventDefault();
        var data = $(this).serializeArray();
        var form = $(this);
        var arrangedData = {};


        if(grecaptcha) {
            grecaptcha.execute(ajax_object.google_captcha_site_key, {action: 'submit_flexya_form'}).then(function (resp_token) {
                token = resp_token;

                for(var key in data){

                    if(arrangedData.hasOwnProperty(data[key].name)){
                        if(!Array.isArray(arrangedData[data[key].name])){
                            arrangedData[data[key].name] = [arrangedData[data[key].name]];
                        }
                        arrangedData[data[key].name].push(data[key].value);

                    }else{
                        arrangedData[data[key].name] = data[key].value;
                    }
                }

                $.ajax({
                    url: ajax_object.ajaxurl,
                    data: {
                        'data': arrangedData,
                        'token' : token,
                        'action': 'submit_search_agent_form'
                    },
                    method:"post",
                    beforeSend: function(){
                        $('.loading-spinner', form).show();
                        $('input[type="submit"]',form).hide();
                    },
                    success:function(res){
                        console.log(res);
                        data = JSON.parse(res);

                        if(data.type == 'error'){
                            $('.status-message', form).text(data.message).fadeIn();
                            $('input[type="submit"]',form).show();
                        }else if(data.type == 'success'){
                            $(form).animate({
                                opacity: 0
                            }, 500, 'swing', function(){
                                form.hide();
                                form.siblings('.success-message').fadeIn();
                            })
                        }

                        console.log(data);
                    },
                    complete:function(){
                        $('.loading    -spinner', form).hide();
                    }
                })

            });
        }

        return false;

    }

})(jQuery);