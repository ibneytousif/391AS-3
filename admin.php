<?php
// admin.php - Admin Panel for managing appointments
include 'db.php'; // Include the database connection

// Fetch appointments
$result = $conn->query("SELECT appointments.appointment_id, clients.name, clients.phone, clients.car_license_number, appointments.appointment_date, mechanics.name AS mechanic_name 
                        FROM appointments
                        JOIN clients ON appointments.client_id = clients.client_id
                        JOIN mechanics ON appointments.mechanic_id = mechanics.mechanic_id");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="styles.css">
    <script src="script.js"></script>
</head>
<body>
    <div class="container">
        <h1>Admin Panel</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="appointment.php">User Panel - Book Appointment</a></li>
                <li><a href="admin.php">Admin Panel - Manage Appointments</a></li>
            </ul>
        </nav>

        <h2>Appointments List</h2>
        <table border="1">
            <tr>
                <th>Client Name</th>
                <th>Phone</th>
                <th>Car License Number</th>
                <th>Appointment Date</th>
                <th>Mechanic Name</th>
                <th>Actions</th>
            </tr>

            <?php
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['name']}</td>
                            <td>{$row['phone']}</td>
                            <td>{$row['car_license_number']}</td>
                            <td>{$row['appointment_date']}</td>
                            <td>{$row['mechanic_name']}</td>
                            <td><a href='edit_appointment.php?appointment_id={$row['appointment_id']}'>Edit</a></td>
                        </tr>";
                }
            ?>
        </table>
    </div>
</body>
</html>
