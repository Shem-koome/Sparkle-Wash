<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Sparkle Wash</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=PT+Serif:wght@400;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="../lib/animate/animate.min.css" rel="stylesheet">
    <link href="../lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="../lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="../css/style.css" rel="stylesheet">
</head>
<?php include '../home/dash.php'; ?>
<style>
     label {
        color: black;
    }
            /* Cart Styles */
        .cart {
            margin-top: 50px;
        }

        /* Cart Table Styles */
        #cart-items {
            width: 100%;
            border-collapse: collapse;
        }

        #cart-items th, #cart-items td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        #cart-items th {
            text-align: left;
            background-color: #f2f2f2;
        }

        #cart-items td {
            vertical-align: middle;
        }

        /* Total Price Styles */
        #total-price {
            font-weight: bold;
        }
        
        .button-container {
            display: flex;
            justify-content: space-between;
        }

        /* Modal Styles */
        .modal-body {
            padding: 20px;
        }

        #washer-list .card {
            margin-bottom: 20px;
        }

        #washer-list .card img {
            height: 340px;
            object-fit: cover;
        }

        #washer-list .card-title {
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        #washer-list .assign-button {
            width: 100%;
        }

        .modal-footer {
            justify-content: center;
        }

        .modal-footer .btn {
            width: 150px;
        }

        .form-control {
            color: black; /* Text color */
            border: 1px solid deeppink; /* Border color */
        }
        .form-check-input {
            border: 1px solid deeppink; /* Border color */
        }

    </style>

    </script>
<body>

    <!-- Spinner Start -->
    <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->
    
    <!-- Header Start -->
    <div class="container-fluid bg-breadcrumb py-5">
        <div class="container text-center py-5">
            <h3 class="text-white display-3 mb-4">Your Cart</h3>
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="../home/Home.php">Home</a></li>
                <li class="breadcrumb-item"><a href="../service/service.php">Services</a></li>
                <li class="breadcrumb-item active text-white">Cart</li>
            </ol>
        </div>
    </div>
    <!-- Header End -->

    <section>
        <div class="container-fluid services py-5">
            <div class="container py-5">
                <div class="mx-auto text-center mb-5" style="max-width: 800px;">
                    <h1 class="display-3">Your Cart</h1>
                </div>

                <div class="cart">
                    <h2>Services Cart</h2>
                    <br>
                    <hr>
                    <br>
                    <table id="cart-items">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="cart-items-body">
                            <!-- Cart items will be added here dynamically -->
                        </tbody>
                    </table>
                    <br><br>
                    <div class="container">
                        <h2>
                            <center> <div>Total: $<span id="total-price">0.00</span></div> </center>
                        </h2>
                    </div>
                    <br>
                    <div id="alert-placeholder"></div>
                    <div class="button-container">
                        <button onclick="window.location.href = '../service/service.php';" class="btn btn-primary btn-primary-outline-0 rounded-pill py-2 px-4">Continue Booking</button>
                        <button id="assign-washer-button" class="btn btn-primary btn-primary-outline-0 rounded-pill py-2 px-4">Assign Washer</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Assign Washer Modal -->
    <div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignModalLabel">Assign a Washer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" id="washer-list">
                        <!-- Washer cards will be added here dynamically -->
                    </div>
                    <div class="mt-4">
                        <label for="date">Preferred Date:</label>
                        <input type="date" id="date" class="form-control" min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="mt-4">
                        <label for="location">Preferred Location:</label>
                        <select id="location" class="form-control">
                            <option value="Roysambu">Roysambu</option>
                            <option value="Kasarani">Kasarani</option>
                            <option value="C.B.D">C.B.D</option>
                            <option value="Wendani">Wendani</option>
                            <!-- Add more locations as needed -->
                        </select>
                    </div>
                    <div class="mt-4">
        <label for="vehicle-plate">Vehicle Plate:</label>
        <input type="text" id="vehicle-plate" class="form-control" pattern="[A-Z]{3}[0-9]{3}[A-Z]" required oninput="convertToUpperCase(this)">
    </div>

    <script>
        function convertToUpperCase(input) {
            input.value = input.value.toUpperCase();
        }
    </script>
                    <!--payment-->
                 <!-- Modal Body -->
