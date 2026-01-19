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
  const selectableButtons = document.querySelectorAll(".selectable-button");
  const makeAppointmentBtn = document.getElementById("makeAppointmentBtn");

  if (selectableButtons.length) {
    selectableButtons.forEach((button) => {
      button.addEventListener("click", function () {
        this.classList.toggle("selected");
      });
    });
  }

  if (makeAppointmentBtn) {
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
  }
});

const gridButtons = document.querySelectorAll(".grid button");

if (gridButtons.length) {
  gridButtons.forEach((button) => {
    button.addEventListener("click", () => {
      gridButtons.forEach((btn) => btn.classList.remove("active"));

      button.classList.add("active");
    });
  });
}

window.addEventListener("load", function () {
  const content = document.querySelector(".content");
  const preloader = document.querySelector(".preloader");

  if (content) {
    content.classList.remove("hidden");
  }

  if (preloader) {
    preloader.classList.add("hidden");
  }
});

const phoneIcon = document.getElementById("phoneIcon");
const phoneNumber = document.getElementById("phoneNumber");

if (phoneIcon && phoneNumber) {
  phoneIcon.addEventListener("mouseenter", () => {
    phoneNumber.classList.remove("hidden");
  });

  phoneIcon.addEventListener("mouseleave", () => {
    phoneNumber.classList.add("hidden");
  });
}
//Cart
function incrementQuantity() {
  var quantityInput = document.getElementById("quantityInput");
  if (!quantityInput) {
    return;
  }
  var currentValue = parseInt(quantityInput.value || "0", 10);
  quantityInput.value = currentValue + 1;
}

function decrementQuantity() {
  var quantityInput = document.getElementById("quantityInput");
  if (!quantityInput) {
    return;
  }
  var currentValue = parseInt(quantityInput.value || "0", 10);
  if (currentValue > 1) {
    quantityInput.value = currentValue - 1;
  }
}
