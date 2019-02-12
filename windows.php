<html>
        <head>
                <title>Claranet SSL</title>
                <link rel="stylesheet" type="text/css" href="style.css" />
        </head>
        <body>
            <div id="title-wrapper"><div id="title-content">
                <h1>Windows Certificate</h1>
            </div></div>
            <div id="page-wrapper"><div id="page-content">

<?php

define("OPEN_SSL_CONF_PATH", "./openssl.cnf");

$sscert = $_POST['sscert'];
$privkey = $_POST['privkey'];
$passphrase = "1qazxsw2$!";
$today = date("Ymd-His");
$pkcs12file = "pkcs12/$today.p12";


$configargs = array(
	    'config' => 'openssl.cnf',
            'digest_alg' => 'sha1',
            'x509_extensions' => 'v3_ca',
	    'req_extensions'   => 'v3_req',
	    'private_key_bits' => 666,
	    'private_key_type' => OPENSSL_KEYTYPE_RSA,
	    'encrypt_key' => false,
            );

// Convert to x509 format. pkcs12 operations require x509 formatted file.
openssl_x509_export($sscert, $crtx509);
openssl_pkcs12_export($sscert, $pkcs12out, $privkey, $passphrase);
openssl_pkcs12_export_to_file($sscert, $pkcs12file, $privkey, $passphrase);

echo "<h2>.p12 certificate status</h2>";

echo "<p>Download <a href=\"$pkcs12file\">$today.p12</a> for use on Windows Servers. (Right click, save target as...)</p> <p>Password is: <strong><tt>CL4R4N3T</tt></strong></p>";

// Show any errors that occurred here
echo "<h3>Errors</h3><p>These can probably be ignored.</p><pre>";
while (($e = openssl_error_string()) !== false) {
     echo $e . "\n";
     }
echo "</pre>";

?>

</div></div></body>
</html>