<div class="mt-4">
    <label>Mode of Payment:</label>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="payment" id="payment-cash" value="cash" required>
        <label class="form-check-label" for="payment-cash">
            Cash
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" name="payment" type="checkbox" id="payment-mpesa" value="mpesa" required>
        <label class="form-check-label" for="payment-mpesa">
            MPesa
        </label>
    </div>
    <div class="mt-4" id="mpesa-form" style="display: none;"> <!-- Initially hidden -->
    <div class="container d-flex justify-content-center">
        <div class="card mt-5 px-3 py-4">
            <div class="media mt-4 pl-2">
                <img src="../img/mpesa.png" class="mr-3" height="75" />
            </div>
            <div class="media mt-3 pl-2">
                <!-- bs5 input -->
                <form class="row g-3" action="../booking/checkout.php" method="POST">
                    <div class="col-12">
                        <label for="totalPrice" class="form-label">Amount</label>
                        <input type="text" class="form-control" id="totalPrice" name="totalPrice" value="0.00" readonly>
                    </div>
                    <div class="col-12">
                        <label for="phoneNumber" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" placeholder="Enter your Phone Number" required>
                    </div>
                </form>
                <!-- bs5 input -->
            </div>
        </div>
    </div>
</div>

                  <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="checkout-button">Checkout</button>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to update the MPesa amount field
    function updateMpesaAmount() {
        // Get the total price from the DOM
        const totalPriceElement = document.getElementById('total-price');
        const totalPrice = parseFloat(totalPriceElement.textContent.replace(/,/g, '')); // Remove commas if present and convert to number
        
        // Get the MPesa amount field
        const mpesaAmountField = document.querySelector('#mpesa-form #totalPrice');

        if (mpesaAmountField) {
            // Update the value of the MPesa amount field
            mpesaAmountField.value = totalPrice.toFixed(2); // Format to 2 decimal places
        }
    }

    // Call the function to set the initial value
    updateMpesaAmount();

    // Optionally, if the total price can change dynamically, add a MutationObserver
    const observer = new MutationObserver(updateMpesaAmount);
    const totalPriceElement = document.getElementById('total-price');
    
    if (totalPriceElement) {
        observer.observe(totalPriceElement, { childList: true, subtree: true });
    }
});

     document.getElementById('assign-washer-button').addEventListener('click', function(event) {
    const totalPrice = parseFloat(document.getElementById('total-price').textContent);
    if (totalPrice <= 0) {
        event.preventDefault();
        showAlert('Please add services to your cart before assigning a washer.', 'danger');
        return false;
    }
    $('#assignModal').modal('show');
});

document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('input[name="payment"]');

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                checkboxes.forEach(box => {
                    if (box !== this) {
                        box.checked = false;
                    }
                });
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const cashCheckbox = document.getElementById('payment-cash');
    const mpesaCheckbox = document.getElementById('payment-mpesa');
    const mpesaForm = document.getElementById('mpesa-form');

    function updateFormVisibility() {
        if (mpesaCheckbox.checked) {
            mpesaForm.style.display = 'block';
        } else {
            mpesaForm.style.display = 'none';
        }
    }

    // Add event listeners to checkboxes
    cashCheckbox.addEventListener('change', function() {
        if (this.checked) {
            mpesaCheckbox.checked = false; // Uncheck MPesa checkbox
            updateFormVisibility();
        }
    });

    mpesaCheckbox.addEventListener('change', function() {
        if (this.checked) {
            cashCheckbox.checked = false; // Uncheck Cash checkbox
            updateFormVisibility();
        } else {
            updateFormVisibility();
        }
    });

    // Initial update of form visibility based on the current state of the checkboxes
    updateFormVisibility();
});

    </script>
   <!-- JavaScript Libraries -->
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../lib/wow/wow.min.js"></script>
    <script src="../lib/easing/easing.min.js"></script>
    <script src="../lib/waypoints/waypoints.min.js"></script>
    <script src="../lib/counterup/counterup.min.js"></script>
    <script src="../lib/lightbox/js/lightbox.min.js"></script>
    <script src="../lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="../js/main.js"></script>
    <script src="../js/script.js"></script>

    <?php include '../home/footer.php'; ?>
</body>
</html>
