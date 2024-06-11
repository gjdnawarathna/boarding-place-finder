// scripts.js
$(document).ready(function() {
    $.ajax({
        url: 'fetch_advertisements.php',
        type: 'GET',
        success: function(data) {
            $('#advertisementsTable tbody').html(data);
        }
    });
});


// scripts.js
$(document).on('click', '.edit-btn', function() {
    var id = $(this).data('id');
    $.ajax({
        url: 'fetch_advertisement.php',
        type: 'GET',
        data: { id: id },
        success: function(data) {
            $('#editModal .modal-body').html(data);
            $('#editModal').modal('show');
        }
    });
});

$('#saveChanges').click(function() {
    var formData = $('#editModal form').serialize();
    $.ajax({
        url: 'update_advertisement.php',
        type: 'POST',
        data: formData,
        success: function(data) {
            $('#editModal').modal('hide');
            // Reload the table to reflect changes
            $.ajax({
                url: 'fetch_advertisements.php',
                type: 'GET',
                success: function(data) {
                    $('#advertisementsTable tbody').html(data);
                }
            });
        }
    });
});

$(document).on('click', '.remove-btn', function() {
    var id = $(this).data('id');
    if (confirm('Are you sure you want to remove this advertisement?')) {
        $.ajax({
            url: 'remove_advertisement.php',
            type: 'POST',
            data: { id: id },
            success: function(data) {
                // Reload the table to reflect changes
                $.ajax({
                    url: 'fetch_advertisements.php',
                    type: 'GET',
                    success: function(data) {
                        $('#advertisementsTable tbody').html(data);
                    }
                });
            }
        });
    }
});
