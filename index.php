<?php
session_start();
require_once 'db.php';

// Fetch cars for the home page
$query = "SELECT * FROM cars";
$stmt = $pdo->query($query);
$cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Car Rental</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    
    <!-- Owl Carousel CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css">
    
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
        
            <img src="images/logo.png" alt="Logo" style="height: 50px;">
      
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <?php if ($isLoggedIn): ?>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                    <li class="nav-item"><a class="nav-link" href="profile.php"><?php echo htmlspecialchars($_SESSION['firstname']); ?></a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="auth.php">Login/Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

<!-- Banner Section Start -->
<!-- Banner Section Start -->
<div class="container">
    <div class="row">
        <!-- Left Column with Carousel -->
        <div class="col-md-6">
            <div id="banner_slider" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="banner_taital_main">
                            <h1 class="banner_taital">Find Your Perfect Ride <br><span style="color: #fe5b29;">With Us</span></h1>
                            <div class="btn_main">
                                <a href="#available-cars" class="btn btn-secondary">Browse Vehicles</a>
                                <a href="#foot" class="btn btn-primary">Get in Touch</a>
                            </div>
                        </div>
                    </div>
                    <!-- You can add more carousel items here if needed -->
                </div>
                <a class="carousel-control-prev" href="#banner_slider" role="button" data-slide="prev">
                    <i class="fa fa-angle-left"></i>
                </a>
                <a class="carousel-control-next" href="#banner_slider" role="button" data-slide="next">
                    <i class="fa fa-angle-right"></i>
                </a>
            </div>
        </div>
        
        <!-- Right Column with Image -->
        <div class="col-md-6">
            
        <img src="images/P.jpg" alt="Car Image" class="car-image img-fluid" style="max-height: 500px; width: 500px; object-fit: cover;">

            
        </div>
    </div>
</div>
<!-- Banner Section End -->

