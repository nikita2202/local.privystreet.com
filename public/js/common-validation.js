
//-----------------------------------------------------------------------
/**
 *@Description Here is the methods starts for the form validations in admin using jquery validator. 
 * 
 */
$(document).ready(function () {


    /**
     * @name validate add app version form
     * @description This method is used to validate add app version form.
     * 
     */
    $("#loginform").validate({
        errorClass: "alert-danger",
        rules: {
            email: {
                required: true,
                email: true,
            },
            password: {
                required: true,
                minlength: 8,
            },
        },
        messages: {
            email: {
                required: string.loginemail,

            },
            password: {
                required: string.loginpassword,
                minlength: string.passwordMinLength,
            },
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    

    $('input').keypress(function (e) {
        var inp = $.trim($(this).val()).length;
        if (inp == 0 && e.which === 32) {
            return false;
        }
    });

});

function validatepassword() {
    var password = $.trim($('#password').val());
    var cnfpassword = $.trim($('#cnfpassword').val());

    var flag = 0;
    if (password.length == 0 || password.length < 8) {
        $('.passwordErr').css({opacity: 1});
        $('.passwordErr').text(string.passwordErr);
    } else {
        $('.passwordErr').css({opacity: 0});
        $('.passwordErr').text('');
        flag++;
    }
    if (cnfpassword.length == 0 || (password != cnfpassword)) {
        $('.cnfpasswordErr').text(string.cnfPassowrdErr);
        $('.cnfpasswordErr').css({opacity: 1});
    } else {
        $('.cnfpasswordErr').css({opacity: 0});
        $('.cnfpasswordErr').text('');
        flag++;
    }
    if (flag == 2) {
        return true;
    } else {
        return false;
    }

}
