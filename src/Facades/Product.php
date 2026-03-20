<?php

declare (strict_types=1);
/**
 * @license MIT, http://opensource.org/licenses/MIT
 * @copyright Aimeos (aimeos.org), 2019-2023
 */
namespace Aimeos\Shop\Facades;

/**
 * Returns the product frontend controller
 */
class Product extends \Illuminate\Support\Facades\Facade
{
    /**
     * Returns a new product frontend controller object
     *
     * @return \Aimeos\Controller\Frontend\Product\Iface
     */
    protected static function get_facade_accessor()
    {
        return 'aimeos.frontend.product';
    }
}