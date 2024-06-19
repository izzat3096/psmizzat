<?php
session_start();

// Check if doktor_id is set in the session
if (!isset($_SESSION['doktor_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit;
}

include('includes/dbconnection.php');

$doktor_id = $_SESSION['doktor_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $patient_name = $_POST['patient_name'];


// Delete the appointment from the appointments table
$deleteAppointmentQuery = "DELETE FROM appointments WHERE patient_id = '$patient_id'";
if (!mysqli_query($conn, $deleteAppointmentQuery)) {
    echo "Error deleting appointment data: " . mysqli_error($conn); // Display error if any
} else {


// Retrieve patient details from the POST request

    // Add more details as needed

    // Example: Fetch additional patient details from the database
    $patientDetailsQuery = "SELECT * FROM patients_list WHERE PatientID = '$patient_id'";
    $patientDetailsResult = mysqli_query($conn, $patientDetailsQuery);

    if ($patientDetailsResult) {
        // Replace the following with your actual logic to fetch and display patient details
        $patientDetails = mysqli_fetch_assoc($patientDetailsResult);
        $patient_name = $patientDetails['Name'];
        $gender = $patientDetails['Gender'];
        $phone_no = $patientDetails['PhoneNo'];
        $nationality = $patientDetails['Nationality'];
        $email = $patientDetails['Email'];
    } else {
        // Handle the error if the query fails
        echo "Error: " . mysqli_error($conn);
    }
} 
}

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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation Form</title>
    <style>
       body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

.container {
    display: flex;
}

.sidebar {
    /* Add your styles for the sidebar here */
    width: 200px;
    background-color: #f4f4f4;
    padding: 20px;
}

.patient-info {
    /* Add your styles for patient info here */
    flex: 1;
    padding: 20px;
}

.patient-details {
    /* Add your styles for patient details here */
    border-bottom: 1px solid #ccc;
    margin-bottom: 20px;
}

/* .consultation-form { */
    /* Add your styles for the consultation form here */
/* } */

/* form { */
    /* Add your form styles here */
/* } */

textarea {
    width: 100%;
    height: 100px;
    margin-bottom: 20px;
}

h2 {
    margin-top: 0;
}

.medicine {
    /* Add your styles for the medicine select field here */
    margin-bottom: 10px;
}

.button-container {
    text-align: center;
    margin-top: 20px; /* Adjust this value as needed */
}

.red-button {
    background-color: red;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-right: 10px; /* Add margin between buttons */
}

.blue-button {
    background-color: blue;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-right: 10px; /* Add margin between buttons */
}

.green-button {
    background-color: green;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
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
    color: white;
}

th:first-child {
    border-top-left-radius: 10px;
}

th:last-child {
    border-top-right-radius: 10px;
}

td:first-child {
    font-weight: bold;
}

/* Add additional styles as needed */

    </style>
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
</head>
<body>
    <div class="container">
        <?php include('includes/sidebar.php'); ?>
        <div class="patient-info">

        <div class="table-container">
            <table>
                <tr>
                <th rowspan="2" style="border-top: none; border-left: none;">
            
                <img src="profile default.jpg" width="200" height="200">


            </th>
                    <td colspan="2">Patient ID:<?php echo '<h4>' . $patient_id . '</h4>';?></td>
                </tr>
                <tr>
                    <td>Phone No:<?php echo '<h4>' . $phone_no . '</h4>';?></td>
                    <td>Gender:<?php echo '<h4>' . $gender . '</h4>';?></td>
                </tr>
                <tr>
                    <td><?php echo '<h4>' . $patient_name . '</h4>';?></td>
                    <td>Nationality:<?php echo '<h4>' . $nationality . '</h4>';?></td>
                    <td>Email:<?php echo '<h4>' . $email . '</h4>';?></td>
                </tr>

            </table>
        </div>
        <br><br><br>

        <div class="table-container">
        <?php echo '<p><strong>Notes:</strong>keluarkan notes utk apointments sebab apa</p>';?>

    </div>
        <br><br><br>

        <form action="process_consultation_appointments.php" method="post" class="button-container">
    <button type="button" class="red-button" onclick="openAppointmentWindow('<?php echo $patient_id; ?>')">New Appointment</button>
    <button type="button" class="blue-button" onclick="openAppointmentWindow2('<?php echo $patient_id; ?>')">Give Medicine</button>
    <input type="hidden" name="patient_id" value="<?php echo $patient_id; ?>">
    <input type="hidden" name="patient_name" value="<?php echo $patient_name; ?>">
    <button type="submit" class="green-button">Complete Appointment Session</button>
</form>





</form>
<script>
    function openAppointmentWindow2(patientId) {
        var newWindow = window.open('appointment_medicine.php?patient_id=' + patientId, 'Give Medicine', 'width=600,height=400');
        // You can adjust width and height as needed
    }
</script>

<script>
    function openAppointmentWindow(patientId) {
        var newWindow = window.open('appointment.php?patient_id=' + patientId, 'New Appointment', 'width=600,height=400');
        // You can adjust width and height as needed
    }
</script>

                </form>
            </div>
        </div>
    </div>
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
