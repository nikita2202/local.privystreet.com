$(window).load(function () {
    $('#loader').hide();
});

$(document).ready(function () {
    // $(".spinloader").show();
    $('input').keypress(function (e) {
        var inp = $.trim($(this).val()).length;
        if (inp == 0 && e.which === 32) {
            return false;
        }
    });

    $('.dispLimit').change(function () {
        var filter = {};
        var pageUrl = $('#pageUrl').val();
        filter = $('#filterVal').val();
        filter = JSON.parse(filter);
        delete filter['perPage'];
        var limit = $('.dispLimit').find(":selected").val();

        if (limit != undefined) {
            filter['limit'] = limit;
        }

        var queryParams = $.param(filter)
        window.location.href = pageUrl + '?' + queryParams;
    })

    $('.searchlike').keyup(function (e) {

        if (e.which === 13) {
            var filter = {};
            filter = $('#filterVal').val();
            var pageUrl = $('#pageUrl').val();
            var searchlike = $.trim($(this).val());
            filter = JSON.parse(filter);
            delete filter['searchlike'];
            if (searchlike == 0) {
                alert('Please type something to search');
                return false;
            }
            if (searchlike != undefined) {
                filter['searchlike'] = searchlike;
            }
            var queryParams = $.param(filter)
            window.location.href = pageUrl + '?' + queryParams;
        }
    });

    $('.searchCloseBtn').click(function (e) {
        var filter = {};
        filter = $('#filterVal').val();
        var pageUrl = $('#pageUrl').val();
        filter = JSON.parse(filter);
        delete filter['searchlike'];
        var filterLen = $.keyCount(filter);
        if (filterLen == 0) {
            window.location.href = pageUrl
        } else {
            var queryParams = $.param(filter)
            window.location.href = pageUrl + '?' + queryParams;
        }

    });

    /*
     * Apply Filter  Notification
     */

    $('.applyfilter').click(function () {

        var filter = {};
        filter = $('#filterVal').val();
        var pageUrl = $('#pageUrl').val();

        filter = JSON.parse(filter);

        delete filter['platform'];
        delete filter['startDate'];
        delete filter['endDate'];

        var platform = $('.platform').find(":selected").val();
        var startDate = $('.startDate').val();
        var endDate = $('.endDate').val();

        if (platform != undefined && platform.length != 0) {
            filter['platform'] = platform;
        }
        if (startDate != undefined && startDate.length != 0) {
            filter['startDate'] = startDate;
        }
        if (endDate != undefined && endDate.length != 0) {
            filter['endDate'] = endDate;
        }

        var filterLen = $.keyCount(filter);
        if (filterLen == 0) {
            alert('Please select a filter');
            return false;
        }

        var queryParams = $.param(filter)

        window.location.href = pageUrl + '?' + queryParams;

    });
    /*
     * Apply Filter User
     */

    $('.applyFilterUser').click(function () {

        var filter = {};
        filter = $('#filterVal').val();
        var pageUrl = $('#pageUrl').val();

        filter = JSON.parse(filter);

        delete filter['status'];
        delete filter['startDate'];
        delete filter['endDate'];

        var status = $('.status').find(":selected").val();
        var type = $('.type').find(":selected").val();
        var country = $('.country').find(":selected").val();
        var startDate = $('.startDate').val();
        var endDate = $('.endDate').val();

        if (status != undefined && status.length != 0) {
            filter['status'] = status.replace(/^\s+|\s+$/gm,'');
        }
        if (type != undefined && type.length != 0) {
            filter['type'] = type.replace(/^\s+|\s+$/gm,'');
        }
        if (startDate != undefined && startDate.length != 0) {
            filter['startDate'] = startDate;
        }
        if (endDate != undefined && endDate.length != 0) {
            filter['endDate'] = endDate;
        }
        if (country != undefined && country.length != 0) {
            filter['country'] = country;
        }

        var filterLen = $.keyCount(filter);
        if (filterLen == 0) {
            alert('Please select a filter');
            return false;
        }
        var queryParams = $.param(filter)

        window.location.href = pageUrl + '?' + queryParams +"#filterTab";

    });
    /*
     * Sport filter
     */
    $('.applyFilterSport').click(function () {

        var filter = {};
        filter = $('#filterVal').val();
        var pageUrl = $('#pageUrl').val();

        filter = JSON.parse(filter);

        delete filter['status'];
        delete filter['startDate'];
        delete filter['endDate'];
        delete filter['matchStartDate'];
        delete filter['matchEndDate'];

        var status = $('.status').val();
        var matchStartDate = $('.matchStartDate').val();
        var matchEndDate = $('.matchEndDate').val();
        var startDate = $('.startDate').val();
        var endDate = $('.endDate').val();

        if (status != undefined && status.length != 0) {
            filter['status'] = status.replace(/^\s+|\s+$/gm,'');
        }
        if (startDate != undefined && startDate.length != 0) {
            filter['startDate'] = startDate;
        }
        if (endDate != undefined && endDate.length != 0) {
            filter['endDate'] = endDate;
        }
        if (matchStartDate != undefined && matchStartDate.length != 0) {
            filter['matchStartDate'] = matchStartDate;
        }
        if (matchEndDate != undefined && matchEndDate.length != 0) {
            filter['matchEndDate'] = matchEndDate;
        }

        var filterLen = $.keyCount(filter);
        if (filterLen == 0) {
            alert('Please select a filter');
            return false;
        }
        var queryParams = $.param(filter)

        window.location.href = pageUrl + '?' + queryParams;

    });

    $('.resetfilter').click(function () {
        var pageUrl = $('#pageUrl').val();
        window.location.href = pageUrl;
    });

    $('.resendPush').click(function () {
        var notiToken = $('#notiToken').val();
        //console.log(notiToken);
        $.ajax({
            type: "get",
            url: baseUrl + "admin/notifications/resendNotification",
            dataType: 'json',
            data: {notiToken: notiToken},
            success: function (respdata) {
                console.log(respdata);
//                if (respdata.code == 200) {
//                    window.location.reload();
//                }
            }
        });
    });

    $('.editPush').click(function () {
        var notiToken = $('#notiToken').val();
        window.location.href = baseUrl + 'admin/notification/edit?id=' + notiToken;
    });

    $('.exportCsv').click(function () {
        var filter = {};
        filter = $('#filterVal').val();
        var pageUrl = $('#pageUrl').val();
        filter = JSON.parse(filter);
        filter['export'] = 1;
        var queryParams = $.param(filter);
        window.location.href = pageUrl + '?' + queryParams;
    });
});
$.extend({
    keyCount: function (o) {
        if (typeof o == "object") {
            var i, count = 0;
            for (i in o) {
                if (o.hasOwnProperty(i)) {
                    count++;
                }
            }
            return count;
        } else {
            return false;
        }
    }
});
function changepassword(oldpassword, newpassword) {
    $.ajax({
        type: "post",
        url: "/ajax/changepassword",
        dataType: 'json',
        data: {password: newpassword, oldpassword: oldpassword},
        success: function (respdata) {
            if (respdata.code == 200) {
                customalert(respdata.msg);
                $('#oldpassword').val('');
                $('#newpassword').val('');
                $('#cnfpassword').val('');
            } else {
                customalert(respdata.msg);
            }
        }
    });
}

