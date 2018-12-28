//Toggle Button js 

$('.toggle-btn-wrap').click(function () {

    $('aside').toggleClass('left-panel-show');
});
/*
 * manage side bar is defined inside common.js
 */
$('.toggle-btn-wrap').click(function () {
    if (!($('body').hasClass('body-sm'))) {
        manageSideBar('minimized');
    } else {
        manageSideBar('maximized');
    }
    $('body').toggleClass('body-sm');
});
//Toggle Button js Close 

//Action Tool tip js Start
$(".user-td").click(function (e) {
    e.stopPropagation();
    $(".user-call-wrap").hide();
    $(this).find(".user-call-wrap").show();
});
$("body").click(function () {
    $(".user-call-wrap").hide();
});

//Action Tool tip js Close  


//Filter Show or hide JS
$("#filter-side-wrapper").click(function (e) {
    e.stopPropagation();
    $(".filter-wrap").addClass("active");
});
$("body").click(function (e) {
    if (!$(e.target).is('.filter-wrap, .filter-wrap *')) {
        $(".filter-wrap").removeClass("active");
    }
});
$(".flt_cl").click(function (e) {
    $(".filter-wrap").removeClass("active");
});
//Filter Show or hide JS Close


//Select Picker Js Start
$('.selectpicker').selectpicker({
});

//Select Picker Js Close


$(".srch-box").keyup(function () {
    var char_length = $(this).val().length;
    if (char_length > 0) {
        $(".srch-close-icon").addClass("show-srch");
    } else {
        $(".srch-close-icon").removeClass("show-srch");
    }
});

$(".srch-close-icon").click(function () {
    $(".srch-close-icon").removeClass("show-srch");
    $(".srch-box").val('');

})