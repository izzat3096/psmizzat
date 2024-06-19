<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        /* Reset styles */
        * {
            box-sizing: border-box;
            padding: 0;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
        }

        .sidebar {
            background-color: #2c3e50; /* Dark blue background */
            color: white;
            width: 250px;
            display: flex;
            flex-direction: column;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            height: 100vh; /* Set sidebar height to 100% of viewport height */
            overflow-y: auto; /* Enable vertical scrolling if needed */
        }

        .profile {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 2em;
        }

        .profile img {
            border-radius: 50%;
            margin-bottom: 10px;
            width: 70px;
            height: 70px;
        }

        .profile p {
            margin: 0;
            font-weight: bold;
        }

        .navigation a {
            text-decoration: none;
            color: white;
            padding: 10px 0;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .navigation a:not(:last-child) {
            border-bottom: 1px solid #1e2c3b; /* Darker blue border */
        }

        /* Add styles for the hover state */
        .navigation a:hover {
            background-color: #34495e; /* Darker blue on hover */
        }

        /* Add space between icon and text */
        .navigation a i {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="profile">
            <img src="logo1.jfif" alt="Doctor Avatar"><br>
            <p>Hello Dr <?php echo $_SESSION['doktor_nama']; ?></p><br>
            <a href="login.php"><i class="fa fa-sign-out" style="font-size:30px; color:white;"></i></a>
        </div>
        <nav class="navigation">
            <a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a>
            <a href="appointmentlist.php"><i class="fa fa-calendar"></i> Appointment</a>
            <a href="patientlist.php"><i class="fa fa-users"></i> Patient List</a>
            <a href="patientreports.php"><i class="fa fa-file-text"></i> Patient Reports</a>
            <a href="listofmedicines.php"><i class="fa fa-medkit"></i> List of Medicines</a>
            <?php
                // Check if the user is an admin to display the button
                if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
                    echo '<a href="doktorlist.php"><i class="fa fa-user-md"></i> Manage Doctor</a>';
                }
            ?>
            <a href="showqrcode.php" target="_blank"><i class="fa fa-qrcode"></i> Show QR Code</a>

        </nav>
    </div>
</body>
</html>
