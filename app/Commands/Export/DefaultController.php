<?php

namespace App\Commands\Export;

use Minicli\Command\CommandController;

class DefaultController extends CommandController
{
    public function handle(): void
    {
        $this->display("Hello export World!");
    }
}