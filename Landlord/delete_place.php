<?php
// Include the database connection
include '../ConnectionDB/DbConnection.php';

// Check if place_id is set and not empty
if (isset($_POST['place_id']) && !empty($_POST['place_id'])) {
    $placeId = $_POST['place_id'];

    // Prepare the DELETE SQL statement
    $sql = "DELETE FROM place WHERE place_id = ?";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the place_id parameter
    $stmt->bind_param("i", $placeId);

    // Execute the statement
    if ($stmt->execute()) {
        // If the deletion is successful, return a success message
        echo json_encode(['status' => 'success', 'message' => 'Place deleted successfully']);
    } else {
        // If the deletion fails, return an error message
        echo json_encode(['status' => 'error', 'message' => 'Error deleting place: ' . $conn->error]);
    }

    // Close the statement
    $stmt->close();
} else {
    // If place_id is not set or empty, return an error message
    echo json_encode(['status' => 'error', 'message' => 'Place ID is not set']);
}

// Close the database connection
$conn->close();
?>