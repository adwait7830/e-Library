<?php
session_start();
if (ini_get('register_globals'))
{
    foreach ($_SESSION as $key=>$value)
    {
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
        <a class=" library-logo display-2 d-none d-lg-block" onclick="clicked()">e Library</a>
        <button class="navbar-toggler " type="button" data-bs-toggle="collapse" aria-expanded="false" aria-label="Toggle navigation" data-bs-target="#toggle-nav" aria-controls="toggle-nav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <button type="button" onclick="toggleProfileModal()" style="display:none;" class=" d-lg-none ms-lg-2 btn rounded-circle profile-btn btn-secondary">
          <i class="fas fa-user"></i>
        </button>
        <div class="collapse navbar-collapse justify-content-lg-end" id="toggle-nav">
          <ul class="navbar-nav text-lg-center align-items-lg-center ">
            <li class="nav-item"><a class="n-item fs-4" href="#topBooks">Top Books</a></li>
            <li class="nav-item"><a class="n-item fs-4" onclick="openContactForm()">Contact Us</a></li>
            <li class="nav-item login-hook" onclick="openLoginDialog()"><a class="n-item  fs-4" href="#">Sign Up</a>
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
    <div class="container intro">
      <p class="quote text-center display-1">The reading of all good books is like a conversation with the finest minds
        of the past centuries.</p>
      <p class="text-center fs-1"><a class="quote" href="https://en.wikipedia.org/wiki/Ren%C3%A9_Descartes" style="text-decoration: none;" target="_blank">- René Descartes -</a></p>
    </div>
  </div>
  </div>
  <div class="login-section dialog">
    <input type="checkbox" id="chk" aria-hidden="true">
    <div class="position-absolute top-0 w-100 text-end"><button type="button" class="btn-close align-end" aria-label="Close" onclick="closeLoginDialog()"></button></div>

    <div class="signUpDialog">
      <form action="" method="post">
        <label class="shifter" for="chk" aria-hidden="true">Sign up</label>
        <input type="text" class='loginField' id="setName" name="setName" placeholder="Name" required="">
        <input type="text" class='loginField' id="setUsername" name="setUsername" placeholder="User name" required="">
        <input type="email" class='loginField' id="setEmail" name="setEmail" placeholder="Email" required="">
        <input type="password" class='loginField' id="setPassword" name="setPassword" placeholder="Password" required="">
        <button type="submit" name="signUp" class="signUpBtn" onclick="signUp()">Log in</button>
      </form>
    </div>
    <div class="signInDialog">
      <form action="" method="post">
        <label class="shifter" for="chk" aria-hidden="true">Sign in</label>
        <input class='loginField' id="username" name="username" placeholder="Username" required="">
        <input class='loginField' id="password" type="password" name="password" placeholder="Password" required="">
        <button class="loginBtn" name="signIn">Log in</button>
      </form>
    </div>
  </div>
  <div class="books-body mt-2">
    <h2 class="text-center fs-1" id="topBooks">Top Books</h2>
    <div class="topBooks p-2 d-flex flex-wrap align-items-center justify-content-center">
    <?php
    $stmt = $conn->prepare('SELECT * FROM top_books');
    $stmt->execute();
    $result = $stmt->get_result();
    while ($book = $result->fetch_assoc()) {
      echo '

      <div class=" book-card card m-3" style="width:15rem; height:27rem; cursor:pointer;" onclick="openBookInfo('.$book['id'].')" id="${books[book].id}">
      <img class="card-img-top h-75" src="data:image/jpeg;base64,' . base64_encode($book['cover']) . '" alt="Book Image">
        <div class="card-body">
          <h5 class="card-title">'.$book['title'].'</h5>
          <h6 class="card-subtitle text-body-secondary">'.$book['author'].'</h6>
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
  <div class="footer" id="about">
    <h3>&COPY; Divyanshu</h3>
    <div class="footer-links">
      <h3><a href="https://www.linkedin.com/in/divyanshu-naugai" target="_blank">Linkedin</a></h3>
      <h3><a href="https://github.com/adwait7830" target="_blank">Github</a></h3>
    </div>
  </div>
  <div class="overlay" style="display: none;"></div>
</body>
<script>
  <?php
  if (isset($_POST['signIn'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];
    $uid = generateSessionToken();

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt2 = $conn->prepare("UPDATE users SET uid = ? WHERE username = ?");

    $stmt->bind_param('ss', $username, $password);
    $stmt2->bind_param('ss', $uid, $username);

    try {
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
        $stmt2->execute();
        $_SESSION['token']= $uid;
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
    $password = $_POST['setPassword'];

    $stmt = $conn->prepare("INSERT INTO users(uid,name,username,email,password) VALUES(?,?,?,?,?)");
    $stmt->bind_param('sssss', $uid, $name, $username, $email, $password);

    try {
      $result = $stmt->execute();
      if ($result) {
        $_SESSION['token']= $uid;
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://kit.fontawesome.com/8256093c76.js" crossorigin="anonymous"></script>

</html>