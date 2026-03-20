<?php

declare (strict_types=1);
/**
 * @license MIT, http://opensource.org/licenses/MIT
 * @copyright Aimeos (aimeos.org), 2015-2023
 */
namespace Aimeos\Shop\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
/**
 * Service providing the context objects
 */
class Context
{
    /**
     * @var \Aimeos\MShop\ContextIface
     */
    private $context;
    /**
     * @var \Illuminate\Session\Store
     */
    private $session;
    /**
     * Initializes the object
     *
     * @param \Illuminate\Session\Store $session Laravel session object
     * @param \Aimeos\Shop\Base\Config $config Configuration object
     * @param \Aimeos\Shop\Base\Locale $locale Locale object
     * @param \Aimeos\Shop\Base\I18n $i18n Internationalisation object
     */
    public function __construct(\Illuminate\Session\Store $session, private readonly \Aimeos\Shop\Base\Config $config, private readonly \Aimeos\Shop\Base\Locale $locale, private readonly \Aimeos\Shop\Base\I18n $i18n)
    {
        $this->session = $session;
    }
    /**
     * Returns the current context
     *
     * @param bool $locale True to add locale object to context, false if not (deprecated, use \Aimeos\Shop\Base\Locale)
     * @param string $type Configuration type, i.e. "frontend" or "backend" (deprecated, use \Aimeos\Shop\Base\Config)
     * @return \Aimeos\MShop\ContextIface Context object
     */
    public function get(bool $locale = true, string $type = 'frontend'): \Aimeos\M_Shop\Context_Iface
    {
        $config = $this->config->get($type);
        if ($this->context === null) {
            $context = new \Aimeos\M_Shop\Context();
            $context->set_config($config);
            $this->add_data_base_manager($context);
            $this->add_filesystem_manager($context);
            $this->add_message_queue_manager($context);
            $this->add_logger($context);
            $this->add_cache($context);
            $this->add_mailer($context);
            $this->add_nonce($context);
            $this->add_password($context);
            $this->add_process($context);
            $this->add_session($context);
            $this->add_token($context);
            $this->add_user_groups($context);
            $this->context = $context;
        }
        $this->context->set_config($config);
        if ($locale === true) {
            $locale_item = $this->locale->get($this->context);
            $this->context->set_locale($locale_item);
            $this->context->set_i18n($this->i18n->get([$locale_item->get_language_id()]));
            $config->apply($locale_item->get_site_item()->get_config());
        }
        return $this->context;
    }
    /**
     * Adds the cache object to the context
     *
     * @param \Aimeos\MShop\ContextIface $context Context object including config
     * @return \Aimeos\MShop\ContextIface Modified context object
     */
    protected function add_cache(\Aimeos\M_Shop\Context_Iface $context): \Aimeos\M_Shop\Context_Iface
    {
        $cache = \Aimeos\M_Admin::create($context, 'cache')->get_cache();
        return $context->set_cache($cache);
    }
    /**
     * Adds the database manager object to the context
     *
     * @param \Aimeos\MShop\ContextIface $context Context object
     * @return \Aimeos\MShop\ContextIface Modified context object
     */
    protected function add_database_manager(\Aimeos\M_Shop\Context_Iface $context): \Aimeos\M_Shop\Context_Iface
    {
        $dbm = new \Aimeos\Base\DB\Manager\Standard($context->config()->get('resource'), 'DBAL');
        return $context->set_database_manager($dbm);
    }
    /**
     * Adds the filesystem manager object to the context
     *
     * @param \Aimeos\MShop\ContextIface $context Context object
     * @return \Aimeos\MShop\ContextIface Modified context object
     */
    protected function add_filesystem_manager(\Aimeos\M_Shop\Context_Iface $context): \Aimeos\M_Shop\Context_Iface
    {
        $config = $context->config()->get('resource');
        $fs = new \Aimeos\Base\Filesystem\Manager\Laravel(app('filesystem'), $config, storage_path('aimeos'));
        return $context->set_filesystem_manager($fs);
    }
    /**
     * Adds the logger object to the context
     *
     * @param \Aimeos\MShop\ContextIface $context Context object
     * @return \Aimeos\MShop\ContextIface Modified context object
     */
    protected function add_logger(\Aimeos\M_Shop\Context_Iface $context): \Aimeos\M_Shop\Context_Iface
    {
        $logger = \Aimeos\M_Admin::create($context, 'log');
        return $context->set_logger($logger);
    }
    /**
     * Adds the mailer object to the context
     *
     * @param \Aimeos\MShop\ContextIface $context Context object
     * @return \Aimeos\MShop\ContextIface Modified context object
     */
    protected function add_mailer(\Aimeos\M_Shop\Context_Iface $context): \Aimeos\M_Shop\Context_Iface
    {
        $mail = new \Aimeos\Base\Mail\Manager\Laravel(app('mail.manager'));
        return $context->set_mail($mail);
    }
    /**
     * Adds the message queue manager object to the context
     *
     * @param \Aimeos\MShop\ContextIface $context Context object
     * @return \Aimeos\MShop\ContextIface Modified context object
     */
    protected function add_message_queue_manager(\Aimeos\M_Shop\Context_Iface $context): \Aimeos\M_Shop\Context_Iface
    {
        $mq = new \Aimeos\Base\M_Queue\Manager\Standard($context->config()->get('resource'));
        return $context->set_message_queue_manager($mq);
    }
    /**
     * Adds the nonce value for inline JS to the context
     *
     * @param \Aimeos\MShop\ContextIface $context Context object
     * @return \Aimeos\MShop\ContextIface Modified context object
     */
    protected function add_nonce(\Aimeos\M_Shop\Context_Iface $context): \Aimeos\M_Shop\Context_Iface
    {
        return $context->set_nonce(base64_encode(random_bytes(16)));
    }
    /**
     * Adds the password hasher object to the context
     *
     * @param \Aimeos\MShop\ContextIface $context Context object
     * @return \Aimeos\MShop\ContextIface Modified context object
     */
    protected function add_password(\Aimeos\M_Shop\Context_Iface $context): \Aimeos\M_Shop\Context_Iface
    {
        return $context->set_password(new \Aimeos\Base\Password\Standard());
    }
    /**
     * Adds the process object to the context
     *
     * @param \Aimeos\MShop\ContextIface $context Context object
     * @return \Aimeos\MShop\ContextIface Modified context object
     */
    protected function add_process(\Aimeos\M_Shop\Context_Iface $context): \Aimeos\M_Shop\Context_Iface
    {
        $config = $context->config();
        $max = $config->get('pcntl_max', 4);
        $prio = $config->get('pcntl_priority', 19);
        $process = new \Aimeos\Base\Process\Pcntl($max, $prio);
        $process = new \Aimeos\Base\Process\Decorator\Check($process);
        return $context->set_process($process);
    }
    /**
     * Adds the session object to the context
     *
     * @param \Aimeos\MShop\ContextIface $context Context object
     * @return \Aimeos\MShop\ContextIface Modified context object
     */
    protected function add_session(\Aimeos\M_Shop\Context_Iface $context): \Aimeos\M_Shop\Context_Iface
    {
        $session = new \Aimeos\Base\Session\Laravel($this->session);
        return $context->set_session($session);
    }
    /**
     * Adds the session token to the context
     *
     * @param \Aimeos\MShop\ContextIface $context Context object
     * @return \Aimeos\MShop\ContextIface Modified context object
     */
    protected function add_token(\Aimeos\M_Shop\Context_Iface $context): \Aimeos\M_Shop\Context_Iface
    {
        if (($token = Session::get('token')) === null) {
            Session::put('token', $token = bin2hex(random_bytes(32)));
        }
        return $context->set_token($token);
    }
    /**
     * Adds the user and groups if available
     *
     * @param \Aimeos\MShop\ContextIface $context Context object
     * @return \Aimeos\MShop\ContextIface Modified context object
     */
    protected function add_user_groups(\Aimeos\M_Shop\Context_Iface $context): \Aimeos\M_Shop\Context_Iface
    {
        $key = collect(config('shop.routes'))->where('prefix', optional(Route::get_current_route())->get_prefix())->keys()->first();
        $gname = data_get(config('shop.guards'), $key, Auth::get_default_driver());
        if (($guard = Auth::guard($gname)) && $userid = $guard->id()) {
            $context->set_user(function () use ($context, $userid) {
                try {
                    return \Aimeos\M_Shop::create($context, 'customer')->get($userid, ['group']);
                } catch (\Aimeos\M_Shop\Exception) {
                    // avoid errors if user is assigned to another site
                    return null;
                }
            });
            $context->set_groups(fn() => $context->user()?->get_groups() ?? []);
            $context->set_editor($guard->user()?->email ?: \Request::ip());
        } elseif ($ip = \Request::ip()) {
            $context->set_editor($ip);
        }
        return $context;
    }
}