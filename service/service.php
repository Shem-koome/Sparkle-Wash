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

    <!-- Custom Stylesheet -->
    <style>
        .services-item {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }
        .services-img {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 200px;
        }
        .services-img img {
            max-height: 100%;
            max-width: 100%;
        }
        .services-content {
            flex-grow: 1;
        }
        .services-content h3 {
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <!-- Spinner Start -->
    <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50  d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->

    <?php include '../home/dash.php'; ?>

    <!-- Header Start -->
    <div class="container-fluid bg-breadcrumb py-5">
        <div class="container text-center py-5">
            <h3 class="text-white display-3 mb-4">Our Services</h3>
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="../home/Home.php">Home</a></li>
                <li class="breadcrumb-item"><a href="../about/about.php">About</a></li>
                <li class="breadcrumb-item active text-white">Our Services</li>
            </ol>    
        </div>
    </div>
    <!-- Header End -->

    <div id="alert-placeholder"></div>

    <!-- Services Start -->
    <div class="container-fluid services py-5">
        <div class="container py-5">
            <div class="mx-auto text-center mb-5" style="max-width: 800px;">
                <p class="fs-4 text-uppercase text-center text-primary">Our Services</p>
                <h1 class="display-3">Sparkle Wash Services</h1>
            </div>
            <div class="row g-4">

                <?php
                // Include the database connection file
                include '../auth/config.php';

                // Fetch services from the database
                $query = "SELECT * FROM services";
                $result = $mysqli->query($query);

                // Check if query was successful
                if ($result) {
                    // Loop through the services and generate HTML
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="col-lg-6">';
                        echo '<div class="services-item bg-light border-4 border-'. ($row['id'] % 2 == 0 ? 'start' : 'end') .' border-primary rounded p-4">';
                        echo '<div class="row align-items-center">';
                        echo '<div class="col-4">';
                        echo '<div class="services-img d-flex align-items-center justify-content-center rounded">';
                        echo '<img src="../img/' . $row['image_path'] . '" class="img-fluid rounded" alt="">';
                        echo '</div>';
                        echo '</div>';
                        echo '<div class="col-8">';
                        echo '<div class="services-content text-start">';
                        echo '<h3>' . $row['service_name'] . '</h3>';
                        echo '<p>' . $row['description'] . '</p>';
                        echo '<p class="price">$' . number_format($row['price'], 2) . '</p>';
                        echo '<button onclick="addToCart(\'../img/' . $row['image_path'] . '\',\'' . $row['service_name'] . '\', \'' . $row['price'] . '\')" class="btn btn-primary btn-primary-outline-0 rounded-pill py-2 px-4">Book Service</button>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No services found.</p>';
                }

                // Free result set and close the database connection
                $result->free();
                $mysqli->close();
                ?>

            </div>
        </div>
    </div>
    <!-- Services End -->

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

<?php include '../home/footer.php'; ?>

</html>
