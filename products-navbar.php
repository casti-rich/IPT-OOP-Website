<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Count items in the cart to render the badge.
$cartCount = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cartCount += $item['quantity'] ?? 0;
    }
}

// Derive account display name from the session email.
$accountEmail = $_SESSION['email'] ?? '';
$isLoggedIn = $accountEmail !== '';
$accountName = $isLoggedIn ? strtok($accountEmail, '@') : 'Guest';

// Look up the remaining remember-token time from the stored token list.
$cookieRemaining = 0;
if (!empty($_COOKIE['remember_token'])) {
  $tokensFile = __DIR__ . '/Scripts/user-token.json';
  if (is_file($tokensFile)) {
    $tokens = json_decode(file_get_contents($tokensFile), true) ?? [];
    foreach ($tokens as $email => $data) {
      if (
        isset($data['token'], $data['expires']) &&
        hash_equals($data['token'], $_COOKIE['remember_token'])
      ) {
        $cookieRemaining = max(0, (int) $data['expires'] - time());
        break;
      }
    }
  }
}

// Format the remaining seconds for the UI label.
$cookieTimerLabel = $cookieRemaining > 0
  ? gmdate($cookieRemaining >= 3600 ? 'H:i:s' : 'i:s', $cookieRemaining)
  : 'Not set';
?>
<header class="store-navbar" aria-label="Products navigation bar">
  <nav class="navbar navbar-expand-lg store-navbar__shell">
    <div class="container">
      <div class="d-flex align-items-center gap-2">
        <a class="navbar-brand d-flex align-items-center" href="index.php" aria-label="Products home">
          <img class="store-navbar__logo" src="Assets/Icons/logo.svg" alt="Logo" />
        </a>
        <a class="store-navbar__brand-link" href="product_list.php">PRODUCTS</a>
        <a class="store-navbar__brand-link" href="Rent.php">RENT</a>
      </div>

      <div class="d-flex align-items-center gap-3 ms-auto">
        <a class="store-navbar__icon position-relative" href="check-out-page.php" aria-label="Shopping cart">
          <img src="Assets/Icons/Shopping cart.svg" alt="" aria-hidden="true" />
          <?php if ($cartCount > 0): ?>
            <span class="store-navbar__badge"><?php echo $cartCount; ?></span>
          <?php endif; ?>
        </a>

        <div class="dropdown">
          <button
            class="btn store-navbar__icon store-navbar__trigger dropdown-toggle"
            type="button"
            data-bs-toggle="dropdown"
            aria-expanded="false"
            aria-label="User account"
          >
            <img src="Assets/Icons/User.svg" alt="" aria-hidden="true" />
          </button>
          <div class="dropdown-menu dropdown-menu-end store-navbar__menu">
            <div class="store-navbar__name">
              <?php echo htmlspecialchars($accountName); ?>
            </div>
            <div class="store-navbar__timer" data-remaining="<?php echo (int) $cookieRemaining; ?>">
              Cookie timer: <?php echo $cookieTimerLabel; ?>
            </div>
            <?php if ($isLoggedIn): ?>
              <a class="store-navbar__action" href="profile.php">Profile</a>
            <?php endif; ?>
            <a class="store-navbar__action" href="<?php echo $isLoggedIn ? 'logout.php' : 'login.php'; ?>">
              <?php echo $isLoggedIn ? 'Log out' : 'Log in'; ?>
            </a>
          </div>
        </div>
      </div>
    </div>
  </nav>
</header>
<div class="session-expired-modal" data-login-url="login.php" aria-hidden="true">
  <div class="session-expired-modal__backdrop" aria-hidden="true"></div>
  <div
    class="session-expired-modal__content"
    role="dialog"
    aria-modal="true"
    aria-labelledby="session-expired-title"
  >
    <h2 class="session-expired-modal__title" id="session-expired-title">Session expired</h2>
    <p class="session-expired-modal__message">Your session has expired. Please log in again.</p>
    <button class="session-expired-modal__button" type="button">I understand</button>
  </div>
</div>
<script src="Scripts/account-timer.js" defer></script>
