<?php
// Include database connection
include('includes/dbconnection.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve appointment details from the form
    $date = $_POST['date'];
    $time = $_POST['time'];
    $doctor = $_POST['doctor'];
    $notes = $_POST['notes'];
    $patient_id = $_POST['patient_id'];

    // Insert appointment details into the database
    $insertQuery = "INSERT INTO appointments (date, time, doctor, notes, patient_id) VALUES ('$date', '$time', '$doctor', '$notes', '$patient_id')";
    $insertResult = mysqli_query($conn, $insertQuery);

    if ($insertResult) {
        // Close the database connection
        mysqli_close($conn);
        // Print JavaScript code to open popup window with success message
        echo '<script>
            alert("Appointment was created successfully.");
            window.close();
        </script>';
        // Exit the script
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request.";
}

// Close the database connection
mysqli_close($conn);
?>
