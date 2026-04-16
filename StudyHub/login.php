<?php
require_once __DIR__ . '/config/db.php';
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$errors = [];
$planChoice = $_POST['plan_choice'] ?? 'current';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $errors[] = 'Email and password are required.';
    } else {
        $stmt = $mysqli->prepare('SELECT id, name, email, password, plan, expiry_date FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        $user = null;
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $name, $emailDb, $hash, $plan, $expiry);
            $stmt->fetch();

            $user = [
                'id' => $id,
                'name' => $name,
                'email' => $emailDb,
                'password' => $hash,
                'plan' => $plan,
                'expiry_date' => $expiry,
            ];
        }

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            // Optional quick plan selection after login
            if ($planChoice === 'Free') {
                header('Location: subscribe.php?plan_id=1');
                exit;
            }
            if ($planChoice === 'Basic') {
                header('Location: subscribe.php?plan_id=2');
                exit;
            }
            if ($planChoice === 'Premium') {
                header('Location: subscribe.php?plan_id=3');
                exit;
            }

            header('Location: dashboard.php');
            exit;
        } else {
            $errors[] = 'Invalid email or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - StudyHub</title>
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
            <li><a href="login.php" class="btn-nav active">Login</a></li>
            <li><a href="register.php">Register</a></li>
            <li><a href="logout.php">Logout</a></li>
          </ul>
          <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation">
            <i class="fa-solid fa-bars"></i>
          </button>
        </nav>
      </div>
    </header>

    <main>
      <section class="auth-section">
        <div class="container auth-container">
          <div class="auth-card">
            <h1>Welcome back</h1>
            <p class="auth-subtitle">
              Login to access your dashboard, courses, and subscription details.
            </p>

            <?php if (!empty($errors)): ?>
              <div class="alert alert-error">
                <ul>
                  <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            <?php endif; ?>

            <form method="post" class="form">
              <div class="form-group">
                <label for="email">Email</label>
                <input
                  type="email"
                  id="email"
                  name="email"
                  placeholder="you@example.com"
                  value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                  required
                />
              </div>

              <div class="form-group">
                <label for="password">Password</label>
                <input
                  type="password"
                  id="password"
                  name="password"
                  placeholder="Your password"
                  required
                />
              </div>

              <div class="form-group">
                <label for="plan_choice">Choose plan after login (optional)</label>
                <select id="plan_choice" name="plan_choice">
                  <option value="current" <?php echo $planChoice === 'current' ? 'selected' : ''; ?>>
                    Keep my current plan
                  </option>
                  <option value="Free" <?php echo $planChoice === 'Free' ? 'selected' : ''; ?>>
                    Switch to Free
                  </option>
                  <option value="Basic" <?php echo $planChoice === 'Basic' ? 'selected' : ''; ?>>
                    Subscribe to Basic (with payment step)
                  </option>
                  <option value="Premium" <?php echo $planChoice === 'Premium' ? 'selected' : ''; ?>>
                    Subscribe to Premium (with payment step)
                  </option>
                </select>
              </div>

              <button type="submit" class="btn btn-primary-full auth-btn">
                Login
              </button>

              <p class="auth-meta">
                New to StudyHub?
                <a href="register.php">Create an account</a>
              </p>
            </form>
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

