<?php
session_start();

// Check if the user is logged in and if their role is Landlord
if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Landlord') {
    // Redirect to login page or another appropriate page
    header('Location: ../access_denied.php'); // Replace 'login.php' with the path to your login page
    exit();
}

// Include the database connection
include '../ConnectionDB/DbConnection.php';

// Fetch all places from the database
$sql = "SELECT * FROM place WHERE status = 1";
$result = $conn->query($sql);
$places = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Edit/Delete Places</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>

     <?php include 'Navigation/Navigation.php'; ?>




    <div class="container">
        <h2>Edit/Delete Places</h2>


  <!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
 <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Place</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="editForm" enctype="multipart/form-data">
          <input type="hidden" id="editPlaceId" name="place_id">
          <div class="form-group">
            <label for="editTitle">Title</label>
            <input type="text" class="form-control" id="editTitle" name="title">
          </div>
          <div class="form-group">
            <label for="editDescription">Description</label>
            <textarea class="form-control" id="editDescription" name="description"></textarea>
          </div>
          <div class="form-group">
            <label for="editPrice">Price</label>
            <input type="text" class="form-control" id="editPrice" name="price">
          </div>
          <div class="form-group">
            <label for="editImage">Image</label>
            <input type="file" class="form-control-file" id="editImage" name="image">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="saveEdit">Save changes</button>
      </div>
    </div>
 </div>
</div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($places as $place): ?>
                    <tr data-id="<?= $place['place_id'] ?>">
                        <td><?= $place['title'] ?></td>
                        <td><?= $place['description'] ?></td>
                        <td><?= $place['price'] ?></td>
                        <td>
                            <button class="btn btn-primary edit-btn" data-id="<?= $place['place_id'] ?>">Edit</button>
                            <button class="btn btn-danger delete-btn" data-id="<?= $place['place_id'] ?>">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>
    $(document).ready(function() {
    $('.edit-btn').click(function() {
    var placeId = $(this).data('id');
    fetch('edit_place.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'place_id=' + encodeURIComponent(placeId),
    })
    .then(response => response.json())
    .then(data => {
        var place = data;
        // Populate the edit form with place details
        $('#editPlaceId').val(place.place_id);
        $('#editTitle').val(place.title);
        $('#editDescription').val(place.description);
        $('#editPrice').val(place.price);
        // Show the edit modal
        $('#editModal').modal('show');
    })
    .catch(error => console.error('Error:', error));
});

    $('#saveEdit').click(function() {
    var formData = new FormData($('#editForm')[0]); // Ensure this line is present
    $.ajax({
        url: 'updatePlace.php',
        type: 'POST',
        data: formData,
        processData: false, // Important: Prevents jQuery from altering the request
        contentType: false, // Important: Prevents jQuery from setting the content type
        success: function(data) {

            try{
                var response = JSON.parse(data);
            if (response.status === 'success') {
                // Update the table row with the new details
                var placeId = $('#editPlaceId').val();
                var title = $('#editTitle').val();
                var description = $('#editDescription').val();
                var price = $('#editPrice').val();

                // Find the table row by the place ID and update its cells
                $('tr[data-id="' + placeId + '"]').find('td:nth-child(1)').text(title);
                $('tr[data-id="' + placeId + '"]').find('td:nth-child(2)').text(description);
                $('tr[data-id="' + placeId + '"]').find('td:nth-child(3)').text(price);

                // Hide the edit modal
                $('#editModal').modal('hide');
                alert('Place updated successfully');
            } else {
                alert('Error updating place');
            }

            }catch(e){
                console.log(e);
            }

            


        }
    });
});

        $('.delete-btn').click(function() {
        var placeId = $(this).data('id');
        if (confirm('Are you sure you want to delete this place?')) {
            $.post('delete_place.php', {place_id: placeId}, function(data) {
                var response = JSON.parse(data);
                if (response.status === 'success') {
                    // Remove the row from the table
                    $('tr[data-id="' + placeId + '"]').remove(); // This line removes the row from the table
                    alert('Place deleted successfully');
                } else {
                    alert('Error deleting place');
                }
            });
        }
    });
});
    </script>
</body>
</html>