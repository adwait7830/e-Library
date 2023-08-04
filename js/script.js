// JavaScript
function setCookie(name, value, daysToExpire) {
  let cookie = name + "=" + encodeURIComponent(value);
  if (daysToExpire) {
    const date = new Date();
    date.setTime(date.getTime() + (daysToExpire * 24 * 60 * 60 * 1000));
    cookie += "; expires=" + date.toUTCString();
  }
  document.cookie = cookie + "; path=/";
}

function getCookie(name) {
  const cookieValue = document.cookie
    .split("; ")
    .find(cookie => cookie.startsWith(name + "="));

  return cookieValue ? decodeURIComponent(cookieValue.split("=")[1]) : null;
}
const orders = {

  alphaAsc: {
    column: 'title',
    order: 'ASC',
  },
  alphaDesc: {
    column: 'title',
    order: 'DESC'
  },
  uploadAsc: {
    column: 'id',
    order: 'ASC',
  },
  uploadDesc: {
    column: 'id',
    order: 'DESC'
  },
  viewsAsc: {
    column: 'views',
    order: 'ASC',
  },
  viewsDesc: {
    column: 'views',
    order: 'DESC'
  },

};
function toggleProfileModal() {
  var profileElement = document.querySelector('.profile');
  if (profileElement.style.display === 'none' || profileElement.style.display === '') {
    profileElement.style.display = 'block';
  } else {
    profileElement.style.display = 'none';
  }
}

function toggleAddBookModal() {
  const overlay = document.querySelector('.overlay');
  overlay.classList.toggle('d-none');
  const addBookElement = document.querySelector('.add-book');
  addBookElement.classList.toggle('d-none');

}


function editModalConfig() {

  document.getElementById("newTitle").innerText = document.querySelector('.book-title').textContent;
  document.getElementById("newAuthor").innerText = document.querySelector('.book-author').textContent;
  document.getElementById("newDescription").innerText = document.querySelector('.book-description').textContent;

}


document.getElementById("orderSelector").addEventListener('change', function (event) {
  var selectedOrder = orders[event.target.value];
  setCookie('column', selectedOrder.column, 2);
  setCookie('order', selectedOrder.order, 2);
  window.location.reload();
});


const column = getCookie('column') ?? 'id';
const order = getCookie('order') ?? 'ASC';
const selectorValue = getSelector(column, order);
if (selectorValue) {
  document.getElementById("orderSelector").value = selectorValue;
}

function getSelector(column, order) {

  switch (column) {
    case 'id':
      return order === 'ASC' ? 'uploadAsc' : 'uploadDesc';
    case 'views':
      return order === 'ASC' ? 'viewsAsc' : 'viewsDesc';
    case 'title':
      return order === 'ASC' ? 'alphaAsc' : 'alphaDesc';
  }

}


document.getElementById('searchBtn').addEventListener('mouseenter',function(){
  document.getElementById('searchIcon').classList.toggle('fa-search');
  document.getElementById('searchIcon').classList.toggle('fa-arrow-right');

});
document.getElementById('searchBtn').addEventListener('mouseleave',function(){
  document.getElementById('searchIcon').classList.toggle('fa-search');
  document.getElementById('searchIcon').classList.toggle('fa-arrow-right');
});


document.getElementById('searchBtn').addEventListener('click',function(){
  var toSearch = document.getElementById('searchIp').value.trim();
  if(toSearch && toSearch.length > 0){
    document.getElementById('allBooks').classList.add('d-none');
    document.getElementById('pagination').classList.add('d-none');
    document.getElementById('searchResult').classList.remove('d-none');
  }
})

function showAllBooks(){
  window.location.reload();
}

