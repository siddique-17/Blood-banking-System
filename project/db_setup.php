<?php
$servername = "localhost";
$username = "root";
$password = "Sid@2004";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql_create_db = "CREATE DATABASE IF NOT EXISTS blood_bank";
if ($conn->query($sql_create_db) === TRUE) {
    echo "✅ Database created successfully!<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Select the database
$conn->select_db('blood_bank');

// Create tables if they don't exist
$sql_create_tables = "
CREATE TABLE IF NOT EXISTS donors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    blood_group VARCHAR(5),
    phone VARCHAR(15),
    email VARCHAR(100),
    city VARCHAR(100),
    donation_date DATE
);
CREATE TABLE IF NOT EXISTS blood_availability (
    id INT AUTO_INCREMENT PRIMARY KEY,
    donor_id INT,
    blood_group VARCHAR(5),
    city VARCHAR(100),
    quantity INT,
    FOREIGN KEY (donor_id) REFERENCES donors(id)
);


CREATE TABLE IF NOT EXISTS requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    blood_group VARCHAR(5),
    phone VARCHAR(15),
    city VARCHAR(100),
    reason TEXT,
    request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
";

if ($conn->multi_query($sql_create_tables)) {
    do {
        if ($result = $conn->store_result()) {
            $result->free();
        }
    } while ($conn->more_results() && $conn->next_result());
    echo "✅ Tables created successfully!";
} else {
    echo "Error creating tables: " . $conn->error;
}

$conn->close();
?>
