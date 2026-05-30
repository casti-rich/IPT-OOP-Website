<?php
session_start();

$accountEmail = $_SESSION['email'] ?? '';
$isLoggedIn = $accountEmail !== '';
$displayName = $isLoggedIn ? strtok($accountEmail, '@') : 'Guest';
$avatarLetter = $displayName !== '' ? strtoupper($displayName[0]) : 'G';

$cartCount = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
	foreach ($_SESSION['cart'] as $item) {
		$cartCount += $item['quantity'] ?? 0;
	}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Profile</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;600&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="CSS/style.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel="stylesheet" href="CSS/products-navbar.css">
	<link rel="stylesheet" href="CSS/profile.css">
</head>
<body class="profile-page">
	<?php include 'products-navbar.php'; ?>

	<main class="container py-4" aria-label="Profile overview">
		<section class="profile-hero card mb-4">
			<div class="card-body d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-4">
				<div class="profile-id">
					<div class="avatar" aria-hidden="true"><?php echo htmlspecialchars($avatarLetter); ?></div>
					<div>
						<p class="eyebrow">Profile</p>
						<h1 class="profile-title">Welcome, <?php echo htmlspecialchars($displayName); ?></h1>
						<p class="profile-subtitle">
							<?php if ($isLoggedIn): ?>
								Your account hub for orders, saved gear, and the latest drops.
							<?php else: ?>
								Log in to unlock order tracking, saved items, and member exclusives.
							<?php endif; ?>
						</p>
					</div>
				</div>

				<div class="profile-actions">
					<a class="btn action-btn" href="product_list.php">Browse products</a>
					<?php if ($isLoggedIn): ?>
						<a class="btn action-btn ghost" href="logout.php">Log out</a>
					<?php else: ?>
						<a class="btn action-btn ghost" href="login.php">Log in</a>
					<?php endif; ?>
				</div>
			</div>
		</section>

		<div class="row g-4">
			<section class="col-12 col-lg-8">
				<div class="profile-card card mb-4">
					<div class="card-body">
						<h2 class="card-title">Account details</h2>
						<div class="detail-row">
							<span class="detail-label">Email</span>
							<span class="detail-value"><?php echo $isLoggedIn ? htmlspecialchars($accountEmail) : 'Not logged in'; ?></span>
						</div>
						<div class="detail-row">
							<span class="detail-label">Member status</span>
							<span class="detail-value"><?php echo $isLoggedIn ? 'Verified' : 'Guest'; ?></span>
						</div>
						<div class="detail-row">
							<span class="detail-label">Shipping address</span>
							<span class="detail-value">No address on file</span>
						</div>
					</div>
				</div>

				<div class="profile-card card">
					<div class="card-body">
						<h2 class="card-title">Features</h2>
						<p class="muted">Features coming soon.</p>
					</div>
				</div>
			</section>

			<aside class="col-12 col-lg-4">
				<div class="profile-card card">
					<div class="card-body">
						<h2 class="card-title">Support</h2>
						<p class="muted">Need help with an order or an item? Reach us any time.</p>
						<a class="btn action-btn small" href="mailto:support@soleana.com">Contact support</a>
					</div>
				</div>
			</aside>
		</div>
	</main>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