<!-- Banner Section End -->


    <main class="container mt-4" id="available-cars">
        <h2>Available Cars</h2>
        <div class="row">
            <?php foreach ($cars as $car): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="images/<?php echo htmlspecialchars($car['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($car['model']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($car['model']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($car['description']); ?></p>
                            <p class="price">₹<?php echo htmlspecialchars($car['price_per_day']); ?> per day</p>
                            <a href="profile.php?car_id=<?php echo htmlspecialchars($car['id']); ?>" class="btn btn-primary">Book Now</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Testimonials Section -->
        <div class="container-full-width my-5">
            <div class="text-center mb-4">
                <i class="fas fa-heart fa-2x text-danger"></i>
                <p class="tag text-muted">Our customers love</p>
                <h2 class="text-primary head">What we do</h2>
            </div>

            <div class="owl-carousel owl-theme">
                <!-- Testimonial 1 -->
                <div class="item">
                    <div class="card p-4 text-center border-0 shadow-sm">
                        <div class="testimonial-rating mb-2">
                            <span class="fas fa-star text-warning"></span>
                            <span class="fas fa-star text-warning"></span>
                            <span class="fas fa-star text-warning"></span>
                            <span class="fas fa-star text-warning"></span>
                            <span class="fas fa-star-half-alt text-warning"></span>
                        </div>
                        <h5 class="font-weight-bold">Excellent Experience!</h5>
                        <p class="testimonial-text text-muted">I rented a car for a family trip, and the service was impeccable. The vehicle was in perfect condition, and the customer service was top-notch.</p>
                        <div class="testimonial-footer mt-4">
                            <div class="name font-weight-bold">Rohan Sharma</div>
                            <p class="designation text-muted">Software Engineer</p>
                        </div>
                    </div>
                </div>
                <!-- Testimonial 2 -->
                <div class="item">
                    <div class="card p-4 text-center border-0 shadow-sm">
                        <div class="testimonial-rating mb-2">
                            <span class="fas fa-star text-warning"></span>
                            <span class="fas fa-star text-warning"></span>
                            <span class="fas fa-star text-warning"></span>
                            <span class="fas fa-star text-warning"></span>
                            <span class="fas fa-star text-warning"></span>
                        </div>
                        <h5 class="font-weight-bold">Highly Recommend!</h5>
                        <p class="testimonial-text text-muted">The booking process was smooth, and the car was delivered on time. I was impressed with the overall service and would definitely recommend it to others.</p>
                        <div class="testimonial-footer mt-4">
                            <div class="name font-weight-bold">Priya Verma</div>
                            <p class="designation text-muted">Marketing Manager</p>
                        </div>
                    </div>
                </div>
                <!-- Testimonial 3 -->
                <div class="item">
                    <div class="card p-4 text-center border-0 shadow-sm">
                        <div class="testimonial-rating mb-2">
                            <span class="fas fa-star text-warning"></span>
                            <span class="fas fa-star text-warning"></span>
                            <span class="fas fa-star text-warning"></span>
                            <span class="fas fa-star text-warning"></span>
                            <span class="fas fa-star-half-alt text-warning"></span>
                        </div>
                        <h5 class="font-weight-bold">Great Value for Money</h5>
                        <p class="testimonial-text text-muted">I was looking for an affordable car rental option, and I found it here. The car was in great shape, and the rates were very reasonable.</p>
                        <div class="testimonial-footer mt-4">
                            <div class="name font-weight-bold">Anjali Patel</div>
                            <p class="designation text-muted">HR Consultant</p>
                        </div>
                    </div>
                </div>
                <!-- Testimonial 4 -->
                <div class="item">
                    <div class="card p-4 text-center border-0 shadow-sm">
                        <div class="testimonial-rating mb-2">
                            <span class="fas fa-star text-warning"></span>
                            <span class="fas fa-star text-warning"></span>
                            <span class="fas fa-star text-warning"></span>
                            <span class="fas fa-star text-warning"></span>
                            <span class="fas fa-star text-warning"></span>
                        </div>
                        <h5 class="font-weight-bold">Smooth and Hassle-Free</h5>
                        <p class="testimonial-text text-muted">Renting a car has never been so easy! The team was very professional, and the car was clean and well-maintained. I had a great experience.</p>
                        <div class="testimonial-footer mt-4">
                            <div class="name font-weight-bold">Arjun Desai</div>
                            <p class="designation text-muted">Entrepreneur</p>
                        </div>
                    </div>
                </div>
                <!-- Testimonial 5 -->
                <div class="item">
                    <div class="card p-4 text-center border-0 shadow-sm">
                        <div class="testimonial-rating mb-2">
                            <span class="fas fa-star text-warning"></span>
                            <span class="fas fa-star text-warning"></span>
                            <span class="fas fa-star text-warning"></span>
                            <span class="fas fa-star text-warning"></span>
                            <span class="fas fa-star-half-alt text-warning"></span>
                        </div>
                        <h5 class="font-weight-bold">Fantastic Service!</h5>
                        <p class="testimonial-text text-muted">The car was delivered right to my doorstep, and it was in excellent condition. The rental process was straightforward and quick. I'm very satisfied.</p>
                        <div class="testimonial-footer mt-4">
                            <div class="name font-weight-bold">Neha Kapoor</div>
                            <p class="designation text-muted">Freelance Designer</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <footer class="footer_section" id="foot">
        <div class="container">
            <div class="row">
                <!-- About Section -->
                <div class="col-md-4 footer_section_2">
                    <h4 class="footer_taital">About Us</h4>
                    <p class="lorem_text">We offer the best car rental services with a wide range of vehicles to meet your needs. Experience exceptional service and value with us.</p>
                </div>

                <!-- Contact & Social Media -->
                <div class="col-md-4 footer_section_2">
                    <h4 class="footer_taital">Contact</h4>
                    <p class="footer_text">1234 Car Rental Lane<br>City, State, ZIP<br>Email: info@carrental.com<br>Phone: +123 456 7890</p>
                    <div class="social_icon">
                        <ul>
                            <li><a href="#" class="fab fa-facebook-f"></a></li>
                            <li><a href="#" class="fab fa-twitter"></a></li>
                            <li><a href="#" class="fab fa-instagram"></a></li>
                            <li><a href="#" class="fab fa-linkedin-in"></a></li>
                        </ul>
                    </div>
                </div>

                <!-- Office Map -->
                <div class="col-md-4 footer_section_2">
                    <h4 class="footer_taital">Our Office</h4>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.209755163351!2d-122.41941568468127!3d37.77492927975859!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80858064b3e6c9e1%3A0x9e6b90a59d372e4d!2sSan%20Francisco%2C%20CA%209418!5e0!3m2!1sen!2sus!4v1639780971954!5m2!1sen!2sus" width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>

            <!-- Subscription -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <a href="#" class="subscribe_bt">Subscribe</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js"></script>
    
    <!-- Owl Carousel JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    
    <!-- Custom JS -->
    <script src="script.js"></script>
</body>
</html>
