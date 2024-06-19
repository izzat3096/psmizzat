<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if doctor ID and password are provided
    if (!empty($_POST["login_doktor_id"]) && !empty($_POST["login_password"])) {
        $login_doktor_id = $_POST["login_doktor_id"];
        $login_password = $_POST["login_password"];

        include('includes/dbconnection.php');

        // Use a prepared statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT Id_Doktor, Nama_Doktor, Kata_Laluan, role FROM Tbl_Doktor WHERE Id_Doktor = ?");
        $stmt->bind_param("s", $login_doktor_id);
        $stmt->execute();
        $stmt->bind_result($doktor_id, $doktor_nama, $hashedPassword, $userRole);
        $stmt->fetch();

        // Verify the password using password_verify
        if (password_verify($login_password, $hashedPassword)) {
            session_start();
            $_SESSION['doktor_id'] = $doktor_id;
            $_SESSION['doktor_nama'] = $doktor_nama;
            $_SESSION['user_role'] = $userRole;
            header("Location: dashboard.php");
            exit(); // Make sure to exit after redirecting
        } else {
            $error_message = "Login failed. Please check your credentials.";
        }

        $stmt->close();
        $conn->close();
    } else {
        $error_message = "Please provide both Doctor ID and Password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klinik ARA Login</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: Arial, sans-serif;
            background-image: url('background-image.jpg'); /* Replace with your actual image path */
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px; /* Adjust as needed */
        }

        .login-form {
            display: flex;
            flex-direction: column;
        }

        .login-form label {
            text-align: left;
            margin-bottom: 5px;
        }

        .login-form input[type="text"],
        .login-form input[type="password"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .login-form button {
            padding: 10px;
            background-color: #d7eefe;
            color: black;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }

        .login-form button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Klinik ARA</h1>
        <?php if ($error_message): ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form class="login-form" method="post" action="login.php">
            <label for="login_doktor_id">Doctor ID</label>
            <input type="text" id="doktor_id" name="login_doktor_id" required>

            <label for="login_password">Password</label>
            <input type="password" id="password" name="login_password" required>

            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
