<?php
$path = __DIR__ . '/../database/database.sqlite';
if (!file_exists($path)) {
    echo "Database file not found: $path\n";
    exit(1);
}
try {
    $pdo = new PDO('sqlite:' . $path);
} catch (Exception $e) {
    echo "Failed to open sqlite: " . $e->getMessage() . "\n";
    exit(1);
}
foreach (['posts', 'comments'] as $t) {
    echo "Table: $t\n";
    $stmt = $pdo->query("PRAGMA table_info($t)");
    $cols = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    if (!$cols) {
        echo "  (no such table)\n";
        continue;
    }
    foreach ($cols as $r) {
        $d = $r['dflt_value'] === null ? 'NULL' : $r['dflt_value'];
        echo "  {$r['cid']} {$r['name']} {$r['type']} notnull:{$r['notnull']} dflt:$d pk:{$r['pk']}\n";
    }
}