function resetpassword(token, password) {
    $.ajax({
        type: "post",
        url: baseUrl + "ajax/reset",
        dataType: 'json',
        data: {token: token, 'password': password},
        success: function (respdata) {
            if (respdata.code == 200) {
                $('.password').val('');
            }
            customalert(respdata.msg);
        }
    });
}


function manageSideBar(action) {
    $.ajax({
        type: "get",
        url: baseUrl + "req/manage-sidebar",
        dataType: 'json',
        data: {action: action, csrf_token: csrf_token},
        success: function (respData) {
            if (respData.code == 200) {
                csrf_token = respData.csrf;
            }
        }
    });
}

function sendforgotemail(email) {
    $.ajax({
        type: "post",
        url: "/ajax/forgot",
        dataType: 'json',
        data: {email: email},
        success: function (respdata) {
            if (respdata.code == 200) {
                $('#email').val('');
            }
            customalert(respdata.msg);
        }
    });
}

var controller = window.location.pathname.split('/')[3];
var action = window.location.pathname.split('/')[4];

var _validFileExtensionsImage = [".jpg", ".png", '.jpeg', '.bmp'];
var _validFileExtensionsDoc = [".doc", ".docx", ".pdf"];
var _validFileExtensionsGif = [".gif"];
var _validFileExtensionsGift = [".jpg", ".png", '.jpeg', '.bmp',".gif"];

function ValidateSingleInput(oInput, _validFileExtensions, id) {
    if (oInput.type == "file") {
        var sFileName = oInput.value;
        if (sFileName.length > 0) {
            var blnValid = false;
            for (var j = 0; j < _validFileExtensions.length; j++) {
                var sCurExtension = _validFileExtensions[j];
                if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                    blnValid = true;
                    break;
                }
            }

            if (!blnValid) {
                //alert("Sorry, " + sFileName + " is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
                $('#image-error').empty().text("Sorry, this file is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
                oInput.value = "";
                return false;
            }
        }
    }
    return true;
}


/**
 *
 * @ code to validate uploaded image.
 */
var loadFile_signup = function (event, id, oInput) {
    $('#image-error').html('');
    var return_data = ValidateSingleInput(oInput, _validFileExtensionsImage, id);
    if (return_data) {
        $('#img1').val('1');
        var output = document.getElementById(id);
        $('#' + id).css('background-image', 'url(' + URL.createObjectURL(event.target.files[0]) + ')');
    }
};

