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

// Get request ID from the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid request ID.");
}

$request_id = intval($_GET['id']);

// Handle POST (submit response)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = $_POST['response'];

    // Update the request with the response
    $stmt = $conn->prepare("UPDATE requests SET response = ? WHERE id = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("si", $response, $request_id);
    if ($stmt->execute()) {
        echo "<p style='color: green;'>✅ Request response updated successfully!</p>";
    } else {
        echo "<p style='color: red;'>❌ Error updating response: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

// Fetch request details
$stmt = $conn->prepare("SELECT * FROM requests WHERE id = ?");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $request_id);
$stmt->execute();
$result = $stmt->get_result();
$request = $result->fetch_assoc();

if (!$request) {
    die("Request not found.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Respond to Request</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f5f5f5;
        }
        h2 {
            color: #d10000;
        }
        textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            font-size: 14px;
            margin-top: 10px;
        }
        button {
            background-color: #d10000;
            color: white;
            padding: 10px 20px;
            margin-top: 10px;
            border: none;
            cursor: pointer;
        }
        a {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            color: #333;
        }
        .box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .box p {
            margin: 8px 0;
        }
    </style>
</head>
<body>
    <div class="box">
        <h2>Respond to Request</h2>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($request['name']); ?></p>
        <p><strong>Blood Group:</strong> <?php echo htmlspecialchars($request['blood_group']); ?></p>
        <p><strong>City:</strong> <?php echo htmlspecialchars($request['city']); ?></p>
        <p><strong>Reason:</strong> <?php echo htmlspecialchars($request['reason']); ?></p>

        <form method="POST">
            <textarea name="response" placeholder="Enter your response" required></textarea><br>
            <button type="submit">Submit Response</button>
        </form>

        <a href="request.php">← Back to Requests</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>
