document.addEventListener('DOMContentLoaded', function() {
    var menuHover = document.getElementById('menu-hover');
    var dropdownMenu = menuHover.querySelector('.dropdown-menu');


    menuHover.addEventListener('mouseover', function() {
        dropdownMenu.style.display = 'block';
    });


    menuHover.addEventListener('mouseout', function() {
        dropdownMenu.style.display = 'none';
    });


    var submenuLinks = dropdownMenu.querySelectorAll('a');
    submenuLinks.forEach(function(link) {
        link.addEventListener('click', function() {

            var href = link.getAttribute('href');
            if (href) {
                window.location.href = href;
            }
        });
    });
});






// increment decrement checkout
var numberElement = document.getElementById('number');
var currentNumber = 0;

function incrementNumber() {
    currentNumber++;
    updateNumber();
}

function decrementNumber() {
    if (currentNumber > 0) {
        currentNumber--;
        updateNumber();
    }
}

function updateNumber() {
    numberElement.innerText = currentNumber;
}



// updating the tips calculator



let selectedButton = null;

function updateTip(tipPercentage) {

    if (selectedButton !== null) {
        selectedButton.classList.remove("clicked");
    }


    selectedButton = event.target;
    selectedButton.classList.add("clicked");

    const subtotal = 21.98;
    const taxRate = 0.06;
    const tipAmount = (subtotal * tipPercentage) / 100;
    const totalTax = subtotal * taxRate;
    const total = subtotal + totalTax + tipAmount;


    document.getElementById("subtotalValue").textContent = `$${(subtotal + tipAmount).toFixed(2)}`;
    document.getElementById("taxValue").textContent = `$${totalTax.toFixed(2)}`;
    document.getElementById("totalValue").textContent = `$${total.toFixed(2)}`;
}

function toggleCustomTip() {
    const customTipSection = document.getElementById("customTip");
    customTipSection.style.display = customTipSection.style.display === "none" ? "block" : "none";
}

function applyCustomTip() {
    const customTipAmount = parseFloat(document.getElementById("customTipAmount").value);
    if (!isNaN(customTipAmount)) {

        const subtotal = 21.98;
        const taxRate = 0.06;
        const totalTax = subtotal * taxRate;
        const total = subtotal + totalTax + customTipAmount;


        document.getElementById("subtotalValue").textContent = `$${(subtotal + customTipAmount).toFixed(2)}`;
        document.getElementById("taxValue").textContent = `$${totalTax.toFixed(2)}`;
        document.getElementById("totalValue").textContent = `$${total.toFixed(2)}`;
    }
}

document.getElementById("customTipAmount").addEventListener("keypress", function(event) {
    if (event.key === "Enter") {
        applyCustomTip();
    }
});