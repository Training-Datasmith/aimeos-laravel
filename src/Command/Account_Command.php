<?php

declare (strict_types=1);
/**
 * @license MIT, http://opensource.org/licenses/MIT
 * @copyright Aimeos (aimeos.org), 2015-2023
 */
namespace Aimeos\Shop\Command;

/**
 * Creates new accounts or resets their passwords
 */
class Account_Command extends Abstract_Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aimeos:account
		{email? : E-Mail adress of the (admin) user (will ask for if not given)}
		{site? : Site to create account for (will use default value if not given}
		{--password= : Secret password for the account (will ask for if not given)}
		{--super : If account should have super user privileges for all sites}
		{--admin : If account should have site administrator privileges}
		{--editor : If account should have limited editor privileges}
	';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates new (admin) accounts';
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $site = $this->argument('site') ?: config('shop.mshop.locale.site', 'default');
        if (($email = $this->argument('email')) === null) {
            $email = $this->ask('E-Mail');
        }
        if (($password = $this->option('password')) === null) {
            $password = $this->secret('Password');
        }
        $context = $this->get_laravel()->make('aimeos.context')->get(false, 'command');
        $context->set_editor('aimeos:account');
        $locale_manager = \Aimeos\M_Shop::create($context, 'locale');
        $locale_item = $locale_manager->bootstrap($site, '', '', false, null, true);
        $context->set_locale($locale_item);
        $manager = \Aimeos\M_Shop::create($context, 'customer');
        try {
            $item = $manager->find($email);
        } catch (\Aimeos\M_Shop\Exception) {
            $item = $manager->create();
        }
        $item = $item->set_code($email)->set_label($email)->set_password($password)->set_status(1);
        $item->get_payment_address()->set_email($email);
        $item = $manager->save($this->add_groups($context, $item));
        \Illuminate\Foundation\Auth\User::find_or_fail($item->get_id())->force_fill(['siteid' => $this->option('super') ? '' : $item->get_site_id(), 'superuser' => $this->option('super') ? 1 : 0, 'email_verified_at' => now()])->save();
    }
    /**
     * Adds the group to the given user
     *
     * @param \Aimeos\MShop\ContextIface $context Aimeos context object
     * @param \Aimeos\MShop\Customer\Item\Iface $user Aimeos customer object
     * @return \Aimeos\MShop\Customer\Item\Iface Updated customer object
     */
    protected function add_groups(\Aimeos\M_Shop\Context_Iface $context, \Aimeos\M_Shop\Customer\Item\Iface $user): \Aimeos\M_Shop\Customer\Item\Iface
    {
        if ($this->option('admin')) {
            $user = $this->add_group($context, $user, 'admin');
        }
        if ($this->option('editor')) {
            return $this->add_group($context, $user, 'editor');
        }
        return $user;
    }
    /**
     * Adds the group to the given user
     *
     * @param \Aimeos\MShop\ContextIface $context Aimeos context object
     * @param \Aimeos\MShop\Customer\Item\Iface $user Aimeos customer object
     * @param string $group Unique customer group code
     */
    protected function add_group(\Aimeos\M_Shop\Context_Iface $context, \Aimeos\M_Shop\Customer\Item\Iface $user, string $group): \Aimeos\M_Shop\Customer\Item\Iface
    {
        $msg = 'Add "%1$s" group to user "%2$s" for site "%3$s"';
        $site = $this->argument('site') ?: config('shop.mshop.locale.site', 'default');
        $this->info(sprintf($msg, $group, $user->get_code(), $site));
        $item = $this->get_group_item($context, $group);
        return $user->set_groups(array_merge($user->get_groups(), [$item->get_id()]));
    }
    /**
     * Returns the customer group item for the given code
     *
     * @param \Aimeos\MShop\ContextIface $context Aimeos context object
     * @param string $code Unique customer group code
     * @return \Aimeos\MShop\Group\Item\Iface Aimeos customer group item object
     */
    protected function get_group_item(\Aimeos\M_Shop\Context_Iface $context, string $code): \Aimeos\M_Shop\Group\Item\Iface
    {
        $manager = \Aimeos\M_Shop::create($context, 'group');
        try {
            $item = $manager->find($code);
        } catch (\Aimeos\M_Shop\Exception) {
            $item = $manager->create();
            $item->set_label($code);
            $item->set_code($code);
            $manager->save($item);
        }
        return $item;
    }
}