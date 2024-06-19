<?php
session_start();

// Check if doktor_id is set in the session
if (!isset($_SESSION['doktor_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit;
}
include('includes/dbconnection.php');

$doktor_id = $_SESSION['doktor_id'];

// Fetch all patients from the database
$query = "SELECT * FROM patients_list";
$result = mysqli_query($conn, $query);

// Check if the query was successful
if ($result) {
    $patients = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    echo "Error: " . mysqli_error($conn);
    $patients = []; // Set to an empty array if there's an error
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient List</title>
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

    .patient-table{
        margin-bottom: 20px;
        border-collapse: collapse;
        width: 100%;
        text-align: center; /* Center text within the table */
    }

    .patient-table th,
    .patient-table td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center; /* Ensure text alignment is centered */
    }

    .patient-table th {
        background-color: #e9ecef;
        color: #333;
    }

    .patient-table tr:nth-child(even) {
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
        <h1>Patient List</h1>
        <form action="tambahpesakit.php" method="get">
                    <button class="add-button">Add</button>
                </form>


            </div>
            <table class="patient-table">
                    <tr>
                        <th>Name</th>
                        <th>Patient ID</th>
                        <th>Gender</th>
                        <th>Phone No</th>
                        <th>Nationality</th>
                        <th>More Information</th>
                    </tr>
                    <?php
                    foreach ($patients as $patient) {
                        echo "<td>" . $patient['Name'] . "</td>";
                        echo "<td>" . $patient['PatientID'] . "</td>";
                        echo "<td>" . $patient['Gender'] . "</td>";
                        echo "<td>" . $patient['PhoneNo'] . "</td>";
                        echo "<td>" . $patient['Nationality'] . "</td>";
                        echo "<td><form action='treatmenthistory.php' method='get'>";
                        echo "<input type='hidden' name='patient_id' value='" . $patient['PatientID'] . "'>";
                        echo "<button class='info-button'>More information</button></form></td>";
                        echo "</tr>";
                    }
                    ?>
            </table>
            <br>
        </div>
    </div>
</body>
</html>
