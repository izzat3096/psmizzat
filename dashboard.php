<?php
header("refresh: 5");
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

// Function to update medication status
function updateMedicationStatus($conn) {
    // Query medicines that are active and have expired
    $query = "UPDATE Medicine m
              INNER JOIN medicines_list ml ON m.MedicineID = ml.medicine_id
              SET m.status = CASE 
                                WHEN TIMESTAMPADD(MINUTE, ml.duration, m.start) <= NOW() THEN 'not active' 
                                ELSE m.status 
                              END
              WHERE m.status = 'active' AND TIMESTAMPADD(MINUTE, ml.duration, m.start) <= NOW()";
    
    if (mysqli_query($conn, $query)) {
        echo "";
    } else {
        echo "Error updating medication statuses: " . mysqli_error($conn);
    }
}

// Retrieve doktor_id and doktor_nama from the session
$doktor_nama = isset($_SESSION['doktor_nama']) ? $_SESSION['doktor_nama'] : '';

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

// Fetch waiting numbers for patients with appointments with the current doctor
$waitingNumbersWithAppointmentsQuery = "SELECT wn.waiting_number, wn.PatientID, pl.Name, wn.received
                                        FROM waiting_numbers wn
                                        INNER JOIN patients_list pl ON wn.PatientID = pl.PatientID
                                        INNER JOIN appointments a ON wn.PatientID = a.patient_id
                                        WHERE a.doctor = ?
                                        ORDER BY wn.received ASC";
$stmt = $conn->prepare($waitingNumbersWithAppointmentsQuery);

if (!$stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}

// Bind parameters and execute the query
$stmt->bind_param("s", $_SESSION['doktor_id']);
$stmt->execute();
$waitingNumbersWithAppointmentsResult = $stmt->get_result();

$waitingNumbersWithAppointments = [];
while ($row = $waitingNumbersWithAppointmentsResult->fetch_assoc()) {
    $waitingNumbersWithAppointments[] = $row;
}

// Close the statement
$stmt->close();

// Fetch waiting numbers for patients along with their appointments and the doctor in charge
$waitingNumbersQuery = "SELECT wn.waiting_number, wn.PatientID, pl.Name, wn.received, a.doctor
                        FROM waiting_numbers wn
                        INNER JOIN patients_list pl ON wn.PatientID = pl.PatientID
                        LEFT JOIN appointments a ON wn.PatientID = a.patient_id
                        WHERE (a.doctor = ? OR a.doctor IS NULL)
                        ORDER BY wn.received ASC";
$stmt = $conn->prepare($waitingNumbersQuery);

if (!$stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}

// Bind parameters and execute the query
$stmt->bind_param("s", $_SESSION['doktor_id']);
$stmt->execute();
$waitingNumbersResult = $stmt->get_result();

$waitingNumbers = [];
while ($row = $waitingNumbersResult->fetch_assoc()) {
    $waitingNumbers[] = $row;
}

// Close the statement
$stmt->close();


// Get the queue number for the first row
$currentQueueNumber = isset($waitingNumbers[0]['waiting_number']) ? $waitingNumbers[0]['waiting_number'] : null;

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $queue_number = isset($_POST['queue_number']) ? $_POST['queue_number'] : '';

    // Update the waiting number in the database with the user's ID
    $update_query = "UPDATE waiting_numbers SET current = 1 WHERE waiting_number = '$queue_number'";
    $update_result = mysqli_query($conn, $update_query);
}

// Call the function to update medication statuses
updateMedicationStatus($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Dashboard</title>
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
            margin: 0; /* Add this line to remove default body margin */
        }

        .container {
            display: flex;
            height: 100vh;
            overflow: hidden;
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




.current-patient {
    display: flex;
    flex-direction: column; /* Change flex direction to column */
    justify-content: center; /* Center items vertically */
    align-items: center; /* Center items horizontally */
    margin-bottom: 20px;
    padding: 20px;
    background-color: #d7eefe;
    color: black;
    border-radius: 8px;
}

.current-patient i {
    font-size: 2em;
}

.current-patient form button {
    padding: 8px 15px;
    border: none;
    background-color: #4caf50;
    color: white;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin-top: 10px; /* Add some top margin to the button for spacing */
}

.current-patient form button:hover {
    background-color: #45a049;
}


.current-patient button {
    margin-top: 10px; /* Add some top margin to the button for spacing */
}


        .queue-number {
            font-size: 2em;
            font-weight: bold;
            color: black;
        }

        .patient-waiting-list table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .patient-waiting-list th,
        .patient-waiting-list td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .patient-waiting-list th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .patient-waiting-list td button {
            padding: 8px 15px;
            border: none;
            background-color: #4caf50;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .patient-waiting-list td button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php include('includes/sidebar.php'); ?>
        <div class="main-content">
            <div class="current-patient">
                <div>
                    <i class="fas fa-user-md"></i> <!-- Font Awesome icon for doctor -->
                    <span>Queue Number: <span class="queue-number"><?php echo $currentQueueNumber; ?></span></span>
                </div>
                <form action="" method="post">
                    <input type="hidden" name="queue_number" value="<?php echo $currentQueueNumber; ?>">
                    <button type="submit">Call Patient</button>
                </form>
            </div>

            <div class="patient-waiting-list">
                <table>
                    <thead>
                        <tr>
                            <th>Queue No</th>
                            <th>Patient's Name</th>
                            <th>Patient's ID</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
    <?php foreach ($waitingNumbers as $waitingNumber): ?>
        <?php
            // Check if the patient has an appointment session
            $patientId = $waitingNumber['PatientID'];
            $checkQuery = "SELECT * FROM appointments WHERE patient_id = ? OR patient_id = ?";
            $stmt = mysqli_prepare($conn, $checkQuery);
            if (!$stmt) {
                die("Error in preparing statement: " . mysqli_error($conn));
            }
            mysqli_stmt_bind_param($stmt, 'ii', $patientId, $patientId);
            mysqli_stmt_execute($stmt);
            $checkResult = mysqli_stmt_get_result($stmt);
            
            if (mysqli_num_rows($checkResult) > 0) {
                // Patient has an appointment
                $appointmentStatus = "Appointment ";
                // while ($row = mysqli_fetch_assoc($checkResult)) {
                //     $appointmentStatus .=  $row['time'];
                //     // If there are multiple appointments, concatenate them with a comma
                //     if (mysqli_num_rows($checkResult) > 1) {
                //         $appointmentStatus .= ", ";
                //     }
                // }
                // Change button color to red and redirect to appointmentlist.php
                $buttonColor = 'style="background-color: red;"';
                $buttonAction = 'action="appointmentlist.php"';
            } else {
                $appointmentStatus = "Consult";
                // Keep button color as default and action as consultation.php
                $buttonColor = '';
                $buttonAction = 'action="consultation.php"';
            }
        ?>
        <tr>
            <td><?php echo $waitingNumber['waiting_number']; ?></td>
            <td><?php echo $waitingNumber['Name']; ?></td>
            <td><?php echo $waitingNumber['PatientID']; ?></td>
            <td>
                <form <?php echo $buttonAction; ?> method="post">
                    <input type="hidden" name="patient_id" value="<?php echo $waitingNumber['PatientID']; ?>">
                    <input type="hidden" name="patient_name" value="<?php echo $waitingNumber['Name']; ?>">
                    <button type="submit" <?php echo $buttonColor; ?>><?php echo $appointmentStatus; ?></p></button>
                    <!-- <td><p><?php echo $appointmentStatus; ?></p></td> -->
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>



                </table>
            </div>
        </div>
    </div>
</body>
</html>
