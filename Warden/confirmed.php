<?php
    session_start();

    // Check if the user is logged in and if their role is Landlord
    if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Warden') {
        // Redirect to login page or another appropriate page
        header('Location: ../access_denied.php'); // Replace 'login.php' with the path to your login page
        exit();
    }

    // include the database connection here
    include '../ConnectionDB/DbConnection.php';

    // Fetch all places from the database
    $sql = "SELECT * FROM place WHERE status = 1";
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
</head>
<body>
    <!-- Navigation Bar -->
    <!-- import the naviation bar here -->
    <?php include 'Navigation/Navigation.php'; ?>

    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h3>Places</h3>
                <ul id="places" class="list-group">
                    <?php foreach ($places as $place): ?>
                        
                        <li class="list-group-item" data-lat="<?= $place['latitude'] ?>" data-lng="<?= $place['longitude'] ?>" data-title="<?= $place['title'] ?>" data-description="<?= $place['description'] ?>" data-price="<?= $place['price'] ?>" data-image="<?= $place['image_path'] ?>" data-id="<?= $place['place_id'] ?>" >
                            <?= $place['title'] ?>
                            <button class="btn btn-danger verify-btn" data-id="<?= $place['place_id'] ?>">Decline</button>
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
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBTFGArXcSNpCDGgz7LJzyRsl9YSfeMHGs&callback=initMap"></script>

    

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
                var imagePath = $(this).data('image'); // Fetch the image path
                var placeId = $(this).data('id'); // Fetch the place ID

                var marker = new google.maps.Marker({
                    position: { lat: lat, lng: lng },
                    map: map,
                    title: title,
                    place_id: placeId // Store the place ID as a custom property
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


        
        $(document).ready(function() {
        $('.verify-btn').click(function() {
            var placeId = $(this).data('id');
            var wardenId = "<?php echo $_SESSION['id']; ?>";
            var $listItem = $(this).closest('li');

            $.post('decline.php', {place_id: placeId, warden_id: wardenId}, function(data) {
                var response = JSON.parse(data);
                if (response.status === 'success') {
                    // Remove the list item from the page
                    $listItem.remove();
                    alert('Request declined successfully');


                     var markerToRemove = markers.find(marker => marker.place_id === placeId);
                        if (markerToRemove) {
                            markerToRemove.setMap(null);
                            markers = markers.filter(marker => marker.place_id !== placeId);
                        }

                } else {
                    alert('Error verifying place');
                }
            });




           

        });
    });

    </script>
     <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBTFGArXcSNpCDGgz7LJzyRsl9YSfeMHGs&callback=initMap"></script>

</body>
</html>