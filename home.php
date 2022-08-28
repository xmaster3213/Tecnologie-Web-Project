<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link href="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css" rel="stylesheet">
  <script>window.VIEW = "HOME";</script>
  <script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="css/navbar.css">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <?php require_once("navbar_logged.php"); ?>
  <div id="modal-div" class="mdc-drawer-app-content mdc-top-app-bar--fixed-adjust">
    <main class="main-content" id="main-content">
      <!-- grid -->
      <div id="cards" class="cards-grid">

      </div>
    </main>
  </div>
  <script src="js/card_expansion.js"></script>
  <script src="js/shared.js"></script>
  <script src="js/navbar.js"></script>
</body>
</html>

<script>
  const selector = '.mdc-button, .mdc-icon-button, .mdc-card__action';
  const ripples = [].map.call(document.querySelectorAll(selector), function(el) {
    return new mdc.ripple.MDCRipple(el);
  });
  changePageTitle("Home");
  renderHome();
  setActiveListElement("list-item-home");
</script>