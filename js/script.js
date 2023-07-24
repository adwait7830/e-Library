
const topBooks = {
  12322: {
    id: 12322,
    title: 'The Art of War',
    author: 'Sun Tzu',
    genre: 'Non Fiction',
    img: 'images/books/art-of-war.jpg',
    description: '"The Art of War" is an ancient Chinese military treatise written by Sun Tzu. It encapsulates strategic principles and tactics for achieving success in warfare. It emphasizes the importance of understanding the enemy, mastering deception, and utilizing efficient planning. It stresses the significance of adaptability, speed, and exploiting weaknesses to secure victory. It advocates for the preservation of resources and knowing when to engage or avoid conflict. The treatise emphasizes the psychological aspect of war, emphasizing the importance of morale, leadership, and discipline. Ultimately, "The Art of War" serves as a guide to achieve triumph through meticulous preparation and strategic execution.'
  },
  14435: {
    id: 14435,
    title: 'The Railway Man',
    author: 'Eric Lomax',
    genre: 'Non Fiction',
    img: 'images/books/The-Railway-Man.jpg',
    description: '"The Railway Man" is a memoir by Eric Lomax, recounting his harrowing experiences as a British soldier during World War II. It tells the story of Lomax\'s capture and torture by the Japanese in a POW camp, where he was forced to work on the Thai-Burma Railway. The book delves into the physical and psychological trauma he endured and the lasting impact it had on his life. Lomax\'s journey towards healing and forgiveness, including his eventual reconciliation with one of his captors, highlights the power of resilience, compassion, and the pursuit of personal peace in the face of unimaginable suffering.'
  },
  23894: {
    id: 23894,
    title: 'The Casual Vacancy',
    author: 'J. K. Rowling',
    img: 'images/books/The-Casual-Vacancy.png',
    description: '"The Casual Vacancy" by J.K. Rowling is a gripping contemporary novel set in the small town of Pagford. It explores the aftermath of a local council member\'s sudden death, unveiling a web of secrets, conflicts, and hidden desires within the community. Rowling delves into themes of class, politics, and the struggles of adolescence, weaving together multiple character perspectives with her trademark storytelling.'
  },
  24839: {
    id: 24839,
    title: 'Ikigai',
    author: 'H. G. Puigcerver',
    img: 'images/books/Ikigai.png',
    description: '"Ikigai" is a thought-provoking book that explores the Japanese concept of finding purpose and fulfillment in life. It delves into the intertwining elements of passion, mission, vocation, and profession, guiding readers towards discovering their own ikigai, or "reason for being." Through practical exercises and anecdotes, it offers a roadmap for aligning one\'s daily actions with a sense of purpose, leading to a more purposeful and satisfying life.'
  },
  24387: {
    id: 24387,
    title: 'The Martian',
    author: 'Andy Weir',
    img: 'images/books/The-Martian.jpg',
    description: '"The Martian" by Andy Weir is an exhilarating science fiction novel set on Mars. It follows the story of astronaut Mark Watney, who is left stranded on the desolate planet after his crew mistakenly believes him dead. Through Watney\'s resourcefulness and scientific expertise, the book showcases his relentless fight for survival, ingeniously solving problems to secure his eventual rescue. It explores themes of human resilience, the pursuit of knowledge, and the indomitable spirit of exploration.'
  },
  28394: {
    id: 28394,
    title: 'A Brief History of Time',
    author: 'Stephen Hawking',
    img: 'images/books/A-Brief-History-of-Time.jpg',
    description: '"A Brief History of Time" by Stephen Hawking is a captivating exploration of the universe\'s origins, laws, and mysteries. Hawking presents complex scientific concepts in a clear and accessible manner, discussing topics such as the Big Bang, black holes, and the nature of time. The book delves into the history of cosmology, presenting a wide range of scientific theories and discoveries, while also addressing philosophical and existential questions.'
  },
  22903: {
    id: 22903,
    title: 'Wings of Fire',
    author: 'Dr. APJ Abdul Kalam',
    img: 'images/books/Wings-of-Fire.jpg',
    description: '"Wings of Fire" is an inspiring autobiography by A.P.J. Abdul Kalam, the renowned scientist and former President of India. It chronicles Kalam\'s humble beginnings, his relentless pursuit of knowledge, and his remarkable journey from a small village to becoming a prominent scientist. The book provides insights into his experiences, challenges, and the pivotal role of education in transforming lives. It serves as a motivational tale, encouraging readers to dream big, overcome obstacles, and work towards personal and national development.'
  },
};

const myCollection = {
  12322: {
    id: 12322,
    title: 'The Art of War',
    author: 'Sun Tzu',
    img: 'images/books/art-of-war.jpg'
  },
  28394: {
    id: 28394,
    title: 'A Brief History of Time',
    author: 'Stephen Hawking',
    img: 'images/books/A-Brief-History-of-Time.jpg'
  },
  22903: {
    id: 22903,
    title: 'Wings of Fire',
    author: 'Dr. APJ Abdul Kalam',
    img: 'images/books/Wings-of-Fire.jpg'
  },
};


