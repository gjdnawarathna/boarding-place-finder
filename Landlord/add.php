<?php
    session_start();

    // Check if the user is logged in and if their role is Landlord
    if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Landlord') {
        // Redirect to login page or another appropriate page
        header('Location: ../access_denied.php'); // Replace 'login.php' with the path to your login page
        exit();
    }
?>

<?php

// include the database connection here
include '../ConnectionDB/DbConnection.php';

// check whether the submit button is clicked
// Check whether the form is submitted
if(isset($_POST['submit'])) {
    // Get the values from the form
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $landlord = $_SESSION['id']; // Assuming you store the landlord's user_id in session
    $status = 0; // Assuming default status is 0

    // Generate a unique place id
    $place_id = uniqid();

    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Define allowed file types
        $allowed = array('jpg', 'jpeg', 'png', 'gif');
        $filename = $_FILES['image']['name'];
        $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        // Check if the file type is allowed
        if(!in_array($filetype, $allowed)) {
            echo "Error: Only JPG, JPEG, PNG, and GIF files are allowed.";
        } else {
            // Define the upload directory
            $uploadDir = '../uploads/';
            $uploadFile = $uploadDir . basename($filename);

            // Move the uploaded file to the upload directory
            if(move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                // File is valid, and was successfully uploaded.
                // You can now insert the file path into the database
                $imagePath = $uploadFile; // Adjust this as needed
            } else {
                echo "Error: There was an error uploading your file.";
            }
        }
    } else {
        echo "Error: No file was uploaded.";
    }

    // Existing code to insert values into the database...

    // Insert the values into the database
    $sql = "INSERT INTO place (place_id, title, description, price, latitude, longitude, landlord, status, image_path) 
        VALUES ('$place_id', '$title', '$description', '$price', '$latitude', '$longitude', '$landlord', '$status', '$imagePath')";


    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "New record created successfully";
        // Redirect to a success page or do something else
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }





}
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
        <?php
        // Check if the session variable is set and display the alert
        if (isset($_SESSION['success'])) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
            echo $_SESSION['success'];
            echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
            echo '<span aria-hidden="true">&times;</span>';
            echo '</button>';
            echo '</div>';
            // Clear the session variable to prevent the alert from showing again on page reload
            unset($_SESSION['success']);
        }
        ?>

        <div class="row">
    <div class="col-md-4">
        <h3>Fill the form</h3>


    <form  method="post" action="" enctype="multipart/form-data">
    <div class="form-group">
        <label for="propertyName">Title</label>
        <input type="text" id="propertyName" class="form-control" name="title">
        <span class="text-danger" id="titleError"></span>
    </div>

    <div class="form-group">
        <label for="Description">Description</label>
        <textarea id="Description" class="form-control" name="description"></textarea>
        <span class="text-danger" id="descriptionError"></span>
    </div>

    <div class="form-group">
        <label for="propertyPrice">Price</label>
        <input type="text" id="propertyPrice" class="form-control" name="price">
        <span class="text-danger" id="priceError"></span>
    </div>

    <div class="form-group">
        <label for="propertyLat">Latitude</label>
        <input type="text" id="propertyLat" class="form-control" readonly name="latitude">
        <span class="text-danger" id="latitudeError"></span>
    </div>

    <div class="form-group">
        <label for="propertyLng">Longitude</label>
        <input type="text" id="propertyLng" class="form-control" readonly name="longitude">
        <span class="text-danger" id="longitudeError"></span>
    </div>


    <div class="form-group">
        <label for="propertyImage">Image</label>
        <input type="file" id="propertyImage" class="form-control" name="image">
        <span class="text-danger" id="imageError"></span>
    </div>


    <div class="m-2">
        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
    </div>
</form>



        <ul id="properties">
            <!-- List of properties will be populated here -->
        </ul>
    </div>
    <div class="col-md-8">
        <h2>Select your location</h2>
        <div id="map" style="height: 400px;"></div>
    </div>
