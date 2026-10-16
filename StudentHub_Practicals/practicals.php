<?php require __DIR__ . '/includes/helpers.php'; page_start('Learning Lab'); ?>
<section class="page-banner learning-lab-intro">
    <p class="eyebrow">STUDENTHUB · PRACTICALS 7–15</p>
    <h2>Learn by exploring the portal</h2>
    <p>Each practical is part of this StudentHub project. Explore the working features below, from validated forms and database storage to administration and final integration.</p>
</section>

<section class="learning-path" aria-labelledby="learning-path-title">
    <h2 id="learning-path-title">One connected project</h2>
    <p>Follow the practicals in order to see how each feature builds on the last.</p>
    <div class="learning-path-steps">
        <span>Submit a form</span><span>Save data</span><span>Manage accounts</span>
        <span>Build portal features</span><span>Test and deploy</span>
    </div>
</section>

<section class="practical-grid" aria-label="Practical modules">
    <article class="practical-card" id="practical-7">
        <span class="practical-number">Practical 07</span>
        <h2>Forms and data storage</h2>
        <p>Submit a validated help request and see how the portal stores and displays contact records.</p>
        <div class="practical-card-links">
            <a class="btn btn-primary" href="contact.php">Open contact form</a>
            <a href="contact_records.php">View saved requests</a>
        </div>
    </article>

    <article class="practical-card" id="practical-8">
        <span class="practical-number">Practical 08</span>
        <h2>Database foundation</h2>
        <p>Connect to MySQL with PDO and explore the tables behind students, events, and registrations.</p>
        <div class="practical-card-links">
            <a class="btn btn-primary" href="db_connection.php">Check database</a>
            <a href="schema.sql">View database schema</a>
        </div>
    </article>

    <article class="practical-card" id="practical-9">
        <span class="practical-number">Practical 09</span>
        <h2>Student registration</h2>
        <p>Create an account with server-side validation, duplicate-email checks, and securely hashed passwords.</p>
        <div class="practical-card-links">
            <a class="btn btn-primary" href="register.php">Create an account</a>
        </div>
    </article>

    <article class="practical-card" id="practical-10">
        <span class="practical-number">Practical 10</span>
        <h2>Sign-in and sessions</h2>
        <p>Sign in securely and use a session-based account to access student and administrator features.</p>
        <div class="practical-card-links">
            <a class="btn btn-primary" href="login.php">Sign in</a>
        </div>
    </article>

    <article class="practical-card" id="practical-11">
        <span class="practical-number">Practical 11</span>
        <h2>Student records</h2>
        <p>Search and manage student records with administrator-only create, update, and delete actions.</p>
        <div class="practical-card-links">
            <a class="btn btn-primary" href="students.php">Manage students</a>
        </div>
    </article>

    <article class="practical-card" id="practical-12">
        <span class="practical-number">Practical 12</span>
        <h2>Events and poster uploads</h2>
        <p>Manage campus events, validate uploaded posters, and keep track of administrative changes.</p>
        <div class="practical-card-links">
            <a class="btn btn-primary" href="events_manager.php">Manage events</a>
        </div>
    </article>

    <article class="practical-card" id="practical-13">
        <span class="practical-number">Practical 13</span>
        <h2>Live event tools</h2>
        <p>Explore the JSON API and use Fetch-based search and event management without reloading the page.</p>
        <div class="practical-card-links">
            <a class="btn btn-primary" href="practical13.php">Open live event tools</a>
            <a href="api/events.php">View events API</a>
        </div>
    </article>

    <article class="practical-card" id="practical-14">
        <span class="practical-number">Practical 14</span>
        <h2>Administration and activity</h2>
        <p>Review portal statistics, recent registrations, and activity records from the protected admin area.</p>
        <div class="practical-card-links">
            <a class="btn btn-primary" href="admin_dashboard.php">Open admin dashboard</a>
        </div>
    </article>

    <article class="practical-card" id="practical-15">
        <span class="practical-number">Practical 15</span>
        <h2>Final integration and testing</h2>
        <p>Use the in-portal setup and testing guide to prepare the complete StudentHub project for a demo.</p>
        <div class="practical-card-links">
            <a class="btn btn-primary" href="practical15.php">Open project guide</a>
        </div>
    </article>
</section>
<?php page_end(); ?>
