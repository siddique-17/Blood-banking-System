<?php
$servername = "localhost";
$username = "root";
$password = "Sid@2004";
$dbname = "blood_bank";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get data from the form
    $name = $_POST['name'];
    $blood_group = $_POST['blood_group'];
    $city = $_POST['city'];
    $reason = $_POST['reason'];

    // Insert request data into the requests table
    $stmt = $conn->prepare("INSERT INTO requests (name, blood_group, city, reason) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $blood_group, $city, $reason);

    if ($stmt->execute()) {
        $message = "<p class='success'>✅ Your request for blood group '$blood_group' has been submitted successfully!</p>";
    } else {
        $message = "<p class='error'>❌ Error submitting your request: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Request Blood</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f7f7f7;
            padding: 20px;
            text-align: center;
        }

        h2 {
            color: #c0392b;
        }

        form {
            background: #ffffff;
            max-width: 500px;
            margin: 0 auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        input, textarea, button {
            width: 90%;
            padding: 12px;
            margin: 10px auto;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            display: block;
        }

        button {
            background-color: #e74c3c;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background-color: #c0392b;
        }

        .success {
            color: green;
            font-weight: bold;
            margin-top: 20px;
        }

        .error {
            color: red;
            font-weight: bold;
            margin-top: 20px;
        }

        a.home-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #2980b9;
        }

        a.home-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h2>Blood Request Form</h2>
    <?php echo $message; ?>
    <form action="request.php" method="POST">
        <input type="text" name="name" placeholder="Your Name" required>
        <input type="text" name="blood_group" placeholder="Required Blood Group" required>
        <input type="text" name="city" placeholder="Your City" required>
        <textarea name="reason" placeholder="Reason for Request" required></textarea>
        <button type="submit">Submit Request</button>
    </form>

    <a class="home-link" href="index.html">🏠 Back to Home</a>
</body>
</html>
