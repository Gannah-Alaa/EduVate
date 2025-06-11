<?php
include '../connection.php';
//session_start(); // Make sure session is started

$news="SELECT * FROM news ORDER BY NewsID DESC";
$result = mysqli_query($connect, $news);

if(isset($_POST['submit'])){
  $Email = $_POST['email'];
  $subject= $_POST['subject'];
  $message = $_POST['message'];
  $phone = $_POST['phone'];

  $query = "INSERT INTO contactus (ContactID, Message, Email, Subject, PhoneNumber) VALUES (NULL, '$Email', '$subject', '$message', '$phone')";
  if(mysqli_query($connect, $query)){
    echo "<script>alert('Message sent successfully!');</script>";
  } else {
    echo "<script>alert('Error sending message. Please try again later.');</script>";
  }

}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../fontawesome-free-6.4.0-web/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/home.css">
    <title>Home</title>
</head>
<body>
    <!-- start navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid ms-4">
            <a class="navbar-brand logo" href="#home"><img src="../Media/logo.png" width="250px" alt=""></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 me-4">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="#aboutus-container">AbouUs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="#events">Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="#contactus">ContactUs</a>
                    </li>
                    <?php if(isset($_SESSION['ParentID'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="ParentProfile.php">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="Subscription.php">Subscription</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="ParentChats.php">Chatting</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <?php if(isset($_SESSION['ParentID'])): ?>
                    <form method="post">
                        <button type="submit" name="logout" class="btn btn-outline-warning ms-3 me-4 ">Logout</button>
                    </form>
                <?php else: ?>
                    <button type="button" class="btn btn-outline-warning ms-3 me-4 "><a href="login.php">Login</a></button>
                <?php endif; ?>
            </div>
        </div>
    </nav>  <br><br>
    <!-- end navbar -->


      <!-- start landing -->

<div class="landing d-flex align-items-center" id="home">

      <!-- start left part -->


      <div class="left-container col-md-6 m-5">


        <h1 class="abril-fatface-regular">Smart Schools Start with <br> <span> EduVate.</span></h1>
        <h5 class="abril-fatface-regular">Elevating Education, Simplifying Communication</h5>
        
    </div>

    <!-- end left part -->

    <!-- start right part -->

    <div class="right-container col-md-4 ">
        <img src="../img/right side landing pic.jpg" alt="landing pic" class="landing-img shadow"width="400px" >
        <div class="pic-border shadow"></div>
    </div>

</div>

<!-- start about us -->
<div id="aboutus-container">
<div class="tit d-flex justify-content-center align-items-center position-relative pt-1">
  <h1>ABOUTUS</h1>
  <h2 class="position-absolute mt-2">ABOUT US</h2>

</div>

<div class="aboutus d-flex py-4">


  <div class=" abt-card mission col-lg-3 col-sm-12 text-center bg-light shadow py-2">
    <H5>VISION</H5>
    <p> simplify school life for families across Egypt through a smart, connected system that brings parents, students, and schools closer together.</p>
  </div>
  <div class=" abt-card vision col-lg-3 col-sm-12 text-center bg-light shadow py-2">
    <H5>MISSION</H5>
    <p>empower schools across Egypt with a reliable, easy-to-use SMS that streamlines administration, enhances communication, and supports student success through data-driven decision-making</p>
  </div>
  <div class=" abt-card objective col-lg-3 col-sm-12 text-center bg-light shadow py-2">
    <H5>OBJECTIVE</H5>
    <p> Simplify school life by connecting parents, students, and schools through one smart, unified system.</p>
  </div>
  

</div>
</div>
<!-- end about us -->


<!-- start news slider -->

<div class="event-container" id="events">

<div class=" tit-events d-flex justify-content-center align-items-center position-relative pt-1">
  <h1>EVENTS</h1>
  <h2 class="position-absolute mt-2">EVENTS</h2>
</div>

<?php
// Fetch all news into an array for easier looping
$newsItems = [];
while ($row = mysqli_fetch_assoc($result)) {
    $newsItems[] = $row;
}
?>

<div id="carouselExampleCaptions" class="carousel slide px-5 pb-5">
  <div class="carousel-indicators">
    <?php foreach($newsItems as $idx => $new): ?>
      <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="<?php echo $idx; ?>" <?php if($idx==0) echo 'class="active" aria-current="true"'; ?> aria-label="Slide <?php echo $idx+1; ?>"></button>
    <?php endforeach; ?>
  </div>
  <div class="carousel-inner w-70 m-auto">
    <?php foreach($newsItems as $idx => $new): ?>
      <div class="carousel-item <?php if($idx==0) echo 'active'; ?>">
        <img src="../media/<?php echo htmlspecialchars($new['Pics']); ?>"
             class="d-block w-100 slide-img"
             style="object-fit:cover; width:100%; height:350px; border-radius:10px;"
             alt="Event Image">
        <div class="carousel-caption d-sm-block" style="background: rgba(2, 26, 0, 0.6); border-radius: 8px; padding: 1rem;">
          <h5 style="color: #fff;"><?php echo htmlspecialchars($new['Title']); ?></h5>
          <p style="color: #fff;"><?php echo htmlspecialchars($new['desc']); ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

</div>

<!-- end sliderr -->

<!-- start contact us -->

<div class="contactus-page mt-5" id="contactus">


<div class="contactus-page" id="contactus">
        <div class="tit d-flex justify-content-center align-items-center position-relative pt-1">
            <h1>CONTACTUS</h1>
            <h2 class="position-absolute mt-2">CONTACT US</h2>
        </div>

        <div class="contactus-container d-flex mt-5">
            <!-- Info Part -->
            <div class="info col-lg-4 ms-5 mt-4">
                <div class="phone d-flex">
                    <i class="fa-solid fa-phone"></i>
                    <p class="ms-2">0123456789</p>
                </div>
                <div class="mail d-flex">
                    <i class="fa-solid fa-inbox"></i>
                    <p class="ms-2">info@gmail.com</p>
                </div>
                <div class="address d-flex">
                    <i class="fa-solid fa-location-dot"></i>
                    <p class="ms-2">Lorem ipsum dolor sit amet.</p>
                </div>
            </div>

            <!-- Inputs Part -->
            <div class="inputs col-lg-7 me-5">
                <form id="contactForm" method="post" action="">
                    <div class="flex-inputs d-flex">
                        <div class="input mb-3 col-lg-6">
                            <input type="text" name="email" class="form-control" placeholder="Email" required>
                        </div>
                        <div class="input mb-3 col-lg-6">
                            <input type="text" name="phone" class="form-control" placeholder="Phone number" required>
                        </div>
                    </div>
                    <div class="input mb-3">
                        <input type="text" class="form-control" name="subject" placeholder="Subject" required>
                    </div>
                    <textarea name="message" id="text-area" class="form-control" placeholder="write your question here.." required></textarea>
                    <div class="text-center">
                        <button type="submit" name="submit" class="btn submit-btn">Send Message</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- end inputs part -->

<!-- end contact us -->

<!-- start footer -->

 <footer>
  <section class="p-3 pt-2 mt-5 footer">
    <div class="row d-flex align-items-center">
      <div class="col-md-7 col-lg-8 text-center text-md-start">
        <div class="p-3 text-warning">
          © 2025 Copyright
        </div>
      </div>

      <div class="col-md-5 col-lg-4 ml-lg-0 text-center text-md-end">
        <a
           class="btn btn-outline-warning btn-floating m-1"
           class="text-warning"
           role="button"
           ><i class="fab fa-facebook-f"></i
          ></a>

          <a
          class="btn btn-outline-warning btn-floating m-1"
           class="text-warning"
           role="button"
           ><i class="fab fa-twitter"></i
          ></a>

        <a
           class="btn btn-outline-warning btn-floating m-1"
           class="text-warning"
           role="button"
           ><i class="fab fa-google"></i
          ></a>

        <a
           class="btn btn-outline-warning btn-floating m-1"
           class="text-warning"
           role="button"
           ><i class="fab fa-instagram"></i
          ></a>
      </div>

      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3452.784704148786!2d31.220951999999997!3d30.071705199999993!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x145840fc5cf0dcfb%3A0x5c8b8a3b52b6bcbc!2sFaculty%20of%20Commerce%20and%20Business%20Administration%20Helwan%20University!5e0!3m2!1sen!2seg!4v1745734229322!5m2!1sen!2seg" width="100%" height="25%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
  </section>

</footer> 



<script src="js/home.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html> 



<!-- <div class="up me-5 mb-5">
  <button class="upbtn p-3 ">
    <i class="fa-solid fa-arrow-up-long"></i>
  </button>
</div> -->
