#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;
use App\Command\CalculateTotalsCommand;

$application = new Application();

$application->add(new CalculateTotalsCommand());
$application->run();
