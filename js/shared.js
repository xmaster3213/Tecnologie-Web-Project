class Cart {

  #cart;
  #username;

  constructor(username) {
    this.#username = username;
    this.#cart = this.#loadCart();
  }

  #loadCart() {
    const x = JSON.parse(window.localStorage.getItem(`cart-${this.#username}`));
    return x != null ? x : [];
  }

  #saveCart() {
    window.localStorage.setItem(`cart-${this.#username}`, JSON.stringify(this.#cart));
  }

  // add a product to the cart
  // @product: {id, name, description, price, product_image, quantity, seller, seller_image}
  addElement(product) {
    this.#cart.push(product);
    this.#saveCart()
  }

  // remove a product from the cart
  // @id: id of the product
  removeElement(id) {
    let idx = this.#cart.findIndex(product => product.id == id);
    this.#cart.splice(idx, 1);
    this.#saveCart();
  }

  empty() {
    window.localStorage.removeItem(`cart-${this.#username}`);
  }

  // generator of products
  // return: generator<product>
  *getProducts() {
    for(const product of this.#cart) {
      yield product;
    }
  }

}

// cart
const cart = new Cart(window.localStorage.getItem('username'));
// products fetched
let allProducts = [];
// current number of cards rendered
let offset = 0;
// the device is a deskotop or mobile
const IS_DESKTOP = screen.width > 700;
// sleep function(@params milliseconds to wait)
const sleep = ms => new Promise(r => setTimeout(r, ms));

function checkLogged() {
  if (!window.sessionStorage.getItem('logged')) {
    window.location.href = "http://localhost/login.php";
  }
}

function logout() {
  window.sessionStorage.clear();
}

// remove element from cart
const onRemoveFromCartClick = async (e) => {
  element = e.currentTarget.parentNode;
  cart.removeElement(element.id);
  element.remove();
}

const onRemoveFromZoomedCartClick = async (e) => {
  element = e.currentTarget.parentNode;
  cart.removeElement(element.id);
  element.parentNode.parentNode.innerHTML = "";
  renderCart();
}

// add element to cart
const onAddToCartClick = async (e) => {
  element = e.currentTarget.parentNode;
  let product = allProducts.find(el => el.id == element.id);
  let productInCart = 0;
  for(let el of cart.getProducts()) {
    if(el.id == product.id) {
      productInCart++;
    }
  }
  if(product.quantity <= 0 || productInCart >= product.quantity) {
    e.currentTarget.style.backgroundColor = "#FF7F7F";
    e.currentTarget.removeEventListener('click', onAddToCartClick);
  } else {
    cart.addElement(product);
  }
}

const onEditClick = async (e) => {
  const url = new URL('http://localhost/edit_product.php');
  url.searchParams.append('id', e.currentTarget.parentNode.id);
  window.location.href = url;
}

const onClickAddNewProduct = async (e) => {
  window.location.href = 'http://localhost/edit_product.php';
}

const onLoginClick = async () => {
  const userInput = document.getElementById("username");
  const username = userInput.value;
  const passInput = document.getElementById("password");
  const password = passInput.value;
  const formData = new FormData();
  formData.append('username', username);
  formData.append('password', password);
  const response = await fetch("http://localhost/rest_api.php/user/checkCredentials", {
    method: "POST",
    body: formData
  });
  if (response.ok) {
    const result = await response.json();
    if (result.length) {
      window.localStorage.setItem('username', username);
      window.sessionStorage.setItem('logged', 'true');
      window.location.href = "http://localhost/home.php";
  }
  }
}

const onConfirmPurchaseClick = async (e) => {
  for(const card of cart.getProducts()) {
    const url = new URL('http://localhost/rest_api.php/sale/add');
    url.searchParams.append('username', window.localStorage.getItem('username'));
    url.searchParams.append('product', card.id);
    await fetch(url, {
      method: 'GET'
    });
  }
  cart.empty();
  window.location.href = 'http://localhost/home.php';
}

const onEditProductClick = async (e) => {
  const url = new URL(window.location.href);
  if (url.searchParams.has('id')) {
    const tempUrl = new URL('http://localhost/rest_api.php/product/edit');
    const formData = new FormData();
    formData.append('id', url.searchParams.get('id'));
    const nameInput = document.getElementById('name');
    formData.append('name', nameInput.value);
    const descriptionInput = document.getElementById('description');
    formData.append('description', descriptionInput.value);
    const priceInput = document.getElementById('price');
    formData.append('price', priceInput.value);
    const imageInput = document.getElementById('image');
    formData.append('image', imageInput.value);
    const quantityInput = document.getElementById('quantity');
    formData.append('quantity', quantityInput.value);
    await fetch(tempUrl, {
      method: 'POST',
      body: formData
    });
    window.location.href = 'http://localhost/sell.php';
  }
}

