<?php
require_once __DIR__ . '/config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$message = '';
$error = '';
$plan = null;
$planName = '';
$requiresPayment = false;

if (!isset($_GET['plan_id'])) {
    $error = 'No plan selected.';
} else {
    $planId = (int) $_GET['plan_id'];

    // Fetch plan details
    $stmt = $mysqli->prepare('SELECT plan_id, plan_name, price FROM plans WHERE plan_id = ?');
    $stmt->bind_param('i', $planId);
    $stmt->execute();
    $stmt->store_result();

    $plan = null;
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($pid, $pname, $price);
        $stmt->fetch();
        $plan = [
            'plan_id' => $pid,
            'plan_name' => $pname,
            'price' => $price,
        ];
    }

    if (!$plan) {
        $error = 'Invalid plan selected.';
    } else {
        $planName = $plan['plan_name'];
        $requiresPayment = strtolower($planName) !== 'free';

        // If Free plan or payment confirmed (POST), update subscription
        if ($_SERVER['REQUEST_METHOD'] === 'POST' || !$requiresPayment) {
            if (strtolower($planName) === 'free') {
                $expiryDate = null;
            } else {
                $expiryDate = date('Y-m-d', strtotime('+30 days'));
            }

            if ($expiryDate === null) {
                $update = $mysqli->prepare('UPDATE users SET plan = ?, expiry_date = NULL WHERE id = ?');
                $update->bind_param('si', $planName, $userId);
            } else {
                $update = $mysqli->prepare('UPDATE users SET plan = ?, expiry_date = ? WHERE id = ?');
                $update->bind_param('ssi', $planName, $expiryDate, $userId);
            }
            $update->execute();

            $message = 'You have successfully subscribed to the ' . htmlspecialchars($planName) . ' plan.';
            $requiresPayment = false;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Subscription Status - StudyHub</title>
    <link rel="stylesheet" href="css/style.css" />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
      integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
  </head>
  <body>
    <header class="nav">
      <div class="container nav-inner">
        <div class="logo">
          <span class="logo-icon"><i class="fa-solid fa-graduation-cap"></i></span>
          <span class="logo-text">StudyHub</span>
        </div>
        <nav>
          <ul class="nav-links">
            <li><a href="index.html">Home</a></li>
            <li><a href="plans.php">Plans</a></li>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="logout.php">Logout</a></li>
          </ul>
          <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation">
            <i class="fa-solid fa-bars"></i>
          </button>
        </nav>
      </div>
    </header>

    <main>
      <section class="status-section">
        <div class="container status-container">
          <div class="status-card">
            <?php if ($error): ?>
              <div class="alert alert-error">
                <?php echo htmlspecialchars($error); ?>
              </div>
              <a href="plans.php" class="btn btn-outline-full status-btn">
                Back to Plans
              </a>
            <?php elseif ($requiresPayment && $plan): ?>
              <div class="alert alert-info">
                You selected the
                <strong><?php echo htmlspecialchars($planName); ?></strong>
                plan. Complete the mock payment below to continue.
              </div>

              <div class="payment-card">
                <h2>Mock payment (for demo only)</h2>
                <p class="payment-subtitle">
                  This is not a real payment. It just simulates a PhonePe / UPI style screen
                  for your mini project presentation.
                </p>

                <div class="payment-options">
                  <div class="payment-option">
                    <span class="payment-icon payment-phonepe">₹</span>
                    <div>
                      <p class="payment-title">PhonePe / UPI</p>
                      <p class="payment-meta">Scan QR or pay via UPI ID</p>
                    </div>
                  </div>
                  <div class="payment-option">
                    <span class="payment-icon payment-card">
                      <i class="fa-solid fa-credit-card"></i>
                    </span>
                    <div>
                      <p class="payment-title">Debit / Credit Card</p>
                      <p class="payment-meta">Visa, RuPay, MasterCard</p>
                    </div>
                  </div>
                  <div class="payment-option">
                    <span class="payment-icon payment-netbanking">
                      <i class="fa-solid fa-building-columns"></i>
                    </span>
                    <div>
                      <p class="payment-title">Net Banking</p>
                      <p class="payment-meta">Popular Indian banks</p>
                    </div>
                  </div>
                </div>
                <a href="upi://pay?pa=9398590364@ibl&pn=StudyHub&am=<?php echo $plan['price']; ?>&cu=INR"
   style="text-decoration:none; color:inherit; display:block;">

  <div class="payment-option">
    <span class="payment-icon payment-phonepe">₹</span>
    <div>
      <p class="payment-title">PhonePe / UPI</p>
      <p class="payment-meta">Scan QR or pay via UPI ID</p>
    </div>
  </div>

</a>

                <form method="post" class="form payment-form">
                  <div class="form-group">
                    <label>Amount</label>
                    <input
                      type="text"
                      readonly
                      value="₹<?php echo htmlspecialchars(number_format((float) $plan['price'], 2)); ?>"
                    />
                  </div>
                  <button type="submit" class="btn btn-primary-full status-btn">
                    Complete Payment &amp; Subscribe
                  </button>
                </form>
              </div>
            <?php else: ?>
              <div class="alert alert-success">
                <?php echo $message; ?>
              </div>

              <a href="dashboard.php" class="btn btn-primary-full status-btn">
                Go to Dashboard
              </a>
            <?php endif; ?>
          </div>
        </div>
      </section>
    </main>

    <footer class="footer">
      <div class="container footer-inner">
        <p>&copy; <span id="year"></span> StudyHub Membership Engine.</p>
      </div>
    </footer>

    <script src="js/script.js"></script>
  </body>
  </html>

