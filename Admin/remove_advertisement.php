<?php
include '../ConnectionDB/DbConnection.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $sql = "DELETE FROM advertisements WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "Advertisement removed successfully.";
    } else {
        echo "Error removing advertisement.";
    }
}
?>
