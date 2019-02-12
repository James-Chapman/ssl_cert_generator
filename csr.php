<html>
        <head>
                <title>SSL</title>
                <link rel="stylesheet" type="text/css" href="style.css" />
        </head>
        <body>
            <div id="title-wrapper"><div id="title-content">
                <h1>CSR and Private Key</h1>
            </div></div>
            <div id="page-wrapper"><div id="page-content">

<?php

define("OPEN_SSL_CONF_PATH", "./openssl.cnf");
define("OPEN_SSL_CERT_DAYS_VALID", $_POST['days']);

$domain = $_POST['domain'];
$company = $_POST['company'];
$city = $_POST['city'];
$county = $_POST['county'];
$country = $_POST['country'];
$email = $_POST['email'];
$today = date("Ymd-His");

print ("CN - Common Name: <tt>$domain</tt> <br /> O - Organization Name: <tt>$company</tt> <br /> OU - Organization Unit Name: <tt>&lt;blank&gt;</tt> <br /> L - Locality Name: <tt>$city</tt> <br /> S - State Or Province Name: <tt>$county</tt> <br /> C - Country Name: <tt>$country</tt> <br /> E - Email Address: <tt>$email</tt> <br /><br /><br />");


$configargs = array(
	    'config' => 'openssl.cnf',
            'digest_alg' => 'sha1',
            'x509_extensions' => 'v3_ca',
	    'req_extensions'   => 'v3_req',
	    'private_key_bits' => 666,
	    'private_key_type' => OPENSSL_KEYTYPE_RSA,
	    'encrypt_key' => false,
            );

$dn = array(
	    "countryName" => $country,
	    "stateOrProvinceName" => $county,
	    "localityName" => $city,
	    "organizationName" => $company,
	    "organizationalUnitName" => $company,
	    "commonName" => $domain,
	    "emailAddress" => $email
	    );

// Generate a new private (and public) key pair
$privateconfig = array('private_key_bits' => 4096);
$privkey = openssl_pkey_new($privateconfig);

// Generate a certificate signing request
$csr = openssl_csr_new($dn, $privkey, $configargs);

// Set CSR and Key files
$csroutfile = "certs/" . $today . "-" . $domain . ".csr";
$pkeyoutfile = "certs/" . $today . "-" . $domain . ".key";
$csroutfileShort = $today . "-" . $domain . ".csr";
$pkeyoutfileShort = $today . "-" . $domain . ".key";

// Export CSR and Key
openssl_csr_export($csr, $csrout);
openssl_csr_export_to_file($csr, $csroutfile);
openssl_pkey_export($privkey, $pkeyout);
openssl_pkey_export_to_file($privkey, $pkeyoutfile);

// Print CSR and Key on Screen
echo "<strong>CSR and Key available as files <a href=\"./certs/\">here</a>.</strong><br /><br />";
echo "<h3>CSR (<a href=\"$csroutfile\" title=\"$csroutfile\">$csroutfileShort</a>)</h3><pre>$csrout</pre>";
echo "<h3>Private Key (<a href=\"$pkeyoutfile\" title=\"$pkeyoutfile\">$pkeyoutfileShort</a>)</h3><pre>$pkeyout</pre>";


// Show any errors that occurred here
echo "<h3>Errors</h3><p>These can probably be ignored.</p><pre>";
while (($e = openssl_error_string()) !== false) {
     echo $e . "\n";
     }
echo "</pre>";

?>

</div></div></body>
</html>
