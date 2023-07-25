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
console.log('hello');

function click() {
  console.log('hello');
}















