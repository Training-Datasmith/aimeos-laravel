<?php

declare (strict_types=1);
/**
 * @license MIT, http://opensource.org/licenses/MIT
 * @copyright Aimeos (aimeos.org), 2015-2023
 */
namespace Aimeos\Shop\Base;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
/**
 * Service providing the context objects
 */
class Locale
{
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    private $config;
    /**
     * @var \Aimeos\MShop\Locale\Item\Iface
     */
    private $locale;
    /**
     * Initializes the object
     *
     * @param \Illuminate\Contracts\Config\Repository $config Configuration object
     */
    public function __construct(\Illuminate\Contracts\Config\Repository $config)
    {
        $this->config = $config;
    }
    /**
     * Returns the locale item for the current request
     *
     * @param \Aimeos\MShop\ContextIface $context Context object
     * @return \Aimeos\MShop\Locale\Item\Iface Locale item object
     */
    public function get(\Aimeos\M_Shop\Context_Iface $context): \Aimeos\M_Shop\Locale\Item\Iface
    {
        if ($this->locale === null) {
            $site = config('shop.mshop.locale.site', 'default');
            $lang = app()->get_locale();
            $currency = '';
            if (Route::current()) {
                $site = Request::route('site', $site);
                $lang = Request::route('locale', $lang);
                $currency = Request::route('currency', $currency);
            }
            $site = Request::input('site', $site);
            $lang = Request::input('locale', $lang);
            $currency = Request::input('currency', $currency);
            $locale_manager = \Aimeos\M_Shop::create($context, 'locale');
            $disable_sites = $this->config->get('shop.disableSites', true);
            $this->locale = $locale_manager->bootstrap($site, $lang, $currency, $disable_sites);
        }
        return $this->locale;
    }
    /**
     * Returns the locale item for the current request
     *
     * @param \Aimeos\MShop\ContextIface $context Context object
     * @param string $site Unique site code
     * @return \Aimeos\MShop\Locale\Item\Iface Locale item object
     */
    public function get_backend(\Aimeos\M_Shop\Context_Iface $context, string $site): \Aimeos\M_Shop\Locale\Item\Iface
    {
        $locale_manager = \Aimeos\M_Shop::create($context, 'locale');
        try {
            $locale_item = $locale_manager->bootstrap($site, '', '', false, null, true);
        } catch (\Aimeos\M_Shop\Exception) {
            $locale_item = $locale_manager->create();
        }
        return $locale_item->set_currency_id(null)->set_language_id(null);
    }
}