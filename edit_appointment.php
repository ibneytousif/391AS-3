<?php
// edit_appointment.php - Admin can edit appointment details
include 'db.php'; // Include the database connection

$appointment_id = $_GET['appointment_id']; // Get the appointment ID from URL
$result = $conn->query("SELECT * FROM appointments WHERE appointment_id = $appointment_id");
$appointment = $result->fetch_assoc();

// Handle form submission for updates
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_mechanic_id = $_POST['mechanic_id'];
    $new_date = $_POST['appointment_date'];

    $update_query = "UPDATE appointments SET mechanic_id = $new_mechanic_id, appointment_date = '$new_date' WHERE appointment_id = $appointment_id";
    
    if ($conn->query($update_query) === TRUE) {
        echo "Appointment updated successfully!";
        header("Location: admin.php"); // Redirect to the admin panel
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Appointment</title>
    <link rel="stylesheet" href="styles.css">
    <script src="script.js"></script>
</head>
<body>
    <div class="container">
        <h1>Edit Appointment</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="appointment.php">User Panel - Book Appointment</a></li>
                <li><a href="admin.php">Admin Panel - Manage Appointments</a></li>
            </ul>
        </nav>

        <form action="edit_appointment.php?appointment_id=<?php echo $appointment['appointment_id']; ?>" method="POST">
            <label for="appointment_date">Appointment Date:</label>
            <input type="date" id="appointment_date" name="appointment_date" value="<?php echo $appointment['appointment_date']; ?>" required><br>

            <label for="mechanic_id">Select Mechanic:</label>
            <select name="mechanic_id" id="mechanic_id" required>
                <?php
                    // Fetch available mechanics
                    $mechanics = $conn->query("SELECT mechanic_id, name FROM mechanics WHERE available_slots > 0");
                    while ($mechanic = $mechanics->fetch_assoc()) {
                        // Generate options for mechanics
                        echo "<option value='" . $mechanic['mechanic_id'] . "'>" . $mechanic['name'] . "</option>";
                    }
                ?>
            </select><br>

            <button type="submit">Update Appointment</button>
        </form>
    </div>
</body>
</html>
