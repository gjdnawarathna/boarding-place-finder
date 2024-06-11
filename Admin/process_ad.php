<?php
session_start();    
include '../ConnectionDB/DbConnection.php'; 


// Assuming you have a database connection established as $conn
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and sanitize inputs here
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $startDate = filter_input(INPUT_POST, 'start_date', FILTER_SANITIZE_STRING);
    $endDate = filter_input(INPUT_POST, 'end_date', FILTER_SANITIZE_STRING);

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        // Specify the target directory
        $targetDir = "../uploads/";
        // Ensure the target directory exists, if not, create it
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        // Define the target file path
        $targetFile = $targetDir . basename($_FILES["image"]["name"]);
        
        // Attempt to move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            // Prepare an SQL statement to insert the image path into the database
            $stmt = $conn->prepare("INSERT INTO advertisements (title, description, image, start_date, end_date) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $title, $description, $targetFile, $startDate, $endDate);

            // Execute the statement
            if ($stmt->execute()) {
                echo "The advertisement has been uploaded successfully.";
                $_SESSION['success'] = "The advertisement created successfully";
                header('Location: ./create_advertisement.php');
                die();
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "Error uploading file.";
    }
}
?>