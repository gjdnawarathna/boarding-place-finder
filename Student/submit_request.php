<?php
// Include your database connection here
include '../ConnectionDB/DbConnection.php';

if (isset($_POST['place_id']) && isset($_POST['student_id']) && isset($_POST['message'])) {
    $placeId = $_POST['place_id'];
    $studentId = $_POST['student_id'];
    $message = $_POST['message'];


    // Insert the request into the database
    $sql = "INSERT INTO requests (place_id, student_id, message, status) VALUES ('$placeId', '$studentId', '$message', '1')";

    // $stmt = $conn->prepare($sql);
    // $stmt->bind_param("sss", $placeId, $studentId, $message);

   
   


    if (mysqli_query($conn, $sql)) {
         $sql2 = "Update place set student_id = '$studentId' where place_id = '$placeId'";
         if(mysqli_query($conn, $sql2)){
            echo json_encode(['status' => 'success']);
        }
        
    } else {
        echo json_encode(['status' => 'error']);
    }
}
?>