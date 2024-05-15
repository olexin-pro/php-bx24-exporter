<?php

namespace App\Commands;

use DivineOmega\CliProgressBar\ProgressBar;
use Minicli\Command\CommandController;

abstract class BaseCommand extends CommandController
{


    public function getProgressBar(string $message = 'В проецессе...'): ProgressBar
    {
        return (new ProgressBar())->setMessage($message);
    }
}