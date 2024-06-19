<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit;
}

include('includes/dbconnection.php');


$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve patient information and password from the form
    $name = $_POST['name'];
    $patient_id = $_POST['patient_id'];
    $gender = $_POST['gender'];
    $phone_no = $_POST['phone_no'];
    $nationality = $_POST['nationality'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

    // Insert patient information and password into the database
    $query = "INSERT INTO patients_list (Name, PatientID, Gender, PhoneNo, Nationality, Password) 
              VALUES ('$name', '$patient_id', '$gender', '$phone_no', '$nationality', '$password')";

    $result = mysqli_query($conn, $query);

    if ($result) {
        // Redirect to the desired page after successful insertion
        header("Location: listofpatients.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Patient</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-field {
            margin-bottom: 10px;
        }

        .form-field input {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-field input:focus {
            outline: none;
            border-color: #007bff;
        }

        .form-button {
            width: 100%;
            background-color: #d7eefe;
            color: black;
            padding: 10px 20px;
            margin: 10px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Add New Patient</h2>
    <form action="" method="post">
        <div class="form-field">
            <input type="text" name="name" placeholder="Name">
        </div>
        <div class="form-field">
            <input type="text" name="patient_id" placeholder="Patient ID">
        </div>
        <div class="form-field">
            <input type="text" name="gender" placeholder="Gender">
        </div>
        <div class="form-field">
            <input type="text" name="phone_no" placeholder="Phone No">
        </div>
        <div class="form-field">
            <input type="text" name="nationality" placeholder="Nationality">
        </div>
        <!-- Add a password field -->
        <div class="form-field">
            <input type="password" name="password" placeholder="Password">
        </div>
        <!-- Add a submit button to submit the form -->
        <button type="submit" class="form-button">Add</button>
    </form>
</div>

</body>
</html>
