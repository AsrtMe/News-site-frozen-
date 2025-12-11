<?php
$path = __DIR__ . '/../database/database.sqlite';
$pdo = new PDO('sqlite:' . $path);
$stmt = $pdo->prepare("SELECT id, post_id, parent_id, content, user_id, created_at FROM comments WHERE content LIKE :q ORDER BY id DESC LIMIT 10");
$stmt->execute([':q' => '%Автотестовый%']);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
if(!$rows) { echo "No matches\n"; exit; }
foreach($rows as $r){ echo "{$r['id']} post:{$r['post_id']} parent:{$r['parent_id']} user:{$r['user_id']} created:{$r['created_at']} content:{$r['content']}\n"; }
