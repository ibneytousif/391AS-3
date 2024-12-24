<?php
// appointment.php - User Panel for booking appointments
include 'db.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form values
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $car_license_number = $_POST['car_license_number'];
    $car_engine_number = $_POST['car_engine_number'];
    $appointment_date = $_POST['appointment_date'];
    $mechanic_id = $_POST['mechanic_id'];

    // Check if the client already has an appointment on this date
    $check_appointment = $conn->query("SELECT * FROM appointments WHERE client_id = (SELECT client_id FROM clients WHERE phone = '$phone') AND appointment_date = '$appointment_date'");
    if ($check_appointment->num_rows > 0) {
        echo "You already have an appointment on this date.";
        exit;
    }

    // Check available slots for the selected mechanic
    $check_slots = $conn->query("SELECT available_slots FROM mechanics WHERE mechanic_id = $mechanic_id");
    $slots = $check_slots->fetch_assoc()['available_slots'];
    if ($slots <= 0) {
        echo "The selected mechanic has no available slots.";
        exit;
    }

    // Check if the client already exists, if not, insert into clients table
    $client_check = $conn->query("SELECT client_id FROM clients WHERE phone = '$phone'");
    if ($client_check->num_rows == 0) {
        $insert_client = "INSERT INTO clients (name, address, phone, car_license_number, car_engine_number) 
                          VALUES ('$name', '$address', '$phone', '$car_license_number', '$car_engine_number')";
        $conn->query($insert_client);
    }

    // Get client_id
    $client_query = $conn->query("SELECT client_id FROM clients WHERE phone = '$phone'");
    $client_id = $client_query->fetch_assoc()['client_id'];

    // Insert appointment
    $insert_appointment = "INSERT INTO appointments (client_id, mechanic_id, appointment_date) 
                           VALUES ('$client_id', '$mechanic_id', '$appointment_date')";

    if ($conn->query($insert_appointment) === TRUE) {
        $conn->query("UPDATE mechanics SET available_slots = available_slots - 1 WHERE mechanic_id = $mechanic_id");
        echo "Appointment booked successfully!";
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
    <title>Book Appointment</title>
    <link rel="stylesheet" href="styles.css">
    <script src="script.js"></script>
</head>
<body>
    <div class="container">
        <h1>Book an Appointment</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="appointment.php">User Panel - Book Appointment</a></li>
                <li><a href="admin.php">Admin Panel - Manage Appointments</a></li>
            </ul>
        </nav>

        <form action="appointment.php" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required><br>

            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" required><br>

            <label for="car_license_number">Car License Number:</label>
            <input type="text" id="car_license_number" name="car_license_number" required><br>

            <label for="car_engine_number">Car Engine Number:</label>
            <input type="text" id="car_engine_number" name="car_engine_number" required><br>

            <label for="appointment_date">Appointment Date:</label>
            <input type="date" id="appointment_date" name="appointment_date" required><br>

            <label for="mechanic">Select Mechanic:</label>
            <select name="mechanic_id" id="mechanic" required>
                <?php
                    $result = $conn->query("SELECT mechanic_id, name, available_slots FROM mechanics WHERE available_slots > 0");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['mechanic_id']}'>{$row['name']} (Available Slots: {$row['available_slots']})</option>";
                    }
                ?>
            </select><br>

            <button type="submit">Submit Appointment</button>
        </form>
    </div>
</body>
</html>
