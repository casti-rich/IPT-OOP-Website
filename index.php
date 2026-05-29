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

        <section class="py-5">
            <div class="container">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
                    <div>
                        <p class="text-uppercase small mb-1" style="color: var(--accent); letter-spacing: 0.3em;">Featured picks</p>
                        <h2 class="h1 mb-0">Spotlight instruments</h2>
                    </div>
                    <a class="btn btn-link text-decoration-none" href="product_list.php">See all products</a>
                </div>
                <div class="row g-4">
                    <div class="col-md-6">
                        <a class="card h-100 text-decoration-none store-card" href="product.php?key=gibson-les-paul-jr-ikuyo-kita-model">
                            <span class="badge position-absolute m-3 store-badge">New arrival</span>
                            <img src="Assets/Products/ikuyo_kita_model.jpg" class="card-img-top" alt="Gibson Les Paul Jr. Kita model">
                            <div class="card-body">
                                <h3 class="h5 card-title">Les Paul Jr. Kita Model</h3>
                                <p class="card-text text-secondary">New official collaboration with “Bocchi the Rock!”.</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a class="card h-100 text-decoration-none store-card" href="product.php?key=fender-rhodes-suitcase-73-key">
                            <span class="badge position-absolute m-3 store-badge">Studio favorite</span>
                            <img src="Assets/Products/Fender_Rhodes/01.jpg" class="card-img-top" alt="Fender Rhodes keyboard">
                            <div class="card-body">
                                <h3 class="h5 card-title">Fender Rhodes</h3>
                                <p class="card-text text-secondary">Warm, bell-like keys built for soulful sessions.</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>