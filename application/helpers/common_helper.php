<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('pr')) {
    function pr($d) {
        echo "<pre>";
        print_r($d);
        echo "</pre>";
        exit();
    }
}
function load_views($customView, $data = array()) {
    $CI = &get_instance();
    $CI->load->view('templates/header', $data);
    $CI->load->view($customView, $data);
    $CI->load->view('templates/footer', $data);
}

function load_outer_views($customView, $data = array()){
    $CI = &get_instance();
    $CI->load->view('/templates/outer-header', $data);
    $CI->load->view($customView, $data);
    $CI->load->view('/templates/outer-footer', $data);
}

function getConfig($uploadPath, $acptFormat, $maxSize = 3000, $maxWidth = 1024, $maxHeight = 768, $encryptName = TRUE) {
    $config = [];
    $config['upload_path'] = $uploadPath;
    $config['allowed_types'] = $acptFormat;
    $config['max_size'] = $maxSize;
    $config['max_width'] = $maxWidth;
    $config['max_height'] = $maxHeight;
    $config['encrypt_name'] = $encryptName;
    return $config;
}

function create_access_token($user_id = '1', $email = 'dummyemail@gmail.com') {
    $session_private_key = chr(mt_rand(ord('a'), ord('z'))) . substr(md5(time()), 1);
    $session_public_key = encrypt($user_id . $email, $session_private_key, true);
    $access_token['private_key'] = base64_encode($session_private_key);
    $access_token['public_key'] = base64_encode($session_public_key);
    return $access_token;
}

function encrypt($text, $salt = 'A3p@pI#%!nVeNiT@#&vNaZiM', $isBaseEncode = true) {
    if ($isBaseEncode) {
        return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
    } else {
        return trim(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
    }
}

function decrypt($text, $salt = 'A3p@pI#%!nVeNiT@#&vNaZiM') {
    return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $salt, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
}

function datetime() {
    return date('Y-m-d H:i:s');
}

function encryptDecrypt($string, $type = 'encrypt') {

    if ($type == 'decrypt') {
        $enc_string = decrypt_with_openssl($string);
    }
    if ($type == 'encrypt') {
        $enc_string = encrypt_with_openssl($string);
    }
    return $enc_string;
}

function decrypt_with_openssl($string, $urldecode = true) {
    $obj = new OpenSSLEncrypt($string);
    $obj->key = OPEN_SSL_KEY;
    $string = str_replace(array('Beee', 'Kiii', 'Per'), array('/', '=', '%'), $string);
    $string = rawurldecode($string);
    $dcrypt = explode(":", $string);
    if (count($dcrypt) != 2) {
        return false;
    }

    $decryptedData = $obj->decrypt($dcrypt[0], $dcrypt[1]);


    return $decryptedData;
}

function encrypt_with_openssl($string, $urlencode = true) {
    $obj = new OpenSSLEncrypt($string);
    $obj->key = OPEN_SSL_KEY;
    $iv = $obj->initializationVector;
    $encryptedData = $obj->encrypt() . ":" . $iv;
    $encryptedData = rawurlencode($encryptedData);
    $encryptedData = str_replace(array('/', '=', '%'), array('Beee', 'Kiii', 'Per'), $encryptedData);
    return $encryptedData;
}

function show404($error = "", $url = 'admin') {
    $error = (empty($error)) ? 'Invalid Request' : $error;
    echo $error;
    die('<a href="/admin/admin"><br>Click here to redirect</a>');
}


function post_curl($url, $newData) {
    try {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://rest.cricketapi.com/rest/v2/auth/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $newData
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            return $response;
        }
    } catch (Exception $ex) {
        echo $ex->getMessage();
        die;
    }
}


  

?>