/**
 *
 * @ code to validate uploaded image.
 */
var loadFile_signup2 = function (event, id, oInput) {
    $('#image-error2').html('');
    var return_data = ValidateSingleInput(oInput, _validFileExtensionsImage, id);
    if (return_data) {
         $('#img2').val('2');
        var output = document.getElementById(id);
        $('#' + id).css('background-image', 'url(' + URL.createObjectURL(event.target.files[0]) + ')');
    }
};

/**
 *
 * @ code to validate upload gif.
 */
var loadFile_gif = function (event, id, oInput) {
    $('#image-error').html('');
    var return_data = ValidateSingleInput(oInput, _validFileExtensionsGif, id);
    if (return_data) {
        var output = document.getElementById(id);
        $('#' + id).css('background-image', 'url(' + URL.createObjectURL(event.target.files[0]) + ')');
    }
};

/**
 *
 * @ code to validate upload gif.
 */
var loadFile_gift = function (event, id, oInput) {
    $('#image-error').html('');
    var return_data = ValidateSingleInput(oInput, _validFileExtensionsGift, id);
    if (return_data) {
        var output = document.getElementById(id);
        $('#' + id).css('background-image', 'url(' + URL.createObjectURL(event.target.files[0]) + ')');
    }
};


//$("#add").click(function (e)
//{
//    var image = $('#upload').val();
//    if (image == '')
//    {
//        $('#image-error').html('Please upload an image');
//        return false;
//    }
//});
//
//$("#addgif").click(function (e)
//{
//    var image = $('#upload').val();
//    if (image == '')
//    {
//        $('#image-error').html('Please upload an GIF');
//        return false;
//    }
//});


/**
 *
 *@description This is used to fadeout the ajax loader from page.
 *
 */

$(document).ready(function () {
    $('#pre-page-loader').hide();
});

/**
 * @description This code of jquery is used to show ckeditor on the add & edit cms pages.
 *
 */

$(document).ready(function () {
    if (controller === 'cms' && (action === 'add' || action === 'edit')) {
        CKEDITOR.replace('page_desc');
    }
});


/**
 * forgot password validation
 */

$('#forgot').click(function (event) {
    var arr = [];
    var f = 0;
    var email = $("#email").val();
    var emailptrn = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    var csrf_token = $("#csrf_token").val();
    if (email.length == 0)
    {
        document.getElementById('emails').innerHTML = "Please fill this field";
        $("#email_error").addClass("commn-animate-error");
        f = 1;
        arr.push('email')
        setTimeout(function () {
            $('#emails').text('');
        }, 5000);
    } else if (email.match(emailptrn)) {
        $.ajax({
            type: "post",
            url: domain + '/admin/Admin/check_email_avalibility',
            data: {'email': email, 'csrf_token': csrf_token},
            async: false,
            success: function (result) {
                console.log(result);

                var Obj = JSON.parse(result);
                if (Obj.code == 201) {
                    $('#incorrectemail').html('Email Exist');
                    $("#email_error").addClass("commn-animate-error");
                    setTimeout(function () {
                        $('#incorrectemail').text('');
                    }, 3000);

                } else if (Obj.code == 200) {
                    $('#errmsg2').html('Email Doesnot Exist');
                    $("#email_error").addClass("commn-animate-error");

                    setTimeout(function () {
                        $('#errmsg2').text('');
                    }, 3000);
                    f = 1;
                }
                $("#csrf_token").val(Obj.csrf_token);
            }
        });

    } else {
        document.getElementById('emails').innerHTML = "Please enter valid email";
        $("#email_error").addClass("commn-animate-error");
        f = 1;
        arr.push('email')
        setTimeout(function () {
            $('#emails').text('');
        }, 5000);
    }

    if (f == 1) {
        $('#' + arr[0]).focus();
        return false;
    } else {
        $('#forgetpass').submit();
    }
});

//$('#resetbtn').click(function (event) {
//
//    $('#resetform').submit();
//});


$(document).ready(function () {
    $('#resetbtn').click(function (event) {
        var arr = [];
        var f = 0;
        var new_pass = $("#new_pass").val();
        var con_pass = $("#con_pass").val();
        var token = $("#token").val();
        //  var passptr = /^(?=.*\d)(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z]).{8,}$/;
        var passptr = /^.{6,}$/;
        if (new_pass.length == 0)
        {
            document.getElementById('new_pass1').innerHTML = "Please fill this field";
            $("#passerror").addClass("commn-animate-error");
            return false;
        } else if (!new_pass.match(passptr)) {
            document.getElementById('new_pass1').innerHTML = "Password must be atleast 6 character";
            $("#passerror").addClass("commn-animate-error");
            return false;

        }
        if (con_pass.length == 0)
        {
            document.getElementById('con_pass1').innerHTML = "Please fill this field";
            $("#conpassreq").addClass("commn-animate-error");
            return false;
        } else if (!new_pass.match(passptr)) {
            document.getElementById('con_pass1').innerHTML = "Password must be atleast 6 characters ";
            $("#conpassreq").addClass("commn-animate-error");
            return false;

        }

        if (new_pass != con_pass) {
            document.getElementById('con_pass1').innerHTML = "Confirm password does not match";
            $("#conpassreq").addClass("commn-animate-error");
            return false;
        }

        if (f == 1) {
            $('#' + arr[0]).focus();
            return false;
        } else {
            //alert('hdfh');
            $('#resetform').submit();
        }

    }
    );
});

