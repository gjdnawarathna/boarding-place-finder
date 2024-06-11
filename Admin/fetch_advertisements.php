<?php
include '../ConnectionDB/DbConnection.php'; 

// Assuming you have a database connection established as $conn
$sql = "SELECT * FROM advertisements";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['title'] . "</td>";
        echo "<td>" . $row['description'] . "</td>";
        echo "<td>";
        echo "<button class='btn btn-primary edit-btn' data-id='" . $row['id'] . "'>Edit</button>";
        echo "<button class='btn btn-danger remove-btn' data-id='" . $row['id'] . "'>Remove</button>";
        echo "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='3'>No advertisements found.</td></tr>";
}
?>
