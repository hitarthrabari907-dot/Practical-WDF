<?php require __DIR__ . '/includes/helpers.php'; page_start('Final Integration Guide'); ?>
<section class="page-banner">
    <p class="eyebrow">LEARNING LAB · PRACTICAL 15</p>
    <h2>Set up, test, and present StudentHub</h2>
    <p>Use this guide to run the integrated project locally and verify the main student and administrator workflows.</p>
</section>

<section class="practical-box">
    <h2>Run the project with XAMPP</h2>
    <ol>
        <li>Place the project folder in <code>C:\xampp\htdocs\StudentHub_Practicals</code>.</li>
        <li>Start Apache and MySQL from the XAMPP Control Panel.</li>
        <li>Open phpMyAdmin and import <code>schema.sql</code> into a fresh local database.</li>
        <li>If using an existing database, back it up and apply the appropriate upgrade SQL instead of re-importing the fresh schema.</li>
        <li>If your MySQL credentials differ from the XAMPP defaults, copy <code>config/database.local.php.example</code> to <code>config/database.local.php</code> and update the local values.</li>
        <li>Open <a href="db_connection.php">the database check</a> and confirm the connection before using database-backed features.</li>
        <li>Open <a href="index.php">StudentHub</a> and use the navigation to access the integrated portal.</li>
    </ol>
</section>

<section class="practical-box">
    <h2>Verify the student experience</h2>
    <ol>
        <li>Create an account through <a href="register.php">student registration</a>, then sign in.</li>
        <li>Browse <a href="events.php">events</a> and register for an event while signed in.</li>
        <li>Submit the <a href="contact.php">contact form</a> and confirm that a clear success or error message is shown.</li>
        <li>Sign out, then sign back in to confirm the session flow works as expected.</li>
    </ol>
</section>

<section class="practical-box">
    <h2>Verify administrator features</h2>
    <p>For a local classroom demonstration, use a test account and assign its role to <code>admin</code> in the local database. Do not expose role editing to public users.</p>
    <ol>
        <li>Sign in again and open the <a href="admin_dashboard.php">administrator dashboard</a>.</li>
        <li>Test student record management in <a href="students.php">Student Records</a>.</li>
        <li>Create, update, and remove an event in <a href="events_manager.php">Event Management</a>.</li>
        <li>Test accepted and rejected poster uploads, then review <a href="practical13.php">live event tools</a> and the activity summary.</li>
    </ol>
</section>

<section class="practical-box">
    <h2>Final quality checks</h2>
    <ul>
        <li>Try invalid form values, a duplicate email, and an unsupported or oversized poster.</li>
        <li>Confirm student accounts cannot access administrator-only pages or make unauthorized changes.</li>
        <li>Check that tables work on narrow screens and forms can be used with a keyboard.</li>
        <li>Review browser errors and network responses while testing the Fetch-based event tools.</li>
        <li>Keep local database credentials and private student data out of public source control.</li>
    </ul>
</section>

<p><a class="btn btn-primary" href="practicals.php">Back to the Learning Lab</a></p>
<?php page_end(); ?>