/*
 * Login validation
 */
$('#login').click(function (event) {

    var arr = [];
    var f = 0;
    var user_email = $("#useremail").val();
    var user_password = $("#userpassword").val();

    //var passptr = /^(?=.*\d)(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z]).{8,}$/;
    var emailptrn = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    var passptr = /^.{6,}$/;

    if (user_email.length == 0) {
        document.getElementById('email1').innerHTML = "Please fill this field";
        $("#email_error").addClass("commn-animate-error");
        return false;
    } else if (!user_email.match(emailptrn)) {
        document.getElementById('email1').innerHTML = "Please enter valid email";
        $("#email_error").addClass("commn-animate-error");
        return false;
    }
    if (user_password.length == 0) {
        document.getElementById('password1').innerHTML = "Please enter password";
        $("#passworderr").addClass("commn-animate-error");
        return false;

    } else if (!user_password.match(passptr)) {
        document.getElementById('password1').innerHTML = "Password must be atleast 6 characters";
        $("#passworderr").addClass("commn-animate-error");
        return false;
    }

    if (f == 1) {
        $('#' + arr[0]).focus();
        return false;

    } else {
        $('#adminlogin').submit();
    }
});


$('.removemessage').keyup(function () {
    $(".form-field-wrap").removeClass("commn-animate-error");
});

$('#email').keyup(function () {
    $("#emails").removeClass("commn-animate-error");
});

//-----------------------------------------------------------------------
$(function () {
    var flag = false;
    $('.filter').each(function () {

        if ($(this).val()) {
            flag = true;
        }

    });

    if (flag == false) {
        $('#filterbtn').prop('disabled', true);
        $('#resetbtn').prop('disabled', true);
    } else {
        $('#filterbtn').prop('disabled', false);
        $('#resetbtn').prop('disabled', false);
    }

});

$(document.body).on("change", ".filter", function () {
    var flag = false;
    $(this).each(function () {

        if ($(this).val()) {
            flag = true;
        }

    });
    if (flag == true) {
        $('#filterbtn').prop('disabled', false);
        $('#resetbtn').prop('disabled', false);
    } else {
        $('#filterbtn').prop('disabled', true);
        $('#resetbtn').prop('disabled', true);
    }
});

$(document.body).on("blur", ".filtertxt", function () {
    var flag = false;
    $(this).each(function () {

        if ($(this).val()) {
            flag = true;
        }

    });
    if (flag == true) {
        $('#filterbtn').prop('disabled', false);
        $('#resetbtn').prop('disabled', false);
    } else {
        $('#filterbtn').prop('disabled', true);
        $('#resetbtn').prop('disabled', true);
    }
});



//-----------------------------------------------------------------------
/**
 *
 * @name starting to implement jquery code when document loaded.
 *
 **/
$(function () {

    if ($('.search-box').val()) {

        if ($('.search-box').val().length) {
            $('.srch-close-icon').show();
            $('.search-icon').hide();
        } else {
            $('.srch-close-icon').hide();
            $('.search-icon').show();
        }
    }
});



$(document.body).on("keyup", ".search-box", function () {

    if ($(this).val().length > 0) {
        //$('.srch span').removeClass('search-icon');
        // $('.srch span').addClass('search-close-icon');
        $('.srch-close-icon').show();
        $('.search-icon').hide();
    } else {
        // $('.srch span').removeClass('search-close-icon');
        //$('.srch span').addClass('search-icon');
        $('.srch-close-icon').hide();
        $('.search-icon').show();
    }
});




/**
 *
 * @returns {undefined}
 *
 */
function pageCountForm() {
    $('#page_count_form').submit();
}
//-----------------------------------------------------------------------
/**
 * @name getStates
 * @description Function to get States list as per the country.
 */
//function getStates(value, id) {
//
//    var csrf = $('#csrfToken').val();
//    if (value) {
//        $.ajax({
//            method: "GET",
//            url: baseUrl + "admin/AjaxUtil/getStatesByCountry",
//            data: {id: value, csrf_token: csrf}
//        }).done(function (msg) {
//            msg = JSON.parse(msg);
//            $('#' + id).empty();
//            $("#" + id).append('<option value=>Select State</option>');
//            $.each(msg, function (i, item) {
//                $('#' + id).append($('<option>', {
//                    value: item.id,
//                    text: item.name,
//                }));
//            });
//            $('#' + id).selectpicker('refresh');
//        });
//    }
//}

