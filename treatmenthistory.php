<?php
session_start();

// Check if doktor_id is set in the session
if (!isset($_SESSION['doktor_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit;
}
include('includes/dbconnection.php');

$doktor_id = $_SESSION['doktor_id'];

// Check if a patient ID is provided in the URL
if (isset($_GET['patient_id'])) {
    // Sanitize the input to prevent SQL injection
    $patient_id = mysqli_real_escape_string($conn, $_GET['patient_id']);

    // Fetch patient details from the database based on the provided ID
    $query = "SELECT * FROM patients_list WHERE PatientID = '$patient_id'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $patient = mysqli_fetch_assoc($result);

        // Fetch consultation and medicine details for the patient
        $consultationQuery = "SELECT * FROM consultation WHERE PatientID = '$patient_id'";
        $consultationResult = mysqli_query($conn, $consultationQuery);
    } else {
        echo "Error: " . mysqli_error($conn);
        $patient = []; // Set to an empty array if there's an error or no results
    }
} else {
    // Redirect back to the patient list page if no patient ID is provided
    header("Location: patientlist.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Treatment History</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .back-button {
            font-size: 24px;
            padding: 10px 20px;
            border: none;
            background-color: transparent;
            cursor: pointer;
            position: absolute;
            top: 20px;
            left: 20px;
        }

        .back-button:hover {
            background-color: #f0f0f0;
        }

        .main-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 1200px;
            margin: auto;
            width: 100%;
            height: 100vh;
            overflow: auto;
        }

        .table-container {
            background-color: #f5f5f5;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 80%; /* Adjust the width of the table container */
            overflow: auto; /* Add overflow property if needed */
            margin: 0 auto; /* Center the table container */
            position: relative; /* Add position relative for fixed positioning */
        }

        table {
            width: 100%; /* Set the table width to 100% */
            border-collapse: collapse;
            table-layout: fixed; /* Fix the table layout */
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f5f5f5;
            color: black;
        }

        th:first-child {
            border-top-left-radius: 10px;
        }

        th:last-child {
            border-top-right-radius: 10px;
        }

    </style>
</head>
<body>
    <button onclick="history.back()" class="back-button">&#x2190;</button>

    <div class="main-content">
        <div class="table-container">
            <table>
                <tr>
                    <th rowspan="2" style="border-top: none; border-left: none;">
                        <?php echo '<img src="'. $patient["ImagePath"] .'" alt="Image" style="width:200px;height:200px;margin:10px;"><br>'; ?>
                        <!-- <img src="profile default.jpg" width="200" height="200"> -->
                    </th>
                    <th colspan="2">Patient ID: <?php echo $patient['PatientID']; ?></th>
                </tr>
                <tr>
                    <td>Phone No: <?php echo $patient['PhoneNo']; ?></td>
                    <td>Gender: <?php echo $patient['Gender']; ?></td>
                </tr>
                <tr>
                    <th><?php echo $patient['Name']; ?></th>
                    <td>Nationality: <?php echo $patient['Nationality']; ?></td>
                    <td>Email: <?php echo $patient['Email']; ?></td>
                </tr>
            </table>
        </div>

        <h1>Consultation History</h1>
        <table class="treatment-table">
            <thead>
                <tr>
                    <th>Id Consultation</th>
                    <th>Date</th>
                    <th>Doctor</th>
                    <th>Medicine</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($consultationResult) && $consultationResult && mysqli_num_rows($consultationResult) > 0) {
                    while ($row = mysqli_fetch_assoc($consultationResult)) {
                        // Fetch medicine details for each consultation
                        $consultationId = $row['ConsultationID'];
                        $medicineQuery = "SELECT MedicineName FROM medicine WHERE ConsultationID='$consultationId'";
                        $medicineResult = mysqli_query($conn, $medicineQuery);

                        // Prepare medicine names for display
                        $medicineNames = [];
                        while ($medicineRow = mysqli_fetch_assoc($medicineResult)) {
                            $medicineNames[] = $medicineRow['MedicineName'];
                        }

                        // Display consultation details and associated medicines
                        echo "<tr>";
                        echo "<td>" . $row['ConsultationID'] . "</td>";
                        echo "<td>" . $row['ConsultationDate'] . "</td>";
                        echo "<td>" . $row['DoctorID'] . "</td>";
                        echo "<td>" . implode(", ", $medicineNames) . "</td>";
                        echo "<td>" . $row['Notes'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No consultation history available.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
