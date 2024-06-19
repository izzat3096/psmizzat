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

    <h2>Add Appointment</h2>
    <!-- Appointment form -->
    <form id="appointmentForm" action="process_appointment.php" method="post">
        <!-- Patient ID (hidden field) -->
        <input type="hidden" name="patient_id" value="<?php echo $patient_id; ?>">

        <!-- Date and time fields -->
        <label for="date">Date:</label>
        <input type="date" id="date" name="date" required><br>
        <label for="time">Time:</label>
        <input type="time" id="time" name="time" required><br>

        <!-- Doctor select field -->
        <label for="doctor">Doctor:</label>
        <select id="doctor" name="doctor" required>
            <!-- Populate doctor options from the database -->
            <?php
            // Include database connection
            include('includes/dbconnection.php');

            // Fetch doctor data from the database
            $doctorQuery = "SELECT Id_Doktor, Nama_Doktor FROM tbl_doktor";
            $doctorResult = mysqli_query($conn, $doctorQuery);

            // Display doctor options
            while ($doctorRow = mysqli_fetch_assoc($doctorResult)) {
                echo "<option value='{$doctorRow['Id_Doktor']}'>{$doctorRow['Nama_Doktor']}</option>";
            }

            // Close the database connection
            mysqli_close($conn);
            ?>
        </select><br>

        <!-- Notes textarea -->
        <label for="notes">Notes:</label><br>
        <textarea id="notes" name="notes" rows="4" cols="50" required></textarea><br>

        <!-- Submit button -->
        <button type="button" id="submitAppointment">Add Appointment</button>
    </form>

    <!-- JavaScript to submit form asynchronously -->
    <script>
        document.getElementById('submitAppointment').addEventListener('click', function() {
            var form = document.getElementById('appointmentForm');
            var formData = new FormData(form);
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Display success message
                        alert("Appointment was created successfully.");
                        // Close the window
                        window.close();
                    } else {
                        alert("Error: " + xhr.responseText);
                    }
                }
            };
            xhr.open('POST', form.action, true);
            xhr.send(formData);
        });
    </script>
</body>
</html>
