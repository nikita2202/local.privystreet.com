<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Function name: generateRandomString
 * Description-Generating random access number of length 10 
 * with the combination of int,lowercase char and uppercase char.
 * @return string 
 */
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

/**
 * Function name: generateRandomString
 * Description-Generating random access number of length 10 
 * with the combination of numbers.
 * @return string 
 */
function generateRandomNumberString($length = 10) {
    $numbers = '0123456789';
    $numbersLength = strlen($numbers);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $numbers[rand(0, $numbersLength - 1)];
    }
    return $randomString;
}

function encrypt_decrypt($action, $string) {

    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'horo$sc@op';
    $secret_iv = 'sc@horo&op';
    // hash
    $key = hash('sha256', $secret_key);
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if ($action == 'encrypt') {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if ($action == 'decrypt') {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}

function s3_uplode($filename, $temp_name) {
    $name = explode('.', $filename);
    $ext = array_pop($name);
    $name = 'budfie' . uniqid() . strtotime("now") . '.' . $ext;

    $imgdata = $temp_name;
    $s3 = new S3();
    $uri = $name;
    $bucket = BUCKET;

    $result = $s3->putObjectFile($imgdata, $bucket, $uri, S3::ACL_PUBLIC_READ);
    $url = S3_URL . $uri;
    return $url;
}

function curl($url) {
    $ch = curl_init();  // Initialising cURL
    curl_setopt($ch, CURLOPT_URL, $url);    // Setting cURL's URL option with the $url variable passed into the function
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); // Setting cURL's option to return the webpage data
    curl_setopt($ch, CURLOPT_ENCODING, "");
    $data = curl_exec($ch); // Executing the cURL request and assigning the returned data to the $data variable
    curl_close($ch);    // Closing cURL
    return $data;   // Returning the data from the function
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

function xml_url($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);    // get the url contents
    $data = curl_exec($ch); // execute curl request
    curl_close($ch);
    return $data;   // Returning the data from the function
}

?>
