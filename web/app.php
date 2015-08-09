<?php
error_reporting(E_ALL);

require '../vendor/autoload.php';

use Cerad\Component\Arbiter\App\AppKernel;

$app = new AppKernel();
$app->run();



