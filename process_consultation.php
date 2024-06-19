<?php
include('includes/dbconnection.php');

// Retrieve data from the POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $notes = $_POST['notes'];
    $medicines = $_POST['medicine']; // Assuming 'medicine' is an array of selected medicine IDs

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

                if (mysqli_query($conn, $insertMedicineQuery)) {
                    // Continue the loop for other medicines
                } else {
                    echo "Error inserting medicine data: " . mysqli_error($conn);
                }
            } else {
                echo "Error fetching medicine data: " . mysqli_error($conn);
            }
        }

        // Delete the row from waiting_numbers based on patient_id
        $deleteWaitingNumberQuery = "DELETE FROM waiting_numbers WHERE PatientID = '$patient_id'";
        if (!mysqli_query($conn, $deleteWaitingNumberQuery)) {
            echo "Error deleting waiting number data: " . mysqli_error($conn);
        } else {
            // Redirect to dashboard.php after successful operations
            header("Location: dashboard.php");
            exit();
        }
    } else {
        echo "Error inserting consultation data: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request method.";
}

// Close the database connection
mysqli_close($conn);
?>
