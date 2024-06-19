<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit;
}

include('includes/db.php');

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Check if the user has already received a waiting number
$query = "SELECT * FROM waiting_numbers WHERE user_id = '$user_id' AND received = 1";
$result = mysqli_query($conn, $query);

$received_waiting_number = (mysqli_num_rows($result) > 0);

// Initialize variables to store messages
$message = "";
$error_message = "";

// Check if the button is clicked to receive a waiting number
if (isset($_POST['receive_number']) && !$received_waiting_number) {
    // Generate a waiting number (you can customize this logic)
    $waiting_number = rand(100, 999);

    // Store the waiting number in the database and mark it as received
    $insertQuery = "INSERT INTO waiting_numbers (user_id, waiting_number, received) VALUES ('$user_id', '$waiting_number', 1)";
    if (mysqli_query($conn, $insertQuery)) {
        $received_waiting_number = true;
    } else {
        $error_message = "Error: " . mysqli_error($conn);
    }
}

// Check if the button is clicked to drop or cancel the waiting number
if (isset($_POST['cancel_number']) && $received_waiting_number) {
    // Remove the waiting number entry from the database
    $deleteQuery = "DELETE FROM waiting_numbers WHERE user_id = '$user_id' AND received = 1";
    if (mysqli_query($conn, $deleteQuery)) {
        $received_waiting_number = false; // Mark the waiting number as canceled
        $message = "Your waiting number has been canceled.";
    } else {
        $error_message = "Error: " . mysqli_error($conn);
    }
}

// Get the current number
$current_number_query = "SELECT waiting_number FROM waiting_numbers WHERE current = 1";
$current_number_result = mysqli_query($conn, $current_number_query);
$latest_number = "No current number"; // Default message if no current number is found

if ($current_number_result && $row = mysqli_fetch_assoc($current_number_result)) {
    $latest_number = $row['waiting_number'];
}

// Get the user's received number
$user_waiting_number = "You haven't taken the number yet";

if ($received_waiting_number) {
    // Query the database to get the user's own received number
    $user_waiting_number_query = "SELECT waiting_number FROM waiting_numbers WHERE user_id = '$user_id' AND received = 1";
    $user_waiting_number_result = mysqli_query($conn, $user_waiting_number_query);
    if ($user_waiting_number_result && $row = mysqli_fetch_assoc($user_waiting_number_result)) {
        $user_waiting_number = $row['waiting_number'];
    }
}

// Check if the user's waiting number matches the current waiting number
if ($received_waiting_number && $latest_number != "No current number" && $user_waiting_number == $latest_number) {
    $your_turn_message = "Your turn now";
} else {
    $your_turn_message = "";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home Page</title>
</head>
<body>
    <h2>Welcome, <?php echo $username; ?></h2>
    
    <!-- Display success or error message if applicable -->
    <?php
    if (!empty($message)) {
        echo "<p>$message</p>";
    }
    if (!empty($error_message)) {
        echo "<p>$error_message</p>";
    }
    ?>

    <!-- Display the user's received number -->
    <p>Your waiting number is: <?php echo $user_waiting_number; ?></p>

    <!-- Display the current waiting number -->
    <p>Current waiting number is: <?php echo $latest_number; ?></p>

    <!-- Display "Your turn now" message if applicable -->
    <p><?php echo $your_turn_message; ?></p>

    <!-- Display the "Receive Waiting Number" button only if the user hasn't received a waiting number -->
    <?php if (!$received_waiting_number): ?>
        <form method="post">
            <input type="submit" name="receive_number" value="Take Queue Number">
        </form>
    <?php else: ?>
        <!-- Display the "Cancel Waiting Number" button if the user has received a waiting number -->
        <form method="post">
            <input type="submit" name="cancel_number" value="Cancel Waiting Number">
        </form>
    <?php endif; ?>

    <a href="create_dependent.php">Create Dependent Account</a><br>
    <a href="#">Sejarah Perubatan</a><br>
    <a href="#">Jadual Ubat</a><br>
    <a href="#">Janji Temu Saya</a><br>
    <a href="#">Laporan</a><br>
    <a href="login.php">LogOut</a><br>
</body>
</html>
