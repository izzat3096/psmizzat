<?php
include('includes/dbconnection.php');

// Retrieve data from the POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = mysqli_real_escape_string($conn, $_POST['patient_id']);
    $doctor_id = mysqli_real_escape_string($conn, $_POST['doctor_id']);
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);
    $medicines = $_POST['medicine']; // Assuming 'medicine' is an array of selected medicine IDs

    // Check if the doctor ID exists in the tbl_doktor table
    $checkDoctorQuery = "SELECT * FROM tbl_doktor WHERE Id_Doktor = '$doctor_id'";
    $doctorResult = mysqli_query($conn, $checkDoctorQuery);
    if (mysqli_num_rows($doctorResult) == 0) {
        echo "Error: Doctor with ID $doctor_id does not exist.";
        exit(); // Exit the script if the doctor ID doesn't exist
    }

    // Insert data into the Consultation table without explicitly setting ConsultationID
    $insertConsultationQuery = "INSERT INTO Consultation (PatientID, DoctorID, Notes) VALUES ('$patient_id', '$doctor_id', '$notes')";
    if (mysqli_query($conn, $insertConsultationQuery)) {
        $consultationId = mysqli_insert_id($conn); // Get the ID of the last inserted consultation

        // Insert selected medicines into the Medicine table
        foreach ($medicines as $medicineId) {
            // Fetch MedicineName based on MedicineID
            $fetchMedicineNameQuery = "SELECT MedicineName FROM medicines_list WHERE medicine_id = '$medicineId'";
            $result = mysqli_query($conn, $fetchMedicineNameQuery);

            if ($result) {
                $medicineNameRow = mysqli_fetch_assoc($result);
                $medicineName = $medicineNameRow['MedicineName'];

                // Insert data into the Medicine table with status as "active"
                $insertMedicineQuery = "INSERT INTO Medicine (ConsultationID, MedicineID, MedicineName, status) VALUES ('$consultationId', '$medicineId', '$medicineName', 'active')";

                if (!mysqli_query($conn, $insertMedicineQuery)) {
                    echo "Error inserting medicine data: " . mysqli_error($conn);
                }
            } else {
                echo "Error fetching medicine data: " . mysqli_error($conn);
            }
        }
        // Close the database connection after the loop
        mysqli_close($conn);
        // Print JavaScript code to open popup window with success message
        echo '<script>
            alert("Appointment was created successfully.");
            window.close();
        </script>';
    } else {
        echo "Error inserting consultation data: " . mysqli_error($conn);
    }
}
?>
