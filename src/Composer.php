<?php

declare(strict_types=1);

/**
 * @license MIT, http://opensource.org/licenses/MIT
 * @copyright Aimeos (aimeos.org), 2020-2023
 */

namespace Aimeos\Shop;

/**
 * Performs setup during composer installs
 */
class Composer
{
    /**
     * @param \Composer\Script\Event $event Event instance
     */
    public static function join(\Composer\Script\Event $event): void
    {
    }
}
