<?php

use Framework\Http\Request;

chdir(dirname(__DIR__));
// require 'src/Framework/Http/Request.php';
require 'vendor/autoload.php';

### Initialization

$request = new Request();

### Action

$name = $request->getQueryParam()['name'] ?? 'Guest';
header('X-Developer: Dev800');
echo "Hello, " . $name;