<?php
session_start();

// Check if doktor_id is set in the session
if (!isset($_SESSION['doktor_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit;
}

include('includes/dbconnection.php');

$doktor_id = $_SESSION['doktor_id'];

// Retrieve patient details from the POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $patient_name = $_POST['patient_name'];
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
} else {
    // Redirect to dashboard.php if accessed without proper POST data
    header("Location: dashboard.php");
    exit;
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

        .patient-details {
            margin-bottom: 20px;
        }

        .patient-details h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .patient-details p {
            margin: 5px 0;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
        }

        textarea {
            width: 100%;
            height: 100px;
            margin-bottom: 20px;
        }

        select.medicine {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .add-button,
        .send-button,
        button[type="button"] {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .add-button:hover,
        .send-button:hover,
        button[type="button"]:hover {
            background-color: #0056b3;
        }

        .add-button {
            margin-right: 10px;
        }

        .send-button {
            background-color: #28a745;
        }

        .send-button:hover {
            background-color: #218838;
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


    </style>
 
</head>
<body>
<div class="container">
    <!-- Include the sidebar -->
    <?php include('includes/sidebar.php'); ?>
    <div class="main-content">
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
        <div>
            <form action="process_consultation.php" method="post">
                <input type="hidden" name="patient_id" value="<?php echo $patient_id; ?>">
                <input type="hidden" name="doctor_id" value="<?php echo $doktor_id; ?>">
                <textarea name="notes" placeholder="notes"></textarea>
                <h2>Select Medicines</h2>
                <div id="medicine-container">
    <!-- Initial medicine field -->
    <div class="medicine-row">
        <select name="medicine[]" class="medicine">
            <option value="">Select Medicine</option>
            <?php
            while ($medicineRow = mysqli_fetch_assoc($medicineResult)) {
                echo "<option value='{$medicineRow['medicine_id']}'>{$medicineRow['MedicineName']}</option>";
            }
            ?>
        </select>
        <button type="button" class="delete-button" onclick="removeMedicineRow(this)">Delete</button>
    </div>
</div>

<script>
    function addMedicineField() {
        var originalMedicineRow = document.querySelector('.medicine-row');
        var clonedMedicineRow = originalMedicineRow.cloneNode(true);
        clonedMedicineRow.querySelector('.medicine').value = "";
        var medicineContainer = document.getElementById('medicine-container');
        medicineContainer.appendChild(clonedMedicineRow);
    }

    function removeMedicineRow(button) {
        var medicineRow = button.parentNode;
        medicineRow.parentNode.removeChild(medicineRow);
    }
</script>

                <button type="button" class="add-button" onclick="addMedicineField()" style="display: block; margin: 0 auto;">+</button>

                <br><br>

                <div id="medicine-container" style="text-align: center;">
                <button type="button" onclick="openAppointmentWindow('<?php echo $patient_id; ?>')" style="background-color: red;">Make Appointment</button>
                <button type="submit" class="send-button">Send</button>
                </div>
                
                
                <script>
                    function openAppointmentWindow(patientId) {
                        var newWindow = window.open('appointment.php?patient_id=' + patientId, 'New Appointment', 'width=600,height=400');
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
