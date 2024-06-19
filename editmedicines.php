<?php
session_start();

// Check if doktor_id is set in the session
if (!isset($_SESSION['doktor_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit;
}
include('includes/dbconnection.php');

$doktor_id = $_SESSION['doktor_id'];

// Check if the medicine number is provided in the URL
if (!isset($_GET['medicine_no'])) {
    header("Location: listofmedicines.php"); // Redirect to the medicine list if no medicine number is provided
    exit;
}

$medicine_no = $_GET['medicine_no'];

// Retrieve medicine details from the database
$query = "SELECT * FROM medicines_list WHERE medicine_id = '$medicine_no'";
$result = mysqli_query($conn, $query);

// Check if the query was successful
if ($result) {
    $medicine = mysqli_fetch_assoc($result);
} else {
    echo "Error: " . mysqli_error($conn);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit a Medicine</title>
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
    <h2>Edit a Medicine</h2>
    <form action="updatemedicine.php" method="post">
        <div class="form-field">
            <input type="text" name="medicine_id" placeholder="Medicine No" value="<?php echo $medicine['medicine_id']; ?>" readonly>
        </div>
        <div class="form-field">
            <input type="text" name="medicine_name" placeholder="Medicine name" value="<?php echo $medicine['MedicineName']; ?>">
        </div>
        <div class="form-field">
            <input type="text" name="usage" placeholder="Usage" value="<?php echo $medicine['Usage']; ?>">
        </div>
        <div class="form-field">
            <input type="text" name="dosage" placeholder="Dos" value="<?php echo $medicine['Dos']; ?>">
        </div>
        <div class="form-field">
            <input type="text" name="ml" placeholder="ML" value="<?php echo $medicine['ML']; ?>">
        </div>
        <div class="form-field">
            <input type="text" name="tablet" placeholder="Tablet" value="<?php echo $medicine['Tablet']; ?>">
        </div>
        <div class="form-field">
            <input type="text" name="per_day" placeholder="Per Day" value="<?php echo $medicine['PerDay']; ?>">
        </div>
        <button type="submit" class="form-button">Save</button>
    </form>
    <form action="listofmedicines.php" method="get"><button class="form-button">Cancel</button></form>
</div>

</body>
</html>
