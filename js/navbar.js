let notificationsOffset = 0;

async function loadProfile() {
  let user = window.sessionStorage.getItem('profile');
  if (!user) {
    const url = new URL('http://localhost/rest_api.php/user/get');
    url.searchParams.append('username', window.localStorage.getItem('username'));
    const response = await fetch(url, {
      method: "GET"
    });
    const arrUser = await response.json();
    user = arrUser[0];
    console.log(user)
    window.sessionStorage.setItem('profile',JSON.stringify(user));
  } else {
    user = JSON.parse(user);
  }
  document.getElementById('navbar-image').src = user.image;
  document.getElementById('navbar-name').innerText = window.localStorage.getItem('username');
  document.getElementById('navbar-email').innerText = user.email;
}

async function initializeNavbar() {
  const div = document.getElementById('modal-div')
  const aside = document.getElementById('modal-aside')

  const drawer = mdc.drawer.MDCDrawer.attachTo(document.querySelector('.mdc-drawer'));
  const listEl = document.querySelector('.mdc-drawer .mdc-list');
  const mainContentEl = document.querySelector('.main-content');

  if (screen.width > 700) {
    listEl.addEventListener('click', (event) => {
    mainContentEl.querySelector('input, button').focus();
    });
    
    document.body.addEventListener('MDCDrawer:closed', () => {
    mainContentEl.querySelector('input, button').focus();
    });
  } else {
    let divToAdd = document.createElement('div');
    divToAdd.setAttribute("class", "mdc-drawer-scrim");
    div.before(divToAdd);
    aside.classList.add("mdc-drawer--modal");
    
    listEl.addEventListener('click', (event) => {
      drawer.open = false;
    });

    document.body.addEventListener('MDCDrawer:closed', () => {
      mainContentEl.querySelector('input, button').focus();
    });
  }

  const topAppBar = mdc.topAppBar.MDCTopAppBar.attachTo(document.getElementById('navbar'));
  topAppBar.setScrollTarget(document.getElementById('main-content'));
  topAppBar.listen('MDCTopAppBar:nav', () => {
  drawer.open = !drawer.open;
  });
  $('#logout').on('click', () => {
    logout();
  });

  loadProfile();
  document.getElementById('search').addEventListener('click', searchFunction);
  const belt = document.getElementById('notifications');
  belt.addEventListener('click', openNotifications);
  const tempUrl = new URL('http://localhost/rest_api.php/notification/unreadNumber');
  tempUrl.searchParams.append('username', window.localStorage.getItem('username'));

  const response = await fetch(tempUrl, {
    method: 'GET'
  });
  if (response.ok) {
    const newNotifications = (await response.json())[0].number;
    if (newNotifications > 0) {
      belt.innerText = 'notification_important';
      belt.style.color = 'red';
    }
  }
  
}

const resetNavbar = async (e) => {
  const navbar = document.getElementById('navbar');
  navbar.classList.remove('background-white');
  navbar.innerHTML = `
  <div class="mdc-top-app-bar__row">
    <section id="navbar-start" class="mdc-top-app-bar__section mdc-top-app-bar__section--align-start">
      <button class="material-icons mdc-top-app-bar__navigation-icon mdc-icon-button" aria-label="Open navigation menu">menu</button>
      <span id="page_title" class="mdc-top-app-bar__title"></span>
    </section>
    <section id="navbar-end" class="mdc-top-app-bar__section mdc-top-app-bar__section--align-end" role="toolbar">
      <a href="cart.php">
        <button class="material-icons mdc-top-app-bar__action-item mdc-icon-button" aria-label="Shopping cart">shopping_cart</button>
      </a>  
      <button id="notifications" class="material-icons mdc-top-app-bar__action-item mdc-icon-button" aria-label="Notifications">notifications</button>
      <button id="search" class="material-icons mdc-top-app-bar__action-item mdc-icon-button" aria-label="Search">search</button>
    </section>
  </div>`;
  initializeNavbar();
}

const searchFunction = async (e) => {
  const navbar = document.getElementById('navbar');
  navbar.classList.add('background-white');
  const navbarStart = document.getElementById('navbar-start');
  const navbarEnd = document.getElementById('navbar-end');
  navbarStart.innerHTML = "";
  navbarEnd.innerHTML = "";
  const searchForm = document.createElement('form');
  searchForm.method = "GET";
  searchForm.action = 'http://localhost/home.php'
  searchForm.style = `
    height: 100%;
    width: 100%;
  `;
  const searchText = document.createElement('input');
  searchText.type = 'text';
  searchText.placeholder = "Search"
  searchText.name = "search";
  searchText.classList.add('search-input');
  searchForm.appendChild(searchText);
  navbarStart.appendChild(searchForm);
  const resetNavbarButton = document.createElement('button');
  resetNavbarButton.classList.add('serach-close-button');
  const buttonIcon = document.createElement('i');
  buttonIcon.setAttribute("class", "material-icons pointer");
  buttonIcon.innerText = 'clear';
  resetNavbarButton.appendChild(buttonIcon);
  resetNavbarButton.addEventListener('click', resetNavbar);
  navbarEnd.appendChild(resetNavbarButton);
}