</div>

    </div>













    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>

    <script>
    $(document).ready(function(){
        $("#propertyForm").submit(function(e){
            var title = $("#propertyName").val();
            var description = $("#Description").val();
            var price = $("#propertyPrice").val();
            var latitude = $("#propertyLat").val();
            var longitude = $("#propertyLng").val();
            var isValid = true;

            // Title validation
            if(title.trim() === ""){
                $("#propertyName").removeClass('is-valid');
                $("#propertyName").addClass('is-invalid');
                $("#titleError").text("Title is required.");
                isValid = false;
            } else {
                $("#propertyName").removeClass('is-invalid');
                $("#propertyName").addClass('is-valid');
                $("#titleError").text("");
            }

            // Description validation
            if(description.trim() === ""){
                $("#Description").removeClass('is-valid');
                $("#Description").addClass('is-invalid');
                $("#descriptionError").text("Description is required.");
                isValid = false;
            } else {
                $("#Description").removeClass('is-invalid');
                $("#Description").addClass('is-valid');
                $("#descriptionError").text("");
            }

            // Price validation
            if(price.trim() === ""){
                $("#propertyPrice").removeClass('is-valid');
                $("#propertyPrice").addClass('is-invalid');
                $("#priceError").text("Price is required.");
                isValid = false;
            } else {
                $("#propertyPrice").removeClass('is-invalid');
                $("#propertyPrice").addClass('is-valid');
                $("#priceError").text("");
            }

            // Latitude validation
            if(latitude.trim() === ""){
                $("#propertyLat").removeClass('is-valid');
                $("#propertyLat").addClass('is-invalid');
                $("#latitudeError").text("Latitude is required.");
                isValid = false;
            } else {
                $("#propertyLat").removeClass('is-invalid');
                $("#propertyLat").addClass('is-valid');
                $("#latitudeError").text("");
            }

            // Longitude validation
            if(longitude.trim() === ""){
                $("#propertyLng").removeClass('is-valid');
                $("#propertyLng").addClass('is-invalid');
                $("#longitudeError").text("Longitude is required.");
                isValid = false;
            } else {
                $("#propertyLng").removeClass('is-invalid');
                $("#propertyLng").addClass('is-valid');
                $("#longitudeError").text("");
            }

            // Prevent form submission if any validation fails
            if(!isValid){
                e.preventDefault(); // Prevent form submission
            }
        });
    });
</script>


    <script>
        var map;
        var markers = [];

        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 15,
                center: { lat: 6.8192122, lng: 80.0281007 }
            });

            map.addListener('click', function (e) {
                addMarker({
                    lat: e.latLng.lat(),
                    lng: e.latLng.lng(),
                    name: 'New Property'
                });
            });


        }

        function addMarker(property) {
            var marker = new google.maps.Marker({
                position: { lat: property.lat, lng: property.lng },
                map: map,
                title: property.name,
               
            });

            var infoWindow = new google.maps.InfoWindow({
                content: '<h1>' + property.name + '</h1>'
            });

            marker.addListener('click', function () {
                infoWindow.open(map, marker);
            });



            // Add a right-click event listener
            marker.addListener('rightclick', function () {
                // Remove the marker from the map
                marker.setMap(null);

                // Remove the marker from the markers array
                var index = markers.indexOf(marker);
                if (index !== -1) {
                    markers.splice(index, 1);
                }

                // Clear the lat and lng form fields
                $('#propertyLat').val('');
                $('#propertyLng').val('');
            });




            $('#propertyLat').val(property.lat);
            $('#propertyLng').val(property.lng);

            markers.push(marker);






        }

        function clearMarkers() {
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(null);
            }
            markers = [];
        }


        $(document).ready(function () {

            var property = {
                lat: 6.8168858,
                lng: 80.0126683,
                name: 'Kanage Gedara'
            };

            addMarker(property);
            // Fetch properties from your API
            $.get('/api/properties', function (properties) {
                var $propertiesList = $('#properties');
                properties.forEach(function (property) {
                    var $li = $('<li>').text(property.name).appendTo($propertiesList);
                    $li.click(function () {
                        clearMarkers();
                        addMarker(property);

                    });
                });
            });

        });

    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBTFGArXcSNpCDGgz7LJzyRsl9YSfeMHGs&callback=initMap"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>