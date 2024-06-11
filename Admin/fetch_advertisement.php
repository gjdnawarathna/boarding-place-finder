<?php
include '../ConnectionDB/DbConnection.php'; 

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM advertisements WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $ad = $result->fetch_assoc();
        echo "<form id='editForm' enctype='multipart/form-data' method='post'>";
        echo "<input type='hidden' name='id' value='" . $ad['id'] . "'>";
        echo "<input type='hidden' name='oldImagePath' value='" . $ad['image'] . "'>"; // Store the old image path
        echo "<div class='form-group'><label>Title</label><input type='text' name='title' class='form-control' value='" . $ad['title'] . "'></div>";
        echo "<div class='form-group'><label>Description</label><textarea name='description' class='form-control'>" . $ad['description'] . "</textarea></div>";
        echo "<div class='form-group'><label>Image</label><input type='file' name='image' class='form-control'></div>";
        echo "</form>";
    }
}
?>
