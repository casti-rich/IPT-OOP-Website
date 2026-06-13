<?php
session_start();
require_once __DIR__ . '/database/db.php';
processExpiredRentals($conn);

$accountEmail = $_SESSION['email'] ?? '';
$accountUserId = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 0;
$profileMessage = '';

if ($accountUserId <= 0 && $accountEmail !== '' && $conn) {
	$stmt = mysqli_prepare($conn, 'SELECT User_ID FROM login_credentials WHERE email = ? LIMIT 1');
	if ($stmt) {
		mysqli_stmt_bind_param($stmt, 's', $accountEmail);
		mysqli_stmt_execute($stmt);
		$res = mysqli_stmt_get_result($stmt);
		$row = $res ? mysqli_fetch_assoc($res) : null;
		mysqli_stmt_close($stmt);
		if ($row && isset($row['User_ID'])) {
			$accountUserId = (int) $row['User_ID'];
			$_SESSION['user_id'] = $accountUserId;
		}
	}
}

$isLoggedIn = $accountEmail !== '' && $accountUserId > 0;
$displayName = $isLoggedIn ? strtok($accountEmail, '@') : 'Guest';
$avatarLetter = $displayName !== '' ? strtoupper($displayName[0]) : 'G';

if (isset($_SESSION['profile_message'])) {
	$profileMessage = (string) $_SESSION['profile_message'];
	unset($_SESSION['profile_message']);
}

$profileData = [
	'first_name' => '',
	'last_name' => '',
	'date_of_birth' => '',
];

if ($isLoggedIn && $conn) {
	$stmt = mysqli_prepare($conn, 'SELECT first_name, last_name, date_of_birth FROM user_info WHERE User_ID = ? LIMIT 1');
	if ($stmt) {
		mysqli_stmt_bind_param($stmt, 'i', $accountUserId);
		mysqli_stmt_execute($stmt);
		$res = mysqli_stmt_get_result($stmt);
		$row = $res ? mysqli_fetch_assoc($res) : null;
		mysqli_stmt_close($stmt);
		if ($row) {
			$profileData['first_name'] = (string) ($row['first_name'] ?? '');
			$profileData['last_name'] = (string) ($row['last_name'] ?? '');
			$profileData['date_of_birth'] = (string) ($row['date_of_birth'] ?? '');
		}
	}
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_profile'])) {
	if (! $isLoggedIn) {
		$profileMessage = 'Please log in to save your profile.';
	} elseif (! $conn) {
		$profileMessage = 'Database connection failed.';
	} else {
		$firstName = trim($_POST['first_name'] ?? '');
		$lastName = trim($_POST['last_name'] ?? '');
		$dateOfBirth = trim($_POST['date_of_birth'] ?? '');

		if ($dateOfBirth === '') {
			$dateOfBirth = null;
		}

		$stmt = mysqli_prepare(
			$conn,
			'INSERT INTO user_info (User_ID, first_name, last_name, date_of_birth)
			 VALUES (?, ?, ?, ?)
			 ON DUPLICATE KEY UPDATE
			 first_name = VALUES(first_name),
			 last_name = VALUES(last_name),
			 date_of_birth = VALUES(date_of_birth)'
		);
		if ($stmt) {
			mysqli_stmt_bind_param($stmt, 'isss', $accountUserId, $firstName, $lastName, $dateOfBirth);
			if (mysqli_stmt_execute($stmt)) {
				$_SESSION['profile_message'] = 'Profile saved successfully.';
			} else {
				$_SESSION['profile_message'] = 'Unable to save profile.';
			}
			mysqli_stmt_close($stmt);
		} else {
			$_SESSION['profile_message'] = 'Unable to prepare profile save.';
		}

		header('Location: profile.php');
		exit();
	}
}

$cartCount = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
	foreach ($_SESSION['cart'] as $item) {
		$cartCount += (int) ($item['quantity'] ?? 0);
	}
}

$boughtItems = [];
$rentedItems = [];

