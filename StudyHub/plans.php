<?php
require_once __DIR__ . '/config/db.php';
session_start();

// Fetch plans from database for demonstration
// mysqli doesn't throw exceptions by default, so we check for success.
$plans = [];
$res = $mysqli->query('SELECT plan_id, plan_name, price, features FROM plans ORDER BY plan_id ASC');
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $plans[] = $row;
    }
}

if (empty($plans)) {
    // Fallback if table is not yet created
    $plans = [
        [
            'plan_id' => 1,
            'plan_name' => 'Free',
            'price' => 0,
            'features' => 'Limited access to sample videos,Basic practice material'
        ],
        [
            'plan_id' => 2,
            'plan_name' => 'Basic',
            'price' => 199,
            'features' => 'All Free features,More course videos,Topic-wise tests'
        ],
        [
            'plan_id' => 3,
            'plan_name' => 'Premium',
            'price' => 399,
            'features' => 'All Basic features,Premium-only courses,Full mock tests'
        ],
    ];
}

$loggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Plans - StudyHub</title>
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
            <li><a href="plans.php" class="active">Plans</a></li>
            <?php if ($loggedIn): ?>
              <li><a href="dashboard.php">Dashboard</a></li>
              <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
              <li><a href="login.php">Login</a></li>
              <li><a href="register.php" class="btn-nav">Register</a></li>
            <?php endif; ?>
          </ul>
          <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation">
            <i class="fa-solid fa-bars"></i>
          </button>
        </nav>
      </div>
    </header>

    <main>
      <section class="plans-section plans-section-page">
        <div class="container">
          <h1 class="section-title">Choose your plan</h1>
          <p class="section-subtitle">
            Upgrade from Free to unlock more courses, video lessons, and practice tests.
          </p>

          <div class="plan-grid">
            <?php foreach ($plans as $index => $plan): ?>
              <?php
              $featureList = array_map('trim', explode(',', $plan['features']));
              $isFeatured = strtolower($plan['plan_name']) === 'basic';
              ?>
              <article class="plan-card <?php echo $isFeatured ? 'plan-card-featured' : ''; ?>">
                <?php if ($isFeatured): ?>
                  <div class="badge">Best Value</div>
                <?php endif; ?>
                <h3><?php echo htmlspecialchars($plan['plan_name']); ?></h3>
                <p class="price">
                  <?php echo $plan['price'] == 0 ? '₹0' : '₹' . htmlspecialchars($plan['price']); ?>
                  <span>/ month</span>
                </p>
                <p class="plan-tagline">
                  <?php
                  if (strtolower($plan['plan_name']) === 'free') {
                      echo 'Try StudyHub with selected content.';
                  } elseif (strtolower($plan['plan_name']) === 'basic') {
                      echo 'Great for regular learners.';
                  } else {
                      echo 'Ideal for serious exam preparation.';
                  }
                  ?>
                </p>
                <ul class="plan-features">
                  <?php foreach ($featureList as $f): ?>
                    <li><?php echo htmlspecialchars($f); ?></li>
                  <?php endforeach; ?>
                </ul>

                <?php if ($loggedIn): ?>
                  <a
                    href="subscribe.php?plan_id=<?php echo urlencode($plan['plan_id']); ?>"
                    class="btn <?php echo $isFeatured ? 'btn-primary-full' : 'btn-outline-full'; ?>"
                  >
                    Subscribe
                  </a>
                <?php else: ?>
                  <a
                    href="login.php"
                    class="btn <?php echo $isFeatured ? 'btn-primary-full' : 'btn-outline-full'; ?>"
                  >
                    Login to Subscribe
                  </a>
                <?php endif; ?>
              </article>
            <?php endforeach; ?>
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

