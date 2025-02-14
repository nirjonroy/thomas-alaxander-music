// Burger menus
document.addEventListener("DOMContentLoaded", function () {
  // open
  const burger = document.querySelectorAll(".navbar-burger");
  const menu = document.querySelectorAll(".navbar-menu");

  if (burger.length && menu.length) {
    for (var i = 0; i < burger.length; i++) {
      burger[i].addEventListener("click", function () {
        for (var j = 0; j < menu.length; j++) {
          menu[j].classList.toggle("hidden");
        }
      });
    }
  }

  // close
  const close = document.querySelectorAll(".navbar-close");
  const backdrop = document.querySelectorAll(".navbar-backdrop");

  if (close.length) {
    for (var i = 0; i < close.length; i++) {
      close[i].addEventListener("click", function () {
        for (var j = 0; j < menu.length; j++) {
          menu[j].classList.toggle("hidden");
        }
      });
    }
  }

  if (backdrop.length) {
    for (var i = 0; i < backdrop.length; i++) {
      backdrop[i].addEventListener("click", function () {
        for (var j = 0; j < menu.length; j++) {
          menu[j].classList.toggle("hidden");
        }
      });
    }
  }
});
document.addEventListener("DOMContentLoaded", function () {
  const buttons = document.querySelectorAll(".selectable-button");
  const makeAppointmentBtn = document.getElementById("makeAppointmentBtn");

  buttons.forEach((button) => {
    button.addEventListener("click", function () {
      this.classList.toggle("selected");
    });
  });

  makeAppointmentBtn.addEventListener("click", function () {
    const selectedButtons = document.querySelectorAll(
      ".selectable-button.selected"
    );
    if (selectedButtons.length > 0) {
      console.log("Appointment booked for:", selectedButtons);
    } else {
      alert("Please select at least one service.");
    }
  });
});

const buttons = document.querySelectorAll(".grid button");

buttons.forEach((button) => {
  button.addEventListener("click", () => {
    buttons.forEach((btn) => btn.classList.remove("active"));

    button.classList.add("active");
  });
});
window.onload = function () {
  document.querySelector(".content").classList.remove("hidden");
  document.querySelector(".preloader").classList.add("hidden");
};

const phoneIcon = document.getElementById("phoneIcon");
const phoneNumber = document.getElementById("phoneNumber");

phoneIcon.addEventListener("mouseenter", () => {
  phoneNumber.classList.remove("hidden");
});

phoneIcon.addEventListener("mouseleave", () => {
  phoneNumber.classList.add("hidden");
});
//Cart
function incrementQuantity() {
  var quantityInput = document.getElementById("quantityInput");
  quantityInput.value = parseInt(quantityInput.value) + 1;
}

function decrementQuantity() {
  var quantityInput = document.getElementById("quantityInput");
  if (parseInt(quantityInput.value) > 1) {
    quantityInput.value = parseInt(quantityInput.value) - 1;
  }
}
