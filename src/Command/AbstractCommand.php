<?php

declare (strict_types=1);
/**
 * @license MIT, http://opensource.org/licenses/MIT
 * @copyright Aimeos (aimeos.org), 2015-2023
 */
namespace Aimeos\Shop\Command;

use Illuminate\Console\Command;
/**
 * Common base class for all commands
 */
abstract class Abstract_Command extends Command
{
    /**
     * Adds the configuration options from the input object to the given context
     *
     * @param \Aimeos\MShop\ContextIface $ctx Context object
     */
    protected function add_config(\Aimeos\M_Shop\Context_Iface $ctx): \Aimeos\M_Shop\Context_Iface
    {
        $config = $ctx->config();
        foreach ((array) $this->option('option') as $option) {
            [$name, $value] = explode(':', (string) $option, 2);
            $config->set($name, $value);
        }
        return $ctx;
    }
    /**
     * Executes the function for all given sites
     *
     * @param \Aimeos\MShop\ContextIface $context Context object
     * @param \Closure $fcn Function to execute
     * @param array|string|null $sites Site codes
     */
    protected function exec(\Aimeos\M_Shop\Context_Iface $context, \Closure $fcn, $sites)
    {
        $process = $context->process();
        $aimeos = $this->get_laravel()->make('aimeos')->get();
        $site_manager = \Aimeos\M_Shop::create($context, 'locale/site');
        $locale_manager = \Aimeos\M_Shop::create($context, 'locale');
        $filter = $site_manager->filter();
        $start = 0;
        if (!empty($sites)) {
            $filter->add(['locale.site.code' => !is_array($sites) ? explode(' ', (string) $sites) : $sites]);
        }
        do {
            $site_items = $site_manager->search($filter->slice($start));
            foreach ($site_items as $site_item) {
                \Aimeos\M_Shop::cache(true);
                \Aimeos\M_Admin::cache(true);
                $locale_item = $locale_manager->bootstrap($site_item->get_code(), '', '', false);
                $locale_item->set_language_id(null);
                $locale_item->set_currency_id(null);
                $lcontext = clone $context;
                $lcontext->set_locale($locale_item);
                $tmpl_paths = $aimeos->get_template_paths('controller/jobs/templates', $site_item->get_theme());
                $view = $this->get_laravel()->make('aimeos.view')->create($lcontext, $tmpl_paths);
                $lcontext->set_view($view);
                $config = $lcontext->config();
                $config->apply($site_item->get_config());
                $process->start($fcn, [$lcontext, $aimeos], false);
            }
            $count = count($site_items);
            $start += $count;
        } while ($count === $filter->get_limit());
        $process->wait();
    }
}