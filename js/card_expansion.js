const toggleExpansion = (element, to, duration = 350) => {
  return new Promise((res) => {
    element.animate([
      {
  top: to.top,
  left: to.left,
  width: to.width,
  height: to.height
      }
    ], {duration, fill: 'forwards', ease: 'ease-in'})
    setTimeout(res, duration);
  })
}

const fadeContent = (element, opacity, duration = 300) => {
  return new Promise(res => {
    [...element.children].forEach((child) => {
      requestAnimationFrame(() => {
        child.style.transition = `opacity ${duration}ms linear`;
        child.style.opacity = opacity;
      });
    })
    setTimeout(res, duration);
  })
}

const getCardContent = (page, name, description, price, product_image, quantity, seller, seller_image, id, fun) => {
  let action = "";
  switch (page) {
    case "HOME":
      action = `ADD TO CART`;
      break;
    case "SELL":
      action = `EDIT`;
      break;
    case "CART":
      action = `REMOVE FROM CART`;
      break;
  }
  // creating zoomed card
  const zoomedCardContainer = document.createElement("div");
  zoomedCardContainer.id = id;
  zoomedCardContainer.classList.add("zoomed-card-content");
  // creating product image
  const productImgElment = document.createElement("img");
  productImgElment.src = product_image;
  productImgElment.alt = "product image";
  productImgElment.classList.add("zoomed-card-image");
  zoomedCardContainer.appendChild(productImgElment);
  // creating image separator
  const separatorElment = document.createElement("hr");
  separatorElment.classList.add("solid");
  zoomedCardContainer.appendChild(separatorElment);
  // creating zoomed card details
  const productInfoContainer = document.createElement("div");
  productInfoContainer.classList.add("zoomed-card-details");
  
  const sellerImgElment = document.createElement("img");
  sellerImgElment.src = seller_image;
  sellerImgElment.alt = "seller image";
  sellerImgElment.classList.add("seller-image");
  productInfoContainer.appendChild(sellerImgElment);

  const sellerInfoContainer = document.createElement("div");
  sellerInfoContainer.classList.add("product-seller");
  const productNameElment = document.createElement("h2");
  productNameElment.innerText = name;
  sellerInfoContainer.appendChild(productNameElment);
  const productSellerElment = document.createElement("h3");
  productSellerElment.innerText = seller;
  sellerInfoContainer.appendChild(productSellerElment);
  productInfoContainer.appendChild(sellerInfoContainer);

  const priceElment = document.createElement("span");
  priceElment.classList.add("price", "product-price", "center-vertical");
  priceElment.innerText = price + "$";
  productInfoContainer.appendChild(priceElment);
  
  zoomedCardContainer.appendChild(productInfoContainer);
  // creating item quantity text
  const quantityElement = document.createElement("h3");
  quantityElement.classList.add("zoomed-card-items", "color-green");
  quantityElement.innerText = "Left: " + quantity;
  zoomedCardContainer.appendChild(quantityElement);
  // creating description
  const descriptionContainer = document.createElement("div");
  descriptionContainer.classList.add("zoomed-card-description", "description");
  const descriptionElement = document.createElement("p");
  descriptionElement.innerText = description;
  descriptionContainer.appendChild(descriptionElement);
  zoomedCardContainer.appendChild(descriptionContainer);
  // creating action button
  const buttonElement = document.createElement("button");
  buttonElement.classList.add("mdc-button", "zoomed-card-actions");
  const buttonRipple = document.createElement("span");
  buttonRipple.classList.add("mdc-button__ripple");
  buttonElement.appendChild(buttonRipple);
  const buttonLable = document.createElement("span");
  buttonLable.classList.add("mdc-button__label");
  buttonLable.innerText = action;
  buttonElement.appendChild(buttonLable);
  if (page == "HOME" && quantity == 0) {
    buttonElement.disabled = true;
  }
  buttonElement.addEventListener('click', fun);
  zoomedCardContainer.appendChild(buttonElement);
  return zoomedCardContainer;
}

async function createZoomedCard(card) {
  // get the info of the selected card
  // clone the card
  const cardClone = card.cloneNode(true);
  // get the location of the card in the view
  const {top, left, width, height} = card.getBoundingClientRect();
  // position the clone on top of the original
  cardClone.style.position = 'fixed';
  cardClone.style.top = top + 'px';
  cardClone.style.left = left + 'px';
  cardClone.style.width = width + 'px';
  cardClone.style.height = height + 'px';
  // hide the original card with opacity
  card.style.opacity = '0';
  // add card to the same container
  card.parentNode.appendChild(cardClone);
  // create a close button to handle the undo
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
  // attach click event to the close button
  closeButton.addEventListener('click', async () => {
    // remove the button on close
    closeButton.remove();
    // remove the display style so the original content is displayed right
    cardClone.style.removeProperty('display');
    cardClone.style.removeProperty('padding');
    // show original card content
    [...cardClone.children].forEach(child => child.style.removeProperty('display'));
    fadeContent(cardClone, '0');
    // shrink the card back to the original position and size
    await toggleExpansion(cardClone, {top: `${top}px`, left: `${left}px`, width: `${width}px`, height: `${height}px`}, 300)
    // show the original card again
    card.style.removeProperty('opacity');
    // remove the clone card
    cardClone.remove();
  });
  // fade the content away
  fadeContent(cardClone, '0')
    .then(() => {
      [...cardClone.children].forEach(child => child.style.display = 'none');
    });
  // expand the clone card
  await toggleExpansion(cardClone, {top: '56px', left: 0, width: '100vw', height: '100%'});
  // set the display block so the content will follow the normal flow in case the original card is not display block
  cardClone.style.display = 'block';
  cardClone.style.padding = '0';
  // append the close button after the expansion is done
  cardClone.appendChild(closeButton);
  return cardClone;
}

const onCardClick = async (e) => {
  const card = e.currentTarget.parentNode;
  const cardClone = await createZoomedCard(card);
  let product;
  let fun;
  if(window.VIEW == "CART") {
    fun = onRemoveFromZoomedCartClick;
    for(let el of cart.getProducts()) {
      if(el.id == card.id) {
        product = el;
      }
    }
  } else {
    product = allProducts.find(el => el.id == card.id);
    fun = window.VIEW == "HOME" ? onAddToCartClick : onEditClick;
  }
  const content = getCardContent(window.VIEW, product.name, product.description, product.price, product.product_image, product.quantity, product.seller, product.seller_image, product.id, fun);
  cardClone.appendChild(content);
};