function createNotificationsCard(description, date, time, checked) {
  // card
  const card = document.createElement('div');
  card.classList.add('notifications-card', 'mdc-card');

  // card header
  const cardHeader = document.createElement("div");
  cardHeader.classList.add('center-vertical', 'notifications-card-header', checked ? 'background-lightblue' : 'background-red');
  const headerText = document.createElement('span');
  headerText.classList.add('date-time');
  headerText.innerText = (checked ? '' : '[Unread]') + `Arrived on ${date} at ${time}`;
  cardHeader.appendChild(headerText);
  card.appendChild(cardHeader);

  // card body
  const cardBody = document.createElement("div");
  cardBody.classList.add('notifications-card-body')
  const bodyText = document.createElement('p');
  bodyText.innerText = description;
  cardBody.appendChild(bodyText);
  card.appendChild(cardBody);

  return card;
}

function renderNotificationsMenu() {
  const container = document.createElement('div');
  container.id = 'notifications-menu';
  container.classList.add('notifications-container');

  // title
  const title = document.createElement('h2');
  title.innerText = 'Notifications';
  title.classList.add('notifications-title', 'center-text');
  container.appendChild(title);

  // notifications grid
  const grid = document.createElement('div');
  grid.id = 'notifications-grid';
  grid.classList.add('notifications-grid');

  container.appendChild(grid);
  
  
  // close button
  const closeButton = document.createElement('i');
  closeButton.setAttribute("class", "material-icons pointer");
  closeButton.innerHTML = 'clear';
  // position the close button top corner
  closeButton.style = `
    color: black;
    position: fixed;
    z-index: 10000;
    top: 91px;
    left: 35px;
  `;
  closeButton.addEventListener('click', async () => {
    const element = document.getElementById('notifications-menu');
    const url = new URL('http://localhost/rest_api.php/notification/clearUnread');
    url.searchParams.append('username', window.localStorage.getItem('username'));
    fetch(url, {
      method: 'GET'
    })
    element.remove();
    const not = document.getElementById('notifications');
    not.innerText = 'notifications';
    not.style.color = 'white';
  });
  container.appendChild(closeButton);

  const main = document.getElementById('main-content');
  main.appendChild(container);
}

const openNotifications = async (e) => {
  const url = new URL('http://localhost/rest_api.php/notification/list');

  const CARDS_LOADED = 50;
  url.searchParams.append('limit', CARDS_LOADED);
  url.searchParams.append('offset', notificationsOffset);
  url.searchParams.append('username', window.localStorage.getItem('username'));

  const result = await fetch(url, {
    method: 'GET'
  });
  if (result.ok) {
    const notifications = await result.json();
    let grid = document.getElementById('notifications-grid');
    if (!grid) {
      renderNotificationsMenu();
      grid = document.getElementById('notifications-grid');
    }    

    for (const notification of notifications) {
      grid.appendChild(createNotificationsCard(notification.description, notification.date, notification.time, notification.checked));
    }
    
    window.onscroll = function(ev) {
      if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
        // you're at the bottom of the page
        notificationsOffset += CARDS_LOADED;
        openNotifications();
      }
    };
  }
}

function setActiveListElement(elementID) {
  const elements = document.getElementsByClassName('mdc-list-item');
  for (const element of elements) {
    element.classList.remove('mdc-list-item--activated');
  }
  if (elementID != null) {
    document.getElementById(elementID).classList.add('mdc-list-item--activated');
  }
}



{/* <header id="navbar" class="mdc-top-app-bar app-bar mdc-top-app-bar--fixed" id="app-bar">
  <div class="mdc-top-app-bar__row">
    <section id="navbar-start" class="mdc-top-app-bar__section mdc-top-app-bar__section--align-start">
      <button class="material-icons mdc-top-app-bar__navigation-icon mdc-icon-button" aria-label="Open navigation menu">menu</button>
      <span id="page_title" class="mdc-top-app-bar__title"></span>
    </section>
    <section id="navbar-end" class="mdc-top-app-bar__section mdc-top-app-bar__section--align-end" role="toolbar">
      <a href="cart.php">
        <button class="material-icons mdc-top-app-bar__action-item mdc-icon-button" aria-label="Shopping cart">shopping_cart</button>
      </a>  
      <button class="material-icons mdc-top-app-bar__action-item mdc-icon-button" aria-label="Notifications">notifications</button>
      <button id="search" class="material-icons mdc-top-app-bar__action-item mdc-icon-button" aria-label="Search">search</button>
    </section>
  </div>
</header> */}


initializeNavbar();

