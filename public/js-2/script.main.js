$(function() {
    //rightside menu toggle
    $('.leftsidebar-nav li').click(function(e) {
        var _ele = $(this).find('ul.dropmenu');
        if (_ele.length > 0) {
            if ($(this).hasClass('active')) {
                $('.leftsidebar-nav li').removeClass('active');
                $('ul.dropmenu').slideUp();
            } else {
                $('.leftsidebar-nav li').removeClass('active');
                $('ul.dropmenu').slideUp();
                $(this).addClass('active');
                $(this).find('ul').slideDown();
            }
        } else {

            if ($(this).parent().hasClass('dropmenu')) {
                e.stopPropagation();
                $('li ul.dropmenu li').removeClass('active');
                $(this).addClass('active');
            } else {
                $('.leftsidebar-nav li').removeClass('active');
                $(this).addClass('active');
                $('ul.dropmenu').slideUp();
            }
        }

    });
    //toggle menu
    $('.trigger-right-nav').click(function() {
        $('body').toggleClass('body-xs');
    });
    //trigger account menu
    $('.trigger-account-menu').click(function(e) {
        e.stopPropagation();
        $(this).prev().addClass('active');
    });

    var ele = $('html').find('input[type="password"]');
    $(ele).parent().append('<a href="javascript:void(0);" class="typeToggle"><i class="fa fa-eye" aria-hidden="true"></i></a>')
    $('body').on('click', '.typeToggle', function() {
        if ($(this).hasClass('open')) {
            $(this).removeClass('open');
            $(this).find('.fa').addClass('fa-eye').removeClass('fa-eye-slash');
            $(ele).prop('type', 'password');
        } else {
            $(this).addClass('open');
            $(this).find('.fa').addClass('fa-eye-slash').removeClass('fa-eye');
            $(ele).prop('type', 'text');
        }
    });

    //tab container js
    $('body').on('click', '.tabaction-wrap a', function() {
        $('.tabaction-wrap a').removeClass('active');
        $('.tabpane').removeClass('open');
        $(this).addClass('active');
        $($(this).attr('data-id')).addClass('open');
    });

    $(document).click(function() {
        $('header .drop-menu').removeClass('active');
    })
    
});