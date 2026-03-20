<?php

declare (strict_types=1);
/**
 * @license MIT, http://opensource.org/licenses/MIT
 * @copyright Aimeos (aimeos.org), 2015-2023
 */
namespace Aimeos\Shop\Base;

/**
 * Service providing the config object
 */
class Config
{
    /**
     * @var \Aimeos\Shop\Base\Config[]
     */
    private array $objects = [];
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    private $config;
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
     * Creates a new configuration object.
     *
     * @param string $type Configuration type ("frontend" or "backend")
     * @return \Aimeos\Base\Config\Iface Configuration object
     */
    public function get(string $type = 'frontend'): \Aimeos\Base\Config\Iface
    {
        if (!isset($this->objects[$type])) {
            $config_paths = $this->aimeos->get()->get_config_paths();
            $cfgfile = dirname(__DIR__, 2) . '/config/default.php';
            $config = new \Aimeos\Base\Config\Php_Array(require $cfgfile, $config_paths);
            if ($this->config->get('shop.apc_enabled', false) == true) {
                $config = new \Aimeos\Base\Config\Decorator\APC($config, $this->config->get('shop.apc_prefix', 'laravel:'));
            }
            $config = new \Aimeos\Base\Config\Decorator\Memory($config, $this->config->get('shop'));
            if (($conf = $this->config->get('shop.' . $type, [])) !== []) {
                $config = new \Aimeos\Base\Config\Decorator\Memory($config, $conf);
            }
            $this->objects[$type] = $config;
        }
        return $this->objects[$type];
    }
}