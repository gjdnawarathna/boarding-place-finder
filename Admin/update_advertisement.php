<?php
include '../ConnectionDB/DbConnection.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];

    var_dump($_FILES);
    
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
        $imagePath = 'this runs man'; // Use existing image path if no new image is uploaded
    }

    // Prepare an SQL statement
    $sql = "UPDATE advertisements SET title = '$title', description = '$description', image = '$imagePath' WHERE id = '$id'";
    // $stmt = $conn->prepare($sql);
    // $stmt->bind_param("sssi", $title, $description, $imagePath, $id);

    // Execute the statement
    if (mysqli_query($conn, $sql)) {
        echo "Advertisement updated successfully.";
    } else {
        echo "Error updating advertisement.";
    }
}
?>
