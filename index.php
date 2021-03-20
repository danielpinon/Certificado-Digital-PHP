<?php
echo 'OK';
echo "<table>";
echo "<tr><th>Chave</th><th>valor</th></tr>";
foreach ($_SERVER as $key => $value) {
    switch (true) {
        //case strpos($key,"HTTP") == 0:
        case preg_match("/^SSL_CLIENT(.*)$/", $key):
            echo "<tr><td>", $key, "</td><td><pre>", $value, "</pre></td></tr>", PHP_EOL;
            break;
        default:
            break;
    }
}

if ($_SERVER['SSL_CLIENT_CERT']) {
    $pub_key = openssl_pkey_get_public($_SERVER['SSL_CLIENT_CERT']);
    $keyData = openssl_pkey_get_details($pub_key);
    echo "<tr><td>Public Key Resource</td><td><pre>", $pub_key, "</pre></td></tr>", PHP_EOL;
    echo "<tr><td>Bits</td><td><pre>", $keyData["bits"], "</pre></td></tr>", PHP_EOL;
    echo "<tr><td>PUBLIC KEY</td><td><pre>", $keyData["key"], "</pre></td></tr>", PHP_EOL;
    echo "<tr><td>RSA N</td><td><pre>", $keyData["rsa"]['n'], "</pre></td></tr>", PHP_EOL;
    echo "<tr><td>RSA E</td><td><pre>", $keyData["rsa"]['e'], "</pre></td></tr>", PHP_EOL;
    echo "<tr><td>Type</td><td><pre>", $keyData['type'], "</pre></td></tr>", PHP_EOL;
    echo "<tr><td>Raw Key Data Key</td><td><pre>";
    var_dump($keyData);
    echo "</pre></td></tr>", PHP_EOL;

    openssl_pkey_free($pub_key);

    $cert = openssl_x509_read($_SERVER['SSL_CLIENT_CERT']);
    $certData = openssl_x509_parse($cert);
    foreach ($certData as $k => $d) {
        echo "<tr><td>", strtoupper($k), "</td><td><pre>";
        switch (gettype($d)) {
            case "string":
                echo $d;
                break;
            default:
                var_dump($d);
                break;
        }

        echo "</pre></td></tr>", PHP_EOL;
    }
    echo "<tr><td>Certificate Raw</td><td><pre>";
    var_dump($certData);
    echo "</pre></td></tr>", PHP_EOL;
    openssl_x509_free($cert);
}


echo "</table>";