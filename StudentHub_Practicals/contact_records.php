<?php
require __DIR__ . '/includes/helpers.php';

page_start('Saved Contact Records — Practical 7');

$file = __DIR__ . '/data/contact_submissions.csv';

echo '<div class="practical-box">';
echo '<p>Records stored in CSV file storage.</p>';
echo '<div style="overflow-x:auto">';
echo '<table>';
echo '<tr><th>Submitted</th><th>Name</th><th>Email</th><th>Category</th><th>Message</th></tr>';

if (is_readable($file) && ($fileHandle = fopen($file, 'rb'))) {
    $header = fgetcsv($fileHandle);

    while (($row = fgetcsv($fileHandle)) !== false) {
        if (count($row) >= 5) {
            echo '<tr>'
                . implode(
                    '',
                    array_map(
                        fn ($value) => '<td>' . e($value) . '</td>',
                        array_slice($row, 0, 5)
                    )
                )
                . '</tr>';
        }
    }

    fclose($fileHandle);
}

echo '</table>';
echo '</div>';
echo '</div>';

page_end();