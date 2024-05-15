<?php

namespace App\Commands\Help;

use Minicli\App as MiniCli;
use Minicli\Command\CommandCall;
use Minicli\Command\CommandController;

class DefaultController extends CommandController
{
    /** @var  array */
    protected array $command_map = [];

    public function boot(MiniCli $app, CommandCall $input): void
    {
        parent::boot($app, $input);
        $this->command_map = $app->commandRegistry->getCommandMap();
    }

    public function handle(): void
    {
        $this->info('Available Commands');

        foreach ($this->command_map as $command => $sub) {

            $this->newline();
            $this->out($command, 'info_alt');

            if (is_array($sub)) {
                foreach ($sub as $subcommand) {
                    if ($subcommand !== 'default') {
                        $this->newline();
                        $this->out(sprintf('%s%s','└──', $subcommand));
                    }
                }
            }
            $this->newline();
        }

        $this->newline();
        $this->newline();
    }
}