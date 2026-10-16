<?php
require __DIR__ . '/includes/helpers.php';
require __DIR__ . '/config/database.php';

start_secure_session();

$pdo = getDatabaseConnection();
$token = csrf_token();

page_start('Practical 13 — JSON API and Fetch CRUD');

echo '<p>Use the Fetch API to list and manage events asynchronously. Add/update/delete requests require an administrator login.</p>';

echo '<div class="practical-box">';
echo '<label>Search events<input id="api-search" placeholder="Search title or category"></label>';
echo '<p id="api-message" role="status" aria-live="polite"></p>';
echo '<form id="api-event-form">';
echo '<input type="hidden" id="event-id">';
echo '<div class="form-grid">';
echo '<label>Title<input id="event-title" maxlength="150" required></label>';
echo '<label>Date/time<input id="event-date" type="datetime-local" required></label>';
echo '<label>Location<input id="event-location" maxlength="150" required></label>';
echo '<label>Capacity<input id="event-capacity" type="number" min="1" max="50000" value="100" required></label>';
echo '<label>Category<select id="event-category">';
echo '<option>Technical</option>';
echo '<option>Workshop</option>';
echo '<option>Seminar</option>';
echo '<option>Cultural</option>';
echo '<option>Sports</option>';
echo '<option>Other</option>';
echo '</select></label>';
echo '<label>Status<select id="event-status">';
echo '<option value="open">Open</option>';
echo '<option value="closed">Closed</option>';
echo '<option value="coming_soon">Coming soon</option>';
echo '</select></label>';
echo '</div>';
echo '<label>Description<textarea id="event-description" maxlength="500" required></textarea></label>';
echo '<button class="btn btn-primary" id="api-submit">Add event</button> ';
echo '<button type="button" id="api-cancel">Clear</button>';
echo '</form>';
echo '</div>';

echo '<div class="practical-box">';
echo '<h2>Events from JSON API</h2>';
echo '<div style="overflow-x:auto">';
echo '<table>';
echo '<thead><tr><th>ID</th><th>Event</th><th>Date</th><th>Category</th><th>Actions</th></tr></thead>';
echo '<tbody id="api-events"><tr><td colspan="5">Loading…</td></tr></tbody>';
echo '</table>';
echo '</div>';
echo '</div>';

echo '<script>window.STUDENTHUB_CSRF=' . json_encode($token) . ';</script>';
echo '<script src="js/practical13.js" defer></script>';

page_end();
