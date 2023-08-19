// JavaScript

function getIdFromURL() {
  const currentURL = new URL(window.location.href);
  return currentURL.hash.substring(1);
}

function setCookie(name, value, daysToExpire = 1) {
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



document.getElementById("addBookForm").addEventListener("submit", function (event) {
  event.preventDefault();

  const formData = new FormData(event.target);

  fetch("bookHandling.php", {
    method: "POST",
    body: formData,
  })
    .then(res => res.json())
    .then(server => {
      if (server.response === 'success') {
        showToast('Book Added Successfully', 1);
        setTimeout(() => {
          window.location.reload();
        }, 1500);
      }
    })
    .catch(() => {
      showToast('Server Error', 0);
      setTimeout(() => {
        window.location.reload();
      }, 1500);
    })
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
    .then(res => res.json())
    .then(server => {
      if (server.response === 'success') {
        showToast('Book Deleted Successfully', 1);
        setTimeout(() => {
          window.location.reload();
        }, 1500);
      }
    })
    .catch(() => {
      showToast('Server Error', 0);
      setTimeout(() => {
        window.location.reload();
      }, 1500);
    })

});




document.getElementById("editBookForm").addEventListener("submit", function (event) {
  event.preventDefault();

  const formData = new FormData(event.target);
  formData.append('id', getIdFromURL());

  fetch("bookHandling.php", {
    method: "POST",
    body: formData,
  })
    .then(res => res.json())
    .then(server => {
      if (server.response === 'success') {
        showToast('Book Edited Successfully', 1);
        setTimeout(() => {
          window.location.reload();
        }, 1500);
      }
    })
    .catch((err) => {
      showToast('Server Error', 0);
      setTimeout(() => {
        window.location.reload();
      }, 1500);
    })
});

document.querySelectorAll('.contactUs').forEach((form) => {
  form.addEventListener('submit', function (event) {
    event.preventDefault();
    const formData = new FormData(event.target);

    fetch('services.php', {
      method: 'POST',
      body: formData,
    })
      .then(res => res.json())
      .then(server => {
        if (server.response === 'success') {
          showToast('Feedback Submitted Successfully');
          setTimeout(() => {
            window.location.reload();
          }, 1500);
        }
      })
      .catch(() => {
        showToast('Server Error', 0);
        setTimeout(() => {
          window.location.reload();
        }, 1500);
      })
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
      .catch(() => {
        showToast('Server Error', 0);
      })
  } else {
    document.getElementById('allBooks').classList.remove('d-none');
    document.getElementById('pagination').classList.remove('d-none');
    document.getElementById('searchResult').classList.add('d-none');
  }
})

function showAllBooks() {
  setCookie('page', 'books');
  document.getElementById('adminPanel').classList.add('d-none');
  document.getElementById('searchForm').classList.remove('d-none');
  document.getElementById('allBooks').classList.remove('d-none');
  document.getElementById('pagination').classList.remove('d-none');
}

function showAdminPanel() {
  setCookie('page', 'admin');
  document.getElementById('adminPanel').classList.remove('d-none');
  document.getElementById('searchForm').classList.add('d-none');
  document.getElementById('allBooks').classList.add('d-none');
  document.getElementById('pagination').classList.add('d-none');
}

fetch('admin.php', {
  method: "POST",
  headers: {
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({ stats: '' })
})
  .then(res => res.json())
  .then(data => {

    const profChart = document.getElementById('profChart');
    const onboardChart = document.getElementById('onboardChart');

    new Chart(profChart, {
      type: 'doughnut',
      data: {
        labels: data.professions.map(entry => entry.profession), // Extracting 'profession' from each object
        datasets: [{
          label: 'Professions of users',
          data: data.professions.map(entry => entry.count), // Extracting 'count' from each object
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
    new Chart(onboardChart, {
      type: 'line',
      data: {
        labels: data.onboard.map(entry => entry.onboard), // Extracting 'profession' from each object
        datasets: [{
          label: 'User Onboard to website',
          data: data.onboard.map(entry => entry.count), // Extracting 'count' from each object
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  })
  .catch(() => {
    showToast('Server Error', 0);
  })

fetch('admin.php', {
  method: "POST",
  headers: {
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({ isAdmin: '' })
})
  .then(res => res.json())
  .then(user => {
    if (user.isAdmin) {
      document.getElementById('adminPanelHook').classList.remove('d-none');
      document.getElementById('contactUsHook').classList.add('d-none');
      document.querySelectorAll('.config-btn').forEach(config=>{
        config.classList.remove('d-none');
      })
    }
  })
  .catch(() => {
    showToast('Server Error', 0);
  })
if (getCookie('page') === 'admin') {
  showAdminPanel();
} else {
  showAllBooks();
}



document.getElementById('dltUserBtn').addEventListener('click', function () {

  var uid = document.getElementById('userID').textContent;
  fetch(`admin.php`, {
    method: "POST",
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ removeById: uid })
  })
    .then(res => res.json())
    .then(server => {
      if (server.response === 'success') {
        showToast('User Removed Successfully', 1);
        setTimeout(() => {
          window.location.reload();
        }, 1500);
      }
    })
    .catch(() => {
      showToast('Server Error', 0);
      setTimeout(() => {
        window.location.reload();
      }, 1500);
    })

})

document.getElementById('dltResBtn').addEventListener('click', function () {

  var id = document.getElementById('resID').textContent;
  fetch(`admin.php`, {
    method: "POST",
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ dltById: id })
  })
    .then(res => res.json())
    .then(server => {
      if (server.response === 'success') {
        showToast('Feedback Deleted Successfully', 1);
        setTimeout(() => {
          window.location.reload();
        }, 1500);
      }
    })
    .catch(() => {
      showToast('Server Error', 0);
      setTimeout(() => {
        window.location.reload();
      }, 1500);
    })

})


const userChart = document.getElementById('userChart');
let radar;
function getDetails(uid) {
  var modal = document.getElementById("userModal");
  var userModal = new bootstrap.Modal(modal);
  fetch(`admin.php`, {
    method: "POST",
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ userById: uid })
  })
    .then(res => res.json())
    .then(user => {

      userModal.show();
      document.querySelector('.user-name').textContent = user.name;
      if (user.admin) {
        document.querySelector('.user-admin').classList.remove('d-none');
      } else {
        document.querySelector('.user-admin').classList.add('d-none');
      }
      document.querySelector('.user-email').textContent = user.email;
      document.querySelector('.user-prof').textContent = user.profession;
      document.querySelector('.user-onboard').textContent = `On e library since ${user.onboard}`;
      if (radar) {
        radar.destroy();
      }
      radar = new Chart(userChart, {
        type: 'radar',
        data: data = {
          labels: [
            'Book Added',
            'Book Edited',
            'Book Deleted',
          ],
          datasets: [{
            label: 'Activities',
            data: [user.added, user.edited, user.deleted],
            fill: true,
            backgroundColor: 'rgba(99, 255, 132, 0.2)',
            borderColor: 'rgb(99, 255, 132)',
            pointBackgroundColor: 'rgb(99, 255, 132)',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: 'rgb(99, 255, 132)'

          }]
        }
      });

    })
    .catch(() => {
      showToast('Server Error', 0);
    })
}


function reply(id) {
  var modal = document.getElementById("replyModal");
  var replyModal = new bootstrap.Modal(modal);
  replyModal.show();

  document.getElementById('replyTo').value = id;
}

document.getElementById('clrReplyForm').addEventListener('click', function () {
  document.getElementById('replyForm').reset();
  document.getElementById('replySent').classList.add('d-none');
  document.getElementById('replyForm').classList.remove('d-none');
})


document.getElementById('replyForm').addEventListener('submit', function (event) {
  event.preventDefault();
  document.getElementById('replyForm').classList.add('d-none');
  document.getElementById('loader').classList.remove('d-none');
  const formData = new FormData(event.target);
  fetch('admin.php', {
    method: "POST",
    body: formData
  })
    .then(res => res.json())
    .then(server => {
      setTimeout(() => {
        if (server.response === 'sent') {
          document.getElementById('loader').classList.add('d-none');
          document.getElementById('replySent').classList.remove('d-none');
        }
      }, 2000);
    })
    .catch(() => {
      showToast('Server Error', 0);
    })
})

function showToast(msg, status = -1) {
  var toastEl = document.querySelector('.toast');
  switch (status) {
    case 0:
      toastEl.classList.add('text-bg-danger');
      break;
    case 1:
      toastEl.classList.add('text-bg-success');
      break;
    default:
      toastEl.classList.add('text-bg-primary');
      break;
  }
  document.getElementById('toastTxt').textContent = msg;
  var toast = new bootstrap.Toast(toastEl);
  toast.show();
}

document.getElementById('addAdminBtn').addEventListener('click', () => {

  let modal = document.getElementById("addAdminModal");
  let addAdminModal = new bootstrap.Modal(modal);
  addAdminModal.show();

})

document.getElementById('searchUser').addEventListener('input', (event) => {

  let keyword = event.target.value.trim();

  if (keyword !== '') {
    document.getElementById('userSearchTable').classList.remove('d-none');
    document.getElementById('searchTableAlt').classList.add('d-none');
    fetch('admin.php', {
      body: JSON.stringify({ keyword: keyword }),
      method: "POST"
    })
      .then(res => res.json())
      .then(users => {
        let resultBody = document.getElementById('searchUserResult');
        while (resultBody.firstChild) {
          resultBody.removeChild(resultBody.firstChild);
        }
        users.forEach(user => {
          let userRow = document.createElement('tr');
          userRow.innerHTML = `
          <td>${user.name}</td>
          <td>${user.username}</td>
          <td><button class='btn btn-sm btn-primary' onclick='createAdmin(${user.uid})'>Make Admin</button></td>
          `;
          resultBody.appendChild(userRow);
        });

      })
      .catch(() => {
        showToast('Server Error', 0);
      })

  } else {
    document.getElementById('userSearchTable').classList.add('d-none');
    document.getElementById('searchTableAlt').classList.remove('d-none');
  }

})


function createAdmin(uid) {

  fetch('admin.php', {
    method: "POST",
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ addAdmin: uid })
  })
    .then(res => res.json())
    .then(server => {
      if (server.response === 'success') {
        showToast('Administrator Added Successfully', 1);
        setTimeout(() => {
          window.location.reload();
        }, 1500);
      }
    })
    .catch(() => {
      showToast('Server Error', 0);
    })
}