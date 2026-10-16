<?php
declare(strict_types=1);

function e(?string $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function start_secure_session(): void {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        ini_set('session.use_strict_mode', '1');
        session_set_cookie_params([
            'httponly' => true,
            'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
            'samesite' => 'Lax',
        ]);
        session_start();
    }
}

function csrf_token(): string {
    start_secure_session();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return (string)$_SESSION['csrf_token'];
}

function csrf_valid(): bool {
    start_secure_session();
    return isset($_POST['csrf_token'], $_SESSION['csrf_token'])
        && is_string($_POST['csrf_token'])
        && hash_equals((string)$_SESSION['csrf_token'], $_POST['csrf_token']);
}

function page_start(string $title): void {
    start_secure_session();
    $loggedIn = !empty($_SESSION['student_id']);
    $isAdmin = $loggedIn && (($_SESSION['role'] ?? 'student') === 'admin');
    $name = $loggedIn ? (string)($_SESSION['full_name'] ?? 'Student') : '';

    echo '<!doctype html><html lang="en"><head><meta charset="utf-8">'
        . '<meta name="viewport" content="width=device-width,initial-scale=1">'
        . '<title>' . e($title) . ' | StudentHub</title>'
        . '<link rel="stylesheet" href="css/style.css">'
        . '<style>
        .practical-wrap{max-width:1200px;margin:0 auto;padding:26px 20px 40px;flex:1;width:100%}
        .practical-box{background:var(--card-bg,#fff);padding:24px;border:1px solid #dbe3ee;border-radius:14px;margin:18px 0;box-shadow:0 8px 24px rgba(15,23,42,.035)}
        .practical-box input,.practical-box select,.practical-box textarea{width:100%;padding:10px;margin:5px 0 14px;border:1px solid #bbc7d6;border-radius:6px;font:inherit}
        .practical-box input[type=checkbox],.practical-box input[type=radio]{width:auto}
        .practical-box table{width:100%;border-collapse:collapse;min-width:650px}
        .practical-box th,.practical-box td{text-align:left;padding:10px;border-bottom:1px solid #ddd;vertical-align:top}
        .notice{padding:12px 15px;background:#e9f8ee;border:1px solid #b6e2c2;border-radius:8px;margin:12px 0;color:#14532d}
        .notice.error{background:#fff0f0;border-color:#efb8b8;color:#991b1b}
        .practical-nav{display:flex;gap:8px;flex-wrap:wrap;margin:15px 0 22px}
        .practical-nav a{padding:8px 11px;background:#e9efff;border-radius:7px;text-decoration:none;color:#1d3b75;font-weight:600}
        .practical-nav a:hover,.practical-nav a:focus{background:#d7e4ff}
        .form-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:12px}
        .integration-bar{background:#eef4ff;border-bottom:1px solid #d9e4f5;padding:8px 20px;color:#334155;font-size:.9rem}
        .integration-bar .container{max-width:1200px;margin:auto;display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap}
        body.dark-theme .practical-box{background:#1f2937;color:#e5e7eb;border-color:#374151}
        body.dark-theme .practical-box input,body.dark-theme .practical-box select,body.dark-theme .practical-box textarea{background:#111827;color:#f9fafb;border-color:#4b5563}
        body.dark-theme .practical-nav a{background:#263955;color:#e5e7eb}
        body.dark-theme .integration-bar{background:#172033;color:#dbeafe;border-color:#374151}
        @media(max-width:650px){.practical-wrap{padding:18px 12px}.practical-box{padding:16px}.practical-box table{min-width:600px}}
        </style></head><body>';

    echo '<a href="#main-content" class="skip-link">Skip to main content</a>';
    echo '<header class="site-header"><div class="container header-inner">'
        . '<a href="index.php" class="brand-title"><span style="font-size:1.6rem">🎓</span><div><h1>StudentHub</h1><span class="tagline">University Student &amp; Academic Portal</span></div></a>'
        . '<nav class="main-nav" aria-label="Main navigation"><ul>'
        . '<li><a href="index.php">Home</a></li>'
        . '<li><a href="about.html">About</a></li>'
        . '<li><a href="events.php">Events</a></li>'
        . '<li><a href="practicals.php">Learning Lab</a></li>'
        . '<li><a href="contact.php">Contact</a></li>';
    if ($loggedIn) {
        echo '<li><a href="events.php">My events</a></li>';
        if ($isAdmin) {
            echo '<li><a href="admin_dashboard.php">Admin Dashboard</a></li><li><a href="events_manager.php">Manage Events</a></li><li><a href="students.php">Manage Students</a></li>';
        }
        echo '<li><a href="logout.php">Logout (' . e($name) . ')</a></li>';
    } else {
        echo '<li><a href="login.php">Sign in</a></li><li><a href="register.php">Create account</a></li>';
    }
    echo '</ul></nav></div></header>';
    echo '<div class="integration-bar"><div class="container"><span>Integrated PHP + MySQL StudentHub Portal</span><span>'
        . ($loggedIn ? 'Signed in as ' . e($name) . ' · ' . e((string)($_SESSION['role'] ?? 'student')) : 'Browse events, register, and explore practical modules')
        . '</span></div></div>';
    echo '<main id="main-content" class="practical-wrap"><p class="eyebrow">STUDENTHUB · FULL-STACK PORTAL</p><h1>' . e($title) . '</h1>';
    if ($title === 'Learning Lab') {
        echo '<nav class="practical-nav" aria-label="Practical modules">'
            . '<a href="#practical-7">Forms and storage</a><a href="#practical-8">Database</a>'
            . '<a href="#practical-9">Registration</a><a href="#practical-10">Authentication</a>'
            . '<a href="#practical-11">Student records</a><a href="#practical-12">Event management</a>'
            . '<a href="#practical-13">JSON API</a><a href="#practical-14">Administration</a>'
            . '<a href="#practical-15">Final integration</a></nav>';
    }
}

function page_end(): void {
    echo '</main><footer class="site-footer"><div class="container"><div class="footer-grid">'
        . '<div class="footer-column"><h4>StudentHub Portal</h4><p>Student services and campus event management in one integrated PHP/MySQL application.</p></div>'
        . '<div class="footer-column"><h4>Learning Lab</h4><p>Practicals 7–15 · PHP · MySQL · Fetch API · Sessions · CRUD</p><p><a href="practicals.php">Explore practicals</a></p></div>'
        . '</div><div class="footer-bottom"><p>&copy; ' . date('Y') . ' StudentHub Portal.</p></div></div></footer><script src="js/theme.js"></script></body></html>';
}
