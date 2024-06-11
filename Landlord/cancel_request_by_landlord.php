<?php
// Include your database connection here
include '../ConnectionDB/DbConnection.php';

if (isset($_POST['request_id'])) {
    $requestId = $_POST['request_id'];

    // Update the request status in the database
    $sql = "UPDATE requests SET status = '2' WHERE id = ?"; // Assuming '2' represents a cancelled status by landlord
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $requestId);

    if ($stmt->execute()) {
        // Fetch the place_id related to the request
        $sql2 = "SELECT place_id FROM requests WHERE id = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("s", $requestId);
        $stmt2->execute();
        $result = $stmt2->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $placeId = $row['place_id'];

            // Update the student_id and availability in the place table to an empty string
            $sql3 = "UPDATE place SET student_id = '', availability = '' WHERE place_id = ?";
            $stmt3 = $conn->prepare($sql3);
            $stmt3->bind_param("s", $placeId);
            $stmt3->execute();
        }

        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}
?>
