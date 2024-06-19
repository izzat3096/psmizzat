<?php
session_start();

// Check if doktor_id is set in the session
if (!isset($_SESSION['doktor_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit;
}

include('includes/dbconnection.php');

// Retrieve patient details from the POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $patient_name = $_POST['patient_name'];
    
    // Delete the waiting number for the patient
    $deleteWaitingNumberQuery = "DELETE FROM waiting_numbers WHERE PatientID = '$patient_id'";
    if (mysqli_query($conn, $deleteWaitingNumberQuery)) {
        // No need to echo anything here
    } else {
        echo "Error deleting waiting number: " . mysqli_error($conn);
    }
} else {
    // Redirect to dashboard.php if accessed without proper POST data
    header("Location: dashboard.php");
    exit;
}

// Close the database connection
mysqli_close($conn);

// Redirect to dashboard.php after executing the code
header("Location: dashboard.php");
exit;
?>
