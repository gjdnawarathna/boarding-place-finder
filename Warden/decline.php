<?php
include '../ConnectionDB/DbConnection.php';

if (isset($_POST['place_id'])) {
    $placeId = $_POST['place_id'];
    $wardenId = $_POST['warden_id'];

    $sql = "UPDATE place SET status = 2, warden = '$wardenId' WHERE place_id = '$placeId'";



  

    if (mysqli_query($conn, $sql)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error updating place status']);
    }
}
?>