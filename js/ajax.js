function getIdFromURL() {
  const currentURL = new URL(window.location.href);
  return currentURL.hash.substring(1);
}

function openBookInfo(id) {

  const currentURL = new URL(window.location.href);
  const newURL = `${currentURL.origin}${currentURL.pathname}${currentURL.search}#${id}`;
  window.history.replaceState({}, '', newURL);

  fetch(`bookHandling.php`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ showById: id }),
  })
    .then(response => response.json())
    .then(book => {

      document.querySelectorAll('.book-title').forEach((element) => {
        element.textContent = book.title;
      });

      document.querySelectorAll('.book-author').forEach((element) => {
        element.textContent = book.author;
      });

      document.querySelectorAll('.book-description').forEach((element) => {
        element.textContent = book.description;
      });

      const coverElements = document.querySelectorAll('.cover');

      coverElements.forEach((coverElement) => {
        const imgElement = document.createElement('img');
        imgElement.alt = 'Image';
        imgElement.classList.add('img-fluid');

        coverElement.innerHTML = '';
        coverElement.appendChild(imgElement);

        imgElement.src = book.cover;
      });



    })
    .catch(error => {
      console.log(error)
    });

  const overlay = document.querySelector('.overlay');
  overlay.style.display = 'block';

  window.addEventListener('resize', handleResize);

  window.dispatchEvent(new Event('resize'));
}

function handleResize() {
  const pcViewCard = document.querySelector('.pc-view-card');
  const mobileViewCard = document.querySelector('.mobile-view-card');
  const viewportWidth = window.innerWidth;

  if (viewportWidth >= 992) {
    pcViewCard.style.display = 'block';
    mobileViewCard.style.display = 'none';
  } else {
    mobileViewCard.style.display = 'block';
    pcViewCard.style.display = 'none';
  }
}

function closeBookInfo() {

  const currentURL = new URL(window.location.href);
  const newURL = `${currentURL.origin}${currentURL.pathname}${currentURL.search}`;
  window.history.replaceState({}, '', newURL);

  document.querySelector(".overlay").style.display = "none";
  document.querySelectorAll(".bookInfo").forEach((element) => {
    element.style.display = "none";
  });
  window.removeEventListener('resize', handleResize);

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
      const toastContainer = document.getElementById("home");

      const toast = document.createElement("div");
      toast.classList.add("toast");
      toast.classList.add("show");
      toast.setAttribute("role", "alert");
      toast.setAttribute("aria-live", "assertive");
      toast.setAttribute("aria-atomic", "true");

      const toastBody = document.createElement("div");
      toastBody.classList.add("toast-body");
      toastBody.textContent = "Data inserted successfully!";

      toast.appendChild(toastBody);

      toastContainer.appendChild(toast);

      setTimeout(() => {
        toast.classList.remove("show");
        toastContainer.removeChild(toast);
      }, 3000);

      openBookInfo()
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
    })
    .catch(error => console.log('error'));

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
      };
      return {};
    })
    .then(() => {

      window.location.reload();

    })
    .catch((error) => {

      console.log(error);

    });
});


document.getElementById("orderSelector").addEventListener('change', function (event) {

  orders = {

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

  var selectedOrder = orders[event.target.value];

  document.getElementById('bookContainer').innerHTML = `
  

  `;

});

