<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;

$has = Schema::hasColumn('posts', 'deleted_at') ? 'yes' : 'no';
echo "posts.deleted_at exists? $has\n";
