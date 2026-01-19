document.addEventListener('DOMContentLoaded', function() {
    var menuHover = document.getElementById('menu-hover');
    if (!menuHover) {
        return;
    }

    var dropdownMenu = menuHover.querySelector('.dropdown-menu');
    if (!dropdownMenu) {
        return;
    }

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
    if (numberElement) {
        numberElement.innerText = currentNumber;
    }
}



// updating the tips calculator



let selectedButton = null;

function updateTip(tipPercentage) {

    if (selectedButton !== null) {
        selectedButton.classList.remove("clicked");
    }


    selectedButton = typeof event !== 'undefined' ? event.target : selectedButton;
    if (selectedButton) {
        selectedButton.classList.add("clicked");
    }

    const subtotal = 21.98;
    const taxRate = 0.06;
    const tipAmount = (subtotal * tipPercentage) / 100;
    const totalTax = subtotal * taxRate;
    const total = subtotal + totalTax + tipAmount;


    var subtotalValue = document.getElementById("subtotalValue");
    var taxValue = document.getElementById("taxValue");
    var totalValue = document.getElementById("totalValue");

    if (subtotalValue) {
        subtotalValue.textContent = `$${(subtotal + tipAmount).toFixed(2)}`;
    }
    if (taxValue) {
        taxValue.textContent = `$${totalTax.toFixed(2)}`;
    }
    if (totalValue) {
        totalValue.textContent = `$${total.toFixed(2)}`;
    }
}

function toggleCustomTip() {
    const customTipSection = document.getElementById("customTip");
    if (!customTipSection) {
        return;
    }
    customTipSection.style.display = customTipSection.style.display === "none" ? "block" : "none";
}

function applyCustomTip() {
    const customTipInput = document.getElementById("customTipAmount");
    if (!customTipInput) {
        return;
    }
    const customTipAmount = parseFloat(customTipInput.value);
    if (!isNaN(customTipAmount)) {

        const subtotal = 21.98;
        const taxRate = 0.06;
        const totalTax = subtotal * taxRate;
        const total = subtotal + totalTax + customTipAmount;

        var subtotalValue = document.getElementById("subtotalValue");
        var taxValue = document.getElementById("taxValue");
        var totalValue = document.getElementById("totalValue");

        if (subtotalValue) {
            subtotalValue.textContent = `$${(subtotal + customTipAmount).toFixed(2)}`;
        }
        if (taxValue) {
            taxValue.textContent = `$${totalTax.toFixed(2)}`;
        }
        if (totalValue) {
            totalValue.textContent = `$${total.toFixed(2)}`;
        }
    }
}

var customTipAmountInput = document.getElementById("customTipAmount");
if (customTipAmountInput) {
    customTipAmountInput.addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            applyCustomTip();
        }
    });
}
