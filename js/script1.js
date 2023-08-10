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

  document.querySelector(".overlay").classList.remove('d-none');
  document.body.style.overflow = "hidden";
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

  document.querySelector(".overlay").classList.add('d-none');
  document.body.style.overflow = "auto";
  document.querySelectorAll(".bookInfo").forEach((element) => {
    element.style.display = "none";
  });
  window.removeEventListener('resize', handleResize);

}





