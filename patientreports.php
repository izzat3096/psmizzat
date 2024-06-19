<?php
session_start();

// Check if doktor_id is set in the session
if (!isset($_SESSION['doktor_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit;
}
include('includes/dbconnection.php');

// Fetch all patient consultation and medicine information for all doctors
$query = "SELECT c.PatientID AS patient_id, c.ConsultationDate AS date, GROUP_CONCAT(m.MedicineName SEPARATOR '<br>') AS medicines, p.Name AS patient_name, d.Nama_Doktor AS doctor_name
          FROM consultation c
          JOIN patients_list p ON c.PatientID = p.PatientID
          LEFT JOIN medicine m ON c.ConsultationID = m.ConsultationID
          LEFT JOIN tbl_doktor d ON c.DoctorID = d.Id_Doktor
          GROUP BY c.ConsultationID";

$result = mysqli_query($conn, $query);
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Reports</title>
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


    .report-list {
        margin-bottom: 20px;
        border-collapse: collapse;
        width: 100%;
        text-align: center; /* Center text within the table */
    }

    .report-list th,
    .report-list td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center; /* Ensure text alignment is centered */
    }

    .report-list th {
        background-color: #e9ecef;
        color: #333;
    }

    .report-list tr:nth-child(even) {
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

.search-box {
    width: 150px; /* Adjust the width as needed */
    padding: 8px; /* Optional: Add padding for better appearance */
    font-size: 16px; /* Optional: Set font size */
}

</style>
</head>
<body>
    <div class="container">
        <?php include('includes/sidebar.php'); ?>
        <div class="main-content">
        <div class="header">
            <h1>Patient Reports</h1>
            <input type="text" id="searchInput" placeholder="Search by Patient ID" class="search-box">
            </div>

            <table class="report-list" id="reportsTable">
                
                    <tr>
                        <th onclick="sortTable(0)">Id Patient</th>
                        <th onclick="sortTable(1)">Date</th>
                        <th onclick="sortTable(2)">Patient's name</th>
                        <th onclick="sortTable(3)">Medicine</th>
                        <th onclick="sortTable(4)">Doctor</th>
                    </tr>
                
                
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>{$row['patient_id']}</td>";
                        echo "<td>{$row['date']}</td>";
                        echo "<td>{$row['patient_name']}</td>";
                        echo "<td>{$row['medicines']}</td>";
                        echo "<td>{$row['doctor_name']}</td>";
                        echo "</tr>";
                    }
                    ?>
                
            </table>
        </div>
    </div>

    <script>
        function sortTable(columnIndex) {
            var table, rows, switching, i, x, y, shouldSwitch;
            table = document.getElementById("reportsTable");
            switching = true;
            while (switching) {
                switching = false;
                rows = table.rows;
                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("td")[columnIndex];
                    y = rows[i + 1].getElementsByTagName("td")[columnIndex];
                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                    }
                }
                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                }
            }
        }

        document.getElementById("searchInput").addEventListener("keyup", function() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("reportsTable");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        });
    </script>
</body>
</html>
