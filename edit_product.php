<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link href="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css" rel="stylesheet">
  <script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="css/navbar.css">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <?php require_once("navbar_logged.php"); ?>
  <div id="modal-div" class="mdc-drawer-app-content mdc-top-app-bar--fixed-adjust">
    <main class="main-content center" id="main-content">
      <!-- input form -->
      <form id="form">
        <!-- title -->
        <div>
          <h1 class="center-text">Edit Product</h1>
        </div>
        <!-- product name input -->
        <div>
          <label for="name" class="mdc-text-field mdc-text-field--outlined">
            <span class="mdc-notched-outline">
              <span class="mdc-notched-outline__leading"></span>
              <span class="mdc-notched-outline__notch">
                <span class="mdc-floating-label">Name</span>
              </span>
              <span class="mdc-notched-outline__trailing"></span>
            </span>
            <input id="name" type="text" class="mdc-text-field__input" aria-labelledby="my-label-id" required>
          </label>
        </div>
        <!-- product price input -->
        <div>
          <label for="price" class="mdc-text-field mdc-text-field--outlined">
            <span class="mdc-notched-outline">
              <span class="mdc-notched-outline__leading"></span>
              <span class="mdc-notched-outline__notch">
                <span class="mdc-floating-label">Price</span>
              </span>
              <span class="mdc-notched-outline__trailing"></span>
            </span>
            <input id="price" type="number" step="0.01" min="0" class="mdc-text-field__input" aria-labelledby="my-label-id" required>
          </label>
        </div>
        <!-- product quantity input -->
        <div>
          <label for="quantity" class="mdc-text-field mdc-text-field--outlined">
            <span class="mdc-notched-outline">
              <span class="mdc-notched-outline__leading"></span>
              <span class="mdc-notched-outline__notch">
                <span class="mdc-floating-label">Quantity</span>
              </span>
              <span class="mdc-notched-outline__trailing"></span>
            </span>
            <input id="quantity" type="number" class="mdc-text-field__input" aria-labelledby="my-label-id" required>
          </label>
        </div>
        <!-- product image input -->
        <div>
          <label for="image" class="mdc-text-field mdc-text-field--outlined">
            <span class="mdc-notched-outline">
              <span class="mdc-notched-outline__leading"></span>
              <span class="mdc-notched-outline__notch">
                <span class="mdc-floating-label">Image</span>
              </span>
              <span class="mdc-notched-outline__trailing"></span>
            </span>
            <input id="image" type="url" class="mdc-text-field__input" aria-labelledby="my-label-id" required>
          </label>
        </div>
        <!-- product description input -->
        <div>
          <label for="description" class="mdc-text-field mdc-text-field--outlined mdc-text-field--textarea mdc-text-field--no-label">
            <span class="mdc-notched-outline">
              <span class="mdc-notched-outline__leading"></span>
              <span class="mdc-notched-outline__notch">
                <span class="mdc-floating-label" id="my-label-id">Description</span>
              </span>
              <span class="mdc-notched-outline__trailing"></span>
            </span>
            <span class="mdc-text-field__resizer">
              <textarea id="description" class="mdc-text-field__input" aria-labelledby="my-label-id" rows="5"
                cols="40" maxlength="140" required></textarea>
            </span> 
          </label>
        </div>
        <!-- save button -->
        <div>
          <button id="save" class="mdc-button mdc-button--raised">
            <span class="mdc-button__ripple"></span>
            <span class="mdc-button__touch"></span>
            <span class="mdc-button__label">Save</span>
          </button>
        </div>

      </form>

    </main>
  </div>
  <script src="js/card_expansion.js"></script>
  <script src="js/navbar.js"></script>
  <script src="js/shared.js"></script>
</body>
</html>

<script>
  const selector = '.mdc-button, .mdc-icon-button, .mdc-card__action';
  const ripples = [].map.call(document.querySelectorAll(selector), function(el) {
    return new mdc.ripple.MDCRipple(el);
  });
  document.querySelectorAll('.mdc-text-field').forEach((el) => {
    new mdc.textField.MDCTextField(el);
  });

  changePageTitle("Edit Product");
  initializeEditproduct();
</script>