<?php
try {
  require_once('db.php');
} catch (\Throwable $th) {
  header('Location: serverDown.php');
}
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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://kit.fontawesome.com/8256093c76.js" crossorigin="anonymous"></script>
</head>
<header style="background:url('images/jas-bg.jpg');">
  <nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
      <a class=" library-logo display-2 d-none d-lg-block">e Library</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarID" aria-controls="navbarID" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <button type="button" data-bs-toggle="modal" data-bs-target="#profileModal" class=" d-lg-none ms-lg-2 btn rounded-circle profile-btn btn-secondary">
        <i class="fas fa-user"></i>
      </button>
      <div class="collapse navbar-collapse justify-content-lg-end" id="navbarID">
        <ul class="navbar-nav text-lg-center align-items-lg-center ">
          <li class="nav-item"><a class="n-item fs-4" onclick="showAllBooks()">All Books</a></li>
          <li class="nav-item d-none" id='adminPanelHook'><a class="n-item fs-4" onclick="showAdminPanel()">Admin Panel</a></li>
          <li class="nav-item" id='contactUsHook'><a class="n-item fs-4" href="#" data-bs-toggle="modal" data-bs-target="#contactForm">Contact Us</a></li>
          <li class="nav-item"><a class="n-item fs-4" href="#about">About</a></li>
          <li class="nav-item d-none d-lg-block">
            <button type="button" data-bs-toggle="modal" data-bs-target="#profileModal" class=" ms-lg-2 btn rounded-circle btn-secondary">
              <i class="fas fa-user"></i>
            </button>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</header>

<div class="toast align-items-center border-0 position-fixed top-0 start-50 translate-middle-x mt-3" style="z-index: 9999;" role="alert" aria-live="assertive" aria-atomic="true">
  <div class="d-flex">
    <div class="toast-body">
      <span id='toastTxt'></span>
    </div>
    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
  </div>
</div>

<div class="overlay d-none"></div>

<div class="modal fade" id="contactForm" tabindex="-1" aria-labelledby="contactFormLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content d-flex flex-column align-items-center">
      <form class="modal-body contactUs">
        <h2>CONTACT US</h2>
        <input name="name" placeholder="Write your name here.." required=''></input>
        <input name="email" placeholder="Let us know how to contact you back.." type='email' required=''></input>
        <input name="response" placeholder="What would you like to tell us.." required=''></input>
        <button type="submit" name='feedback' data-bs-dismiss="modal">Send Message</button>
      </form>
    </div>
  </div>
</div>

