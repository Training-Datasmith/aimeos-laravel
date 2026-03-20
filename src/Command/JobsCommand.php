<?php

declare (strict_types=1);
/**
 * @license MIT, http://opensource.org/licenses/MIT
 * @copyright Aimeos (aimeos.org), 2015-2023
 */
namespace Aimeos\Shop\Command;

use Illuminate\Console\Command;
/**
 * Command for executing the Aimeos job controllers
 */
class Jobs_Command extends Abstract_Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aimeos:jobs
		{jobs : One or more job controller names like "admin/job customer/email/watch"}
		{site? : Site codes to execute the jobs for like "default unittest" (none for all)}
		{--option= : Setup configuration, name and value are separated by colon like "setup/default/demo:1"}
	';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Executes the job controllers';
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $jobs = $this->argument('jobs');
        $jobs = !is_array($jobs) ? explode(' ', (string) $jobs) : $jobs;
        $fcn = function (\Aimeos\M_Shop\Context_Iface $lcontext, \Aimeos\Bootstrap $aimeos) use ($jobs): void {
            $jobfcn = function ($context, $aimeos, $jobname): void {
                \Aimeos\Controller\Jobs::create($context, $aimeos, $jobname)->run();
            };
            $process = $lcontext->process();
            $site = $lcontext->locale()->get_site_item()->get_code();
            foreach ($jobs as $jobname) {
                $this->info(sprintf('Executing Aimeos jobs "%s" for "%s"', $jobname, $site), 'v');
                $process->start($jobfcn, [$lcontext, $aimeos, $jobname], false);
            }
            $process->wait();
        };
        $this->exec($this->context(), $fcn, $this->argument('site'));
    }
    /**
     * Returns a context object
     *
     * @return \Aimeos\MShop\ContextIface Context object
     */
    protected function context(): \Aimeos\M_Shop\Context_Iface
    {
        $lv = $this->get_laravel();
        $context = $lv->make('aimeos.context')->get(false, 'command');
        $lang_manager = \Aimeos\M_Shop::create($context, 'locale/language');
        $langids = $lang_manager->search($lang_manager->filter(true))->keys()->to_array();
        $i18n = $lv->make('aimeos.i18n')->get($langids);
        $context->set_session(new \Aimeos\Base\Session\None());
        $context->set_cache(new \Aimeos\Base\Cache\None());
        $context->set_editor('aimeos:jobs');
        $context->set_i18n($i18n);
        return $this->add_config($context);
    }
}