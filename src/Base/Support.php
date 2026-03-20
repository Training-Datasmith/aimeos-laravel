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
 * Service providing the supporting functionality
 */
class Support
{
    private array $access = [];
    /**
     * Initializes the object
     *
     * @param \Aimeos\Shop\Base\Context $context Context provider
     * @param \Aimeos\Shop\Base\Locale $locale Locale provider
     */
    public function __construct(private readonly \Aimeos\Shop\Base\Context $context, private readonly \Aimeos\Shop\Base\Locale $locale)
    {
    }
    /**
     * Checks if the user is in the specified group and associatied to the site
     *
     * @param \Illuminate\Foundation\Auth\User $user Authenticated user
     * @param string|array $groupcodes Unique user/customer group codes that are allowed
     * @return bool True if user is part of the group, false if not
     */
    public function check_user_group(\Illuminate\Foundation\Auth\User $user, $groupcodes): bool
    {
        $groups = is_array($groupcodes) ? implode(',', $groupcodes) : $groupcodes;
        if (isset($this->access[$user->id][$groups])) {
            return $this->access[$user->id][$groups];
        }
        $this->access[$user->id][$groups] = false;
        $context = $this->context->get(false);
        $siteid = current(array_reverse(explode('.', trim($user->siteid, '.'))));
        if ($siteid) {
            $site = \Aimeos\M_Shop::create($context, 'locale/site')->get($siteid)->get_code();
        } else {
            $site = config('shop.mshop.locale.site', 'default');
        }
        $site = Route::current() ? Route::input('site', Request::get('site', $site)) : $site;
        $context->set_locale($this->locale->get_backend($context, $site));
        foreach (array_reverse($context->locale()->get_site_path()) as $siteid) {
            if ($user->siteid === '' || $user->siteid === $siteid) {
                $this->access[$user->id][$groups] = $this->check_groups($context, $user->id, $groupcodes);
            }
        }
        return $this->access[$user->id][$groups];
    }
    /**
     * Checks if one of the groups is associated to the given user ID
     *
     * @param \Aimeos\MShop\ContextIface $context Context item
     * @param string $userid ID of the logged in user
     * @param string[]|string $groupcodes List of group codes to check against
     * @return bool True if the user is in one of the groups, false if not
     */
    protected function check_groups(\Aimeos\M_Shop\Context_Iface $context, string $userid, $groupcodes): bool
    {
        $manager = \Aimeos\M_Shop::create($context, 'group');
        $search = $manager->filter();
        $search->set_conditions($search->compare('==', 'group.code', (array) $groupcodes));
        $group_ids = $manager->search($search)->keys()->to_array();
        $manager = \Aimeos\M_Shop::create($context, 'customer/lists');
        $search = $manager->filter()->slice(0, 1);
        $expr = [$search->compare('==', 'customer.lists.parentid', $userid), $search->compare('==', 'customer.lists.refid', $group_ids), $search->compare('==', 'customer.lists.domain', 'group')];
        $search->set_conditions($search->combine('&&', $expr));
        return !$manager->search($search)->is_empty();
    }
}