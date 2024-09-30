document.addEventListener('DOMContentLoaded', (event) => {
    // Password validation and matching
    var password = document.getElementById("password");
    var confirm_password = document.getElementById("confirm_password");

    if (password && confirm_password) {
        function validatePassword() {
            if (password.value != confirm_password.value) {
                confirm_password.setCustomValidity("Passwords Don't Match");
            } else {
                confirm_password.setCustomValidity('');
            }
        }

        password.onchange = validatePassword;
        confirm_password.onkeyup = validatePassword;
    }

    var myInput = document.getElementById("password");
    var letter = document.getElementById("letter");
    var capital = document.getElementById("capital");
    var number = document.getElementById("number");
    var length = document.getElementById("length");

    if (myInput) {
        myInput.onfocus = function() {
            document.getElementById("message").style.display = "block";
        }

        myInput.onblur = function() {
            document.getElementById("message").style.display = "none";
        }

        myInput.onkeyup = function() {
            var lowerCaseLetters = /[a-z]/g;
            if (myInput.value.match(lowerCaseLetters)) {
                letter.classList.remove("invalid");
                letter.classList.add("valid");
            } else {
                letter.classList.remove("valid");
                letter.classList.add("invalid");
            }

            var upperCaseLetters = /[A-Z]/g;
            if (myInput.value.match(upperCaseLetters)) {
                capital.classList.remove("invalid");
                capital.classList.add("valid");
            } else {
                capital.classList.remove("valid");
                capital.classList.add("invalid");
            }

            var numbers = /[0-9]/g;
            if (myInput.value.match(numbers)) {
                number.classList.remove("invalid");
                number.classList.add("valid");
            } else {
                number.classList.remove("valid");
                number.classList.add("invalid");
            }

            if (myInput.value.length >= 8) {
                length.classList.remove("invalid");
                length.classList.add("valid");
            } else {
                length.classList.remove("valid");
                length.classList.add("invalid");
            }
        }
    }

    var togglePassword = document.getElementById('togglePassword');
    if (togglePassword) {
        togglePassword.addEventListener('click', function(e) {
            const password = document.getElementById('password');
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    }

    // Cart related functionalities
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    console.log("Loaded cart from localStorage:", cart);

    function updateCart() {
        const cartTable = document.querySelector('#cart-items tbody');
        if (!cartTable) {
            console.error("Cart table element not found");
            return;
        }
        cartTable.innerHTML = '';
        let total = 0;

        cart.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><img src="${item.image}" width="50" /></td>
                <td class="service">${item.name}</td>
                <td class="item-total">$${(item.price * item.quantity).toFixed(2)}</td>
                <td><i class="fas fa-times remove" data-name="${item.name}"></i></td>
            `;
            cartTable.appendChild(row);
            total += item.price * item.quantity;
        });

        document.getElementById('total-price').innerText = total.toFixed(2);

        document.querySelectorAll('.remove').forEach(icon => {
            icon.addEventListener('click', function() {
                removeFromCart(this.getAttribute('data-name'));
            });
        });
    }

    function addToCart(image, name, price) {
        console.log("Adding to cart:", image, name, price);
        const existingItem = cart.find(item => item.name === name);
        if (existingItem) {
            existingItem.quantity += 1; // Fixed increment
            showAlert('Service already exists in cart!', 'warning');
        } else {
            cart.push({ image, name, price: parseFloat(price), quantity: 1 });
            showAlert('Service booked successfully!', 'success');
        }
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCart();
    }

    function removeFromCart(name) {
        console.log("Removing from cart:", name);
        const itemIndex = cart.findIndex(item => item.name === name);
        if (itemIndex > -1) {
            cart.splice(itemIndex, 1);
            localStorage.setItem('cart', JSON.stringify(cart));
            updateCart();
            showAlert('Service removed!', 'info');
        }
    }

    window.addToCart = addToCart;

    updateCart();
    $(document).ready(function() {
        function fetchWashers() {
            $.ajax({
                url: '../booking/fetch_washers.php',
                method: 'GET',
                dataType: 'json',
                success: function(washers) {
                    const washerList = $('#washer-list');
                    washerList.empty(); // Clear any existing washers
    
                    washers.forEach(washer => {
                        // Check if washer is busy
                        $.ajax({
                            url: '../booking/washer_availability.php',
                            method: 'GET',
                            data: {
                                washerId: washer.id
                            },
                            dataType: 'json',
                            success: function(response) {
                                const isBusy = response.busy;
                                const buttonClass = isBusy ? 'btn btn-danger assign-button' : 'btn btn-primary assign-button';
                                const buttonText = isBusy ? 'Busy' : 'Assign';
    
                                washerList.append(`
                                    <div class="col-md-4">
                                        <div class="card mb-4">
                                            <img src="${washer.image}" class="card-img-top" alt="${washer.name}">
                                            <div class="card-body">
                                                <h5 class="card-title">${washer.name}</h5>
                                                <button class="${buttonClass}" data-id="${washer.id}" ${isBusy ? 'disabled' : ''}>${buttonText}</button>
                                            </div>
                                        </div>
                                    </div>
                                `);
                            },
                            error: function(xhr, status, error) {
                                showAlert('Error checking washer availability: ' + error, 'danger');
                            }
                        });
                    });
                },
                error: function(xhr, status, error) {
                    showAlert('Error fetching washer data: ' + error, 'danger');
                }
            });
        }
    
        fetchWashers();
    
        $('#washer-list').on('click', '.assign-button:not([disabled])', function() {
            $('.assign-button').text('Assign').removeClass('btn-success').addClass('btn-primary');
            $(this).text('Assigned').removeClass('btn-primary').addClass('btn-success');
        });

        $('#checkout-button').click(function() {
            const selectedWasher = $('.assign-button.btn-success').data('id');
            const washerName = $('.assign-button.btn-success').closest('.card-body').find('.card-title').text();
            const date = $('#date').val();
            const location = $('#location').val();
            const vehiclePlate = $('#vehicle-plate').val();
            
            // Get the payment mode by checking each radio button id
            let paymentMode = '';
            // Initialize phone number variable
let phoneNumber = '';
if ($('#payment-cash').is(':checked')) {
    paymentMode = $('#payment-cash').val();
} else if ($('#payment-mpesa').is(':checked')) {
    paymentMode = $('#payment-mpesa').val();
    phoneNumber = $('#phoneNumber').val(); // Get the phone number if MPesa is selected
}

const totalPrice = $('#total-price').text();

let services = [];

$('#cart-items tbody tr').each(function(index, row) {
    const serviceName = $(row).find('.service').text();
    const servicePrice = parseFloat($(row).find('.item-total').text().replace('$', ''));

    services.push({
        name: serviceName,
        price: servicePrice
    });
});

if (!selectedWasher) {
    showAlert('Please assign a washer.', 'danger');
    return;
}

if (!date) {
    showAlert('Please select a date.', 'danger');
    return;
}

if (!location) {
    showAlert('Please select a location.', 'danger');
    return;
}

if (!vehiclePlate) {
    showAlert('Please enter a valid vehicle plate.', 'danger');
    return;
}

if (!paymentMode) {
    showAlert('Please select mode of payment.', 'danger');
    return;
}

if (paymentMode === 'mpesa') {
    // Create a RegExp object for phone number validation
    // Matches local numbers (07XXXXXXXX or 01XXXXXXXX) and international numbers (2547XXXXXXXX)
    const phonePattern = /^(07\d{8}|01\d{8}|2547\d{8})$/;

    if (!phoneNumber) {
        showAlert('Please enter your phone number.', 'danger');
        return;
    }

    if (!phonePattern.test(phoneNumber)) {
        showAlert('Please enter a valid phone number. It should start with 07, 01, or 254 followed by 9 digits.', 'danger');
        return;
    }
}

$.ajax({
    url: '../booking/checkout.php',
    method: 'POST',
    data: {
        washerId: selectedWasher,
        washerName: washerName,
        date: date,
        location: location,
        vehiclePlate: vehiclePlate,
        paymentMode: paymentMode,
        phoneNumber: phoneNumber, // Send phone number only if MPesa is selected
        totalPrice: totalPrice,
        services: JSON.stringify(services)
    },
    success: function(response) {
        showAlert('Booked Services successfully!', 'success');
        localStorage.removeItem('cart');
        updateCart();
        $('#assignModal').modal('hide');
    },
    error: function(error) {
        showAlert('Error during checkout. Please try again.', 'danger');
    }
});
        });        
    });
});

document.addEventListener('DOMContentLoaded', function() {
    var receiptModal = document.getElementById('receiptModal');
    receiptModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var orderId = button.getAttribute('data-order-id');

        // Make an AJAX call to fetch order details based on orderId
        $.ajax({
            url: '../booking/receipt.php',
            method: 'GET',
            data: { order_id: orderId },
            success: function(response) {
                console.log(response);  // Check the structure of the response

                if (response && Array.isArray(response.details)) {
                    // Populate the modal with the fetched data
                    $('#receiptTableBody').empty();
                    response.details.forEach(function(detail) {
                        $('#receiptTableBody').append('<tr><td>' + detail.service_name + '</td><td>' + detail.service_price + '</td></tr>');
                    });

                    $('#receiptModalBody').find('.total-price').text('Total Price: ' + response.total_price);
                    $('#receiptModalBody').find('.washer-name').text('Washer: ' + response.washer_name);
                } else {
                    console.log('No data received or data format is incorrect.');
                }
            },
            error: function(error) {
                console.log('Error fetching order details:', error);
            }
        });
    });
});

