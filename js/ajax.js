

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
  overlay.classList.toggle('d-none');
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

document.getElementById('searchForm').addEventListener('submit', function (event) {
  event.preventDefault();
  const formData = new FormData(event.target);
  fetch('bookHandling.php', {
    method: 'POST',
    body: formData,
  })
    .then((response) => response.json())
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
    .catch((error) => {

      console.log(error);

    });

})
