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
<div class="overlay" style='display:none'></div>
<body class='home-body' style="background-color:aliceblue;">

  <?php

  $token = $_SESSION['token'];
  $stmt = $conn->prepare("SELECT * FROM users WHERE uid = ?");
  $stmt->bind_param('s', $token);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();

  if ($user) {
    $name = $user['name'];
    $email = $user['email'];
  } else {
    echo 'Error';
  }

  $stmt->close();
  $profession = 'Student';
  ?>

  <div class="modal profile" id="profileModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog top-right">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">My Profile</h1>
        </div>
        <div class="modal-body p-0">
          <div class="card m-0 rounded-0" style="width: 18rem;">
            <div class="card-body">
              <h5 class="card-title"><?php echo $name ?></h5>
              <h6 class="card-subtitle mb-2 text-body-secondary"><?php echo $profession ?></h6>
              <h6 class="card-subtitle mb-2 text-body-secondary"><?php echo $email ?></h6>
            </div>
            <ul class="list-group list-group-flush">
              <li class="list-group-item"><button class="btn border-0" href="#collection">My Collection</button></li>
              <li class="list-group-item"><button class="btn border-0" onclick="toggleAddBookModal()">Add a book</button></li>
              <li class="list-group-item"><button class="btn border-0">Edit Profile</button></li>
            </ul>
          </div>
        </div>
        <form class="modal-footer justify-content-between" action="" method="post">
          <button type="submit" class="btn btn-danger" name="log-out" onclick="log_out()">Log out<i class="fa-solid fa-right-from-bracket ms-2"></i></button>
          <button type="button" class="btn btn-secondary" onclick="toggleProfileModal()">Close</button>
        </form>
      </div>
    </div>
  </div>

  <div id="add-modal" class="modal z-4 add-book" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <form class="modal-content" action="" enctype="multipart/form-data" method='post'>
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
          <button type="submit" name='add-book' class="btn btn-primary">Add Book</button>
          <button type="button" class="btn btn-secondary" onclick="toggleAddBookModal()">Cancel</button>
        </div>
      </form>
    </div>
  </div>
  <div class="searchbar w-100 d-flex flex-lg-row justify-content-center align-content-center py-5">
    <input class="w-50 rounded-pill border-0 px-2 bg-warning me-3" placeholder="Enter book title..." type="text">
    <button class="btn btn-sm btn-warning rounded-circle"><i class="fas fa-search m-2"></i></button>
  </div>
  <div class="allBooks p-2 d-flex flex-wrap align-items-center justify-content-center">
    <?php
    $stmt = $conn->prepare('SELECT * FROM all_books');
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
</body>
<script>
  <?php
  if (isset($_POST['add-book'])) {

    $cover = addslashes(file_get_contents($_FILES['setCover']['tmp_name']));
    $title = $_POST['setTitle'];
    $author = $_POST['setAuthor'];
    $description = $_POST['setDescription'];

    $stmt = $conn->prepare('INSERT INTO all_books (cover, title, author, description) VALUES (?, ?, ?, ?)');
    $stmt->bind_param('bsss', $cover, $title, $author, $description);

    if ($stmt->execute()) {
      echo 'Book Added Successfully';
    } else {
      echo 'Error: ' . $stmt->error;
    }
  }

  if (isset($_POST['log-out'])) {
    session_destroy();
    echo 'window.location.replace("index.php");';
  }

  if (!isset($_SESSION['token'])) {
    echo 'window.location.replace("index.php");';
  }

  ?>

  function openBookInfo(id){
    $('.overlay').fadeIn();
    $.post('test.php',{id:id},function(book){
      $('.home-body').append(
        `
        <div class='pc-view-card'>
    <div class="dialog card position-fixed book-dialog  " style="width:55rem; height:auto;">
      <div class="card-header d-flex justify-content-between">
        Book Information <button type="button" class="btn-close align-end" aria-label="Close" onclick="closeBookInfo()"></button>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-8">
            <h2 id='title' class="display-4">${book.title}</h2>
            <h3 id='author'>by ${book.author}</h3>
            <br>
            <h6 id='description'>${book.description}</h6>
          </div>
          <div class="col-md-4">
            <img src="data:image/jpeg;base64,${book.cover}" alt="Image" class="img-fluid">
          </div>
        </div>
      </div>
      <div class="card-footer d-flex justify-content-between">
        <div class='config-btn' >
          <button  class="dlt-btn btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i> Delete</button>
          <button  class="edit-btn btn btn-sm btn-outline-primary" data-target='#edit-modal' data-toggle='modal'><i class="fas fa-edit"></i> Edit</button>
        </div>
        <div class='config-btn'>
          <button id='addBtn' class=" btn btn-sm btn-warning add-btn" onclick='addToCollection(${id})'>Add to Collection</button>
          <button id='removeBtn' class="btn btn-sm btn-warning remove-btn" onclick='removeFromCollection(${id})'>Remove from collection</button>
        </div>
      </div>
    </div>
    </div>

    <div class='mobile-view-card'>
    <div class="card dialog position-fixed book-dialog " style="width: 18rem;">
        <div class="card-header d-flex justify-content-between">
          Book Information <button type="button" class="btn-close align-end" aria-label="Close" onclick="closeBookInfo()"></button>
        </div>
        <div class="card-body">
          <div class="d-flex align-content-center justify-content-center">
            <img src="data:image/jpeg;base64,${book.cover}" class="img-fluid h-75 w-50" alt="...">
          </div>
          <h2 id='title' class="card-title text-black text-center">${book.title}</h2>
          <h4 id='author' class="card-subtitle text-secondary text-center">${book.author}</h4>
          <div class="card-text-scroll mt-2">
            <div id='description' class="card-text-scroll-inner text-center">${book.description}</div>
          </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
          <div class='config-btn' >
            <button  class="dlt-btn btn btn-sm btn-outline-danger" ><i class="fas fa-trash"></i></button>
            <button  class="edit-btn btn btn-sm btn-outline-primary" data-target='#edit-modal' data-toggle='modal' ><i class="fas fa-edit"></i></button>
          </div>
          <div class='config-btn'>
            <button id='addBtn' class="btn btn-sm btn-warning add-btn" onclick='addToCollection(${id})'>Add to Collection</button>
            <button id='removeBtn' class="btn btn-sm btn-warning remove-btn" onclick='removeFromCollection(${id})'>Remove from collection</button>
          </div>
        </div>
      </div>
    </div>

    <style>
        .add-btn, .remove-btn{
          display:none;
        }
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
    
        `
      );
    });
    
    $(window).on('resize', function () {
    var viewportWidth = $(window).width();
    var pcViewCard = $('.pc-view-card');
    var mobileViewCard = $('.mobile-view-card');

    if (viewportWidth >= 992) {
      pcViewCard.addClass('visible');
      mobileViewCard.removeClass('visible');
    } else {
      pcViewCard.removeClass('visible');
      mobileViewCard.addClass('visible');
    }
  }).trigger('resize');
  }
  function closeBookInfo() {
  $('.book-dialog').remove();
  $('.overlay').fadeOut();
  

}
</script>
<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/script.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://kit.fontawesome.com/8256093c76.js" crossorigin="anonymous"></script>

</html>