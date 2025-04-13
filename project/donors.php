<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Find Donors</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #c0392b;
        }

        form {
            text-align: center;
            margin-bottom: 30px;
        }

        input[type="text"],
        select {
            padding: 10px;
            margin: 0 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 200px;
        }

        button {
            padding: 10px 20px;
            background-color: #c0392b;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #922b21;
        }

        .donors-table {
            width: 90%;
            margin: 0 auto;
            border-collapse: collapse;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .donors-table th,
        .donors-table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .donors-table th {
            background-color: #e74c3c;
            color: white;
        }

        .home-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #c0392b;
        }

        .home-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<?php
$conn = new mysqli("localhost", "root", "Sid@2004", "blood_bank");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$group = isset($_GET['blood_group']) ? $_GET['blood_group'] : '';
$city = isset($_GET['city']) ? $_GET['city'] : '';

$filter = [];
if (!empty($group)) {
    $filter[] = "blood_group = '" . $conn->real_escape_string($group) . "'";
}
if (!empty($city)) {
    $filter[] = "city LIKE '%" . $conn->real_escape_string($city) . "%'";
}

$where = count($filter) > 0 ? "WHERE " . implode(" AND ", $filter) : "";

echo "<h2>" . ($group || $city ? "Filtered Donors" : "All Available Donors") . "</h2>";

echo '
    <form method="GET">
        <select name="blood_group">
            <option value="">All Blood Groups</option>
            <option value="A+" ' . ($group == "A+" ? "selected" : "") . '>A+</option>
            <option value="O+" ' . ($group == "O+" ? "selected" : "") . '>O+</option>
            <option value="B+" ' . ($group == "B+" ? "selected" : "") . '>B+</option>
            <option value="AB+" ' . ($group == "AB+" ? "selected" : "") . '>AB+</option>
            <option value="A-" ' . ($group == "A-" ? "selected" : "") . '>A-</option>
            <option value="O-" ' . ($group == "O-" ? "selected" : "") . '>O-</option>
            <option value="B-" ' . ($group == "B-" ? "selected" : "") . '>B-</option>
            <option value="AB-" ' . ($group == "AB-" ? "selected" : "") . '>AB-</option>
        </select>
        <input type="text" name="city" placeholder="Search by City" value="' . htmlspecialchars($city) . '">
        <button type="submit">Search</button>
    </form>
';

$sql = "SELECT * FROM donors $where ORDER BY donation_date DESC";
$result = $conn->query($sql);

echo "<table class='donors-table'>
        <tr>
            <th>Name</th>
            <th>Blood Group</th>
            <th>City</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Donation Date</th>
        </tr>";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['name']}</td>
                <td>{$row['blood_group']}</td>
                <td>{$row['city']}</td>
                <td>{$row['phone']}</td>
                <td>{$row['email']}</td>
                <td>{$row['donation_date']}</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='6'>No donors found</td></tr>";
}
echo "</table>";

$conn->close();
?>

<a href="index.html" class="home-link">🏠 Back to Home</a>

</body>
</html>