<body class='home-body' id="home" style="background-color:aliceblue;">


  <?php
  if (verifySessionToken()) {
    $uid = getUserID();
    $stmt = $conn->prepare("SELECT * FROM users WHERE uid = ?");
    $stmt->bind_param('s', $uid);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $user = $result->fetch_assoc();
    $name = $user['name'];
    $email = $user['email'];
    $profession = $user['profession'];
  } else {
    echo '
    <div id="sessionOut" class="w-100 h-100 bg-body-tertiary position-fixed top-0">
      <div id="sessionDialog" class="d-flex flex-column text-center align-items-center justify-content-center" >
      <p class="m-0">Session out</p>
      <p class="m-0">Refresh to proceed</p>
      </div>
    </div>
    <style>
    #sessionOut{
      z-index:9999;
      display: grid;
      place-items: center;
    }
    #sessionDialog{
      width:175px;
      height:100px;
      background: white;
      box-shadow: 0 3px 5px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
      border-radius: 5px;
    }
    </style>
    ';
    session_destroy();
  }


  ?>

  <div class=' bookInfo pc-view-card'>
    <div class="dialog card position-fixed book-dialog  " style="width:55rem; height:auto;">
      <div class="card-header d-flex justify-content-between">
        Book Information <button type="button" class="btn-close align-end" aria-label="Close" onclick="closeBookInfo()"></button>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-8">

            <h2 id='title' class="placeholder-glow display-4">
              <p class='book-title placeholder'></p>
            </h2>
            <h3 class="placeholder-glow" id='author'>
              <p class='book-author placeholder'></p>
            </h3>
            <br>
            <h6 class="placeholder-glow" id='description'>
              <p class='book-description placeholder'></p>
            </h6>
          </div>
          <div class="book-cover placeholder-glow col-md-4">
            <img src="" alt="Image not available" class="placeholder img-fluid">
          </div>
        </div>
      </div>
      <div class="card-footer d-flex justify-content-between">
        <div class='config-btn'>
          <button class="dlt-btn btn btn-sm btn-outline-danger" data-bs-target='#delete-modal' data-bs-toggle='modal'><i class="fas fa-trash"></i> Delete</button>
          <button class="edit-btn btn btn-sm btn-outline-primary" onclick="editModalConfig()" data-bs-target='#edit-modal' data-bs-toggle='modal'><i class="fas fa-edit"></i> Edit</button>
        </div>
      </div>
    </div>
  </div>

  <div class='bookInfo mobile-view-card'>
    <div class="card dialog position-fixed book-dialog " style="width:19rem;">
      <div class="card-header d-flex justify-content-between">
        Book Information <button type="button" class="btn-close align-end" aria-label="Close" onclick="closeBookInfo()"></button>
      </div>
      <div class="card-body d-flex flex-column align-content-center justify-content-center ">
        <div class="book-cover d-flex align-content-center justify-content-center ms-auto me-auto" style="width:175px; height: 225px;">
          <img src="" class="placeholder img-fluid h-75 w-50" alt="Loader...">
        </div>
        <h2 id='title' class="mb-0 card-title text-black text-center">
          <p class='book-title placeholder' style='margin-bottom:2px'></p>
        </h2>
        <h4 id='author' class="mb-0 card-subtitle text-secondary text-center">
          <p class='book-author placeholder'></p>
        </h4>
        <div class="card-text-scroll" style='margin-top:5px'>
          <div id='description' class=" card-text-scroll-inner text-center">
            <p class='book-description placeholder' style='margin-bottom:2px'></p>
          </div>
        </div>
      </div>
      <div class="card-footer d-flex justify-content-between">
        <div class='config-btn'>
          <button class="dlt-btn btn btn-sm btn-outline-danger" data-bs-target='#delete-modal' data-bs-toggle='modal'><i class="fas fa-trash"></i></button>
          <button class="edit-btn btn btn-sm btn-outline-primary" onclick="editModalConfig()" data-bs-target='#edit-modal' data-bs-toggle='modal'><i class="fas fa-edit"></i></button>
        </div>
      </div>
    </div>
  </div>

  <div id="edit-modal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form class="modal-content" id="editBookForm" enctype="multipart/form-data">
        <input type="hidden" name='editBook'>
        <div class="modal-header">
          <h5 class="modal-title">Edit Book Details</h5>
        </div>
        <div class="modal-body">
          <div class="form-group input-group">
            <label class="input-group-text" for="newCover">Book Cover</label>
            <input type="file" class="form-control" name='editCover' id="newCover">
          </div>
          <div class="form-group">
            <label for="newTitle">Title</label>
            <textarea class="form-control" id="newTitle" name='editTitle' rows="1"></textarea>
          </div>
          <div class="form-group">
            <label for="newAuthor">Author</label>
            <textarea class="form-control" id="newAuthor" name="editAuthor" rows="1"></textarea>
          </div>
          <div class="form-group">
            <label for="newDescription">Description</label>
            <textarea class="form-control" id="newDescription" name="editDescription" rows="5"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name='editBook' class="btn btn-primary">Save</button>
          <button type="button" class="btn btn-secondary" data-bs-target='#edit-modal' data-bs-toggle='modal'>Cancel</button>
        </div>
      </form>
    </div>
  </div>


  <div class="modal fade" id='delete-modal' tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="d-flex justify-content-center text-center w-100">Once confirmed the book will be permanently deleted.<br>Wish to continue?</div>
        <form class='modal-body d-flex justify-content-around' id='dltBookForm'>
          <input type="hidden" name='dltBook'>
          <button type="submit" class='btn btn-danger'>Confirm</button>
          <button type="button" class="btn btn-secondary" data-bs-target='#delete-modal' data-bs-toggle='modal'>Cancel</button>
        </form>
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


  <div class="modal fade " id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog top-right-modal" style="width: 18rem;">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="profileModalLabel">My Profile</h1>
        </div>
        <div class="modal-body p-0">
          <div class="card m-0 rounded-0">
            <div class="card-body">
              <h5 class="card-title"><?php echo $name ?></h5>
              <h6 class="card-subtitle mb-2 text-body-secondary"><?php echo $profession ?></h6>
              <h6 class="card-subtitle mb-2 text-body-secondary"><?php echo $email ?></h6>
            </div>
            <ul class="list-group list-group-flush">
              <li class="list-group-item"><button class="btn border-0" data-bs-target="#add-modal" data-bs-toggle="modal">Add a book</button></li>
            </ul>
          </div>
        </div>
        <form class="modal-footer justify-content-between" action="" method="post">
          <button type="submit" class="btn btn-danger" name="log-out">Log out<i class="fa-solid fa-right-from-bracket ms-2"></i></button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </form>
      </div>
    </div>
    <style>
      .top-right-modal {
        position: fixed;
        margin: 0;
        top: 1rem;
        right: 1rem;
      }
    </style>
  </div>


  <div id="add-modal" class="modal z-4 add-book" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <form class="modal-content" id="addBookForm" enctype="multipart/form-data">
        <input type="hidden" name='addBook'>
        <div class="modal-header">
          <h5 class="modal-title">Add Book Details</h5>
        </div>
        <div class="modal-body">
          <div class="form-group input-group">
            <label class="input-group-text" for="setCover">Book Cover</label>
            <input type="file" class="form-control" name='setCover' required="">
          </div>
          <div class="form-group">
            <label for="setTitle">Title</label>
            <textarea class="form-control" name='setTitle' rows="1" required=""></textarea>
          </div>
          <div class="form-group">
            <label for="setAuthor">Author</label>
            <textarea class="form-control" name="setAuthor" rows="1" required=""></textarea>
          </div>
          <div class="form-group">
            <label for="setDescription">Description</label>
            <textarea class="form-control" name="setDescription" rows="5" required=""></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name='add-book' class="btn btn-primary">Add Book</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss='modal'>Cancel</button>
        </div>
      </form>
    </div>
  </div>

  <div class="d-none mt-3 container " id='adminPanel'>

    <div class='container border-bottom fs-2'>
      <h1>Admin Panel</h1>
    </div>

    <br>

    <div>
      <div class='kite px-3 mx-1 bg-info'>
        <h3 class=''>Users</h3>
      </div>
      <table class="table" border="1">
        <thead>
          <tr>
            <th scope='col'>Username</th>
            <th scope='col'>Name</th>
            <th class="text-center" scope='col'>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $itemsPerPage = 10; // Number of items per page
          $currentPage = isset($_GET['usp']) ? $_GET['usp'] : 1;
          $offset = ($currentPage - 1) * $itemsPerPage;

          $stmt = $conn->prepare("SELECT * FROM users WHERE admin = 0 LIMIT ?, ?");
          $stmt->bind_param("ii", $offset, $itemsPerPage);
          $stmt->execute();
          $result = $stmt->get_result();

          while ($user = $result->fetch_assoc()) {
          ?>
            <tr>
              <td><?php echo $user['username']; ?></td>
              <td><?php echo $user['name']; ?></td>
              <td class='d-block d-sm-none'>
                <div class='btn-group-vertical d-flex justify-content-center' role="group">
                  <button class="btn btn-sm btn-danger" data-bs-target="#userDltModal" data-bs-toggle='modal'>Remove</button>
                  <p class='d-none' id='userID'><?php echo $user['uid']; ?></p>
                  <button class="btn btn-sm btn-primary" data-bs-target="#userModal" data-bs-toggle='modal'>Details</button>
                </div>
              </td>
              <td class='d-none d-sm-block'>
                <div class="btn-group d-flex justify-content-center" role='group'>
                  <button class="btn btn-sm btn-danger" data-bs-target="#userDltModal" data-bs-toggle='modal'>Remove</button>
                  <p class='d-none' id='userID'><?php echo $user['uid']; ?></p>
                  <button class="btn btn-sm btn-primary" onclick='getDetails(<?php echo $user["uid"]; ?>)'>Details</button>
                </div>
              </td>
            </tr>
          <?php
          }
          ?>
        </tbody>
      </table>

      <?php
      $stmt = $conn->prepare("SELECT COUNT(*) as total FROM users WHERE admin = 0");
      $stmt->execute();
      $result = $stmt->get_result();
      $row = $result->fetch_assoc();
      $totalItems = $row['total'];

      $totalPages = ceil($totalItems / $itemsPerPage);
      ?>

      <ul class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
          <li class="page-item <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
            <a class="page-link" href="?usp=<?php echo $i; ?>"><?php echo $i; ?></a>
          </li>
        <?php endfor; ?>
      </ul>
    </div>

    <br>

    <div class='container'>
      <div class='row mx-1'>
        <div class='col-9 kite px-3 mb-2 bg-info'>
          <h3 class='m-0'>Admins</h3>
        </div>
        <div id="addAdminBtn" class='col-3 btn d-flex justify-content-center align-items-center kite mb-2 bg-warning'>
          <h6 class="m-0">Add <i class="fa-solid fa-plus"></i></h6>
        </div>
      </div>
      <table class="table" border="1">
        <thead>
          <tr>
            <th scope='col'>Username</th>
            <th scope='col'>Name</th>
            <th class="text-center" scope='col'>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $itemsPerPage = 10; // Number of items per page
          $currentPage = isset($_GET['adp']) ? $_GET['adp'] : 1;
          $offset = ($currentPage - 1) * $itemsPerPage;

          $stmt = $conn->prepare("SELECT * FROM users WHERE admin = 1 LIMIT ?, ?");
          $stmt->bind_param("ii", $offset, $itemsPerPage);
          $stmt->execute();
          $result = $stmt->get_result();

          while ($user = $result->fetch_assoc()) {
          ?>
            <tr>
              <td><?php echo $user['username']; ?></td>
              <td><?php echo $user['name']; ?></td>
              <td class='btn-group d-flex justify-content-center' role='group'>
                <button class="btn btn-sm btn-primary" onclick="getDetails(`<?php echo $user['uid']; ?>`)">Details</button>
              </td>
            </tr>
          <?php
          }
          ?>
        </tbody>
      </table>

      <?php
      $stmt = $conn->prepare("SELECT COUNT(*) as total FROM users WHERE admin = 1");
      $stmt->execute();
      $result = $stmt->get_result();
      $row = $result->fetch_assoc();
      $totalItems = $row['total'];

      $totalPages = ceil($totalItems / $itemsPerPage);
      ?>

      <ul class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
          <li class="page-item <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
            <a class="page-link" href="?adp=<?php echo $i; ?>"><?php echo $i; ?></a>
          </li>
        <?php endfor; ?>
      </ul>
    </div>

    <br>

    <div class="container">
      <div class='kite px-3 mx-1 bg-info'>
        <h3>Statistics</h3>
      </div>

      <div>
        <div class="row">
          <div class="col-lg-6 ">
            <div id="userStat" class="d-flex flex-column justify-content-center align-items-center" style='height:400px'>

              <p class="display-3">
                <?php
                $stmt = $conn->prepare("SELECT COUNT(*) as total FROM users");
                $stmt->execute();
                $stmt->bind_result($totalUsers);
                $stmt->fetch();
                $stmt->close();
                echo $totalUsers;
                ?>
              </p>

              <div class="fs-1">Total Users</div>
            </div>
          </div>
          <div class="col-lg-6 d-flex justify-content-center " style='height:400px'>
            <canvas id="profChart"></canvas>
          </div>
        </div>
      </div>

      <div class='w-100 bg-black mt-5' style='height:1px'></div>

      <div>
        <div class="row">
          <div class="col-lg-6 ">
            <div id="onboardStat" class="d-flex flex-column justify-content-center align-items-center" style='height:400px'>

              <p class="display-3">
                <?php
                $stmt = $conn->prepare("SELECT DATEDIFF(MAX(onboard), MIN(onboard)) AS days FROM users");
                $stmt->execute();
                $stmt->bind_result($days);
                $stmt->fetch();
                $stmt->close();
                echo floor($totalUsers / $days);
                ?>
              </p>

              <div class="fs-1">Users Onboard per Day</div>
            </div>
          </div>
          <div class="col-lg-6 d-flex justify-content-center align-items-center " style='height:400px'>
            <canvas id="onboardChart"></canvas>
          </div>
        </div>
      </div>

    </div>

    <div class='container'>
      <div class='kite px-3 mx-1 bg-info'>
        <h3>Feedback</h3>
      </div>

      <table class="table" border="1">
        <thead>
          <tr>
            <th scope='col'>Name</th>
            <th scope='col'>Response</th>
            <th class="text-center" scope='col'>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $itemsPerPage = 10; // Number of items per page
          $currentPage = isset($_GET['fd']) ? $_GET['fd'] : 1;
          $offset = ($currentPage - 1) * $itemsPerPage;

          $stmt = $conn->prepare("SELECT * FROM feedback LIMIT ?, ?");
          $stmt->bind_param("ii", $offset, $itemsPerPage);
          $stmt->execute();
          $result = $stmt->get_result();

          while ($feedback = $result->fetch_assoc()) {
          ?>
            <tr>
              <td><?php echo $feedback['name']; ?></td>
              <td><?php echo $feedback['response']; ?></td>
              <td class='d-block d-sm-none'>
                <div class='btn-group-vertical d-flex justify-content-center' role='group'>
                  <button class="btn btn-sm btn-danger" data-bs-target="#resDltModal" data-bs-toggle='modal'>Delete</button>
                  <p class='d-none' id='resID'><?php echo $feedback['id']; ?></p>
                  <button class="btn btn-sm btn-primary" onclick="reply(<?php echo $feedback['id']; ?>)">Reply</button>
                </div>
              </td>
              <td class='d-none d-sm-block'>
                <div class='btn-group d-flex justify-content-center' role='group'>
                  <button class="btn btn-sm btn-danger" data-bs-target="#resDltModal" data-bs-toggle='modal'>Delete</button>
                  <p class='d-none' id='resID'><?php echo $feedback['id']; ?></p>
                  <button class="btn btn-sm btn-primary" onclick="reply(<?php echo $feedback['id']; ?>)">Reply</button>
                </div>
              </td>
            </tr>
          <?php
          }
          ?>
        </tbody>
      </table>

      <?php
      $stmt = $conn->prepare("SELECT COUNT(*) as total FROM feedback");
      $stmt->execute();
      $result = $stmt->get_result();
      $row = $result->fetch_assoc();
      $totalItems = $row['total'];

      $totalPages = ceil($totalItems / $itemsPerPage);
      ?>

      <ul class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
          <li class="page-item <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
            <a class="page-link" href="?fd=<?php echo $i; ?>"><?php echo $i; ?></a>
          </li>
        <?php endfor; ?>
      </ul>
    </div>

    <style>
      .kite {
        transform: skewX(-45deg);
        border-radius: 5px;
      }

      .kite h3 {
        transform: skewX(45deg);
      }

      .kite h6 {
        transform: skewX(45deg);
      }
    </style>

    <div class="modal fade" id="userDltModal" tabindex="-1" aria-labelledby="userDltModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header ">
            <h1 class="modal-title text-center w-100 fs-5" id="userDltModalLabel">Remove User?</h1>
          </div>
          <div class="modal-body d-flex justify-content-around ">
            <button type="button" class="btn btn-danger" id='dltUserBtn'>Remove</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="resDltModal" tabindex="-1" aria-labelledby="resDltModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header ">
            <h1 class="modal-title text-center w-100 fs-5" id="resDltModalLabel">Delete Feedback?</h1>
          </div>
          <div class="modal-body d-flex justify-content-around ">
            <button type="button" class="btn btn-danger" id='dltResBtn'>Delete</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>

    <!-- User Details Model -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header ">
            <h1 class="modal-title w-100 fs-5" id="userModalLabel">User Details</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body row">
            <div class="col-md-6 card rounded-0 border-0">
              <div class="card-body">
                <h5 class="card-title fs-2 user-name"></h5>
                <h6 class='d-none text-success user-admin'>&#9733; Admin</h6>
                <h6 class="card-subtitle mb-2 text-body-secondary user-email"></h6>
                <h6 class="card-subtitle mb-2 text-body-secondary user-prof"></h6>
                <p class="card-text user-onboard"></p>
              </div>
            </div>
            <div class="col-md-6">
              <canvas id="userChart" class="w-100 h-100"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="addAdminModal" tabindex="-1" aria-labelledby="addAdminModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header ">
            <h1 class="modal-title w-100 fs-5" id="addAdminModalLabel">Add Administrator</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class='d-flex justify-content-center w-100'>
              <input id="searchUser" class="form-control me-2" type="search" placeholder="Search User" aria-label="Search">
            </div>
            <div class='container'>
              <table class="d-none table" id="userSearchTable">
                <thead>
                  <tr>
                    <th scope='col'>Name</th>
                    <th scope='col'>Username</th>
                    <th scope='col'>Action</th>
                  </tr>
                </thead>
                <tbody id='searchUserResult'></tbody>
              </table>

              <div id="searchTableAlt" class='py-4 text-center fs-5'>
                Search User by Name or Username to Make Them Administrator
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="replyModal" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header ">
            <h1 class="modal-title text-center w-100 fs-5" id="replyModalLabel">Send Reply</h1>
            <button type="button" id="clrReplyForm" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body d-flex justify-content-center">
            <form id="replyForm" class='d-flex flex-column justify-content-center'>
              <input type="hidden" name='reply'>
              <input type="hidden" name='id' id='replyTo'>
              <div class="mb-3">
                <label for="sub" class="form-label">Subject</label>
                <input type="text" class="form-control" name="subject" placeholder="Enter your subject of reply" required=''>
              </div>
              <div class="mb-3">
                <label for="msg" class="form-label">Message</label>
                <textarea class="form-control" name="msg" rows="3" required=''></textarea>
                <small>Just write the main content, greetings will be added automatically while sending.</small>
              </div>
              <div class='d-flex justify-content-center'>
                <button type="submit" class='btn btn-success'>Send <i class="fas fa-paper-plane"></i></button>
              </div>
            </form>

            <div class="spinner-border d-none m-5 text-primary" style="width: 3rem; height: 3rem;" id='loader' role="status">
              <span class="visually-hidden">Loading...</span>
            </div>

            <div class="w-100 d-flex flex-column align-items-center justify-content-center d-none" id='replySent'>
              <i class="fa-solid fa-check text-success" style='font-size:5rem'></i>
              <p>Reply Sent</p>
            </div>

          </div>
        </div>
      </div>
    </div>

  </div>

  <div id="searchForm" class="my-5 container d-flex flex-row justify-content-center flex-row align-content-center" style="height: 3rem;">
    <input id="searchIp" name="searchIp" class="w-75 bg-warning rounded-pill border-0 p-2 text-center" type="text" placeholder='Search by Title/Author'>
    <style>
      #searchIp:focus {
        outline: none;
      }
    </style>
  </div>

  <div class="container d-none p-5 d-flex flex-wrap justify-content-center" id='searchResult'>
  </div>

  <!-- Book are getting printed here -->

  <div class="p-2 d-flex flex-wrap align-items-center justify-content-center" id='allBooks'>

    <div class="container-fluid d-flex justify-content-end" style='width:84%'>
      <label for="orderSelector">Sort Books</label>
      <select class="ms-1" name="" id="orderSelector">
        <option value="uploadAsc">Oldest First</option>
        <option value="uploadDesc">Newest First</option>
        <option value="alphaAsc">By Name A&rarr;Z</option>
        <option value="alphaDesc">By Name Z&rarr;A</option>
        <option value="viewsDesc">Most Viewed</option>
        <option value="viewsAsc">Rarely Visited</option>
      </select>
    </div>


    <?php
    $stmt = $conn->prepare('SELECT * FROM all_books');
    $stmt->execute();
    $result1 = $stmt->get_result();
    $noOfBooks = $result1->num_rows;
    $booksPerPage = 8;
    $currentPage = 1;
    $noOfPages = ceil($noOfBooks / $booksPerPage);
    $maxVisiblePages = 5;

    $startPage = max($currentPage - floor($maxVisiblePages / 2), 1);
    $endPage = min($startPage + $maxVisiblePages - 1, $noOfPages);

    if (isset($_GET['page']) && is_numeric($_GET['page'])) {
      $currentPage = max(1, $_GET['page']);
      $currentPage = min($noOfPages, $_GET['page']);
    }
    $startFrom = ($currentPage - 1) * $booksPerPage;
    $stmt->close();



    ?>

    <style>
      .book-card {
        background-color: inherit;
      }

      .book-card:hover {
        box-shadow: 0 3px 5px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
      }
    </style>

    <div id='bookContainer' class='d-flex flex-wrap justify-content-center'>
      <?php

      $column = $_COOKIE['column'] ?? 'id';
      $order = $_COOKIE['order'] ?? 'ASC';


      $stmt2 = $conn->prepare("SELECT * FROM all_books ORDER BY $column $order  LIMIT  ?,?");
      $stmt2->bind_param('ii', $startFrom, $booksPerPage);
      $stmt2->execute();
      $result = $stmt2->get_result();

      while ($book = $result->fetch_assoc()) {
        echo '
      <div class=" book-card card m-5" style="width:15rem; height:27rem; cursor:pointer;" onclick="openBookInfo(' . $book['id'] . ')">
      <img class="card-img-top h-75" src="' . $book['cover'] . '" alt="Book Image">
        <div class="card-body">
          <h5 class="card-title">' . $book['title'] . '</h5>
          <h6 class="card-subtitle text-body-secondary">' . $book['author'] . '</h6>
        </div>
      </div>  
              ';
      }
      ?>
    </div>

  </div>

  <nav id="pagination" aria-label="Page navigation" style='z-index:1'>
    <ul class="pagination justify-content-center">
      <?php if ($currentPage > 1) { ?>
        <li class="page-item">
          <a class="page-link" href="?page=<?php echo $currentPage - 1 ?>">Previous</a>
        </li>
      <?php } ?>
      <?php if ($startPage > 1) { ?>
        <li class="page-item">
          <a class="page-link" href="?page=1">1</a>
        </li>
        <?php if ($startPage > 2) { ?>
          <li class="page-item disabled">
            <span class="page-link">...</span>
          </li>
        <?php } ?>
      <?php } ?>
      <?php for ($i = $startPage; $i <= $endPage; $i++) {
        $class = ($i == $currentPage) ? 'active' : '';
      ?>
        <li class="page-item <?php echo $class ?>" style='z-index:1'>
          <a class="page-link" style='z-index:1' href="?page=<?php echo $i ?>"><?php echo $i ?></a>
        </li>
      <?php } ?>
      <?php if ($endPage < $noOfPages) { ?>
        <?php if ($endPage < $noOfPages - 1) { ?>
          <li class="page-item disabled">
            <span class="page-link">...</span>
          </li>
        <?php } ?>
        <li class="page-item">
          <a class="page-link" href="?page=<?php echo $noOfPages ?>"><?php echo $noOfPages ?></a>
        </li>
      <?php } ?>
      <?php if ($currentPage < $noOfPages) { ?>
        <li class="page-item">
          <a class="page-link" href="?page=<?php echo $currentPage + 1 ?>">Next</a>
        </li>
      <?php } ?>
    </ul>
  </nav>
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
  <?php

  if (isset($_POST['log-out'])) {
    dltSessionToken();
    echo 'window.location.replace("index.php");';
  }

  if (!(verifySessionToken())) {
    echo 'window.location.replace("index.php");';
  }

  ?>
</script>
<script src="js/script1.js" type="text/javascript"></script>
<script src="js/script2.js" type="text/javascript"></script>


</html>