<?php
session_start();

// Check if doktor_id is set in the session
if (!isset($_SESSION['doktor_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit;
}
include('includes/dbconnection.php');

$doktor_id = $_SESSION['doktor_id'];

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve medicine information from the form
    $medicine_no = $_POST['medicine_no'];
    $medicine_name = $_POST['medicine_name'];
    $usage = $_POST['usage'];
    $dosage = $_POST['dosage'];
    $ml = $_POST['ml'];
    $tablet = $_POST['tablet'];
    $per_day = $_POST['per_day'];
    $duration = $_POST['duration'];

    // Insert medicine information into the database
    $query = "INSERT INTO medicines_list (medicine_id, MedicineName, `Usage`, Dos, ML, Tablet, PerDay, duration) 
              VALUES ('$medicine_no', '$medicine_name', '$usage', '$dosage', '$ml', '$tablet', '$per_day', '$duration')";

    $result = mysqli_query($conn, $query);

    if ($result) {
        // Redirect to the desired page after successful insertion
        header("Location: listofmedicines.php");
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
    <title>Add New Medicines</title>
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
    <h2>Add New Medicines</h2>
    <form action="" method="post">
        <div class="form-field">
            <input type="text" name="medicine_no" placeholder="Medicine No">
        </div>
        <div class="form-field">
            <input type="text" name="medicine_name" placeholder="Medicine name">
        </div>
        <div class="form-field">
            <input type="text" name="usage" placeholder="Usage">
        </div>
        <div class="form-field">
            <input type="text" name="dosage" placeholder="Dos">
        </div>
        <div class="form-field">
            <input type="text" name="ml" placeholder="ML">
        </div>
        <div class="form-field">
            <input type="text" name="tablet" placeholder="Tablet">
        </div>
        <div class="form-field">
            <input type="text" name="per_day" placeholder="Per Day">
        </div>
        <div class="form-field">
            <input type="text" name="duration" placeholder=" Duration">
        </div>
        <!-- Add a submit button to submit the form -->
        <button type="submit" class="form-button">Add</button>
    </form>
</div>

</body>
</html>
