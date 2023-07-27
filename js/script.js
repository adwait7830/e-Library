// JavaScript
function toggleProfileModal() {
  var profileElement = document.querySelector('.profile');
  if (profileElement.style.display === 'none' || profileElement.style.display === '') {
    profileElement.style.display = 'block';
  } else {
    profileElement.style.display = 'none';
  }
}

function toggleAddBookModal() {
  var overlayElement = document.querySelector('.overlay');
  var addBookElement = document.querySelector('.add-book');

  if (overlayElement.style.display === 'none' || overlayElement.style.display === '') {
    overlayElement.style.display = 'block';
    addBookElement.style.display = 'block';
  } else {
    overlayElement.style.display = 'none';
    addBookElement.style.display = 'none';
  }
}