const onAddProductClick = async (e) => {
  const url = new URL('http://localhost/rest_api.php/product/add');
  const formData = new FormData();
  formData.append('seller', window.localStorage.getItem('username'));
  const nameInput = document.getElementById('name');
  formData.append('name', nameInput.value);
  const descriptionInput = document.getElementById('description');
  formData.append('description', descriptionInput.value);
  const priceInput = document.getElementById('price');
  formData.append('price', priceInput.value);
  const imageInput = document.getElementById('image');
  formData.append('image', imageInput.value);
  const quantityInput = document.getElementById('quantity');
  formData.append('quantity', quantityInput.value);
  await fetch(url, {
    method: "POST",
    body: formData
  })
  window.location.href = 'http://localhost/sell.php';
}

const onEditProfileClick = async (e) => {
  const url = new URL('http://localhost/rest_api.php/user/edit');
  const formData = new FormData();
  const passwordInput = document.getElementById('password');
  formData.append('password', passwordInput.value);
  const emailInput = document.getElementById('email');
  formData.append('email', emailInput.value);
  const creditCardInput = document.getElementById('credit-card');
  formData.append('creditCard', creditCardInput.value);
  const imageInput = document.getElementById('image');
  formData.append('image', imageInput.value);
  formData.append('username', window.localStorage.getItem('username'));
  await fetch(url, {
    method: 'POST',
    body: formData
  });
  window.location.href = 'http://localhost/home.php';
}

const onRegisterClick = async (e) => {
  const url = new URL('http://localhost/rest_api.php/user/add');
  const formData = new FormData();
  formData.append('username', document.getElementById('username').value);
  formData.append('password', document.getElementById('password').value);
  formData.append('email', document.getElementById('email').value);
  formData.append('creditCard', document.getElementById('credit-card').value);
  formData.append('image', document.getElementById('image').value);
  const response = await fetch(url, {
    method: 'POST',
    body: formData
  })
  if (response.ok) {
    window.localStorage.setItem('username', document.getElementById('username').value);
    window.sessionStorage.setItem('logged', 'true');
    window.location.href = 'http://localhost/home.php'
  }
}

// create an image for the cards
// @link: link of the image
// @alt: alt of the image
// return: node
function createImage(link, alt) {
  const img = document.createElement("img");
  img.src = link;
  img.alt = alt;
  img.classList.add("card-image");
  return img;
}

// create the info text for the card
// @name: name of the product
// @seller: seller of the product
// @price: price of the product
// return: node
function createInfoText(name, seller, price) {
  const container = document.createElement("div");
  container.classList.add("card-info");
  const nameText = document.createElement("h2");
  nameText.innerText = name;
  const sellerText = document.createElement("h3");
  sellerText.innerText = seller;
  const priceText = document.createElement("span");
  priceText.innerText = price + "$";
  priceText.classList.add("price");
  container.appendChild(nameText);
  container.appendChild(sellerText);
  container.appendChild(priceText);
  return container;
}

// create the icon button for the card
// @action: material design icon's name
// @onClickFunction: function to perform onClick
// return node
function createMaterialIcon(action, onClickFunction) {
  const container = document.createElement("div");
  container.classList.add("card-action", "mdc-card__action");
  const icon = document.createElement("i");
  icon.classList.add("material-icons");
  icon.innerText = action;
  container.appendChild(icon);
  container.addEventListener("click", onClickFunction);
  return container;
}

function createDateTimeHeader(date, time, seller) {
  // creating container
  const container = document.createElement("div");
  container.classList.add(seller ? 'background-green' : 'background-red', 'center-vertical', 'card-header');
  const text = document.createElement('span');
  text.classList.add('date-time');
  text.innerText = (seller ? 'Sold ' : 'Bought ') + `on ${date} at ${time}`;
  container.appendChild(text);
  return container;
}

// assembly a standard card
// @id: id of the product
// @product_image: image of the product
// @name: name of the product
// @seller: seller of the product
// @price: price of the product
// @button: the action button of the card, setted to default = null
// @onCardClickFunction: function to perform when clicking on the card
// return: node
function createCard(id, product_image, name, seller, price, button = null, onCardClickFunction) {
  const card = document.createElement("div");
  card.classList.add("card", "mdc-card");
  card.id = id;
  const img = createImage(product_image, "product image")
  img.addEventListener('click', onCardClickFunction);
  card.appendChild(img);
  const infoText = createInfoText(name, seller, price)
  infoText.addEventListener('click', onCardClickFunction);
  card.appendChild(infoText);
  if(button != null) {
    card.appendChild(button);
  }
  return card;
}

