<header id="navbar" class="mdc-top-app-bar app-bar mdc-top-app-bar--fixed" id="navbar">
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
  </div>
</header>
<aside id="modal-aside" class="mdc-drawer mdc-drawer--dismissible mdc-top-app-bar--fixed-adjust">
  <div class="mdc-drawer__header">
    <a href="profile.php">
      <img id="navbar-image" src="public/default_image.png" alt="Profile picture" class="avatar">
      <h3 id="navbar-name" class="mdc-drawer__title">Profile Name</h3>
      <h6 id="navbar-email" class="mdc-drawer__subtitle">email@material.io</h6>
    </a>
  </div>
  <hr class="solid">
  <div class="mdc-drawer__content">
    <div class="mdc-list buttons">
      <a id="list-item-home" class="mdc-list-item" href="home.php" aria-current="page">
        <span class="mdc-list-item__ripple"></span>
        <i class="material-icons mdc-list-item__graphic" aria-hidden="true">home</i>
        <span class="mdc-list-item__text">Home</span>
      </a>
      <a id="list-item-sell" class="mdc-list-item" href="sell.php">
        <span class="mdc-list-item__ripple"></span>
        <i class="material-icons mdc-list-item__graphic" aria-hidden="true">sell</i>
        <span class="mdc-list-item__text">Sell</span>
      </a>
      <a id="list-item-history" class="mdc-list-item" href="history.php">
        <span class="mdc-list-item__ripple"></span>
        <i class="material-icons mdc-list-item__graphic" aria-hidden="true">history</i>
        <span class="mdc-list-item__text">History</span>
      </a>
      <a id="logout" class="mdc-list-item" href="login.php">
        <span class="mdc-list-item__ripple"></span>
        <i class="material-icons mdc-list-item__graphic" aria-hidden="true">logout</i>
        <span class="mdc-list-item__text">Logout</span>
      </a>
    </div>
  </div>
</aside>