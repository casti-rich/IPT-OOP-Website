<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$cartCount = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cartCount += $item['quantity'] ?? 0;
    }
}

$accountEmail = $_SESSION['email'] ?? '';
$isLoggedIn = $accountEmail !== '';
$accountName = $isLoggedIn ? strtok($accountEmail, '@') : 'Guest';

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

$cookieTimerLabel = $cookieRemaining > 0
  ? gmdate($cookieRemaining >= 3600 ? 'H:i:s' : 'i:s', $cookieRemaining)
  : 'Not set';
?>
<header class="products-nav" aria-label="Products navigation bar">
  <div class="products-nav__inner">
    <div class="brand-group">
      <a class="brand" href="index.php" aria-label="Products home">
        <img class="brand__logo" src="Assets/Icons/logo.svg" alt="Logo" />
      </a>
      <a class="brand" href="product_list.php">
        <span class="brand__text">PRODUCTS</span>
      </a>
    </div>

    <nav class="actions" aria-label="User actions">
      <a class="icon-link cart-icon-wrapper" href="check-out-page.php" aria-label="Shopping cart">
        <img src="Assets/Icons/Shopping cart.svg" alt="" aria-hidden="true" />
        <?php if ($cartCount > 0): ?>
          <span class="cart-badge"><?php echo $cartCount; ?></span>
        <?php endif; ?>
      </a>
      <div class="account-menu">
        <a
          class="icon-link account-trigger"
          href="<?php echo $isLoggedIn ? 'profle.php' : 'login.php'; ?>"
          aria-label="User account"
        >
          <img src="Assets/Icons/User.svg" alt="" aria-hidden="true" />
        </a>
        <div class="account-panel" role="dialog" aria-label="Account details">
          <div class="account-panel__name">
            <?php echo htmlspecialchars($accountName); ?>
          </div>
          <div class="account-panel__timer" data-remaining="<?php echo (int) $cookieRemaining; ?>">
            Cookie timer: <?php echo $cookieTimerLabel; ?>
          </div>
          <a class="account-panel__action" href="<?php echo $isLoggedIn ? 'logout.php' : 'login.php'; ?>">
            <?php echo $isLoggedIn ? 'Log out' : 'Log in'; ?>
          </a>
        </div>
      </div>
    </nav>
  </div>
</header>
<script src="Scripts/account-timer.js" defer></script>
