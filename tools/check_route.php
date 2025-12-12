<?php
$h = @get_headers('http://127.0.0.1:8000/posts');
if (!$h) {
    echo "NO_RESPONSE\n";
    exit(1);
}
echo $h[0] . "\n";
