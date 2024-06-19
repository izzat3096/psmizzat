<?php
session_start();

// Check if doktor_id is set in the session
if (!isset($_SESSION['doktor_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit;
}
include('includes/dbconnection.php');

$doktor_id = $_SESSION['doktor_id'];

// Check if the medicine number is provided in the URL for deletion
if (isset($_GET['delete_medicine_no'])) {
    $delete_medicine_no = $_GET['delete_medicine_no'];

    // Delete medicine from the database
    $delete_query = "DELETE FROM medicines_list WHERE medicine_id = '$delete_medicine_no'";
    $delete_result = mysqli_query($conn, $delete_query);

    // Check if the query was successful
    if ($delete_result) {
        header("Location: listofmedicines.php"); // Redirect to the medicine list after deletion
        exit;
    } else {
        echo "Error deleting medicine: " . mysqli_error($conn);
    }
}

// Retrieve medicines from the database
$query = "SELECT * FROM medicines_list";
$result = mysqli_query($conn, $query);

// Check if the query was successful
if ($result) {
    $medicines = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    echo "Error: " . mysqli_error($conn);
    // Initialize $medicines as an empty array to avoid errors
    $medicines = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Medicines</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
    /* Reset styles */
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f4f4;
        margin: 0;
    }

    .container {
        display: flex;
        height: 100%;
    }

    .main-content {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    max-width: 1200px; /* Set a maximum width to avoid stretching on larger screens */
    margin: auto; /* Center the main content */
    width: 100%; /* Set the width to 100% of the viewport width */
    height: 100vh; /* Set the height to 100% of the viewport height */
    overflow: auto; /* Add scrollbar if content exceeds container dimensions */
}

    .medicine-list {
        margin-bottom: 20px;
        border-collapse: collapse;
        width: 100%;
        text-align: center; /* Center text within the table */
    }

    .medicine-list th,
    .medicine-list td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center; /* Ensure text alignment is centered */
    }

    .medicine-list th {
        background-color: #e9ecef;
        color: #333;
    }

    .medicine-list tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    .add-button {
        background-color: #d7eefe;
        color: black;
        padding: 10px 20px;
        border: none;
        cursor: pointer;
        font-size: 16px;
        float: center; /* Align the button to the right */
    }

    .edit-button,
    .delete-button {
        cursor: pointer;
        text-align: center;
    }

    .header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.header h1 {
    margin: 0;
    text-align: center; /* Center-align the text */
    flex: 1; /* Let the text take up available space */
}

.add-button {
    background-color: #d7eefe;
    color: black;
    padding: 10px 20px;
    border: none;
    cursor: pointer;
    font-size: 16px;
}

</style>

</head>
<body>

<div class="container">
    <?php include('includes/sidebar.php'); ?>
    <div class="main-content">
        
        <div class="header">
        <h1>List of Medicines</h1>
        <form action="addmedicines.php" method="get"><button class="add-button">Add</button></form>

        </div>
        <table class="medicine-list">
            <tr>
                <th>No</th>
                <th>Medicine name</th>
                <th>Usage</th>
                <th>Dos</th>
                <th>ML</th>
                <th>Tablet</th>
                <th>Per Day</th>
                <th>Duration</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            <?php
            // Display the medicines in the table
            foreach ($medicines as $medicine) {
                echo "<tr>";
                echo "<td>{$medicine['medicine_id']}</td>";
                echo "<td>{$medicine['MedicineName']}</td>";
                echo "<td>{$medicine['Usage']}</td>";
                echo "<td>{$medicine['Dos']}</td>";
                echo "<td>{$medicine['ML']}</td>";
                echo "<td>{$medicine['Tablet']}</td>";
                echo "<td>{$medicine['PerDay']}</td>";
                echo "<td>{$medicine['duration']}</td>";
                echo "<td>
                        <form action='editmedicines.php' method='get'>
                            <input type='hidden' name='medicine_no' value='{$medicine['medicine_id']}'>
                            <button type='submit' class='edit-button'>Edit</button>
                        </form>
                      </td>";
                echo "<td>
                        <button onclick='confirmDelete({$medicine['medicine_id']})' class='delete-button'>Delete</button>
                      </td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</div>

</body>
</html>
