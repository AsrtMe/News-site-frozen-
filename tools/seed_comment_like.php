<?php
require __DIR__ . '/../vendor/autoload.php';
$path = __DIR__ . '/../database/database.sqlite';
$pdo = new PDO('sqlite:' . $path);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// create a comment for post_id 1 by user_id 1
$stmt = $pdo->prepare('INSERT INTO comments (user_id,parent_id,content,gif_url,created_at,updated_at) VALUES (:uid,:pid,:content,:gif,:now,:now)');
$now = date('Y-m-d H:i:s');
$stmt->execute([':uid'=>1, ':pid'=>null, ':content'=>'Seeded comment for testing', ':gif'=>null, ':now'=>$now]);
$commentId = $pdo->lastInsertId();
echo "Created comment id: $commentId\n";
// create a like by user 1 for that comment
$stmt = $pdo->prepare('INSERT INTO likes (user_id,comment_id,is_like,created_at,updated_at) VALUES (:uid,:cid,:is_like,:now,:now)');
$stmt->execute([':uid'=>1, ':cid'=>$commentId, ':is_like'=>1, ':now'=>$now]);
echo "Created like for comment $commentId by user 1\n";
// show counts
$stmt = $pdo->prepare('SELECT SUM(CASE WHEN is_like=1 THEN 1 ELSE 0 END) as likes, SUM(CASE WHEN is_like=0 THEN 1 ELSE 0 END) as dislikes FROM likes WHERE comment_id=:cid');
$stmt->execute([':cid'=>$commentId]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
print_r($row);