function createHistoryCard(product_image, name, seller, price, date, time) {
  const card = document.createElement("div");
  card.classList.add("mdc-card");
  const header = createDateTimeHeader(date, time, seller == window.localStorage.username ? true : false);
  card.appendChild(header);
  const body = document.createElement('div');
  body.classList.add('card');
  const img = createImage(product_image, "product image")
  body.appendChild(img);
  const infoText = createInfoText(name, seller, price)
  body.appendChild(infoText);
  card.appendChild(body);
  return card;
}

// render the elements in the cart
function renderCart() {
  checkLogged();
  const button = document.getElementById('confirm-purchase');
  button.addEventListener('click', onConfirmPurchaseClick);
  const cardsGrid = document.getElementById("cards");
  for(const product of cart.getProducts()){
    if(IS_DESKTOP) {
      const fun = onRemoveFromCartClick;
      const card = getCardContent(window.VIEW, product.name, product.description, product.price, product.product_image, product.quantity, product.seller, product.seller_image, product.id, fun); 
      card.classList.add('mdc-card');
      cardsGrid.appendChild(card);
    } else {
      cardsGrid.appendChild(createCard(
        product.id, 
        product.product_image, 
        product.name, 
        product.seller, 
        product.price, 
        createMaterialIcon("remove_shopping_cart", onRemoveFromCartClick), 
        onCardClick
      ));
    }
  }
}

// fetch the elements from the server and the renders them in cards
// @limit: number of product to fetch from the server
// @offset: the current last element
// @button: the action button of the cards
async function renderCards(buttonText, buttonOnClickFunction, url) {
  const response = await fetch(url, {
    method: 'GET'
  });
  if(response.ok) {
    const products = await response.json();
    const cardsGrid = document.getElementById("cards");
    for(const product of products){
      allProducts.push(product);
      if(IS_DESKTOP) {
        const fun = window.VIEW == "HOME" ? onAddToCartClick : onEditClick;
        const card = getCardContent(window.VIEW, product.name, product.description, product.price, product.product_image, product.quantity, product.seller, product.seller_image, product.id, fun); 
        card.classList.add('mdc-card');
        cardsGrid.appendChild(card);
      } else {
        cardsGrid.appendChild(createCard(product.id, product.product_image, product.name, product.seller, product.price, createMaterialIcon(buttonText, buttonOnClickFunction), onCardClick));
      }
    }
  }
}

async function renderHistoryCards(url) {
  const response = await fetch(url, {
    method: 'GET'
  });
  if(response.ok) {
    const products = await response.json();
    const cardsGrid = document.getElementById("cards");
    for(const product of products){
      allProducts.push(product);
      // if(IS_DESKTOP) {
      //   cardsGrid.appendChild() //TODO: create createHistoryCardDesktop function
      // } else {
      //   cardsGrid.appendChild(createHistoryCard(product.product_image, product.name, product.seller, product.price, product.date, product.time));
      // }
      cardsGrid.appendChild(createHistoryCard(product.product_image, product.name, product.seller, product.price, product.date, product.time));

    }
  }
}

// render the products when necessary
function renderHome() {
  checkLogged();

  const currentUrl = new URL(window.location.href);
  let url;
  if (currentUrl.searchParams.has('search')) {
    url = new URL('http://localhost/rest_api.php/product/listGivenKeywords');
    url.searchParams.append('keywords', currentUrl.searchParams.get('search'));
  } else {
    url = new URL('http://localhost/rest_api.php/product/list');
  }
  
  const CARDS_LOADED = 50;
  url.searchParams.append('limit', CARDS_LOADED);
  url.searchParams.append('offset', offset);

  renderCards("add_shopping_cart", onAddToCartClick, url);
  const main = document.getElementById('main-content');
  main.onscroll = function(ev) {
    if ((main.offsetHeight + main.scrollTop) >= main.scrollHeight)
      if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
        // you're at the bottom of the page
        offset += CARDS_LOADED;
        renderHome();
    }
  };
}

function renderSell() {
  checkLogged();
  const button = document.getElementById('add-product');
  button.addEventListener('click', onClickAddNewProduct);

  const CARDS_LOADED = 50;
  const url = new URL('http://localhost/rest_api.php/product/bySellerList');
  url.searchParams.append('limit', CARDS_LOADED);
  url.searchParams.append('offset', offset);
  url.searchParams.append('username', window.localStorage.getItem('username'));
  renderCards("edit", onEditClick, url);
  const main = document.getElementById('main-content');
  main.onscroll = function(ev) {
    if ((main.offsetHeight + main.scrollTop) >= main.scrollHeight)
    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
      // you're at the bottom of the page
      offset += CARDS_LOADED;
      renderSell();
    }
  };
}

