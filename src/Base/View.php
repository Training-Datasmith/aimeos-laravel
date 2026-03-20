<?php

declare (strict_types=1);
/**
 * @license MIT, http://opensource.org/licenses/MIT
 * @copyright Aimeos (aimeos.org), 2015-2023
 */
namespace Aimeos\Shop\Base;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
/**
 * Service providing the view objects
 */
class View
{
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    private $config;
    /**
     * Initializes the object
     *
     * @param \Illuminate\Contracts\Config\Repository $config Configuration object
     * @param \Aimeos\Shop\Base\I18n $i18n I18n object
     */
    public function __construct(\Illuminate\Contracts\Config\Repository $config, private readonly \Aimeos\Shop\Base\I18n $i18n)
    {
        $this->config = $config;
    }
    /**
     * Creates the view object for the HTML client.
     *
     * @param \Aimeos\MShop\ContextIface $context Context object
     * @param array $templatePaths List of base path names with relative template paths as key/value pairs
     * @param string|null $locale Code of the current language or null for no translation
     * @return \Aimeos\Base\View\Iface View object
     */
    public function create(\Aimeos\M_Shop\Context_Iface $context, array $template_paths, string $locale = null): \Aimeos\Base\View\Iface
    {
        $engine = new \Aimeos\Base\View\Engine\Blade(app('Illuminate\Contracts\View\Factory'));
        $view = new \Aimeos\Base\View\Standard($template_paths, ['.blade.php' => $engine]);
        $config = $context->config();
        $session = $context->session();
        $this->add_csrf($view);
        $this->add_access($view, $context);
        $this->add_config($view, $config);
        $this->add_number($view, $config, $locale);
        $this->add_param($view);
        $this->add_request($view);
        $this->add_response($view);
        $this->add_session($view, $session);
        $this->add_translate($view, $locale);
        $this->add_url($view);
        return $view;
    }
    /**
     * Adds the "access" helper to the view object
     *
     * @param \Aimeos\Base\View\Iface $view View object
     * @param \Aimeos\MShop\ContextIface $context Context object
     * @return \Aimeos\Base\View\Iface Modified view object
     */
    protected function add_access(\Aimeos\Base\View\Iface $view, \Aimeos\M_Shop\Context_Iface $context): \Aimeos\Base\View\Iface
    {
        if ($this->config->get('shop.accessControl', true) === false || ($user = \Illuminate\Support\Facades\Auth::user()) !== null && $user->superuser) {
            $helper = new \Aimeos\Base\View\Helper\Access\All($view);
        } else {
            $helper = new \Aimeos\Base\View\Helper\Access\Standard($view, function () use ($context) {
                $manager = \Aimeos\M_Shop::create($context, 'group');
                $filter = $manager->filter(true)->add('group.id', '==', $context->groups());
                return $manager->search($filter)->col('group.code')->all();
            });
        }
        $view->add_helper('access', $helper);
        return $view;
    }
    /**
     * Adds the "config" helper to the view object
     *
     * @param \Aimeos\Base\View\Iface $view View object
     * @param \Aimeos\Base\Config\Iface $config Configuration object
     * @return \Aimeos\Base\View\Iface Modified view object
     */
    protected function add_config(\Aimeos\Base\View\Iface $view, \Aimeos\Base\Config\Iface $config): \Aimeos\Base\View\Iface
    {
        $config = new \Aimeos\Base\Config\Decorator\Protect(clone $config, ['resource/*/baseurl'], ['resource']);
        $helper = new \Aimeos\Base\View\Helper\Config\Standard($view, $config);
        $view->add_helper('config', $helper);
        return $view;
    }
    /**
     * Adds the "access" helper to the view object
     *
     * @param \Aimeos\Base\View\Iface $view View object
     * @return \Aimeos\Base\View\Iface Modified view object
     */
    protected function add_csrf(\Aimeos\Base\View\Iface $view): \Aimeos\Base\View\Iface
    {
        $helper = new \Aimeos\Base\View\Helper\Csrf\Standard($view, '_token', csrf_token());
        $view->add_helper('csrf', $helper);
        return $view;
    }
    /**
     * Adds the "number" helper to the view object
     *
     * @param \Aimeos\Base\View\Iface $view View object
     * @param \Aimeos\Base\Config\Iface $config Configuration object
     * @param string|null $locale Code of the current language or null for no translation
     * @return \Aimeos\Base\View\Iface Modified view object
     */
    protected function add_number(\Aimeos\Base\View\Iface $view, \Aimeos\Base\Config\Iface $config, string $locale = null): \Aimeos\Base\View\Iface
    {
        if (config('shop.num_formatter', 'Locale') === 'Locale') {
            $pattern = $config->get('client/html/common/format/pattern');
            $helper = new \Aimeos\Base\View\Helper\Number\Locale($view, $locale, $pattern);
        } else {
            $sep1000 = $config->get('client/html/common/format/separator1000', '');
            $decsep = $config->get('client/html/common/format/separatorDecimal', '.');
            $helper = new \Aimeos\Base\View\Helper\Number\Standard($view, $decsep, $sep1000);
        }
        return $view->add_helper('number', $helper);
    }
    /**
     * Adds the "param" helper to the view object
     *
     * @param \Aimeos\Base\View\Iface $view View object
     * @return \Aimeos\Base\View\Iface Modified view object
     */
    protected function add_param(\Aimeos\Base\View\Iface $view): \Aimeos\Base\View\Iface
    {
        $params = (Route::current() ? Route::current()->parameters() : []) + Request::all();
        $helper = new \Aimeos\Base\View\Helper\Param\Standard($view, $params);
        $view->add_helper('param', $helper);
        return $view;
    }
    /**
     * Adds the "request" helper to the view object
     *
     * @param \Aimeos\Base\View\Iface $view View object
     * @return \Aimeos\Base\View\Iface Modified view object
     */
    protected function add_request(\Aimeos\Base\View\Iface $view): \Aimeos\Base\View\Iface
    {
        $helper = new \Aimeos\Base\View\Helper\Request\Laravel($view, Request::instance());
        $view->add_helper('request', $helper);
        return $view;
    }
    /**
     * Adds the "response" helper to the view object
     *
     * @param \Aimeos\Base\View\Iface $view View object
     * @return \Aimeos\Base\View\Iface Modified view object
     */
    protected function add_response(\Aimeos\Base\View\Iface $view): \Aimeos\Base\View\Iface
    {
        $helper = new \Aimeos\Base\View\Helper\Response\Laravel($view);
        $view->add_helper('response', $helper);
        return $view;
    }
    /**
     * Adds the "session" helper to the view object
     *
     * @param \Aimeos\Base\View\Iface $view View object
     * @param \Aimeos\Base\Session\Iface $session Session object
     * @return \Aimeos\Base\View\Iface Modified view object
     */
    protected function add_session(\Aimeos\Base\View\Iface $view, \Aimeos\Base\Session\Iface $session): \Aimeos\Base\View\Iface
    {
        $helper = new \Aimeos\Base\View\Helper\Session\Standard($view, $session);
        $view->add_helper('session', $helper);
        return $view;
    }
    /**
     * Adds the "translate" helper to the view object
     *
     * @param \Aimeos\Base\View\Iface $view View object
     * @param string|null $locale ISO language code, e.g. "de" or "de_CH"
     * @return \Aimeos\Base\View\Iface Modified view object
     */
    protected function add_translate(\Aimeos\Base\View\Iface $view, ?string $locale = null): \Aimeos\Base\View\Iface
    {
        if ($locale !== null) {
            $i18n = $this->i18n->get([$locale]);
            $translation = $i18n[$locale];
        } else {
            $translation = new \Aimeos\Base\Translation\None('en');
        }
        $helper = new \Aimeos\Base\View\Helper\Translate\Standard($view, $translation);
        $view->add_helper('translate', $helper);
        return $view;
    }
    /**
     * Adds the "url" helper to the view object
     *
     * @param \Aimeos\Base\View\Iface $view View object
     * @return \Aimeos\Base\View\Iface Modified view object
     */
    protected function add_url(\Aimeos\Base\View\Iface $view): \Aimeos\Base\View\Iface
    {
        $fixed = ['site' => env('SHOP_MULTISHOP') ? config('shop.mshop.locale.site', 'default') : '', 'locale' => env('SHOP_MULTILOCALE') ? app()->get_locale() : '', 'currency' => ''];
        if (Route::current()) {
            $fixed['site'] = Request::route('site', $fixed['site']);
            $fixed['locale'] = Request::route('locale', $fixed['locale']);
            $fixed['currency'] = Request::route('currency', $fixed['currency']);
        }
        $fixed['site'] = Request::input('site', $fixed['site']);
        $fixed['locale'] = Request::input('locale', $fixed['locale']);
        $fixed['currency'] = Request::input('currency', $fixed['currency']);
        $helper = new \Aimeos\Base\View\Helper\Url\Laravel($view, app('url'), array_filter($fixed));
        $view->add_helper('url', $helper);
        return $view;
    }
}