<?php
$arr = [
    'a', '1', '%'
];

foreach ($arr as $key => $value) {
    echo !is_string($key);
}
// echo is_string(1);
