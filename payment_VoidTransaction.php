<?php
require_once("config.php");

function ReadTransList()
{
    $filename = 'translist.json';
    $handle = fopen($filename, 'r');
    $contents = fread($handle, filesize($filename));
    fclose($handle);
    return $contents;
}

try {
    $data = ReadTransList();
    $basicauth = "Basic " . base64_encode($username . ":" . $password);

    $ch = curl_init($apiurl . 'pay/v3/void');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Authorization: $basicauth",
        "Content-Type: application/json",
        "Content-Length: " . strlen($data)));
    $result = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        echo "CURL Error #: $error";
    } else {
        echo '<pre>';
        $response = json_decode($result);
        print_r($response);
        echo '</pre>';
    }
} catch (Exception $e) {
    return $e->getMessage();
}


