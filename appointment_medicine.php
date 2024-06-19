<?php
session_start();

// Check if doktor_id is set in the session
if (!isset($_SESSION['doktor_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit;
}

include('includes/dbconnection.php');

// Retrieve doktor_id from the session
$doktor_id = $_SESSION['doktor_id'];

// Fetch medicine data
$medicineQuery = "SELECT medicine_id, MedicineName FROM medicines_list";
$medicineResult = mysqli_query($conn, $medicineQuery);

// Fetch doctor data
$doctorQuery = "SELECT Id_Doktor, Nama_Doktor FROM tbl_doktor";
$doctorResult = mysqli_query($conn, $doctorQuery);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta tags, title, and styles -->
    <!-- Your existing code -->
</head>
<body>
    <h1>Appointment Details</h1>
    <?php

// Include database connection
include('includes/dbconnection.php');

// Fetch medicine data
$medicineQuery = "SELECT medicine_id, MedicineName FROM medicines_list";
$medicineResult = mysqli_query($conn, $medicineQuery);

// Fetch doctor data
$doctorQuery = "SELECT Id_Doktor, Nama_Doktor FROM tbl_doktor";
$doctorResult = mysqli_query($conn, $doctorQuery);



    // Include database connection
    include('includes/dbconnection.php');

    // Check if patient_id is provided in the URL
    if (isset($_GET['patient_id'])) {
        $patient_id = $_GET['patient_id'];

        // Fetch patient details from the database
        $patientDetailsQuery = "SELECT * FROM patients_list WHERE PatientID = '$patient_id'";
        $patientDetailsResult = mysqli_query($conn, $patientDetailsQuery);

        if ($patientDetailsResult && mysqli_num_rows($patientDetailsResult) > 0) {
            $patientDetails = mysqli_fetch_assoc($patientDetailsResult);
            // Extract patient details
            $patient_name = $patientDetails['Name']; // Patient Name
            // Display patient details
            echo "<p><strong>Patient ID:</strong> $patient_id</p>";
            echo "<p><strong>Patient Name:</strong> $patient_name</p>";
        } else {
            echo "Patient details not found.";
        }
    } else {
        echo "Patient ID not provided in the URL.";
    }

    // Close the database connection
    mysqli_close($conn);
    ?>

    <!-- Appointment form -->
    <div class="consultation-form">
                <!-- Add your form elements here -->
                <form action="process_appointment_medicine.php" method="post">
                    <input type="hidden" name="patient_id" value="<?php echo $patient_id; ?>">
                    <input type="hidden" name="doctor_id" value="<?php echo $doktor_id; ?>">
                    <!-- Add more form fields as needed -->
                    <textarea name="notes" placeholder="Notes"></textarea>

                    <h2>Select Medicines</h2>
                    <div id="medicine-container">
                        <select name="medicine[]" class="medicine">
                            <option value="">Select Medicine</option>
                            <?php
                            // Populate medicine options
                            while ($medicineRow = mysqli_fetch_assoc($medicineResult)) {
                                echo "<option value='{$medicineRow['medicine_id']}'>{$medicineRow['MedicineName']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="button" class="add-button" onclick="addMedicineField()">+</button>
                    <button type="submit" class="send-button">Send</button>







                </form>
                </body>

<script>
        function addMedicineField() {
            // Clone the original medicine field
            var originalMedicineField = document.querySelector('.medicine');
            var clonedMedicineField = originalMedicineField.cloneNode(true);

            // Clear the selected option in the cloned field
            clonedMedicineField.value = "";

            // Append the cloned field to the medicine container
            var medicineContainer = document.getElementById('medicine-container');
            medicineContainer.appendChild(clonedMedicineField);
        }
    </script>
</html>
