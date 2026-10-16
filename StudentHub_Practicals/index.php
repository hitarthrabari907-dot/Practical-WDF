<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>StudentHub Portal - Home</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <!-- 1. Accessibility Skip Link -->
  <a href="#main-content" class="skip-link">Skip to main content</a>

  <!-- 2. Semantic Header & Navigation -->
  <header class="site-header">
    <div class="container header-inner">
      <a href="index.php" class="brand-title">
        <span style="font-size: 1.6rem;">🎓</span>
        <div>
          <h1>StudentHub</h1>
          <span class="tagline">University Student & Academic Portal</span>
        </div>
      </a>

      <!-- Main Navigation Menu -->
      <nav class="main-nav" aria-label="Main Navigation">
        <ul>
          <li><a href="index.php" class="active">Home</a></li>
          <li><a href="about.html">About</a></li>
          <li><a href="events.php">Events</a></li>
          <li><a href="practicals.php">Learning Lab</a></li>
          <li><a href="contact.php">Contact</a></li>
          <li><a href="login.php">Sign in</a></li>
          <li><a href="register.php" class="btn btn-secondary btn-sm">Create account</a></li>
        </ul>
      </nav>
    </div>
  </header>
  <!-- 3. Semantic Main Content -->
  <main id="main-content" class="container">

    <!-- Hero Section -->
    <section class="hero">
      <h2>Everything for student life, in one place</h2>
      <p>
        Discover campus events, manage your student account, and explore practical web development modules in one connected portal.
      </p>
      <div class="hero-actions">
        <a href="register.php" class="btn btn-secondary">Create your account</a>
        <a href="events.php" class="btn btn-outline" style="background-color: #ffffff; color: #003366;">Explore events</a>
      </div>
    </section>

    <!-- Layout: Modules Grid & Sidebar Notices -->
    <div class="layout-with-sidebar">
      
      <!-- Key Modules Section -->
      <section aria-labelledby="modules-title">
        <h3 id="modules-title" style="color: #003366; margin-bottom: 15px;">Key Portal Modules</h3>
        
        <div class="grid-2">
          
          <!-- Card 1: Dashboard -->
          <article class="card">
            <header class="card-header">
              <h4>📅 Events &amp; Registrations</h4>
            </header>
            <div class="card-body">
              <p>Browse campus events and register for activities using your StudentHub account.</p>
            </div>
            <footer class="card-footer">
              <span class="badge badge-primary">Campus</span>
              <a href="events.php" class="btn btn-sm btn-outline">Browse events</a>
            </footer>
          </article>

          <!-- Card 2: Events -->
          <article class="card">
            <header class="card-header">
              <h4>🧪 Learning Lab</h4>
            </header>
            <div class="card-body">
              <p>Explore practicals 7–15, built into the portal with forms, database features, and administration tools.</p>
            </div>
            <footer class="card-footer">
              <span class="badge badge-success">Practicals 7–15</span>
              <a href="practicals.php" class="btn btn-sm btn-outline">Explore modules</a>
            </footer>
          </article>

          <!-- Card 3: Profile -->
          <article class="card">
            <header class="card-header">
              <h4>👤 Student Account</h4>
            </header>
            <div class="card-body">
              <p>Create an account and sign in to register for events and use student services.</p>
            </div>
            <footer class="card-footer">
              <span class="badge badge-warning">Account</span>
              <a href="register.php" class="btn btn-sm btn-outline">Get started</a>
            </footer>
          </article>

          <!-- Card 4: Admin Panel -->
          <article class="card">
            <header class="card-header">
              <h4>🛡️ Administration</h4>
            </header>
            <div class="card-body">
              <p>Role-restricted tools for managing student and event records and viewing portal activity.</p>
            </div>
            <footer class="card-footer">
              <span class="badge badge-danger">Admin access</span>
              <a href="admin_dashboard.php" class="btn btn-sm btn-outline">Admin dashboard</a>
            </footer>
          </article>

        </div>
      </section>

      <!-- Sidebar: Notices & Quick Links -->
      <aside aria-label="Campus Notices">
        
        <div class="sidebar-widget">
          <h3>📢 Latest Notices</h3>
          <ul class="widget-list">
            <li>
              <strong>Mid-Semester Exams:</strong> Exam timetables will be published next week.
            </li>
            <li>
              <strong>National Hackathon:</strong> Registrations are now open. Visit the events section to register.
            </li>
            <li>
              <strong>Library Notice:</strong> Central Library reading room open extended hours.
            </li>
          </ul>
        </div>

        <div class="sidebar-widget">
          <h3>⚡ Quick Links</h3>
          <ul class="widget-list">
            <li><a href="register.php">Student Registration Form</a></li>
            <li><a href="login.php">Sign In to Portal</a></li>
            <li><a href="contact.php">Campus Helpdesk Directory</a></li>
            <li><a href="admin_dashboard.php">Administrator Control Center</a></li>
          </ul>
        </div>

      </aside>

    </div>

  </main>

  <!-- 4. Semantic Footer -->
  <footer class="site-footer">
    <div class="container">
      <div class="footer-grid">
        <div class="footer-column">
          <h4>StudentHub Portal</h4>
          <p>A centralized academic portal designed to facilitate student learning and campus administration.</p>
        </div>
        <div class="footer-column">
          <h4>University Helpdesk</h4>
          <p>University Main Campus</p>
          <p>Email: support@studenthub.edu</p>
          <p>Phone: +91 9876543210</p>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; 2026 StudentHub Portal.</p>
      </div>
    </div>
  </footer>

  <script src="js/theme.js"></script>
</body>
</html>
