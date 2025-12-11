<?php
$pdo=new PDO('sqlite:'.__DIR__.'/../database/database.sqlite');
$now=date('Y-m-d H:i:s');
$pdo->exec("INSERT INTO comments (user_id,parent_id,content,created_at,updated_at) VALUES (2,5,'Seeded reply', '$now', '$now')");
echo "Inserted reply\n";
$stmt=$pdo->query('SELECT id,parent_id,content FROM comments WHERE parent_id=5');
foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $r){ echo json_encode($r)."\n"; }