//----------------------------------------------------------------------
/**
 * @name getCities
 * @description Function to get cities list as per the states.
 */
//function getCities(value, id) {
//
//    var csrf = $('#csrfToken').val();
//    if (value) {
//        $.ajax({
//            method: "GET",
//            url: baseUrl + "admin/AjaxUtil/getCityByState",
//            data: {id: value, csrf_token: csrf}
//        }).done(function (msg) {
//            msg = JSON.parse(msg);
//            $('#' + id).empty();
//            $("#" + id).append('<option value=>Select City</option>');
//            $.each(msg, function (i, item) {
//                $('#' + id).append($('<option>', {
//                    value: item.id,
//                    text: item.name,
//                }));
//            });
//            $('#' + id).selectpicker('refresh');
//        });
//    }
//}
//----------------------------------------------------------------------
/**
 * @name blockUser
 * @description This method is used to show block user modal.
 *
 */

function blockUser(type, status, id, url, msg, action) {

    $('#new_status').val(status);
    $('#new_id').val(id);
    $('#new_url').val(url);
    $('.modal-para').text(msg);
    $('#action').text(action);
    $('#for').val(type);
    $('#myModal-block').modal('show');
}

//----------------------------------------------------------------------
/**
 * @name blockUser
 * @description This method is used to show block user modal.
 *
 */

function logoutUser() {
    $('.modal-para').text('Are you sure you want to logout ?');
    $('#myModal-logout').modal('show');
}


//-----------------------------------------------------------------------
/**
 * @name changeStatusToBlock
 * @description This method is used to block the user.
 *
 */

function changeStatusToBlock(type, status, id, url) {
    var csrf = $('#csrf').val();
    $.ajax({
        method: "GET",
        url: baseUrl + url,
        data: {type: type, new_status: status, id: id, csrf_token: csrf},
        beforeSend: function () {
            $('#pre-page-loader').fadeIn();
            $('#myModal-block').modal('hide');
        },
        success: function (res) {
            $('#pre-page-loader').fadeOut();
            res = JSON.parse(res);
            var csrf = $('#csrf').val(res.csrf_token);
            if (res.code === 200) {

                if (status == 1) {
                    $('#error').empty().append(string.successPrefix + string.block_success + string.successSuffix);
                    $('#unblock_' + res.id).show();
                    $('#block_' + res.id).hide();
                    $('#status_' + res.id).empty().text('Blocked');
                } else {
                    $('#error').empty().append(string.successPrefix + string.unblock_success + string.successSuffix);
                    $('#block_' + res.id).show();
                    $('#unblock_' + res.id).hide();
                    $('#status_' + res.id).empty().text('Active');
                }
                $('.alert-success').fadeOut(5000);
            }
        },
        error: function (xhr) {
            alert("Error occured.please try again");
            $('#pre-page-loader').fadeOut();
        }
    });
}
//----------------------------------------------------------------------
/**
 * @name deleteUser
 * @description This method is used to show delete user modal.
 *
 */

function deleteUser(type, status, id, url, msg) {

    $('#new_status').val(status);
    $('#new_id').val(id);
    $('#new_url').val(url);
    $('.modal-para').text(msg);
    $('#for').val(type);
    $('#myModal-trash').modal('show');
}

/**
 * @name deleteUser
 * @description This method is used to show delete user modal.
 *
 */

function approveTalk(type, status, id, url, msg) {

    $('#new_status').val(status);
    $('#new_id').val(id);
    $('#new_url').val(url);
    $('.modal-para').text(msg);
  //   $('#action2').text(action);
    $('#for').val(type);
    $('#myModal-approve').modal('show');
}
//-----------------------------------------------------------------------
/**
 * @name changeStatusToDelete
 * @description This method is used to delte the user.
 *
 */

function changeStatusToDelete(type, status, id, url) {
    $.ajax({
        method: "GET",
        url: baseUrl + url,
        data: {type: type, new_status: status, id: id, csrf_token: csrf_token},
        beforeSend: function () {
            $('#pre-page-loader').fadeIn();
            $('#myModal-trash').modal('hide');
             $('#myModal-approve').modal('hide');
        },
        success: function (res) {
            $('#pre-page-loader').fadeOut();
            res = JSON.parse(res);
            if (res.code === 200) {
                window.location.reload();
            }
        },
        error: function (xhr) {
            alert("Error occured.please try again");
            $('#pre-page-loader').fadeOut();
        }
    });
}

