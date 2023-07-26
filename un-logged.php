<?php
session_start();
if (ini_get('register_globals')) {
  foreach ($_SESSION as $key => $value) {
    if (isset($GLOBALS[$key]))
      unset($GLOBALS[$key]);
  }
}
include('db.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>e Library</title>
  <link rel="stylesheet" href="style.css">
  <link rel="icon" href="images/title.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="body">
  <div class="home">
    <nav class="navbar navbar-expand-lg d-flex justify-content-between">
      <div class="container-fluid">
        <a class=" library-logo display-2 d-none d-lg-block">e Library</a>
        <button class="navbar-toggler " type="button" data-bs-toggle="collapse" aria-expanded="false" aria-label="Toggle navigation" data-bs-target="#toggle-nav" aria-controls="toggle-nav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <button type="button" onclick="toggleProfileModal()" style="display:none;" class=" d-lg-none ms-lg-2 btn rounded-circle profile-btn btn-secondary">
          <i class="fas fa-user"></i>
        </button>
        <div class="collapse navbar-collapse justify-content-lg-end" id="toggle-nav">
          <ul class="navbar-nav text-lg-center align-items-lg-center ">
            <li class="nav-item"><a class="n-item fs-4" href="#topBooks">Top Books</a></li>
            <li class="nav-item"><a class="n-item fs-4" href="#" data-bs-toggle="modal" data-bs-target="#contactForm">Contact Us</a></li>
            <li class="nav-item login-hook"><a class="n-item  fs-4" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Sign Up</a>
            </li>
            <li class="nav-item"><a class="n-item fs-4" href="#about">About</a></li>
            <li class="nav-item d-none d-lg-block">
              <button type="button" onclick="toggleProfileModal()" style="display:none;" class=" ms-lg-2 btn rounded-circle profile-btn btn-secondary">
                <i class="fas fa-user"></i>
              </button>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="modal fade" id="contactForm" tabindex="-1" aria-labelledby="contactFormLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content d-flex flex-column align-items-center">
          <form class="modal-body contactUs">
            <h2>CONTACT US</h2>
            <input placeholder="Write your name here.."></input>
            <input placeholder="Let us know how to contact you back.." type='email'></input>
            <input placeholder="What would you like to tell us.."></input>
            <button action='' method='post' name='feedback'>Send Message</button>
          </form>
        </div>
      </div>
    </div>





    <div class="container intro">
      <p class="quote text-center display-1">The reading of all good books is like a conversation with the finest minds
        of the past centuries.</p>
      <p class="text-center fs-1"><a class="quote" href="https://en.wikipedia.org/wiki/Ren%C3%A9_Descartes" style="text-decoration: none;" target="_blank">- René Descartes -</a></p>
    </div>
  </div>
  </div>
  <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="d-flex modal-header align-items-center justify-content-center">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Log in to e-library</h1>
        </div>
        <div class="modal-body">

          <div class="d-flex flex-column align-items-center justify-content-center d-none" id="signUpDialog">
            <form class="d-flex flex-column align-items-center justify-content-center gap-4 w-75" action="" method="post">
              <input type="text" class="loginField w-100" id="setName" name="setName" placeholder="Name" required="">
              <input type="text" class="loginField w-100" id="setUsername" name="setUsername" placeholder="User name" required="">
              <input type="email" class="loginField w-100" id="setEmail" name="setEmail" placeholder="Email" required="">
              <select class="w-100" name="setProfession" id="setProfession" required="">
                <option disabled selected>Choose your profession</option>
                <option value="Student">Student</option>
                <option value="Working Professional">Working Professional</option>
                <option value="Not available">Rather not to say</option>
              </select>
              <input type="password" class="loginField w-100" id="setPassword" name="setPassword" placeholder="Password" required="">
              <button class="btn btn-primary w-50" type="submit" name="signUp">Sign Up</button>
            </form>
            <p class="mt-3">Already have an account? <a onclick="toggleLogin()" class="text-decoration-none text-black" style='cursor:pointer'>Sign in</a></p>
          </div>

          <div class="d-flex flex-column align-items-center justify-content-center" id="signInDialog">
            <form class="d-flex flex-column align-items-center justify-content-center gap-4 w-75" action="" method="post">
              <input class="loginField w-100" id="username" name="username" placeholder="Username" required="">
              <input class="loginField w-100" id="password" type="password" name="password" placeholder="Password" required="">
              <button class="btn btn-primary w-50" name="signIn">Sign In</button>
            </form>
            <p class="mt-3">New to e-Library? <a onclick="toggleLogin()" class="text-decoration-none text-black" style='cursor:pointer'>Sign up</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="books-body mt-2">
    <h2 class="text-center fs-1" id="topBooks">Top Books</h2>
    <div class="topBooks p-2 d-flex flex-wrap align-items-center justify-content-center">
      <?php
      $stmt = $conn->prepare('SELECT * FROM all_books ORDER BY views DESC LIMIT 10');
      $stmt->execute();
      $result = $stmt->get_result();
      while ($book = $result->fetch_assoc()) {
        echo '

      <div class=" book-card card m-3" style="width:15rem; height:27rem; cursor:pointer;" onclick="openBookInfo(' . $book['id'] . ')" id="${books[book].id}">
      <img class="card-img-top h-75" src="data:image/jpeg;base64,' . base64_encode($book['cover']) . '" alt="Book Image">
        <div class="card-body">
          <h5 class="card-title">' . $book['title'] . '</h5>
          <h6 class="card-subtitle text-body-secondary">' . $book['author'] . '</h6>
        </div>
      </div>
      <style>
        .book-card{
          background-color:inherit;
        }
        .book-card:hover{
          box-shadow: 0 3px 5px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        }
      </style>

        
              ';
      }
      ?>
    </div>
  </div>
  <div class="user-collection collection-body mt-2" style="display:none;">
    <h2 class="text-center fs-1" id="userCollection">My Collection</h2>
    <div class="collection p-2 d-flex flex-wrap align-items-center justify-content-center"></div>
  </div>
  <div class="overlay" style="display: none;"></div>

  <div class=' bookInfo pc-view-card'>
    <div class="dialog card position-fixed book-dialog  " style="width:55rem; height:auto;">
      <div class="card-header d-flex justify-content-between">
        Book Information <button type="button" class="btn-close align-end" aria-label="Close" onclick="closeBookInfo()"></button>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-8">
            <h2 id='title' class="book-title display-4">Not Available</h2>
            <h3 class='book-author' id='author'>Not Available</h3>
            <br>
            <h6 class="book-description" id='description'>Not Available</h6>
          </div>
          <div class="cover col-md-4">
            <img src="" alt="Image not available" class="img-fluid">
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class='bookInfo mobile-view-card'>
    <div class="card dialog position-fixed book-dialog " style="width: 18rem;">
      <div class="card-header d-flex justify-content-between">
        Book Information <button type="button" class="btn-close align-end" aria-label="Close" onclick="closeBookInfo()"></button>
      </div>
      <div class="card-body">
        <div class="d-flex align-content-center justify-content-center">
          <img src="data:image/jpeg;base64,${book.cover}" class="img-fluid h-75 w-50" alt="Image Not Available">
        </div>
        <h2 id='title' class="card-title text-black text-center">Not Available</h2>
        <h4 id='author' class="card-subtitle text-secondary text-center">Not Available</h4>
        <div class="card-text-scroll mt-2">
          <div id='description' class="card-text-scroll-inner text-center">Not Available</div>
        </div>
      </div>
    </div>
  </div>

  <style>
    .card-text-scroll {
      height: 200px;
      overflow-y: scroll;
    }

    .card-text-scroll-inner {
      padding-right: 1em;
    }

    .pc-view-card {
      display: none;
    }

    .mobile-view-card {
      display: none;
    }

    .visible {
      display: block !important;
    }
  </style>
</body>

<footer class='container-fluid g-0 bg-secondary-subtle p-3' id='about'>
  <div class="row justify-content-center align-items-center">
    <div class="col-lg-4 d-none d-lg-block text-center justify-content-center align-items-center p-3 " style='border-right: 1px solid black'>
      <p> Discover the enchanting world of <a href="https://coloredcow.com" style='text-decoration:none' target='_blank'>ColoredCow's</a> e-library, where literature comes to life in a captivating digital oasis of wisdom and imagination. </p>
    </div>
    <div class="col-lg-4 d-none d-lg-block text-center justify-content-center align-items-center p-3" style='border-right: 1px solid black'>
      <a class=" library-logo display-2">e Library</a>
    </div>
    <div class="col-lg-4 text-center">
      <div class='mb-1'><a class="fs-5 text-decoration-none" href="#">Divyanshu Naugai</a></div>
      <div>
        <a class="text-black" href="www.linkedin.com/in/divyanshu-naugai"><i class="fa-brands fa-linkedin-in m-3 fa-lg "></i></a>
        <a class="text-black" href="https://github.com/adwait7830"><i class="fa-brands fa-github m-3 fa-lg"></i></a>
        <a class="text-black" href="https://www.instagram.com/alone.thinktank/"><i class="fa-brands fa-instagram m-3 fa-lg"></i></i></a>
      </div>
    </div>
  </div>
</footer>



<script>
  function toggleLogin() {

    $('#signInDialog').toggleClass('d-none');
    $('#signUpDialog').toggleClass('d-none');
  }

  <?php
  if (isset($_POST['signIn'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];
    $uid = generateSessionToken();

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");


    $stmt->bind_param('ss', $username, $password);


    try {
      $stmt->execute();
      $result = $stmt->get_result();
      if ($result->num_rows > 0) {
        $stmt->close();
        $stmt2 = $conn->prepare("UPDATE users SET uid = ? WHERE username = ?");
        $stmt2->bind_param('ss', $uid, $username);
        $stmt2->execute();
        $stmt2->close();
        $_SESSION['token'] = $uid;
        echo 'window.location.replace("index.php");';
      } else {
        echo 'window.location.replace("un-logged.php");';
      }
    } catch (Exception $e) {
      echo "Error " . $e->getMessage();
    }
  }


  if (isset($_POST['signUp'])) {

    $uid = generateSessionToken();
    $name = $_POST['setName'];
    $username = $_POST['setUsername'];
    $email = $_POST['setEmail'];
    $profession = $_POST['setProfession'];
    $password = $_POST['setPassword'];

    $stmt = $conn->prepare("INSERT INTO users(uid,name,username,email, profession, password) VALUES(?,?,?,?,?,?)");
    $stmt->bind_param('ssssss', $uid, $name, $username, $email, $profession, $password);

    try {
      $result = $stmt->execute();
      if ($result) {
        $_SESSION['token'] = $uid;
        echo 'window.location.replace("index.php");';
      } else {
        echo 'window.location.replace("un-logged.php");';
      }
    } catch (Exception $e) {
      echo "Error " . $e->getMessage();
    }
  }
  ?>
</script>
<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/script.js" type="text/javascript"></script>
<script src="js/ajax.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://kit.fontawesome.com/8256093c76.js" crossorigin="anonymous"></script>

</html>