if ($isLoggedIn && $conn) {
	$stmt = mysqli_prepare(
		$conn,
		"SELECT t.Transaction_ID, t.Created_At, t.Amount, o.Order_ID, p.Product_Name, oi.Quantity, oi.Unit_Price, oi.Subtotal
		 FROM transactions t
		 INNER JOIN orders o ON o.Transaction_ID = t.Transaction_ID
		 INNER JOIN order_items oi ON oi.Order_ID = o.Order_ID
		 INNER JOIN products p ON p.Product_ID = oi.Product_ID
		 WHERE t.User_ID = ? AND t.Transaction_Type = 'Order'
		 ORDER BY t.Created_At DESC, o.Order_ID DESC, oi.Order_Items_ID DESC"
	);
	if ($stmt) {
		mysqli_stmt_bind_param($stmt, 'i', $accountUserId);
		mysqli_stmt_execute($stmt);
		$res = mysqli_stmt_get_result($stmt);
		if ($res) {
			while ($row = mysqli_fetch_assoc($res)) {
				$boughtItems[] = $row;
			}
		}
		mysqli_stmt_close($stmt);
	}

	$stmt = mysqli_prepare(
		$conn,
		"SELECT t.Transaction_ID, t.Created_At, t.Amount, r.Rental_ID, p.Product_Name, r.Rent_Start, r.Rent_End, r.Actual_Return, r.Status
		 FROM transactions t
		 INNER JOIN rentals r ON r.Transaction_ID = t.Transaction_ID
		 INNER JOIN products p ON p.Product_ID = r.Product_ID
		 WHERE t.User_ID = ? AND t.Transaction_Type = 'Rental'
		 ORDER BY t.Created_At DESC, r.Rental_ID DESC"
	);
	if ($stmt) {
		mysqli_stmt_bind_param($stmt, 'i', $accountUserId);
		mysqli_stmt_execute($stmt);
		$res = mysqli_stmt_get_result($stmt);
		if ($res) {
			while ($row = mysqli_fetch_assoc($res)) {
				$rentedItems[] = $row;
			}
		}
		mysqli_stmt_close($stmt);
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
								Edit your account details and review your order history.
							<?php else: ?>
								Log in to save your personal information and view your purchase history.
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

		<?php if ($profileMessage !== ''): ?>
			<div class="alert alert-info" role="status"><?php echo htmlspecialchars($profileMessage); ?></div>
		<?php endif; ?>

		<div class="row g-4">
			<section class="col-12 col-lg-8">
				<div class="profile-card card mb-4">
					<div class="card-body">
						<h2 class="card-title">Personal information</h2>
						<?php if ($isLoggedIn): ?>
							<form method="post" class="row g-3">
								<div class="col-md-6">
									<label for="first_name" class="form-label">First name</label>
									<input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($profileData['first_name']); ?>">
								</div>
								<div class="col-md-6">
									<label for="last_name" class="form-label">Last name</label>
									<input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($profileData['last_name']); ?>">
								</div>
								<div class="col-md-6">
									<label for="date_of_birth" class="form-label">Date of birth</label>
									<input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="<?php echo htmlspecialchars($profileData['date_of_birth']); ?>">
								</div>
								<div class="col-12 d-flex gap-2">
									<button type="submit" name="save_profile" value="1" class="btn action-btn">Save profile</button>
									<a href="profile.php" class="btn action-btn ghost">Reset</a>
								</div>
							</form>
						<?php else: ?>
							<p class="muted">Please log in to edit your profile.</p>
						<?php endif; ?>
					</div>
				</div>

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
							<span class="detail-label">Cart items</span>
							<span class="detail-value"><?php echo (int) $cartCount; ?></span>
						</div>
					</div>
				</div>

				<div class="profile-card card mb-4">
					<div class="card-body">
						<h2 class="card-title">Purchased items</h2>
						<?php if (! $isLoggedIn): ?>
							<p class="muted">Log in to see your purchase history.</p>
						<?php elseif (empty($boughtItems)): ?>
							<p class="muted">No purchases yet.</p>
						<?php else: ?>
							<div class="list-group">
								<?php foreach ($boughtItems as $item): ?>
									<div class="list-group-item">
										<div class="d-flex justify-content-between gap-3 flex-wrap">
											<div>
												<div class="fw-bold"><?php echo htmlspecialchars($item['Product_Name']); ?></div>
												<div class="small text-muted">Order #<?php echo htmlspecialchars((string) $item['Order_ID']); ?> · <?php echo htmlspecialchars((string) $item['Created_At']); ?></div>
												<div class="small">Qty: <?php echo htmlspecialchars((string) $item['Quantity']); ?> · Unit: $<?php echo htmlspecialchars(number_format((float) $item['Unit_Price'], 2)); ?></div>
											</div>
											<div class="text-end">
												<div class="fw-bold">$<?php echo htmlspecialchars(number_format((float) $item['Subtotal'], 2)); ?></div>
											</div>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>

				<div class="profile-card card mb-4">
				    <div class="card-body">
				        <h2 class="card-title">Active Rental Timers</h2>
						<div class="list-group">
				        	<?php
				        	$hasActiveRental = false;

				        	foreach ($rentedItems as $item):

    							$isActive = strtolower(trim($item['Status'])) === 'active';
    							$notExpired = strtotime($item['Rent_End']) > time();

    							if (!$isActive || !$notExpired) {
    							    continue;
    							}

    							$hasActiveRental = true;
				        	?>
				        	    <div class="list-group-item mb-3">
				        	        <div class="fw-bold">
				        	            <?= htmlspecialchars($item['Product_Name']) ?>
				        	        </div>
							
				        	        <div class="small">
				        	            Rental #<?= htmlspecialchars((string)$item['Rental_ID']) ?>
				        	        </div>
							
				        	        <div>
				        	            Ends:
				        	            <?= htmlspecialchars($item['Rent_End']) ?>
				        	        </div>
							
				        	        <div
				        	            class="fw-bold text-danger rental-timer"
				        	            data-end="<?= htmlspecialchars($item['Rent_End']) ?>">
				        	            Loading...
				        	        </div>
				        	    </div>
				        	<?php endforeach; ?>
						</div>
				        <?php if (!$hasActiveRental): ?>
				            <p class="muted">No active rentals.</p>
				        <?php endif; ?>
				    </div>
				</div>

				<div class="profile-card card">
					<div class="card-body">
						<h2 class="card-title">Rented items</h2>
						<?php if (! $isLoggedIn): ?>
							<p class="muted">Log in to see your rental history.</p>
						<?php elseif (empty($rentedItems)): ?>
							<p class="muted">No rentals yet.</p>
						<?php else: ?>
							<div class="list-group">
								<?php foreach ($rentedItems as $item): ?>
									<div class="list-group-item">
										<div class="d-flex justify-content-between gap-3 flex-wrap">
											<div>
												<div class="fw-bold"><?php echo htmlspecialchars($item['Product_Name']); ?></div>
												<div class="small text-muted">Rental #<?php echo htmlspecialchars((string) $item['Rental_ID']); ?> · <?php echo htmlspecialchars((string) $item['Created_At']); ?></div>
												<div class="small">Status: <?php echo htmlspecialchars((string) $item['Status']); ?></div>
												<div class="small">From <?php echo htmlspecialchars((string) $item['Rent_Start']); ?> to <?php echo htmlspecialchars((string) $item['Rent_End']); ?></div>
											</div>
											<div class="text-end">
												<div class="fw-bold">$<?php echo htmlspecialchars(number_format((float) $item['Amount'], 2)); ?></div>
											</div>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</section>

			<aside class="col-12 col-lg-4">
				<div class="profile-card card mb-4">
					<div class="card-body">
						<h2 class="card-title">Summary</h2>
						<p class="muted">Use this area for support links, order help, or saved preferences later.</p>
						<a class="btn action-btn small" href="mailto:support@soleana.com">Coming Soon</a>
					</div>
				</div>
			</aside>
		</div>
	</main>

	<script src="Scripts/active_rentals.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"></script>
</body>
</html>