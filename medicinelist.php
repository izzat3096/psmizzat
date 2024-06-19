<?php
session_start();

// Check if doktor_id is set in the session
if (!isset($_SESSION['doktor_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit;
}
include('includes/dbconnection.php');

$doktor_id = $_SESSION['doktor_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>List of Medicines</title>
<style>
    body, html {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
}

.medicine-list-container {
    flex-grow: 1;
    background: white;
    padding: 20px;
    overflow: auto;
}

h1 {
    margin-bottom: 20px;
    text-align: center;
}

.medicine-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

.medicine-table thead {
    background-color: #e9e9e9;
}

.medicine-table th, .medicine-table td {
    text-align: left;
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

.edit-button {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 16px;
}

.add-button {
    display: block;
    width: 100px;
    padding: 10px;
    margin: 0 auto;
    background-color: #d7eefe;
    color: black;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.add-button:hover {
    background-color: #0056b3;
}

.container {
    display: flex;
    height: 100%;
}

</style>
</head>
<body>
<div class="container">
<?php
include('includes/sidebar.php');
?>
<div class="medicine-list-container">
    <h1>List of Medicines</h1>
    <table class="medicine-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Medicine name</th>
                <th>Usage</th>
                <th>Dos</th>
                <th>ML</th>
                <th>Tablet</th>
                <th>Per Day</th>
                <th>Edit</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>384</td>
                <td>Paracetamol</td>
                <td>Fever</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><form action="editmedicine.php" method="get"><button type="submit" class="edit-button">⚙️</button></form></td>
            </tr>
            <tr>
                <td>384</td>
                <td>Adezio</td>
                <td>Flu</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><form action="editmedicine.php" method="get"><button type="submit" class="edit-button">⚙️</button></form></td>
            </tr>
        </tbody>
    </table>
    
    <form action="addmedicine.php" method="get"><button type="submit" class="add-button">Add</button></form>

    
</div>
</div>
</body>
</html>