function renderHistory() {
  checkLogged();
  const CARDS_LOADED = 50;
  const url = new URL('http://localhost/rest_api.php/sale/list'); 
  url.searchParams.append('limit', CARDS_LOADED);
  url.searchParams.append('offset', offset);
  url.searchParams.append('username', window.localStorage.getItem('username'));
  renderHistoryCards(url);
  const main = document.getElementById('main-content');
  main.onscroll = function(ev) {
    if ((main.offsetHeight + main.scrollTop) >= main.scrollHeight) {
      console.log(offset);
      // you're at the bottom of the page
      offset += CARDS_LOADED;
      renderHistory();
    }
  };
}

// set the page title
// @title: the page of the title
function changePageTitle(title) {
  let pageTitle = document.getElementById("page_title");
  pageTitle.innerHTML = title;
}

function initializeLogin() {
  $('#form').submit(function(event) {
    event.preventDefault();
  });
  const button = document.getElementById("login");
  button.addEventListener('click', onLoginClick);
  const userInput = document.getElementById("username");
  const user = window.localStorage.getItem('username');
  if (user != null) {
    userInput.value = user;
    const textFields = document.getElementsByClassName('mdc-text-field');
    textFields[0].classList.add('mdc-text-field--label-floating');
    const labels = document.getElementsByClassName('mdc-floating-label');
    labels[0].classList.add('mdc-floating-label--float-above');
  }
}

function initializeRegister() {
  $('#form').submit(function(event) {
    event.preventDefault();
  });
  const button = document.getElementById("register");
  button.addEventListener('click', onRegisterClick);
}

async function initializeProfile() {
  checkLogged();
  $('#form').submit(function(event) {
    event.preventDefault();
  });
  const button = document.getElementById('save');
  button.addEventListener('click', onEditProfileClick);
  const user = JSON.parse(window.sessionStorage.getItem('profile'));
  const passwordInput = document.getElementById('password');
  passwordInput.value = user.password;
  const emailInput = document.getElementById('email');
  emailInput.value = user.email;
  const CreditCardInput = document.getElementById('credit-card');
  CreditCardInput.value = user.credit_card_number;
  const imageInput = document.getElementById('image');
  imageInput.value = user.image;
  const textFields = document.getElementsByClassName('mdc-text-field');
  [].forEach.call(textFields, textField => textField.classList.add('mdc-text-field--label-floating'));
  const labels = document.getElementsByClassName('mdc-floating-label');
  [].forEach.call(labels, label => label.classList.add('mdc-floating-label--float-above'));
}

async function initializeEditproduct() {
  checkLogged();
  $('#form').submit(function(event) {
    event.preventDefault();
  });
  const button = document.getElementById('save');
  const currentUrl = new URL(window.location.href);
  if (currentUrl.searchParams.has('id')) {
    const tempUrl = new URL('http://localhost/rest_api.php/product/info');
    tempUrl.searchParams.append('id', currentUrl.searchParams.get('id'))
    const response = await fetch(tempUrl, {
      method: 'GET'
    });
    if (response.ok) {
      const products = await response.json();
      const currentProduct = products[0];
      // checking if product is made by the user
      if (currentProduct.seller == window.localStorage.getItem('username')) {
        const nameInput = document.getElementById('name');
        nameInput.value = currentProduct.name;
        const quantityInput = document.getElementById('quantity');
        quantityInput.value = currentProduct.quantity;
        const imageInput = document.getElementById('image');
        imageInput.value = currentProduct.product_image;
        const descriptionInput = document.getElementById('description');
        descriptionInput.innerText = currentProduct.description;
        const priceInput = document.getElementById('price');
        priceInput.value = currentProduct.price;
        const textFields = document.getElementsByClassName('mdc-text-field');
        [].forEach.call(textFields, textField => textField.classList.add('mdc-text-field--label-floating'));
        const labels = document.getElementsByClassName('mdc-floating-label');
        [].forEach.call(labels, label => label.classList.add('mdc-floating-label--float-above'));
      }
    } else {
      // product doesn't exist or error in the request
      window.location.href('http://localhost/sell.php');
    }
    button.addEventListener('click', onEditProductClick);
  } else {
    button.addEventListener('click', onAddProductClick);
  }
}