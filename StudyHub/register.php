<?php
require_once __DIR__ . '/config/db.php';
session_start();

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($name === '' || $email === '' || $password === '' || $confirmPassword === '') {
        $errors[] = 'All fields are required.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if ($password !== $confirmPassword) {
        $errors[] = 'Passwords do not match.';
    }

    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }

    if (empty($errors)) {
        // Check if email already exists
        $stmt = $mysqli->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = 'An account with this email already exists.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $defaultPlan = 'Free';
            $expiryDate = null;

            $stmt = $mysqli->prepare('INSERT INTO users (name, email, password, plan, expiry_date) VALUES (?, ?, ?, ?, ?)');
            $stmt->bind_param('sssss', $name, $email, $hash, $defaultPlan, $expiryDate);
            $stmt->execute();

            $success = 'Registration successful! You can now log in.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register - StudyHub</title>
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
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php" class="btn-nav active">Register</a></li>
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
            <h1>Create your StudyHub account</h1>
            <p class="auth-subtitle">
              Start with the Free plan and upgrade later to access more courses and tests.
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

            <?php if ($success): ?>
              <div class="alert alert-success">
                <?php echo htmlspecialchars($success); ?>
              </div>
            <?php endif; ?>

            <form method="post" class="form">
              <div class="form-group">
                <label for="name">Name</label>
                <input
                  type="text"
                  id="name"
                  name="name"
                  placeholder="Enter your full name"
                  value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
                  required
                />
              </div>

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
                  placeholder="Minimum 6 characters"
                  required
                />
              </div>

              <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input
                  type="password"
                  id="confirm_password"
                  name="confirm_password"
                  placeholder="Re-enter your password"
                  required
                />
              </div>

              <button type="submit" class="btn btn-primary-full auth-btn">
                Register
              </button>

              <p class="auth-meta">
                Already have an account?
                <a href="login.php">Login here</a>
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

