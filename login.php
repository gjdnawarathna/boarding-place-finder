<?php
session_start();

// include the database connection here
include './ConnectionDB/DbConnection.php';

// check whether the submit button is clicked
if(isset($_POST['submit'])){
    // get the values from the form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // fetch the user from the database
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            if(password_verify($password, $row['password'])){

                $_SESSION['email'] = $email;
                $_SESSION['role'] = $row['role'];
                $_SESSION['id'] = $row['user_id'];
                $_SESSION['username'] = $row['username'];

                $_SESSION['success'] = "Logged in successfully";
                // Redirect users based on their roles
                if($row['role'] == 'Admin') {
                    header('Location: /BordingPlaces/Admin/index.php');
                } else if($row['role'] == 'Student') {
                    header('Location: /BordingPlaces/Student/index.php');
                } else if($row['role'] == 'Landlord') {
                    header('Location: /BordingPlaces/Landlord/index.php');
                } else if($row['role'] == 'Warden') {
                    header('Location: /BordingPlaces/Warden/index.php');
                }else {
                    // Redirect to a default page if the role is not recognized
                    header('Location: default_page.php');
                }
                exit();
            } else {
                $_SESSION['error'] = "Invalid Password";;
            }
        }
    } else {
        echo "No user found with this email.";
    }
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login Page</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <!-- Navigation Bar -->
    <!-- import the navigation bar here -->
    <?php include 'Navigation/Navigation.php'; ?>
    <!-- create a bootstrap form to login -->
    <div class="container">
        <h2>Login</h2>
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
        }else if(isset($_SESSION['error'])){
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
            echo $_SESSION['error'];
            echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
            echo '<span aria-hidden="true">&times;</span>';
            echo '</button>';
            echo '</div>';
            // Clear the session variable to prevent the alert from showing again on page reload
            unset($_SESSION['error']);
        }
        ?>

        <form method="post" action="">
            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" class="form-control" id="exampleInputEmail1" name="email" aria-describedby="emailHelp" placeholder="Enter email">
                <div id="emailError" class="text-danger"></div>
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Password">
                <div id="passwordError" class="text-danger"></div>
            </div>

            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
        </form>
    </div>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <!-- validate email field -->
    <script>
    $(document).ready(function(){
        $("button[name='submit']").click(function(e){
            var email = $("#exampleInputEmail1").val();
            var password = $("#exampleInputPassword1").val();
            var isValid = true;

            // Email validation
            if(email.length > 0){
                if(email.indexOf('@') > 0 && email.indexOf('.') > 0){
                    $("#exampleInputEmail1").removeClass('is-invalid');
                    $("#exampleInputEmail1").addClass('is-valid');
                } else {
                    $("#exampleInputEmail1").removeClass('is-valid');
                    $("#exampleInputEmail1").addClass('is-invalid');
                    $("#emailError").text("Please enter a valid email address.");
                    isValid = false;
                }
            } else {
                $("#exampleInputEmail1").removeClass('is-valid');
                $("#exampleInputEmail1").removeClass('is-invalid');
                $("#emailError").text("Email is required.");
                isValid = false;
            }

            // Password validation
            if(password.length < 8){
                $("#exampleInputPassword1").removeClass('is-valid');
                $("#exampleInputPassword1").addClass('is-invalid');
                $("#passwordError").text("Password must be at least 8 characters long.");
                isValid = false;
            } else {
                $("#exampleInputPassword1").removeClass('is-invalid');
                $("#exampleInputPassword1").addClass('is-valid');
            }

            // Prevent form submission if any validation fails
            if(!isValid){
                e.preventDefault(); // Prevent form submission
            }
        });
    });
    </script>
</body>
</html>