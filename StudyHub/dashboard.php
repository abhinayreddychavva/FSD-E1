<?php
require_once __DIR__ . '/config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];

// Fetch user details
$stmt = $mysqli->prepare('SELECT id, name, email, plan, expiry_date FROM users WHERE id = ?');
$stmt->bind_param('i', $userId);
$stmt->execute();
$stmt->store_result();

$user = null;
if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $name, $email, $planDb, $expiryDb);
    $stmt->fetch();
    $user = [
        'id' => $id,
        'name' => $name,
        'email' => $email,
        'plan' => $planDb,
        'expiry_date' => $expiryDb,
    ];
}

if (!$user) {
    // Invalid session
    session_destroy();
    header('Location: login.php');
    exit;
}

// Handle plan expiry: if expired, downgrade to Free
$today = date('Y-m-d');
$plan = $user['plan'] ?: 'Free';
$expiry = $user['expiry_date'];
$planWasDowngraded = false;

if ($expiry && $expiry < $today) {
    $plan = 'Free';
    $expiry = null;
    $update = $mysqli->prepare('UPDATE users SET plan = ?, expiry_date = NULL WHERE id = ?');
    $update->bind_param('si', $plan, $userId);
    $update->execute();
    $planWasDowngraded = true;
}

$user['plan'] = $plan;
$user['expiry_date'] = $expiry;

$planOrder = [
    'Free' => 1,
    'Basic' => 2,
    'Premium' => 3,
];

$currentLevel = $planOrder[$plan] ?? 1;

// B.Tech course catalog with tier logic
$courses = [
    [
        'title' => 'Programming in C',
        'description' => 'Basics of C programming, loops, arrays and functions.',
        'minPlan' => 'Free',
        'videoUrl' => 'https://www.youtube.com/embed/KJgsSFOSQv0',
    ],
    [
        'title' => 'Engineering Mathematics I',
        'description' => 'Calculus and linear algebra concepts used in engineering.',
        'minPlan' => 'Free',
        'videoUrl' => 'https://www.youtube.com/embed/1xJpaj0IGG8',
    ],
    [
        'title' => 'Digital Logic Design',
        'description' => 'Number systems, logic gates and combinational circuits.',
        'minPlan' => 'Free',
        'videoUrl' => 'https://www.youtube.com/embed/IcrBqCFLHIY',
    ],
    [
        'title' => 'Data Structures in C',
        'description' => 'Stacks, queues, linked lists and trees for coding interviews.',
        'minPlan' => 'Basic',
        'videoUrl' => 'https://www.youtube.com/embed/BBpAmxU_NQo',
    ],
    [
        'title' => 'Object Oriented Programming with C++',
        'description' => 'Classes, objects, inheritance and polymorphism.',
        'minPlan' => 'Basic',
        'videoUrl' => 'https://www.youtube.com/embed/vLnPwxZdW4Y',
    ],
    [
        'title' => 'Database Management Systems (DBMS)',
        'description' => 'Relational model, SQL queries and normalization.',
        'minPlan' => 'Basic',
        'videoUrl' => 'https://www.youtube.com/embed/T5G7vHKGF_Q',
    ],
    [
        'title' => 'Operating Systems',
        'description' => 'Processes, threads, scheduling and memory management.',
        'minPlan' => 'Basic',
        'videoUrl' => 'https://www.youtube.com/embed/nbv8B6W6HCI',
    ],
    [
        'title' => 'Computer Networks',
        'description' => 'OSI model, TCP/IP and basic networking protocols.',
        'minPlan' => 'Basic',
        'videoUrl' => 'https://www.youtube.com/embed/qiQR5rTSshw',
    ],
    [
        'title' => 'Software Engineering',
        'description' => 'SDLC models, requirements, design and testing concepts.',
        'minPlan' => 'Basic',
        'videoUrl' => 'https://www.youtube.com/embed/1i8KQSkc4t4',
    ],
    [
        'title' => 'Web Technologies (HTML, CSS, JS)',
        'description' => 'Front-end basics for building responsive web pages.',
        'minPlan' => 'Basic',
        'videoUrl' => 'https://www.youtube.com/embed/pQN-pnXPaVg',
    ],
    [
        'title' => 'Java Programming',
        'description' => 'Core Java syntax, OOP concepts and collections.',
        'minPlan' => 'Premium',
        'videoUrl' => 'https://www.youtube.com/embed/eIrMbAQSU34',
    ],
    [
        'title' => 'Design and Analysis of Algorithms',
        'description' => 'Time complexity, sorting, searching and graph algorithms.',
        'minPlan' => 'Premium',
        'videoUrl' => 'https://www.youtube.com/embed/ZaHTmcgafqE',
    ],
    [
        'title' => 'Machine Learning Basics',
        'description' => 'Supervised vs unsupervised learning and simple models.',
        'minPlan' => 'Premium',
        'videoUrl' => 'https://www.youtube.com/embed/GwIo3gDZCVQ',
    ],
    [
        'title' => 'Cloud Computing Fundamentals',
        'description' => 'IaaS, PaaS, SaaS and basic cloud architecture.',
        'minPlan' => 'Premium',
        'videoUrl' => 'https://www.youtube.com/embed/M988_fsOSWo',
    ],
    [
        'title' => 'Cyber Security Essentials',
        'description' => 'Threats, vulnerabilities and basic protection techniques.',
        'minPlan' => 'Premium',
        'videoUrl' => 'https://www.youtube.com/embed/inWWhr5tnEA',
    ],
    [
        'title' => 'Internet of Things (IoT) Basics',
        'description' => 'Sensors, connectivity and simple IoT applications.',
        'minPlan' => 'Premium',
        'videoUrl' => 'https://www.youtube.com/embed/HBqCcXYgR0I',
    ],
    [
        'title' => 'Microprocessors and Microcontrollers',
        'description' => 'Introduction to 8086/8051 architecture and programming.',
        'minPlan' => 'Basic',
        'videoUrl' => 'https://www.youtube.com/embed/fGxF9QHR9pU',
    ],
    [
        'title' => 'Compiler Design',
        'description' => 'Phases of compiler and lexical, syntax analysis basics.',
        'minPlan' => 'Premium',
        'videoUrl' => 'https://www.youtube.com/embed/87Gx3U0BDlo',
    ],
    [
        'title' => 'Artificial Intelligence Overview',
        'description' => 'Search techniques, knowledge representation and agents.',
        'minPlan' => 'Premium',
        'videoUrl' => 'https://www.youtube.com/embed/JMUxmLyrhSk',
    ],
    [
        'title' => 'Final Year Project Lab',
        'description' => 'Planning, documentation and presentation tips for projects.',
        'minPlan' => 'Premium',
        'videoUrl' => 'https://www.youtube.com/embed/OK_JCtrrv-c',
    ],
];

