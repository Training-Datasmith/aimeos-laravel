<?php

declare (strict_types=1);
/**
 * @license MIT, http://opensource.org/licenses/MIT
 * @copyright Aimeos (aimeos.org), 2015-2023
 */
namespace Aimeos\Shop\Command;

/**
 * Command for clearing the content cache
 */
class Clear_Command extends Abstract_Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aimeos:clear';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears the content cache';
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Clearing Aimeos cache', 'v');
        $context = $this->get_laravel()->make('aimeos.context')->get(false, 'command');
        $context->set_editor('aimeos:clear');
        \Aimeos\M_Admin::create($context, 'cache')->get_cache()->clear();
    }
}