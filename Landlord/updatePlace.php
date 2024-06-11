<?php
include '../ConnectionDB/DbConnection.php';

if (isset($_POST['place_id'], $_POST['title'], $_POST['description'], $_POST['price'])) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    $placeId = $_POST['place_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $uploadDir = '../uploads/';
        $uploadFile = $uploadDir . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
            $imagePath = $uploadFile;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error uploading image']);
            exit;
        }
    } else {
        $imagePath = ''; // Use existing image path if no new image is uploaded
    }

    $sql = "UPDATE place SET title = '$title', description = '$description', price = '$price', image_path = '$imagePath' WHERE place_id = '$placeId'";


    if (mysqli_query($conn, $sql)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}
?>