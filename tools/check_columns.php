<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;

echo 'posts.deleted_at: ' . (Schema::hasColumn('posts','deleted_at') ? 'yes' : 'no') . "\n";
echo 'comments.post_id: ' . (Schema::hasColumn('comments','post_id') ? 'yes' : 'no') . "\n";
