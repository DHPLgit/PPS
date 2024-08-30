<?php


function encrypt_url_segment($segment)
{
    $key = '53cur3_3ncryp710n_k3y'; 

    $encrypted_segment = urlencode(base64_encode(openssl_encrypt($segment, 'aes-256-cbc', $key, 0, substr($key, 0, 16))));

    return $encrypted_segment;
}

function decrypt_url_segment($segment)
{
    $key = '53cur3_3ncryp710n_k3y';  

    $decrypted_segment = openssl_decrypt(base64_decode(urldecode($segment)), 'aes-256-cbc', $key, 0, substr($key, 0, 16));

    return $decrypted_segment;
}
