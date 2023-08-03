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


function editModalConfig(){

  document.getElementById("newTitle").innerText = document.querySelector('.book-title').textContent;
  document.getElementById("newAuthor").innerText = document.querySelector('.book-author').textContent;
  document.getElementById("newDescription").innerText = document.querySelector('.book-description').textContent;

}


function changeOrder() {
  var selectedOrder = document.getElementById("orderSelector").value;
  fetch("logged.php", {
    method: "POST",
    headers: {
      "Content-type": "application/x-www-form-urlencoded",
    },
    body: "order=" + selectedOrder,
  })
    .then((response) => response.text())
    .then((data) => {
      var bookContainer = document.getElementById("bookContainer");
      bookContainer.innerHTML = data;
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}