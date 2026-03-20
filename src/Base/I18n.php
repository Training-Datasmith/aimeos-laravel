<?php

declare (strict_types=1);
/**
 * @license MIT, http://opensource.org/licenses/MIT
 * @copyright Aimeos (aimeos.org), 2015-2023
 */
namespace Aimeos\Shop\Base;

/**
 * Service providing the internationalization objects
 */
class I18n
{
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    private $config;
    private array $i18n = [];
    /**
     * Initializes the object
     *
     * @param \Illuminate\Contracts\Config\Repository $config Configuration object
     * @param \Aimeos\Shop\Base\Aimeos $aimeos Aimeos object
     */
    public function __construct(\Illuminate\Contracts\Config\Repository $config, private readonly \Aimeos\Shop\Base\Aimeos $aimeos)
    {
        $this->config = $config;
    }
    /**
     * Creates new translation objects.
     *
     * @param array $languageIds List of two letter ISO language IDs
     * @return \Aimeos\Base\Translation\Iface[] List of translation objects
     */
    public function get(array $language_ids): array
    {
        $i18n_paths = $this->aimeos->get()->get_i18n_paths();
        foreach ($language_ids as $langid) {
            if (!isset($this->i18n[$langid])) {
                $i18n = new \Aimeos\Base\Translation\Gettext($i18n_paths, $langid);
                if ($this->config->get('shop.apc_enabled', false) == true) {
                    $i18n = new \Aimeos\Base\Translation\Decorator\APC($i18n, $this->config->get('shop.apc_prefix', 'laravel:'));
                }
                if ($this->config->has('shop.i18n.' . $langid)) {
                    $i18n = new \Aimeos\Base\Translation\Decorator\Memory($i18n, $this->config->get('shop.i18n.' . $langid));
                }
                $this->i18n[$langid] = $i18n;
            }
        }
        return $this->i18n;
    }
}