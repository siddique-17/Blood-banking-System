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

// Fetch all requests from the database
$sql = "SELECT * FROM requests ORDER BY request_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Blood Requests</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #c0392b;
        }

        table {
            width: 95%;
            margin: 0 auto;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #e74c3c;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        a {
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        .no-data {
            text-align: center;
            margin-top: 30px;
            font-size: 18px;
            color: #888;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #2c3e50;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<h2>Blood Requests</h2>

<?php
if ($result->num_rows > 0) {
    echo "<table>
            <tr>
                <th>Name</th>
                <th>Blood Group</th>
                <th>City</th>
                <th>Reason</th>
                <th>Request Date</th>
                <th>Action</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['name']}</td>
                <td>{$row['blood_group']}</td>
                <td>{$row['city']}</td>
                <td>{$row['reason']}</td>
                <td>{$row['request_date']}</td>
                <td><a href='respond_request.php?id={$row['id']}'>Respond</a></td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p class='no-data'>No requests found.</p>";
}
$conn->close();
?>

<a class="back-link" href="index.html">← Back to Home</a>

</body>
</html>
