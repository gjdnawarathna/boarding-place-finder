<?php
    session_start();

    // Check if the user is logged in and if their role is Landlord
    if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Landlord') {
        // Redirect to login page or another appropriate page
        header('Location: ../access_denied.php'); // Replace 'login.php' with the path to your login page
        exit();
    }
?>



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

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>