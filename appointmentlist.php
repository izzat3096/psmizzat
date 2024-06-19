<?php
session_start();

// Check if doktor_id is set in the session
if (!isset($_SESSION['doktor_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit;
}

// Include the database connection file
include('includes/dbconnection.php');

// Ensure the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all appointments for the doctor
$appointmentsQuery = "SELECT * FROM appointments WHERE doctor = ?";
$stmt = $conn->prepare($appointmentsQuery);

// Check if the prepared statement was created successfully
if (!$stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}

// Bind parameters and execute the query
$stmt->bind_param("s", $_SESSION['doktor_id']); // Assuming 'doctor' column is of type string
$stmt->execute();
$result = $stmt->get_result();

$appointments = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }
}

// Close the first statement
$stmt->close();

// Fetch patient names for the appointments
foreach ($appointments as &$appointment) {
    $patientId = $appointment['patient_id'];
    $patientNameQuery = "SELECT Name FROM patients_list WHERE PatientID = ?";
    $stmt = $conn->prepare($patientNameQuery);
    if (!$stmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
    $stmt->bind_param("s", $patientId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $appointment['patient_name'] = $row['Name'];
    }
    // Close the statement
    $stmt->close();
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment List</title>
    <!-- Add your CSS links here -->
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


    .appointment-list {
        margin-bottom: 20px;
        border-collapse: collapse;
        width: 100%;
        text-align: center; /* Center text within the table */
    }

    .appointment-list th,
    .appointment-list td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center; /* Ensure text alignment is centered */
    }

    .appointment-list th {
        background-color: #e9ecef;
        color: #333;
    }

    .appointment-list tr:nth-child(even) {
        background-color: #f8f9fa;
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
    padding: 10px 10px;
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
                <h1>Appointment List</h1>
            </div>
            <table class="appointment-list">
                <thead>
                    <tr>
                        <th>Id Patient</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Doctor</th>
                        <th>Notes</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $appointment): ?>
                        <tr>
                            <td><?php echo $appointment['patient_id']; ?></td>
                            <td><?php echo $appointment['date']; ?></td>
                            <td><?php echo $appointment['time']; ?></td>
                            <td><?php echo $appointment['doctor']; ?></td>
                            <td><?php echo $appointment['notes']; ?></td>
                            <td>
                                <form action="consultation_appointments.php" method="post">
                                    <input type="hidden" name="patient_id" value="<?php echo $appointment['patient_id']; ?>">
                                    <input type="hidden" name="patient_name" value="<?php echo $appointment['patient_name']; ?>">
                                    <button type="submit">Consult</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
