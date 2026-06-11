<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STORE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500&family=Space+Grotesk:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="CSS/products-navbar.css">
    <link rel="stylesheet" href="CSS/index.css">
</head>

<body class="store-theme">
    <?php include 'products-navbar.php'; ?>
    <main>
        <section class="py-5">
            <div class="container">
                <div class="store-hero rounded-4 overflow-hidden">
                    <div id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="Assets/Carousel/banner1.jpg" class="d-block w-100" alt="Stage lighting behind the instruments">
                            </div>
                            <div class="carousel-item">
                                <img src="Assets/Carousel/banner4.jpg" class="d-block w-100" alt="Wide shot of a keyboard rig">
                            </div>
                            <div class="carousel-item">
                                <img src="Assets/Carousel/banner5.jpg" class="d-block w-100" alt="Guitar body closeup with glowing light">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                    <div class="store-hero-overlay" aria-hidden="true"></div>
                    <div class="store-hero-content p-4 p-lg-5">
                        <p class="text-uppercase small mb-2" style="color: var(--accent-2); letter-spacing: 0.3em;">Limited drops. Studio grade gear.</p>
                        <h1 class="display-5 fw-semibold">Find your next tune.</h1>
                        <p class="lead text-secondary">Handpicked instruments, curated for players who want a bold, modern tone.</p>
                        <div class="d-flex gap-2 flex-wrap">
                            <a class="btn btn-primary btn-lg" href="product_list.php">Shop the collection</a>
                            <a class="btn btn-outline-dark btn-lg" href="product_list.php">Browse categories</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5 text-center">
            <div class="container">
                <blockquote class="blockquote">
                    <p class="display-6">
                        "Music isn’t something you just play—it’s something you feel!"
                    </p>

                    <footer class="blockquote-footer">
                        Mio Akiyama
                    </footer>
                </blockquote>
            </div>
        </section>

        <section class="py-5">
            <div class="container">
                <h2 class="text-center mb-5">Why Shop With Us?</h2>

                <div class="row text-center">
                    <div class="col-md-4">
                        <h3>Curated Gear</h3>
                        <p>Only instruments we'd actually recommend to musicians and beginners.</p>
                    </div>

                    <div class="col-md-4">
                        <h3>Secure Transaction</h3>
                        <p>Our store is strictly a pick up orders in our physical store model to ensure you get your instrument safely. </p>
                    </div>

                    <div class="col-md-4">
                        <h3>Great Gift Ideas</h3>
                        <p>Looking for a gift for a musician? Find something special for every skill level and style.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5 bg-dark text-white">
            <div class="container">
                <h2>Not sure where to start?</h2>

                <p>
                    Explore beginner-friendly instruments and find the one
                    that matches your style.
                </p>

                <a href="product_list.php"
                   class="btn btn-outline-light">
                   Start Exploring
                </a>
            </div>
        </section>

        <section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <p class="text-uppercase small mb-1" style="color: var(--accent); letter-spacing: 0.3em;">
                Questions?
            </p>
            <h2>Frequently Asked Questions</h2>
        </div>

        <div class="accordion" id="faqAccordion">

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#faqOne">
                        What payment methods do you accept?
                    </button>
                </h2>

                <div id="faqOne"
                     class="accordion-collapse collapse show"
                     data-bs-parent="#faqAccordion">

                    <div class="accordion-body">
                        For now, we only accept cash and gcash payments.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#faqTwo">
                        Can beginners shop here?
                    </button>
                </h2>

                <div id="faqTwo"
                     class="accordion-collapse collapse"
                     data-bs-parent="#faqAccordion">

                    <div class="accordion-body">
                        Absolutely. We offer instruments suitable for musicians of all skill levels, including those just starting out.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#faqThree">
                        What if an item is out of stock?
                    </button>
                </h2>

                <div id="faqThree"
                     class="accordion-collapse collapse"
                     data-bs-parent="#faqAccordion">

                    <div class="accordion-body">
                        Availability is updated regularly. If an item is unavailable, check back later for restocks.
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>