//-----------------------------------------------------------------------
/**
 *@Description Here is the methods starts for the form validations in admin using jquery validator.
 *
 */
$(document).ready(function () {



    $.each($.validator.methods, function (key, value) {
        $.validator.methods[key] = function () {
            if (arguments.length > 0) {
                arguments[0] = $.trim(arguments[0]);
            }

            return value.apply(this, arguments);
        };
    });

    // error message
    $.validator.setDefaults({

        ignore: ':not(select:hidden, input:visible, textarea:visible):hidden:not(:checkbox)',

        errorPlacement: function (error, element) {
            if (element.hasClass('selectpicker')) {
                error.insertAfter(element);
            } else if (element.is(":checkbox")) {
                // element.siblings('span').hasClass('.check_error_msg').append(error);

                error.insertAfter($('.check_error_msg'));
            } else {
                error.insertAfter(element);
            }
            /*Add other (if...else...) conditions depending on your
             * validation styling requirements*/
        }
    });
    //custom methods

    $.validator.addMethod("noSpace", function (value, element) {
        return value == '' || value.trim().length != 0;
    }, "");

    $.validator.addMethod("searchText", function (value, element) {
        return value.replace(/\s+/g, '');
    }, "");

    /**
     * @name validate admin password change form
     * @description This method is used to validate admin change password form.
     *
     */
    $("#password_change_form").validate({
        errorClass: "alert-danger",
        rules: {
            oldpassword: {
                required: true,
            },
            password: {
                required: true,
                minlength: 6,
                maxlength: 50
            },
            confirm_password: {
                required: true,
                minlength: 6,
                maxlength: 50,
                equalTo: "#password"
            }
        },
        messages: {
            oldpassword: string.oldpasswordEmpty,
            password: string.newpasswordEmpty,
            confirm_password: {
                required: string.confirmpasswordEmpty,
                equalTo: string.passwordnotmatch
            }

        },
        submitHandler: function (form) {
            form.submit();
        }
    });


    /**
     * @name: add cms content form
     * @description: Thie function is used to validate admin add content form in cms.
     */
    $("#cms_add_form").validate({
        ignore: [],
        debug: false,
        errorClass: "alert-danger",
        rules: {
            title: {
                required: true,
            },
            page_desc: {
                required: function ()
                {
                    CKEDITOR.instances.page_desc.updateElement();
                },
            },
            status: {
                required: true,
            }
        },
        /* use below section if required to place the error*/
        errorPlacement: function (error, element)
        {
            if (element.attr("name") == "page_desc")
            {
                element.next().css('border', '1px solid #a94442');
                error.insertBefore("textarea#page_desc");
            } else {
                error.insertBefore(element);
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    /**
     * @name validate add app version form
     * @description This method is used to validate add app version form.
     *
     */
    $("#version_add_form").validate({
        errorClass: "alert-danger",
        rules: {
            name: {
                required: true,
            },
            title: {
                required: true,
            },
            desc: {
                required: true,
            },
            platform: {
                required: true,
            },
            update_type: {
                required: true,
            },
            current_version: {
                required: true,
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
    /**
     * @name common search for admin
     *
     *
     */
    $("#admin_search_form").validate({
        errorPlacement: function (error, element) {},
        rules: {
            search: {
                searchText: true,
                required: {
                    depends: function () {
                        if ($.trim($(this).val().length) == 0) {
                            //$('form#admin_search_from :input[type=text]').empty().css('border-color','#ff0000');
                            $('#searchuser').empty().css('border-color', '#a94442');
                            return false;
                        } else {
                            $.trim($(this).val());
                            return true;
                        }
                    }
                }
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    /**
     * @name validate admin password change form
     * @description This method is used to validate admin change password form.
     *
     */
    $("#editadminprofile1").validate({
        errorClass: "alert-danger",
        rules: {
            Admin_Name: {
                required: true,

            },
            email: {
                required: true,
                email: true
            },
            mobile_number: {
                required: true,
            },
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    /**
     * @name validate add movie form
     * @description This method is used to validate add movie form.
     *
     */
    $("#addmovie").validate({
        errorClass: "alert-danger",
        rules: {

            movie_name: {
                required: true,
            },
            type: {
                required: true
            },
            'genre[]': {
                required: true,
            },
            video: {
                required: true,
                url: true
            },
            'video_url[]': {
                url: true
            },
            url: {
                required: true,
                url: true
            },
            realease_date: {
                required: true,
            },
            duration: {
                required: true,
            },
            story: {
                required: true,
            },
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
    
     /**
     * @name validate add movie form
     * @description This method is used to validate add movie form.
     *
     */
    $("#addVersion").validate({
        errorClass: "alert-danger",
        rules: {

            name: {
                required: true,
            },
            title: {
                required: true
            },
            description: {
                required: true,
            },
            platform: {
                required: true,
            },
             update_type: {
                required: true,
            },
             current_version: {
                required: true,
            },
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    $("#addGif").validate({
        errorClass: "alert-danger",
        rules: {

            name: {
                required: true,
            },
           'cat[]': {
                required: true,
            },

        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    /**
     * @name validate add news form
     * @description This method is used to validate add news form.
     *
     */
    $("#addNews").validate({
        errorClass: "alert-danger",
        rules: {

            title: {
                required: true,
            },

            description: {
                required: true,
            },
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    /**
     * @name validate add Joke form
     * @description This method is used to validate add Joke form.
     *
     */
    $("#addJokes").validate({
        errorClass: "alert-danger",
        rules: {

            joke: {
                required: true,
            },
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    /**
     * @name validate add movie form
     * @description This method is used to validate add movie form.
     *
     */
    $("#addVideo").validate({
        errorClass: "alert-danger",
        rules: {
             video_url: {
               // url: true
            },
            'cat[]': {
                required: true,
            },

        },
        submitHandler: function (form) {
            form.submit();
        }
    });
    
     /**
     * @name validate add movie form
     * @description This method is used to validate add movie form.
     *
     */
    $("#addSportVideo").validate({
        errorClass: "alert-danger",
        rules: {

            video_url: {
                required: true,
                url: true
            },
             link: {
                required: true,
                url: true
            }, 
            type: {
                required: true
            },

        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    /**
     * @name validate add Joke form
     * @description This method is used to validate add Joke form.
     *
     */
    $("#addThought").validate({
        errorClass: "alert-danger",
        rules: {

            thought: {
                required: true,
            },
        },
        submitHandler: function (form) {
            form.submit();
        }
    });


    $("#addEventCat").validate({
        errorClass: "alert-danger",
        rules: {

            name: {
                required: true,
            },

        },
        submitHandler: function (form) {
            form.submit();
        }
    });

//    $('#add').click(function () {
//        var type = $(this).attr('greetingType');
//        msg = (type == 1) ? 'Please upload an GIF' : 'Please upload an image';
//        console.log($("#addGreeting").valid());
//    })

    $("#addgenre").validate({
        errorClass: "alert-danger",
        rules: {

            name: {
                required: true,
            },

        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    /**
     *@notification validation
     *
     */

    $("#notification_form").validate({
        errorClass: "alert-danger",
        rules: {

            title: {
                required: true,
            },
            platform: {
                required: true,
            },
            messagetext: {
                required: function ()
                {
                    CKEDITOR.instances.messagetext.updateElement();
                },
            },

        },
        messages: {
            title: "Please select some title",
            platform: "Please select platform",
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

});

function checkNotiValidation() {
    var title = $('#title').val();
    var platform = $(".platform option:selected").val();
    console.log(platform);
    if (title.length == 0) {
        $('.titleErr').text('Please enter title');
        return false;
    } else {
        $('.titleErr').text('');
    }
    if (platform.length == 0) {
        $('.platformErr').text('Please select platform');
        return false;
    } else {
        $('.platformErr').text('');
    }
    return true;
}


function CheckforNum(e) {
    console.log(e.keyCode);
    // Allow: backspace, delete, tab, escape, enter and  +
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 187, 190]) !== -1 || (e.keyCode == 65 && e.ctrlKey === true) || (e.keyCode == 86 && e.ctrlKey === true) || (e.keyCode == 67 && e.ctrlKey === true) || (e.keyCode == 88 && e.ctrlKey === true) || (e.keyCode >= 35 && e.keyCode <= 39)) {
        // let it happen, don't do anything
        return;
    }
    // Ensure that it is a number and stop the keypress
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
    }
}

/*
 * @Shivam Code Start
 */
$(document).ready(function () {
    $("#video").click(function(){
        if($(this).prop("checked")){
            $("#uploadVideo, #uploadUrl").removeClass("show-video");
            $("#uploadVideo").addClass("show-video");
        }
    });

    $("#url").click(function(){
        if($(this).prop("checked")){
            $("#uploadVideo, #uploadUrl").removeClass("show-video");
            $("#uploadUrl").addClass("show-video");
        }
    });

    $("#add").click(function (e)
{
    var image = $('#upload').val();
    if (image == '')
    {
        $('#image-error').html('Please upload an image');
        return false;
    }
});

$("#addgif").click(function (e)
{
    var image = $('#upload').val();
    if (image == '')
    {
        $('#image-error').html('Please upload an GIF');
        return false;
    }
});


    $("#addMultipleImage").click(function (e)
{
    
    var image = $('#upload-img').val();
    if (image == '')
    {
        $('#image-error').html('Please upload an image');
        return false;
    }
});


    /*
     * validate greeting form
     *
     */




    $("#add2").click(function (e) {
        e.preventDefault();
        var image = $('#upload').val();
        var name = $('#name').val();
        var type = $('#greetingType').val();
        var msg = (type == 1) ? 'Please upload an GIF' : 'Please upload an image';
        var flag = true;
        if (image == '') {
            flag = false;
            $('#image-error').html(msg);
        } else {
            $('#image-error').html('');
        }
        if (name.length == 0) {
            flag = false;
            $('.nameErr').text('Please enter name');
        } else {
            $('.nameErr').text('');
        }
        if (flag) {
            $('#addGreeting').submit();
        }
    });
//    $("input[name='image2']").change(function () {
//        var file = $(this).val();
//        var filetype = '.' + file.split('.').pop();
//        var type = $('#greetingType').val();
//        var msg = (type == 1) ? 'Please upload a valid GIF' : 'Please upload a valid Image';
//        var successType;
//        if (type == 1) {
//            successType = $.inArray(filetype, _validFileExtensionsGif);
//        } else {
//            successType = $.inArray(filetype, _validFileExtensionsImage);
//        }
//        if (successType == -1) {
//            $('#image-error').html(msg);
//            $(this).val('');
//        }
//
//    });

    $(".gift_image").change(function () {
        var file = $(this).val();
        var filetype = '.' + file.split('.').pop();
        if ($.inArray(filetype, _validFileExtensionsImage) == -1) {
            $('#image-error').html('Please upload a valid Image');
            $(this).val('');
        }
    });
});


$('#addSport').validate({
    errorClass: "alert-danger",
    rules: {
        title: {
            required: true,
        },
        venue: {
            required: true,
        },
        matchdate: {
            required: true,
        },
        matchtime: {
            required: true,
        },
        team1player1: {
            required: true,
        },
        team2player1: {
            required: true,
        },

    },
    messages: {
        title: "Please enter some title",
        venue: "Please enter the venue",
        matchdate: "Please enter match date",
        matchtime: "Please enter match time",
        team1player1: "Please enter player1",
        team2player1: "Please enter player2",
    },
    submitHandler: function (form) {
        form.submit();
    }
});

$('#addGift').validate({
    errorClass: "alert-danger",
    rules: {
        title: {
            required: true,
        },

    },
    messages: {
        title: {
            required: "Please enter title"
        },
        weburl: {
            required: "Please enter the url",
            url: "Please enter a valid url",
        }
    },
    submitHandler: function (form) {
//        console.log($('.gift_image').val());
        if ($('.gift_image').val() == '') {
            $('#image-error').text('Please select a image')
            $('#image-error').fadeIn();
            return false;
        } else {
            form.submit();
        }
    }
});

$(document).on('blur keyup', '.team_score', function () {
    if ($(this).val() !== '') {
        $(this).parent().next().find('.team_score').prop('disabled', false);
    } else {
        $(this).parent().next().find('.team_score').prop('disabled', true);
        $(this).parent().next().find('.team_score').val('');
    }
});

$(document).on('keydown', '.team_score', function () {
    if ($(this).val().length == 4) {
        return false;
    }
    return CheckforNum(event);
});


function validateSet() {
    var bool = true;
    $('.team_score').each(function () {
        if ($(this).val() !== '') {
            var setNo = $(this).attr('data-score');
            var scoreteam1 = $('.team1score' + setNo).val();
            var scoreteam2 = $('.team2score' + setNo).val();
            if (scoreteam1.length != 0 && scoreteam2.length == 0 || scoreteam2.length != 0 && scoreteam1.length == 0) {
                alert('Please fill score for both teams');
                bool = false;
                return false;
            }
        }
    });
    return bool;
}


 /**
     * @name validate add notification form
     * @description This method is used to validate add notification form.
     *
     */
    $("#addNotification").validate({
        errorClass: "alert-danger",
        rules: {

            title: {
                required: true,
            },
            message: {
                required: true
            },
            notif_type: {
                required: true,
            },
            platform: {
                required: true,
            },
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
    
    /**
     * @name validate add Hot Event form
     * @description This method is used to validate add event form.
     *
     */
    $("#addHotEvent").validate({
        errorClass: "alert-danger",
        rules: {

            name: {
                required: true,
            },
            event_date: {
                required: true
            },
            
            event_time: {
                required: true
            },
            description: {
                required: true,
            },
            location: {
                required: true,
            },
        url: {
            url: "Please enter a valid url",
        }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
    
    
    /**
     * @name validate add trending form
     * @description This method is used to validate add budfie trending form.
     *
     */
    $("#addTrending").validate({
        errorClass: "alert-danger",
        rules: {

            title: {
                required: true,
            },
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

 /**
     * @name validate add App Ads form
     * @description This method is used to validate add app ads form.
     *
     */
    $("#addAppAds").validate({
        errorClass: "alert-danger",
        rules: {
            app_ad_start_date: {
                required: true
            },
            
            app_ad_end_date: {
                required: true
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
   

/*
 *  Code End
 */


