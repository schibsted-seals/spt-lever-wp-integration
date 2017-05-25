<?php

add_action('wp_ajax_my_action', 'my_action');
add_action('wp_ajax_nopriv_my_action', 'my_action');

function my_action()
{
    $target = str_replace('https://jobs.lever.co/', '',$_POST['url']);
    $target2 = str_replace('/apply', '',$target);
    $url = 'https://api.lever.co/v0/postings/'.$target2.'?key=jPybXOwV3EDkVxi9pGne';
    $filename = $_FILES['file']['name'];
    $filedata = $_FILES['file']['tmp_name'];
    $filesize = $_FILES['file']['size'];
    $filetype = $_FILES['file']['type'];
    $cfile = curl_file_create($filedata, $filetype, $filename);
        if ($filedata != '') {
        $headers = array("Content-Type:multipart/form-data"); // cURL headers for file uploading
        $postfields = array("resume" => $cfile,
            "name" => $_POST['name'],
            "email" => $_POST['email'],
            "phone" => $_POST['phone'],
            "org" => $_POST['org'],
            "urls[LinkedIn]" =>  $_POST['LinkedIn'],
            "urls[Github]" =>  $_POST['Github'],
            "urls[Other]" =>  $_POST['Other'],
            "urls[Twitter]" =>  $_POST['Twitter'],
            "urls[Portfolio]" =>  $_POST['Portfolio'],
            "comments" => $_POST['comments'],
            "source" => $_POST['source'],
            "silent" => "true");
        $ch = curl_init();
        $options = array(
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_URL => $url,
            CURLOPT_HEADER => true,
            CURLOPT_POST => 1,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $postfields,
            CURLOPT_INFILESIZE => $filesize,
            CURLOPT_RETURNTRANSFER => true
        ); // cURL options
        curl_setopt_array($ch, $options);
        curl_exec($ch);
        if (!curl_errno($ch)) {
            $info = curl_getinfo($ch);
            if ($info['http_code'] == 200)
                $errmsg = "File uploaded successfully";
        } else {
            $errmsg = curl_error($ch);
        }
        curl_close($ch);
        echo $errmsg;
    }

    wp_die(); // this is required to terminate immediately and return a proper response
}