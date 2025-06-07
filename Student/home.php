<!-- start navbar -->

    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid ms-4">
            <a class="navbar-brand logo" href="#home">EduVate</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 me-4">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="home.php#aboutus-container">AboutUs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="home.php#events">Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="home.php#contactus">ContactUs</a>
                    </li>
                    <?php if(isset($_SESSION['StudentID'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="Profile.php">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="FinalReport.php">Marks</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="Profile.php#classes">Subjects</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <?php if(isset($_SESSION['StudentID'])): ?>
                    <form method="post">
                        <button type="submit" name="logout" class="btn btn-outline-warning ms-3 me-4 ">Logout</button>
                    </form>
                <?php else: ?>
                    <button type="button" class="btn btn-outline-warning ms-3 me-4 "><a href="login.php">Login</a></button>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <!-- end navbar -->
