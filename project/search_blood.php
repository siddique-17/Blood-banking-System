<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "Sid@2004";
$dbname = "blood_bank";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get search parameters
$blood_group = isset($_GET['blood_group']) ? $_GET['blood_group'] : '';
$city = isset($_GET['city']) ? $_GET['city'] : '';

// Build SQL query
$sql = "SELECT name, blood_group, city, phone, donation_date FROM donors WHERE blood_group LIKE ? AND city LIKE ?";
$stmt = $conn->prepare($sql);

// Check for prepare errors
if ($stmt === false) {
    die("Error preparing SQL statement: " . $conn->error);
}

// Bind parameters
$search_blood_group = '%' . $blood_group . '%';
$search_city = '%' . $city . '%';
$stmt->bind_param("ss", $search_blood_group, $search_city);

// Execute query
$stmt->execute();
$result = $stmt->get_result();

// Display results
if ($result->num_rows > 0) {
    echo "<h2>Available Donors</h2>";
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>Name</th><th>Blood Group</th><th>City</th><th>Phone</th><th>Last Donation</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['name']}</td>
                <td>{$row['blood_group']}</td>
                <td>{$row['city']}</td>
                <td>{$row['phone']}</td>
                <td>{$row['donation_date']}</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No donors found for the selected blood group and city.</p>";
}

// Close
$stmt->close();
$conn->close();
?>
