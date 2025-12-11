<?php
$db = new PDO('sqlite:'.__DIR__.'/../database/database.sqlite');
$tables = ['posts','comments'];
foreach ($tables as $t) {
    echo "Table: $t\n";
    $stmt = $db->query("PRAGMA table_info($t);");
    $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$cols) { echo "  (no such table or empty)\n\n"; continue; }
    foreach ($cols as $c) {
        echo "  {$c['name']}\t{$c['type']}\n";
    }
    echo "\n";
}
