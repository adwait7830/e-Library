function openBookInfo(id) {

  fetch(`bookHandling.php?id=${id}`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
  })
    .then(response => response.json())
    .then(book => {
      document.querySelector('.book-title').textContent = book.title;
      document.querySelector('.book-author').textContent = book.author;
      document.querySelector('.book-description').textContent = book.description;

      const imgElement = document.createElement('img');
      imgElement.alt = 'Image';
      imgElement.classList.add('img-fluid');

      const coverElement = document.querySelector('.cover');
      coverElement.innerHTML = '';
      coverElement.appendChild(imgElement);

      imgElement.src = book.cover;

    })
    .catch(error => {
      console.log(error)
    });

  const overlay = document.querySelector('.overlay');
  const pcViewCard = document.querySelector('.pc-view-card');
  const mobileViewCard = document.querySelector('.mobile-view-card');

  overlay.style.display = 'block';

  window.addEventListener('resize', function () {
    const viewportWidth = window.innerWidth;

    if (viewportWidth >= 992) {
      pcViewCard.style.display = 'block';
      mobileViewCard.style.display = 'none';
    } else {
      mobileViewCard.style.display = 'block';
      pcViewCard.style.display = 'none';
    }
  });

  window.dispatchEvent(new Event('resize'));
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

      // Automatically hide the toast after 3 seconds (3000ms)
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



function closeBookInfo() {
  $('.overlay').fadeOut();
  $('.bookInfo').hide();

}
