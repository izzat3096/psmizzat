<?php
session_start();

// Check if doktor_id is set in the session
if (!isset($_SESSION['doktor_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit;
}
include('includes/dbconnection.php');

$doktor_id = $_SESSION['doktor_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $medicine_id = $_POST['medicine_id'];
    $medicine_name = $_POST['medicine_name'];
    $usage = $_POST['usage'];
    $dosage = $_POST['dosage'];
    $ml = $_POST['ml'];
    $tablet = $_POST['tablet'];
    $per_day = $_POST['per_day'];

    // Update medicine in the database using prepared statement
    $query = "UPDATE medicines_list SET
                MedicineName = ?,
                `Usage` = ?,
                Dos = ?,
                ML = ?,
                Tablet = ?,
                PerDay = ?
              WHERE medicine_id = ?";

    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param($stmt, "ssssssi", $medicine_name, $usage, $dosage, $ml, $tablet, $per_day, $medicine_id);

    $result = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    if ($result) {
        header("Location: listofmedicines.php"); // Redirect to the medicine list after a successful update
        exit;
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
} else {
    // Redirect if accessed without a POST request
    header("Location: listofmedicines.php");
    exit;
}
?>
