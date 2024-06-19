<?php
// Include the qrlib.php library
include('phpqrcode-master/qrlib.php');

// Base URL to the waiting number retrieval page (or any URL you want)
$baseUrl = "https://example.com/scan";

// Generate unique identifier for the QR code image
$identifier = uniqid();

// Generate expiration timestamp (5 seconds from now)
$expirationTimestamp = time() + 10;

// URL with the unique identifier, expiration timestamp, and patient ID
$qrCodeData = $baseUrl . "?id=" . $identifier . "&exp=" . $expirationTimestamp;

// Generate QR code image
QRcode::png($qrCodeData, "qr_codes/{$identifier}.png"); // Save QR code image with the identifier as the filename

// Set the appropriate content type header
header("Content-type: image/png");

// Output the generated QR code image
readfile("qr_codes/{$identifier}.png");
?>
