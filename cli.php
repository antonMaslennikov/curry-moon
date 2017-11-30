<?php
// application.php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;

$application = new Application();

$application->add(new \application\commands\DumpImport());
$application->add(new \application\commands\DumpExport());

$application->run();