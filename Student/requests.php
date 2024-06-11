<?php
    session_start();

    // Check if the user is logged in and if their role is Student
    if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Student') {
        // Redirect to login page or another appropriate page
        header('Location: ../access_denied.php');
        exit();
    }

    // Include the database connection here
    include '../ConnectionDB/DbConnection.php';

    // Fetch all requests made by the student
    $sql = "SELECT requests.*, place.title, place.description, place.price, place.latitude, place.longitude, place.image_path FROM requests JOIN place ON requests.place_id = place.place_id WHERE requests.student_id = ? AND requests.status = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $_SESSION['id']);
    $stmt->execute();
    $requests = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cancel Requests</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <!-- Navigation Bar -->
    <!-- Import the navigation bar here -->
    <?php include 'Navigation/Navigation.php'; ?>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3>Your Requests</h3>
                <ul id="requests" class="list-group">
                    <?php foreach ($requests as $request): ?>
                        <li class="list-group-item" data-id="<?= $request['id'] ?>">
                            <h5><?= $request['title'] ?></h5>
                            <p><?= $request['description'] ?></p>
                            <p>Price: <?= $request['price'] ?></p>
                            
                            <img src="<?= $request['image_path'] ?>" alt="<?= $request['title'] ?>" style="width:20%;" class="ml-auto">
                            <button class="btn btn-danger cancel-btn">Cancel</button>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.cancel-btn').click(function() {
                var requestId = $(this).closest('li').data('id');
                var $listItem = $(this).closest('li');

                $.post('cancel_request.php', {request_id: requestId}, function(data) {
                    var response = JSON.parse(data);
                    if (response.status === 'success') {
                        // Remove the list item from the page
                        $listItem.remove();
                        alert('Request cancelled successfully');

                        // Remove the corresponding marker from the map
                        // Assuming you have a way to map request IDs to markers
                        var markerToRemove = markers.find(marker => marker.requestId === requestId);
                        if (markerToRemove) {
                            markerToRemove.setMap(null);
                            markers = markers.filter(marker => marker.requestId !== requestId);
                        }
                    } else {
                        alert('Error cancelling request');
                    }
                });
            });
        });
    </script>
</body>
</html>
