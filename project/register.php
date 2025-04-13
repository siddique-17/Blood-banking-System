<?php
$servername = "localhost";
$username = "root";
$password = "Sid@2004";
$dbname = "blood_bank";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("<script>alert('❌ Database connection failed!'); window.location.href='register_donor.html';</script>");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $blood_group = $_POST['blood_group'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $city = $_POST['city'];
    $donation_date = $_POST['donation_date'];

    $stmt = $conn->prepare("INSERT INTO donors (name, blood_group, phone, email, city, donation_date) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $blood_group, $phone, $email, $city, $donation_date);

    if ($stmt->execute()) {
        $donor_id = $stmt->insert_id;
        $quantity = 1;

        $stmt2 = $conn->prepare("INSERT INTO blood_availability (donor_id, blood_group, city, quantity) VALUES (?, ?, ?, ?)");
        $stmt2->bind_param("issi", $donor_id, $blood_group, $city, $quantity);

        if ($stmt2->execute()) {
            echo "<!DOCTYPE html>
            <html>
            <head>
                <title>Success</title>
                <style>
                    body {
                        font-family: 'Segoe UI', sans-serif;
                        background-color: #f2f2f2;
                        text-align: center;
                        padding: 50px;
                    }
                    .message {
                        background-color: #d4edda;
                        color: #155724;
                        padding: 20px;
                        border-radius: 10px;
                        display: inline-block;
                        font-size: 18px;
                        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                    }
                    .home-link {
                        display: block;
                        margin-top: 30px;
                        text-decoration: none;
                        color: #c0392b;
                        font-weight: bold;
                    }
                    .home-link:hover {
                        text-decoration: underline;
                    }
                </style>
            </head>
            <body>
                <div class='message'>✅ Donor registered successfully and blood availability updated!</div>
                <a href='index.html' class='home-link'>🏠 Back to Home</a>
            </body>
            </html>";
        } else {
            echo "<script>alert('❌ Blood availability insert failed!'); window.location.href='register_donor.html';</script>";
        }

        $stmt2->close();
    } else {
        echo "<script>alert('❌ Donor registration failed!'); window.location.href='register_donor.html';</script>";
    }

    $stmt->close();
}
$conn->close();
?>
