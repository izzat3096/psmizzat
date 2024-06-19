<?php
session_start();

// Check if doktor_id is set in the session
if (!isset($_SESSION['doktor_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit;
}
include('includes/dbconnection.php');

$doktor_id = $_SESSION['doktor_id']; 
$doktor_nama = $_SESSION['doktor_nama']; 

$idDoktor = ""; // Initialize the variable for Id_Doktor

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $namaDoktor = $_POST["namaDoktor"];
    $noTelDoktor = $_POST["noTelDoktor"];
    $role = $_POST["role"];
    $email = $_POST["email"];
    $warganegara = $_POST["warganegara"];
    $kataLaluan = $_POST["kataLaluan"];
    $noKp = $_POST["noKp"];

    // Additional input validation can be added here

    include('includes/dbconnection.php');

    // Ensure successful database connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Use prepared statement to prevent SQL injection
    $hashedPassword = password_hash($kataLaluan, PASSWORD_BCRYPT);

    // Ensure successful password hashing
    if (!$hashedPassword) {
        die("Password hashing failed.");
    }

    // Prepare the SQL statement with placeholders
    $stmt = $conn->prepare("INSERT INTO Tbl_Doktor (Id_Doktor, Nama_Doktor, NoTel_Doktor, role, Email, Warganegara, Kata_Laluan, NoKp) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    // Generate a random 5-digit number for Id_Doktor if not provided in the form
    $idDoktor = empty($_POST["idDoktor"]) ? mt_rand(10000, 99999) : $_POST["idDoktor"];

    // Bind parameters to the prepared statement
    $stmt->bind_param("ssssssss", $idDoktor, $namaDoktor, $noTelDoktor, $role, $email, $warganegara, $hashedPassword, $noKp);

    // Execute the query and check for errors
    if ($stmt->execute()) {
        $_SESSION['username'] = $namaDoktor;
        header("Location: doktorlist.php"); // Redirect to doktorlist.php after successful registration
        exit(); // Ensure script stops execution after redirect
    } else {
        echo "Failed to register user. Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    // Generate a random 5-digit number for Id_Doktor if not in a form submission
    $idDoktor = mt_rand(10000, 99999);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctor Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            max-width: 600px;
            margin: auto;
            overflow: hidden;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            width: 100%;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-top: 20px;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"],
        select {
            width: 100%;
            height: 40px;
            padding: 5px;
            box-sizing: border-box;
            margin-top: 8px;
            margin-bottom: 20px;
        }

        select {
            height: 40px;
        }

        input[type="submit"] {
            width: 100%;
            height: 40px;
            background: #333;
            color: #fff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background: #555;
        }

        /* Responsive Styling */
        @media (max-width: 600px) {
            .container {
                width: 90%;
            }

            form {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Doctor Registration</h2>
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
            <label for="idDoktor">Id Doktor:</label>
            <input type="text" name="idDoktor" value="<?php echo $idDoktor; ?>" readonly><br><br>

            <label for="namaDoktor">Nama Doktor:</label>
            <input type="text" name="namaDoktor" required><br><br>

            <label for="noTelDoktor">No. Telefon Doktor:</label>
            <input type="text" name="noTelDoktor" required><br><br>

            <label for="role">Peranan Doktor:</label>
            <select name="role" required>
                <option value="staff">Staff</option>
                <option value="admin">Admin</option>
            </select><br><br>

            <label for="email">Email:</label>
            <input type="email" name="email" required><br><br>

            <label for="warganegara">Warganegara:</label>
            <input type="text" name="warganegara" required><br><br>

            <label for="noKp">No. Kad Pengenalan:</label>
            <input type="text" name="noKp" required><br><br>

            <label for="kataLaluan">Kata Laluan:</label>
            <input type="password" name="kataLaluan" required><br><br>

            <input type="submit" value="Register">
        </form>
    </div>
</body>
</html>
