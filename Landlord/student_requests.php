<?php
    session_start();

    // Check if the user is logged in and if their role is Landlord
    if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Landlord') {
        // Redirect to login page or another appropriate page
        header('Location: ../access_denied.php');
        exit();
    }

    // Include the database connection here
    include '../ConnectionDB/DbConnection.php';

    // Fetch all requests made to the places owned by the landlord
    $sql = "SELECT requests.*, place.title, place.description, place.price, place.latitude, place.longitude, place.image_path FROM requests JOIN place ON requests.place_id = place.place_id WHERE place.landlord = ? AND requests.status = '1'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $_SESSION['id']);
    $stmt->execute();
    $requests = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Accept Requests</title>
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
                <h3>Requests for Your Places</h3>
                <ul id="requests" class="list-group">
                    <?php foreach ($requests as $request): ?>
                        <li class="list-group-item" data-id="<?= $request['id'] ?>">
                            <h5><?= $request['title'] ?></h5>
                            <p><?= $request['description'] ?></p>
                            <p>Price: <?= $request['price'] ?></p>
                            <p>Message: <?= $request['message'] ?> </p>
                            <img src="<?= $request['image_path'] ?>" alt="<?= $request['title'] ?>" style="width:20%;">
                            <button class="btn btn-success accept-btn">Accept</button>
                            <button class="btn btn-danger cancel-request-btn">Cancel Request</button>
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
            $('.accept-btn').click(function() {
                var requestId = $(this).closest('li').data('id');
                var $listItem = $(this).closest('li');

                $.post('accept_request.php', {request_id: requestId}, function(data) {
                    var response = JSON.parse(data);
                    if (response.status === 'success') {
                        // Update the list item to reflect the change
                        $listItem.find('.accept-btn').text('Accepted').removeClass('btn-success').addClass('btn-secondary').prop('disabled', true);
                        alert('Request accepted successfully');

                        // Update the corresponding marker on the map
                        // Assuming you have a way to map request IDs to markers
                        var markerToUpdate = markers.find(marker => marker.requestId === requestId);
                        if (markerToUpdate) {
                            // Update the marker's icon or color to indicate it's accepted
                            markerToUpdate.setIcon('path/to/accepted/icon.png');
                        }
                    } else {
                        alert('Error accepting request');
                    }
                });
            });
        });

    $(document).ready(function() {
        $('.cancel-request-btn').click(function() {
        var requestId = $(this).closest('li').data('id');
        var $listItem = $(this).closest('li');

        $.post('cancel_request_by_landlord.php', {request_id: requestId}, function(data) {
            var response = JSON.parse(data);
            if (response.status === 'success') {
                // Update the list item to reflect the change
                $listItem.find('.cancel-request-btn').text('Cancelled').removeClass('btn-danger').addClass('btn-secondary').prop('disabled', true);
                alert('Request cancelled successfully');

                // Update the corresponding marker on the map
                // Assuming you have a way to map request IDs to markers
                var markerToUpdate = markers.find(marker => marker.requestId === requestId);
                if (markerToUpdate) {
                    // Update the marker's icon or color to indicate it's cancelled
                    markerToUpdate.setIcon('path/to/cancelled/icon.png');
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
