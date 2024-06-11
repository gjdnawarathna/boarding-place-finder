<?php
// Include your database connection file
include '../ConnectionDB/DbConnection.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if place_id is set
    if (isset($_POST['place_id'])) {
        $placeId = $_POST['place_id'];
        // Fetch place details from the database
        $sql = "SELECT * FROM place WHERE place_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $placeId);
        $stmt->execute();
        $result = $stmt->get_result();
        $place = $result->fetch_assoc();

        // Return the place details as JSON
        echo json_encode($place);
    } else {
        // Return an error if place_id is not set
        http_response_code(400);
        echo json_encode(['error' => 'place_id is required']);
    }
} else {
    // Return an error if the request method is not POST
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?>