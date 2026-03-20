<?php

declare (strict_types=1);
/**
 * @license MIT, http://opensource.org/licenses/MIT
 * @copyright Aimeos (aimeos.org), 2015-2023
 */
namespace Aimeos\Shop\Command;

/**
 * Command for initializing or updating the Aimeos database tables
 */
class Setup_Command extends Abstract_Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aimeos:setup
		{site? : Site for updating database entries}
		{tplsite=default : Site used as template for creating the new one}
		{--q : Quiet}
		{--v=v : Verbosity level}
		{--option=* : Setup configuration, name and value are separated by colon like "setup/default/demo:1"}
	';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize or update the Aimeos database tables';
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        \Aimeos\M_Shop::cache(false);
        \Aimeos\M_Admin::cache(false);
        $template = $this->argument('tplsite');
        if (($site = $this->argument('site')) === null) {
            $site = config('shop.mshop.locale.site', 'default');
        }
        $boostrap = $this->get_laravel()->make('aimeos')->get();
        $ctx = $this->get_laravel()->make('aimeos.context')->get(false, 'command');
        $this->info(sprintf('Initializing or updating the Aimeos database tables for site "%1$s"', $site));
        \Aimeos\Setup::use($boostrap)->verbose($this->option('q') ? '' : $this->option('v'))->context($this->add_config($ctx->set_editor('aimeos:setup')))->up($site, $template);
    }
}