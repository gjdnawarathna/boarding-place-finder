<nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="/BordingPlaces/Student/">Student Dashbord</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
            <li class="nav-item active">
                 <a class="nav-link" href="/BordingPlaces/">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item active">
                 <a class="nav-link" href="/BordingPlaces/Student/requests.php">My Requests<span class="sr-only">(current)</span></a>
            </li>
            
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="../BordingPlaces/register.php">
                    <?php
                         if(isset($_SESSION["username"])){
                              echo $_SESSION["username"];
                         }
                    ?>    
                <span class="sr-only">
                    <?php
                        if(isset($_SESSION["role"])){
                              echo $_SESSION["role"];
                         }
                    ?>
                </span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../logout.php">Logout <span class="sr-only">(current)</span></a>
            </li>
        </ul>
    </div>
</nav>