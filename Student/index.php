<?php
    session_start();

    // Check if the user is logged in and if their role is Warden
    if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Student') {
        // Redirect to login page or another appropriate page
        header('Location: ../access_denied.php');
        exit();
    }

    // Include the database connection here
    include '../ConnectionDB/DbConnection.php';

    // Fetch all places from the database
    $sql = "SELECT * FROM place WHERE status = 1 AND student_id != '$_SESSION[id]' ";
    $result = $conn->query($sql);
    $places = $result->fetch_all(MYSQLI_ASSOC);

    
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Bootstrap Page</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        /* dialog {
            width: 300px;
            border: none;
            border-radius: 5px;
            padding: 20px;
            background: white;
        } */

        .dialog-form {
    width: 300px;
    padding: 20px;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.dialog-title {
    margin-top: 0;
    font-size: 1.5rem;
    color: #333333;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    font-weight: bold;
}

.form-control {
    width: 100%;
    padding: 8px;
    border: 1px solid #cccccc;
    border-radius: 4px;
    box-sizing: border-box;
}

.button-group {
    display: flex;
    justify-content: flex-end;
}

.btn {
    padding: 8px 16px;
    margin-left: 8px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.btn-primary {
    background-color: #007bff;
    color: #ffffff;
}

.btn-secondary {
    background-color: #6c757d;
    color: #ffffff;
}
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <!-- Import the navigation bar here -->
    <?php include 'Navigation/Navigation.php'; ?>

    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h3>Places</h3>
                <ul id="places" class="list-group">
                    <?php foreach ($places as $place): ?>
                        <li class="list-group-item" data-lat="<?= $place['latitude'] ?>" data-lng="<?= $place['longitude'] ?>" data-title="<?= $place['title'] ?>" data-description="<?= $place['description'] ?>" data-price="<?= $place['price'] ?>" data-image="<?= $place['image_path'] ?>" data-id="<?= $place['place_id'] ?>" >
                            <?= $place['title'] ?>
                            <button class="btn btn-primary verify-btn" data-id="<?= $place['place_id'] ?>">Request</button>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="col-md-8">
                <h2>Map</h2>
                <div id="map" style="height: 400px;"></div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and jQuery -->
     <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    


    <!-- <dialog id="requestDialog">
        <form method="dialog">
            <p><label>Message:<br>
                <input type="text" id="messageInput">
            </label></p>
            <menu>
                <button value="submit">Submit</button>
                <button value="cancel">Cancel</button>
            </menu>
        </form>
    </dialog> -->

    <dialog id="requestDialog">
    <form method="dialog" class="dialog-form">
        <h2 class="dialog-title">Send Request</h2>
        <div class="form-group">
            <label for="messageInput" class="form-label">Message:</label>
           
            <textarea name="" id="messageInput" cols="30" rows="10" class="form-control"></textarea>
        </div>
        <div class="button-group">
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="button" class="btn btn-secondary" onclick="closeDialog()">Cancel</button>
        </div>
    </form>
</dialog>

    <script>
        var map;
        var markers = [];

        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 15,
                center: { lat: 6.8192122, lng: 80.0281007 }
            });

            $('#places li').each(function() {
                var lat = $(this).data('lat');
                var lng = $(this).data('lng');
                var title = $(this).data('title');
                var description = $(this).data('description');
                var price = $(this).data('price');
                var imagePath = $(this).data('image');
                var placeId = $(this).data('id');

                var marker = new google.maps.Marker({
                    position: { lat: lat, lng: lng },
                    map: map,
                    title: title
                });

                var infoWindow = new google.maps.InfoWindow({
                    content: '<h1>' + title + '</h1><h5>' + description + '</h5><h6 class="text-primary">Rs. ' + price + '/=</h6><img src="' + imagePath + '" alt="' + title + '" style="width:100%;">'
                });

                marker.addListener('click', function () {
                    infoWindow.open(map, marker);
                });

                markers.push(marker);

                $(this).on('click', function() {
                    map.setCenter(marker.getPosition());
                    infoWindow.open(map, marker);
                });
            });
        }

        function handleRequest(button) {
            var dialog = document.getElementById('requestDialog');
            dialog.showModal();

            dialog.querySelector('form').addEventListener('submit', function(event) {
                event.preventDefault();
                var message = document.getElementById('messageInput').value;
                var placeId = button.getAttribute('data-id');
                var studentId = "<?php echo $_SESSION['id']; ?>";

                $.post('submit_request.php', {place_id: placeId, student_id: studentId, message: message}, function(data) {
                    var response = JSON.parse(data);
                    if (response.status === 'success') {
                        alert('Request submitted successfully');
                        button.disabled = true;
                        button.textContent = "Requested";
                    } else {
                        alert('Error submitting request');
                    }
                });

                dialog.close();
            });
        }


            function closeDialog() {
                var dialog = document.getElementById('requestDialog');
                dialog.close();
            }


        $(document).ready(function() {
            $('.verify-btn').click(function() {
                handleRequest(this);
            });
        });
    </script>
   
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBTFGArXcSNpCDGgz7LJzyRsl9YSfeMHGs&callback=initMap"></script>
</body>
</html>
