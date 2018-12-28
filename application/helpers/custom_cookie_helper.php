<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');


if ( ! function_exists("validate_admin_cookie") ) {
    function validate_admin_cookie($cookieName, $tableName) {
        $CI = &get_instance();
        $CI->load->model("CommonModel");
        $sessionFields = [
            "id",
            "name",
            "email"
        ];
        $dataFields = [
            "admin_id",
            "admin_name",
            "admin_email"
        ];
        
        $cookieCookieData = $CI->CommonModel->validateCookie($cookieName, $tableName, $sessionFields, $dataFields);

        return $cookieCookieData;
    }
}