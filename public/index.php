<?php
// это проверка isnull, но не isempty
$name = $_GET['name'] ?? 'Guest';

header('X-Developer: Dev800');
echo "Hello, " . $name;