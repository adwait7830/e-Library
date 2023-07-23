<?php

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
<header style="background:url('images/jas-bg.jpg');">
  <nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
      <a class=" library-logo display-2 d-none d-lg-block">e Library</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarID" aria-controls="navbarID" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <button type="button" onclick="toggleProfileModal()" class=" d-lg-none ms-lg-2 btn rounded-circle profile-btn btn-secondary">
        <i class="fas fa-user"></i>
      </button>
      <div class="collapse navbar-collapse justify-content-lg-end" id="navbarID">
        <ul class="navbar-nav text-lg-center align-items-lg-center ">
          <li class="nav-item"><a class="n-item fs-4" href="#about">Genres</a></li>
          <li class="nav-item"><a class="n-item fs-4" href="#about">Authors</a></li>
          <li class="nav-item"><a class="n-item fs-4" href="#about">About</a></li>
          <li class="nav-item d-none d-lg-block">
            <button type="button" onclick="toggleProfileModal()" class=" ms-lg-2 btn rounded-circle profile-btn btn-secondary">
              <i class="fas fa-user"></i>
            </button>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</header>

<body class='home-body' style="background-color:aliceblue;">

  <?php

  $name = 'Ankita Sharma';
  $email = 'example123@gmail.com';
  $profession = 'Student';

  echo '
    <div class="modal profile" id="profileModal" tabindex="-1" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog top-right">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">My Profile</h1>
          </div>
          <div class="modal-body p-0">
            <div class="card m-0 rounded-0" style="width: 18rem;">
              <div class="card-body">
                <h5 class="card-title">' . $name . '</h5>
                <h6 class="card-subtitle mb-2 text-body-secondary">' . $profession . '</h6>
                <h6 class="card-subtitle mb-2 text-body-secondary">' . $email . '</h6>
              </div>
              <ul class="list-group list-group-flush">
                <li class="list-group-item"><button class="btn border-0" href="#collection">My Collection</button></li>
                <li class="list-group-item"><button class="btn border-0" onclick="toggleAddBookModal()">Add a book</button></li>
                <li class="list-group-item"><button class="btn border-0">Edit Profile</button></li>
              </ul>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="submit" class="btn btn-danger" name="log-out" onclick="log_out()" >Log out<i
                class="fa-solid fa-right-from-bracket ms-2"></i></button>
            <button type="button" class="btn btn-secondary" onclick="toggleProfileModal()">Close</button>
          </div>
        </div>
      </div>
    </div>
    ';
  ?>

  <div id="add-modal" class="modal z-4 add-book" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <form class="modal-content" id="add-form" action="db.php" enctype="multipart/form-data" method='post'>
        <div class="modal-header">
          <h5 class="modal-title">Add Book Details</h5>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="setCover">Book Cover</label>
            <input type="file" class="form-control-file" name='setCover' id="newCover">
          </div>
          <div class="form-group">
            <label for="setTitle">Title</label>
            <textarea class="form-control" id="newTitle" name='setTitle' rows="1"></textarea>
          </div>
          <div class="form-group">
            <label for="setAuthor">Author</label>
            <textarea class="form-control" id="newAuthor" name="setAuthor" rows="1"></textarea>
          </div>
          <div class="form-group">
            <label for="setDescription">Description</label>
            <textarea class="form-control" id="newDescription" name="setDescription" rows="5"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" id="addBook" class="btn btn-primary">Add Book</button>
          <button type="button" class="btn btn-secondary" onclick="toggleAddBookModal()">Cancel</button>
        </div>
      </form>
    </div>
  </div>
  <div class="searchbar w-100 d-flex flex-lg-row justify-content-center align-content-center py-5">
    <input class="w-50 rounded-pill border-0 px-2 bg-warning me-3" placeholder="Enter book title..." type="text">
    <button class="btn btn-sm btn-warning rounded-circle"><i class="fas fa-search m-2"></i></button>
  </div>
</body>
<script>
  
  

  function log_out() {
    <?php
    session_destroy();
    echo 'window.location.replace("index.php");';

    ?>


  }
</script>
<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/script.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://kit.fontawesome.com/8256093c76.js" crossorigin="anonymous"></script>

</html>