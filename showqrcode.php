<!DOCTYPE html>
<html>
<head>
    <title>Dynamic QR Code Generator</title>
    <style>
        /* Ensure the body and HTML take up the full viewport height */
        html, body {
            height: 100%;
            background-color: #d7eefe; /* Set background color to blue */
        }

        body {
            margin: 0; /* Remove default body margin */
            display: flex; /* Use flexbox to center content vertically */
            justify-content: center; /* Center content horizontally */
            align-items: center; /* Center content vertically */
        }

        .qr-container {
            text-align: center;
            max-width: 80%; /* Limit maximum width of container */
            background-color: white; /* Set background color of container to white */
            padding: 20px; /* Add padding for better spacing */
            border-radius: 10%; /* Make the container circle */
           /* border: 2px solid black; /* Add border */
            overflow: hidden; /* Ensure the border does not overflow */
        }

        .qr-container p {
            font-size: 32px;
            font-weight: bold;
        }

        /* Make the QR code image responsive */
        .qr-container img {
            max-width: 100%;
            height: auto;
            width: 250px; /* Set the width of the image to make it bigger */
            height: 250px; /* Set the height of the image to make it bigger */
        }
    </style>
</head>
<body>
    <div class="qr-container">
        <p>Please scan to take the queue number</p>
        <!-- Set the width of the QR code image to 100% -->
        <img id="qrCodeImage" src="generate_qr.php" alt="QR Code">
    </div>

    <script>
        // Function to update the QR code image every 5 seconds
        function updateQRCode() {
            var qrImage = document.getElementById('qrCodeImage');
            qrImage.src = 'generate_qr.php?' + new Date().getTime(); // Add a timestamp to the URL to force refresh
        }

        // Call the updateQRCode function every 5 seconds
        setInterval(updateQRCode, 5000); // 5000 milliseconds = 5 seconds

        // Open in new popup window
        window.onload = function() {
            window.open(window.location.href, "_blank", "width=600,height=400");
        };
    </script>
</body>
</html>