function search() {

}

function openLoginDialog() {
  $(".login-section").fadeIn();
  $('.overlay').fadeIn();
  $('.body').css('overflow', 'hidden');

}

function toggleProfileModal() {
  $('.profile').fadeToggle();
}

function toggleAddBookModal(){
  $('.overlay').fadeToggle();
  $('.add-book').fadeToggle();
}


function closeLoginDialog() {
  $(".login-section").fadeOut();
  $('.overlay').fadeOut();
  $('.body').css('overflow', 'visible');
}


function addToShelf(books, at) {
  for (const book in books) {
    $(`${at}`).append(
      `
      <div class=" book-card card m-3" style='width:12rem; height:24rem; cursor:pointer;' onclick='openBookInfo(this)' id='${books[book].id}'>
        <img src="${books[book].img}" alt="" class="card-img-top h-75">
        <div class="card-body">
          <h5 class="card-title">${books[book].title}</h5>
          <h6 class="card-subtitle text-body-secondary">${books[book].author}</h6>
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
      `);
  }

}


$(() => addToShelf(topBooks, '.top-books'));

function openBookInfo(book) {
  let id = book.id;
  $('.overlay').fadeIn();

  $('body').append(
    `
    <div class='pc-view-card'>
    <div class="dialog card position-fixed book-dialog  " style="width:55rem; height:auto;">
      <div class="card-header d-flex justify-content-between">
        Book Information <button type="button" class="btn-close align-end" aria-label="Close" onclick="closeBookInfo()"></button>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-8">
            <h2 id='title' class="display-4">${topBooks[id].title}</h2>
            <h3 id='author'>by ${topBooks[id].author}</h3>
            <br>
            <h6 id='description'>${topBooks[id].description}</h6>
          </div>
          <div class="col-md-4">
            <img src="${topBooks[id].img}" alt="Image" class="img-fluid">
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
            <img src="${topBooks[id].img}" class="img-fluid h-75 w-50" alt="...">
          </div>
          <h2 id='title' class="card-title text-black text-center">${topBooks[id].title}</h2>
          <h4 id='author' class="card-subtitle text-secondary text-center">${topBooks[id].author}</h4>
          <div class="card-text-scroll mt-2">
            <div id='description' class="card-text-scroll-inner text-center">${topBooks[id].description}</div>
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

    <div id="edit-modal" class="modal fade z-4" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Book Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="edit-form">
            <div class="form-group">
              <label for="setCover">Book Cover</label>
              <input type="file" class="form-control-file" id="setCover">
            </div>
            <div class="form-group">
              <label for="setTitle">Title</label>
              <textarea class="form-control" id="setTitle" rows="1"></textarea>
            </div>
            <div class="form-group">
              <label for="setAuthor">Author</label>
              <textarea class="form-control" id="setAuthor" rows="1"></textarea>
            </div>
            <div class="form-group">
              <label for="setDescription">Description</label>
              <textarea class="form-control" id="setDescription" rows="5"></textarea>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type=" button" class="save-btn btn btn-primary" >Save Changes</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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
    </div>

    `
  );
  $('.body').addClass('no-scroll');
  if (myCollection.hasOwnProperty(id)) {
    $('.remove-btn').show();
  } else {
    $('.add-btn').show();
  }
  if (!loggedIn) {
    $('.config-btn').hide();
  }

  $('edit-btn').on({
    click: function () {
      console.log(`${book.id} is saved`);
      var newCover = $('#setCover').val();
      var newTitle = $('#setTitle').val().trim();
      var newAuthor = $('#setAuthor').val().trim();
      var newDescription = $('#setDescription').val().trim();
      if (newTitle !== '') {
        $('#title').text(newTitle);
        topBooks[id].title = newTitle;
      }

      if (newAuthor !== '') {
        $('#author').text(newAuthor);
        topBooks[id].author = newAuthor;
      }

      if (newDescription !== '') {
        $('#description').text(newDescription);
        topBooks[id].description = newDescription;
      }

      if (newCover !== '') {
        // You may need to handle image upload and update the 'src' attribute accordingly
        $('#card-image').attr('src', 'path_to_new_image.jpg'); // Replace 'path_to_new_image.jpg' with the new image path
      }

      $('#edit-modal').modal('hide');
      $('.top-books').empty();
      addToShelf(topBooks, '.top-books');
    }
  });

  $('.edit-btn').on({
    click: function () {
      console.log("editing initiated");
      $('#image-input').val(''); // Reset the image input
      $('#setTitle').val($('#title').text().trim()); // Set the text input with the current card text
      $('#setAuthor').val($('#author').text().trim());
      $('#setDescription').val($('#description').text().trim());
      $('#edit-modal').modal('show');
    }
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


function closeBookInfo() {
  $('.book-dialog').remove();
  $('.book-dialog').empty();
  $('.overlay').fadeOut();
  $('.body').removeClass('no-scroll');

}








