// JavaScript

function getIdFromURL() {
  const currentURL = new URL(window.location.href);
  return currentURL.hash.substring(1);
}

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

function showAllBooks() {
  window.location.reload();
}

document.getElementById("addBookForm").addEventListener("submit", function (event) {
  event.preventDefault();

  const formData = new FormData(event.target);

  fetch("bookHandling.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      };
      return {};
    })
    .then((data) => {

      window.location.reload();

    })
    .catch((error) => {

      console.log(error);

    });
});


document.getElementById("dltBookForm").addEventListener('submit', function (event) {
  event.preventDefault();
  fetch('bookHandling.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ dltById: getIdFromURL() }),
  })

    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      };
      return {};
    })
    .then((data) => {
      window.location.reload();
      showToast('Book Added Successfully')
    })
    .catch(error => console.log(error));

});




document.getElementById("editBookForm").addEventListener("submit", function (event) {
  event.preventDefault();

  const formData = new FormData(event.target);
  formData.append('id', getIdFromURL());

  fetch("bookHandling.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }else{
        window.location.reload(true);
      }
    })
    .catch((error) => {

      console.log(error);

    });
});

document.querySelectorAll('.contactUs').forEach((form) => {
  form.addEventListener('submit', function (event) {
    event.preventDefault();
    const formData = new FormData(event.target);

    fetch('services.php', {
      method: 'POST',
      body: formData,
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Network response was not ok");
        };
        return {};
      })
      .then(() => {

      })
      .catch((error) => {

        console.log(error);

      });
  })
});

document.getElementById('searchIp').addEventListener('input', function (event) {
  const keyword = event.target.value.trim();
  if (keyword !== '') {
    document.getElementById('allBooks').classList.add('d-none');
    document.getElementById('pagination').classList.add('d-none');
    document.getElementById('searchResult').classList.remove('d-none');
    fetch('bookHandling.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ keyword: keyword }),
    })
      .then(response => response.json())
      .then((data) => {
        var resultDiv = document.getElementById('searchResult');
        while (resultDiv.firstChild) {
          resultDiv.removeChild(resultDiv.firstChild);
        }
        data.forEach(book => {
          const bookDiv = document.createElement('div');
          bookDiv.innerHTML = `
          <div class=" book-card card m-5" style="width:15rem; height:27rem; cursor:pointer;" onclick="openBookInfo(${book.id})">
          <img class="card-img-top h-75" src="${book.cover}" alt="Book Image">
            <div class="card-body">
              <h5 class="card-title">${book.title}</h5>
              <h6 class="card-subtitle text-body-secondary">${book.author}</h6>
            </div>
          </div>
          <style>
        .book-card {
          background-color: inherit;
        }
  
        .book-card:hover {
          box-shadow: 0 3px 5px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        }
      </style>  
          `;
          resultDiv.appendChild(bookDiv);
        });
      })
      .catch(error => console.error(error));
  }else{
    document.getElementById('allBooks').classList.remove('d-none');
    document.getElementById('pagination').classList.remove('d-none');
    document.getElementById('searchResult').classList.add('d-none');
  }
})

function showToast(message) {
  // Create a div element for the toast
  const toast = document.createElement('div');
  toast.classList.add('toast');
  toast.innerText = message;

  // Add the toast to the page
  document.body.appendChild(toast);

  // Automatically remove the toast after 3 seconds
  setTimeout(() => {
    document.body.removeChild(toast);
  }, 3000);
}
