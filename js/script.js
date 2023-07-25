function openLoginDialog() {
  $(".login-section").fadeIn();
  $('.overlay').fadeIn();
  $('.body').css('overflow', 'hidden');

}

function toggleProfileModal() {
  $('.profile').fadeToggle();
}

function toggleAddBookModal() {
  $('.overlay').fadeToggle();
  $('.add-book').fadeToggle();
}

function closeLoginDialog() {
  $(".login-section").fadeOut();
  $('.overlay').fadeOut();
  $('.body').css('overflow', 'visible');
}

function addToCollection(id) {

  $('.add-btn').removeClass('btn-warning');
  $('.add-btn').addClass('btn-success');
  $('.add-btn').text('Added to Collection');
  myCollection[id] = {

    id: id,
    title: topBooks[id].title,
    author: topBooks[id].author,
    img: topBooks[id].img,

  };
  $('.collection').empty();
  addToShelf(myCollection, '.collection');

}

function removeFromCollection(id) {
  $('.remove-btn').removeClass('btn-warning');
  $('.remove-btn').addClass('btn-danger');
  $('.remove-btn').text('Removed from Collection');
  delete (myCollection[id]);
  $('.collection').empty();
  addToShelf(myCollection, '.collection');
}


function openBookInfo(id) {
  console.log('hello');
  $('.overlay').fadeIn();
  $.post('test.php', {
    id: id
  }, function (book) {
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











