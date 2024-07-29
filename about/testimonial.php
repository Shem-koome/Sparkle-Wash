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

    <body>

        <!-- Spinner Start -->
        <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50  d-flex align-items-center justify-content-center">
            <div class="spinner-grow text-primary" role="status"></div>
        </div>
        <!-- Spinner End -->

<?php
include '../home/dash.php';
?>


      <!-- Header Start -->
      <div class="container-fluid bg-breadcrumb py-5">
            <div class="container text-center py-5">
                <h3 class="text-white display-3 mb-4">Our testimonial</h1>
                <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="../home/Home.php">Home</a></li>
                <li class="breadcrumb-item"><a href="team.php">about</a></li>
                    <li class="breadcrumb-item active text-white">testimonial</li>
                </ol>    
            </div>
        </div>
        <!-- Header End -->

         <!-- Testimonial Start -->
         <div class="container-fluid testimonial py-5" style="background: var(--bs-secondary);">
            <div class="container py-5">
                <div class="text-center mx-auto mb-5" style="max-width: 800px;">
                    <p class="fs-4 text-uppercase text-primary">Testimonials</p>
                    <h1 class="display-4 mb-4 text-white">What Our Clients Say!</h1>
                </div>
                <div class="owl-carousel testimonial-carousel">
                    <div class="testimonial-item rounded p-4">
                        <div class="row">
                            <div class="col-4">
                                <div class="d-flex flex-column mx-auto">
                                    <div class="rounded-circle mb-4" style="border: dashed; border-color: var(--bs-white);">
                                        <img src="../img/testimonial1.jpg" class="img-fluid rounded-circle" alt="">
                                    </div>
                                    <div class="text-center">
                                        <h4 class="mb-2 text-primary">Kevo</h4>
                                        <p class="m-0 text-white">Leadership Coach</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="position-absolute" style="top: 20px; right: 25px;">
                                    <i class="fa fa-quote-right fa-2x text-secondary"></i>
                                </div>
                                <div class="testimonial-content">
                                    <div class="d-flex mb-4">
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <p class="fs-5 mb-0 text-white"> Impressive attention to detail and efficiency! 
                                        As someone who values excellence in service, I was pleasantly surprised by the thoroughness of their work.
                                         The car looked brand new, inside and out. Docked a point only because of a slight delay in completion.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-item rounded p-4">
                        <div class="row">
                            <div class="col-4">
                                <div class="d-flex flex-column mx-auto">
                                    <div class="rounded-circle mb-4" style="border: dashed; border-color: var(--bs-white);">
                                        <img src="../img/testimonial2.jpg" class="img-fluid rounded-circle" alt="">
                                    </div>
                                    <div class="text-center">
                                        <h4 class="mb-2 text-primary">Mimo</h4>
                                        <p class="m-0 text-white">Finance Officer</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="position-absolute" style="top: 20px; right: 25px;">
                                    <i class="fa fa-quote-right fa-2x text-secondary"></i>
                                </div>
                                <div class="testimonial-content">
                                    <div class="d-flex mb-4">
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                    </div>
                                    <p class="fs-5 mb-0 text-white">Incredibly satisfied with the service! Time is money, 
                                         and this car wash understands that perfectly. They were quick, yet meticulous. 
                                        My car sparkled afterward. Will definitely be returning, highly recommend!
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-item rounded p-4">
                        <div class="row">
                            <div class="col-4">
                                <div class="d-flex flex-column mx-auto">
                                    <div class="rounded-circle mb-4" style="border: dashed; border-color: var(--bs-white);">
                                        <img src="../img/testimonial3.jpg" class="img-fluid rounded-circle" alt="">
                                    </div>
                                    <div class="text-center">
                                        <h4 class="mb-2 text-primary">Kim</h4>
                                        <p class="m-0 text-white">Developer</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="position-absolute" style="top: 20px; right: 25px;">
                                    <i class="fa fa-quote-right fa-2x text-secondary"></i>
                                </div>
                                <div class="testimonial-content">
                                    <div class="d-flex mb-4">
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star-half-alt text-primary"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <p class="fs-5 mb-0 text-white">Decent job overall. The exterior cleaning was flawless,
                                         but I noticed a few missed spots inside. 
                                        Attention to detail could be improved. Nevertheless, the staff were friendly and professional.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-item rounded p-4">
                        <div class="row">
                            <div class="col-4">
                                <div class="d-flex flex-column mx-auto">
                                    <div class="rounded-circle mb-4" style="border: dashed; border-color: var(--bs-white);">
                                        <img src="../img/testimonial4.jpg" class="img-fluid rounded-circle" alt="">
                                    </div>
                                    <div class="text-center">
                                        <h4 class="mb-2 text-primary">Steff</h4>
                                        <p class="m-0 text-white">Teacher</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="position-absolute" style="top: 20px; right: 25px;">
                                    <i class="fa fa-quote-right fa-2x text-secondary"></i>
                                </div>
                                <div class="testimonial-content">
                                    <div class="d-flex mb-4">
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <p class="fs-5 mb-0 text-white">Great value for money! My car received a thorough
                                         cleaning at a reasonable price. The staff were polite and efficient. 
                                        Only suggestion would be to offer a loyalty program for frequent customers.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-item rounded p-4">
                        <div class="row">
                            <div class="col-4">
                                <div class="d-flex flex-column mx-auto">
                                    <div class="rounded-circle mb-4" style="border: dashed; border-color: var(--bs-white);">
                                        <img src="../img/testimonial5.jpg" class="img-fluid rounded-circle" alt="">
                                    </div>
                                    <div class="text-center">
                                        <h4 class="mb-2 text-primary">Elijah</h4>
                                        <p class="m-0 text-white">Musician</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="position-absolute" style="top: 20px; right: 25px;">
                                    <i class="fa fa-quote-right fa-2x text-secondary"></i>
                                </div>
                                <div class="testimonial-content">
                                    <div class="d-flex mb-4">
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star-half-alt text-primary"></i>
                                    </div>
                                    <p class="fs-5 mb-0 text-white">As someone who travels a lot for gigs, 
                                        keeping my car clean is essential. This car wash exceeded my expectations. They even managed to remove some 
                                        stubborn stains I thought were permanent. Docking half a point because of a minor delay in service.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-item rounded p-4">
                        <div class="row">
                            <div class="col-4">
                                <div class="d-flex flex-column mx-auto">
                                    <div class="rounded-circle mb-4" style="border: dashed; border-color: var(--bs-white);">
                                        <img src="../img/testimonial6.jpg" class="img-fluid rounded-circle" alt="">
                                    </div>
                                    <div class="text-center">
                                        <h4 class="mb-2 text-primary">Josh</h4>
                                        <p class="m-0 text-white">Nurse</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="position-absolute" style="top: 20px; right: 25px;">
                                    <i class="fa fa-quote-right fa-2x text-secondary"></i>
                                </div>
                                <div class="testimonial-content">
                                    <div class="d-flex mb-4">
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                    </div>
                                    <p class="fs-5 mb-0 text-white">Highly impressed by the professionalism and quality of service. With my busy schedule, I appreciate businesses that respect my time. 
                                        The car was spotless and smelled fantastic. Will definitely be recommending this place to colleagues.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Testimonial End -->

        
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
</body>

<?php
include '../home/footer.php';
?>
</html>
