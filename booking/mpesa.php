<?php 
include'../auth/config.php';
// include'session.php';
// Fetch total price from cart.php
$totalPrice = 0.00; // Initialize total price

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Assuming amount and phone are posted from the form
    $amount = $_POST['amount'];
    $phone = $_POST['phone'];
    
    // Further processing of amount and phone number...
}
?>
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

    <!-- Customized Bootstrap Stylesheet -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="../css/style.css" rel="stylesheet">
</head>
    <style>
      @import url("https://fonts.googleapis.com/css2?family=Rubik:wght@500&display=swap");

      body {
        background-color: #eaedf4;
        font-family: "Rubik", sans-serif;
      }

      .card {
        width: 50%;
        border: none;
        border-radius: 15px;
        padding: 20px;
        margin-left: 300px;
      }

      .justify-content-around div {
        border: none;
        border-radius: 20px;
        background: #f3f4f6;
        padding: 5px 20px 5px;
        color: #8d9297;
      }

      .justify-content-around span {
        font-size: 12px;
      }

      .justify-content-around div:hover {
        background: green;
        color: white !important;
        cursor: pointer;
      }

      .justify-content-around div:nth-child(1) {
        background: green;
        color: white;
      }

      h6 {
        font-size: 15px;
        color:black !important;
      }
      .mpesa {
        background-color: green !important;
      }
      .records-tab:hover{
       color:white !important;
      }

      img {
        border-radius: 15px;
      }
      label {
        color: black;
    }
    .btn-success {
    background-color: green;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
}

.btn-success:hover {
    background-color: darkgreen;
}

    </style>
  </head>
  <body oncontextmenu="return false" class="snippet-body">
    <div class="container d-flex justify-content-center">
      <div class="card mt-5 px-3 py-4">
        <div class="d-flex flex-row justify-content-around">
          <div class="mpesa"><span>Mpesa </span></div>
          <div class="records-tab"> <a class="records-tab" href="./mpesa_records.php" style="text-decoration:none; color:grey;"> <span>Records</span> </a>  </div>
          <div style="display:none;"><span>Card</span></div>
        </div>
        <div class="media mt-4 pl-2">
          <img src="../img/mpesa.png" class="mr-3" height="75" />
          <div class="media-body">
            <h6 class="mt-1">Enter Amount & Number</h6>
          </div>
        </div>
        <div class="media mt-3 pl-2">
                          <!--bs5 input-->

            <form class="row g-3" action="process_mpesa_payment.php" method="POST">
            
                <div class="col-12">
                  <label for="inputAddress" class="form-label">Amount</label>
                  <input type="text" class="form-control" id="totalPrice" name="totalPrice"  placeholder="Enter Amount">
                </div>
                <div class="col-12">
                  <label for="inputAddress2" class="form-label" >Phone Number</label>
                  <input type="text" class="form-control" id="phoneNumber" name="phoneNumber"  placeholder="Enter Phone Number">
                </div>
             
                <div class="col-12">
                  <button type="submit"  class="btn btn-success" name="submit" value="submit" id="checkout-button">Make Payment</button>
                </div>
              </form>
              <!--bs5 input-->
          </div>
        </div>
      </div>
    </div>
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

  </body>
</html>