function canAccessCourse(string $userPlan, int $userLevel, array $course, array $planOrder): bool
{
    $courseMinPlan = $course['minPlan'];
    $requiredLevel = $planOrder[$courseMinPlan] ?? 1;
    return $userLevel >= $requiredLevel;
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard - StudyHub</title>
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
            <li><a href="dashboard.php" class="active">Dashboard</a></li>
            <li><a href="logout.php">Logout</a></li>
          </ul>
          <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation">
            <i class="fa-solid fa-bars"></i>
          </button>
        </nav>
      </div>
    </header>

    <main>
      <section class="dashboard-header">
        <div class="container">
          <h1>Welcome, <?php echo htmlspecialchars($user['name']); ?> 👋</h1>
          <p class="dashboard-subtitle">
            This is your learning dashboard. Your subscription tier controls which courses,
            videos and test questions are available.
          </p>

          <div class="dashboard-grid">
            <div class="dashboard-card">
              <h2>Your Plan</h2>
              <p class="plan-name-badge plan-<?php echo strtolower($plan); ?>">
                <?php echo htmlspecialchars($plan); ?> Plan
              </p>
              <?php if ($expiry): ?>
                <p class="plan-meta">
                  <strong>Expiry date:</strong>
                  <?php echo htmlspecialchars(date('d M Y', strtotime($expiry))); ?>
                </p>
              <?php else: ?>
                <p class="plan-meta">
                  <strong>Expiry date:</strong> Not applicable (Free plan)
                </p>
              <?php endif; ?>

              <ul class="list-check">
                <?php if ($plan === 'Free'): ?>
                  <li>Access to selected free videos</li>
                  <li>Preview of Basic and Premium content</li>
                  <li>Upgrade anytime from the Plans page</li>
                <?php elseif ($plan === 'Basic'): ?>
                  <li>Access to most course videos</li>
                  <li>Topic-wise tests unlocked</li>
                  <li>Great for consistent practice</li>
                <?php else: ?>
                  <li>Everything in Basic, plus premium content</li>
                  <li>Mock tests similar to real exams</li>
                  <li>Ideal for final exam preparation</li>
                <?php endif; ?>
              </ul>

              <a href="plans.php" class="btn btn-outline-full small-btn">
                View / Change Plan
              </a>
            </div>

            <div class="dashboard-card dashboard-info-card">
              <h2>How access works</h2>
              <ul class="list-legend">
                <li>
                  <span class="legend-icon legend-free"></span>
                  Free course – visible to all users
                </li>
                <li>
                  <span class="legend-icon legend-basic"></span>
                  Basic course – visible for Basic & Premium
                </li>
                <li>
                  <span class="legend-icon legend-premium"></span>
                  Premium course – visible only for Premium
                </li>
              </ul>
              <p class="dashboard-note">
                Free users see only free courses and cannot open test questions.
                Subscribed users (Basic and Premium) can open course tests and practice
                questions.
              </p>

              <?php if ($planWasDowngraded): ?>
                <div class="alert alert-info">
                  Your previous plan expired, so your account has been moved back to the Free
                  plan.
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </section>

      <section class="courses-section">
        <div class="container">
          <h2 class="section-title">Your Courses</h2>
          <p class="section-subtitle">
            Watch course videos directly on this page. If you have a subscription (Basic or
            Premium), you can also open practice test questions.
          </p>

          <div class="course-filters">
            <button class="btn btn-outline-full course-filter-btn active" data-filter="all">
              All levels
            </button>
            <button class="btn btn-outline-full course-filter-btn" data-filter="Free">
              Free
            </button>
            <button class="btn btn-outline-full course-filter-btn" data-filter="Basic">
              Basic
            </button>
            <button class="btn btn-outline-full course-filter-btn" data-filter="Premium">
              Premium
            </button>
          </div>

          <div class="course-grid">
            <?php foreach ($courses as $index => $course): ?>
              <?php
              $accessible = canAccessCourse($plan, $currentLevel, $course, $planOrder);
              $courseLevel = $planOrder[$course['minPlan']] ?? 1;
              $levelClass = strtolower($course['minPlan']);
              $locked = !$accessible;
              ?>
              <article
                class="course-card <?php echo $locked ? 'course-card-locked' : ''; ?>"
                data-min-plan="<?php echo htmlspecialchars($course['minPlan']); ?>"
              >
                <div class="course-header">
                  <span class="course-icon">
                    <i class="fa-solid fa-play-circle"></i>
                  </span>
                  <div>
                    <h3><?php echo htmlspecialchars($course['title']); ?></h3>
                    <span class="course-badge course-badge-<?php echo $levelClass; ?>">
                      <?php echo htmlspecialchars($course['minPlan']); ?> content
                    </span>
                  </div>
                </div>

                <p class="course-description">
                  <?php echo htmlspecialchars($course['description']); ?>
                </p>

                <div class="course-video-wrapper">
                  <?php if ($accessible): ?>
                    <iframe
                      src="<?php echo htmlspecialchars($course['videoUrl']); ?>"
                      title="Course video"
                      frameborder="0"
                      allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                      allowfullscreen
                    ></iframe>
                  <?php else: ?>
                    <div class="course-locked-overlay">
                      <?php
                      $requiredPlanId = 1;
                      if ($course['minPlan'] === 'Basic') {
                          $requiredPlanId = 2;
                      } elseif ($course['minPlan'] === 'Premium') {
                          $requiredPlanId = 3;
                      }
                      ?>
                      <p>
                        This video is available from
                        <strong><?php echo htmlspecialchars($course['minPlan']); ?></strong>
                        plan.
                      </p>
                      <a href="subscribe.php?plan_id=<?php echo $requiredPlanId; ?>" class="btn btn-outline-full small-btn">
                        Upgrade &amp; Pay to unlock
                      </a>
                    </div>
                  <?php endif; ?>
                </div>

                <div class="course-actions">
                  <?php if ($accessible): ?>
                    <?php if ($plan === 'Free'): ?>
                      <button class="btn btn-disabled" disabled>
                        Tests available for subscribers
                      </button>
                    <?php else: ?>
                      <button
                        class="btn btn-primary-outline"
                        type="button"
                        data-course-index="<?php echo $index; ?>"
                        onclick="toggleTest(this)"
                      >
                        View Test Questions
                      </button>
                    <?php endif; ?>
                  <?php else: ?>
                    <?php
                    $requiredPlanId = 1;
                    if ($course['minPlan'] === 'Basic') {
                        $requiredPlanId = 2;
                    } elseif ($course['minPlan'] === 'Premium') {
                        $requiredPlanId = 3;
                    }
                    ?>
                    <a
                      href="subscribe.php?plan_id=<?php echo $requiredPlanId; ?>"
                      class="btn btn-outline-full small-btn"
                    >
                      Unlock with payment
                    </a>
                  <?php endif; ?>
                </div>

                <?php if ($plan !== 'Free' && $accessible): ?>
                  <div class="course-test" data-course-test="<?php echo $index; ?>">
                    <h4>Sample Test Questions</h4>
                    <ol>
                      <li>
                        Explain one key concept from this video in your own words.
                      </li>
                      <li>
                        Write one small example or code snippet that uses that concept.
                      </li>
                      <li>
                        Identify one common mistake beginners make with this topic.
                      </li>
                    </ol>
                    <p class="course-test-note">
                      These questions are for practice only. In a real system, answers would
                      be stored and evaluated.
                    </p>
                  </div>
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

