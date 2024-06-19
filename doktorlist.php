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

// Fetch all users from the database
$query = "SELECT * FROM Tbl_Doktor";
$result = mysqli_query($conn, $query);

// Check if the query was successful
if ($result) {
    $Tbl_Doktor = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    echo "Error: " . mysqli_error($conn);
    $Tbl_Doktor = []; // Set to an empty array if there's an error
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    
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
            <h1>Doctor List</h1>
            <form action="doktorregister.php" method="get">
                <button class="add-button">Add</button>
            </form>
        </div>
        <table class="medicine-list">
            <tr>
                <th>Id Doktor</th>
                <th>Nama</th>
                <th>No Tel</th>
                <th>Role</th>
                <th>Email</th>
                <th>Warganegara</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            <?php
            foreach ($Tbl_Doktor as $Tbl_Doktor) {
                echo "<tr>";
                echo "<td>" . $Tbl_Doktor['Id_Doktor'] . "</td>";
                echo "<td>" . $Tbl_Doktor['Nama_Doktor'] . "</td>";
                echo "<td>" . $Tbl_Doktor['NoTel_Doktor'] . "</td>";
                echo "<td>" . $Tbl_Doktor['role'] . "</td>";
                echo "<td>" . $Tbl_Doktor['Email'] . "</td>";
                echo "<td>" . $Tbl_Doktor['Warganegara'] . "</td>";
                echo "<td>
                        <form action='editmedicines.php' method='get'>
                            <button type='submit' class='edit-button'>Edit</button>
                        </form>
                      </td>";
                echo "<td>
                            <button type='submit' class='edit-button'>Delete</button>
                      </td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</div>


</body>
</html>
