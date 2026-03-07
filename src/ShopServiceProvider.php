<?php

/**
 * @license MIT, http://opensource.org/licenses/MIT
 * @copyright Aimeos (aimeos.org), 2015-2023
 */


namespace Aimeos\Shop;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;


/**
 * Aimeos shop service provider for Laravel
 */
class ShopServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;


	/**
     * Bootstrap the application events.
     */
    public function boot(): void
	{
		$this->loadViewsFrom( dirname( __DIR__ ) . '/views', 'shop' );
		$this->loadRoutesFrom( dirname( __DIR__ ) . '/routes/aimeos.php' );

		$this->publishes( [dirname( __DIR__ ) . '/config/shop.php' => config_path( 'shop.php' )], 'config' );
		$this->publishes( [dirname( __DIR__ ) . '/public' => public_path( 'vendor/shop' )], 'public' );

		$class = \Composer\InstalledVersions::class;

		if( class_exists( $class ) && method_exists( $class, 'getInstalledPackagesByType' ) )
		{
			foreach( \Composer\InstalledVersions::getInstalledPackagesByType( 'aimeos-extension' ) as $package )
			{
				$path = realpath( \Composer\InstalledVersions::getInstallPath( $package ) );

				if( file_exists( $path . '/themes/client/html' ) ) {
					$this->publishes( [$path . '/themes/client/html' => public_path( 'vendor/shop/themes' )], 'public' );
				}
			}
		}
	}


	/**
     * Register the service provider.
     */
    public function register(): void
	{
		$this->mergeConfigFrom( dirname( __DIR__ ) . '/config/default.php', 'shop' );

		$this->app->scoped( 'aimeos', fn($app) => new \Aimeos\Shop\Base\Aimeos( $app['config'] ));

		$this->app->scoped( 'aimeos.config', fn($app) => new \Aimeos\Shop\Base\Config( $app['config'], $app['aimeos'] ));

		$this->app->scoped( 'aimeos.i18n', fn($app) => new \Aimeos\Shop\Base\I18n( $this->app['config'], $app['aimeos'] ));

		$this->app->scoped( 'aimeos.locale', fn($app) => new \Aimeos\Shop\Base\Locale( $app['config'] ));

		$this->app->scoped( 'aimeos.context', fn($app) => new \Aimeos\Shop\Base\Context( $app['session.store'], $app['aimeos.config'], $app['aimeos.locale'], $app['aimeos.i18n'] ));

		$this->app->scoped( 'aimeos.support', fn($app) => new \Aimeos\Shop\Base\Support( $app['aimeos.context'], $app['aimeos.locale'] ));

		$this->app->scoped( 'aimeos.view', fn($app) => new \Aimeos\Shop\Base\View( $app['config'], $app['aimeos.i18n'], $app['aimeos.support'] ));

		$this->app->scoped( 'aimeos.shop', fn($app) => new \Aimeos\Shop\Base\Shop( $app['aimeos'], $app['aimeos.context'], $app['aimeos.view'] ));


		$this->app->bind( 'aimeos.frontend.attribute', fn($app) => \Aimeos\Controller\Frontend::create( $app['aimeos.context'], 'attribute' ));

		$this->app->bind( 'aimeos.frontend.basket', fn($app) => \Aimeos\Controller\Frontend::create( $app['aimeos.context'], 'basket' ));

		$this->app->bind( 'aimeos.frontend.catalog', fn($app) => \Aimeos\Controller\Frontend::create( $app['aimeos.context'], 'catalog' ));

		$this->app->bind( 'aimeos.frontend.cms', fn($app) => \Aimeos\Controller\Frontend::create( $app['aimeos.context'], 'cms' ));

		$this->app->bind( 'aimeos.frontend.customer', fn($app) => \Aimeos\Controller\Frontend::create( $app['aimeos.context'], 'customer' ));

		$this->app->bind( 'aimeos.frontend.locale', fn($app) => \Aimeos\Controller\Frontend::create( $app['aimeos.context'], 'locale' ));

		$this->app->bind( 'aimeos.frontend.order', fn($app) => \Aimeos\Controller\Frontend::create( $app['aimeos.context'], 'order' ));

		$this->app->bind( 'aimeos.frontend.product', fn($app) => \Aimeos\Controller\Frontend::create( $app['aimeos.context'], 'product' ));

		$this->app->bind( 'aimeos.frontend.service', fn($app) => \Aimeos\Controller\Frontend::create( $app['aimeos.context'], 'service' ));

		$this->app->bind( 'aimeos.frontend.stock', fn($app) => \Aimeos\Controller\Frontend::create( $app['aimeos.context'], 'stock' ));

		$this->app->bind( 'aimeos.frontend.subscription', fn($app) => \Aimeos\Controller\Frontend::create( $app['aimeos.context'], 'subscription' ));

		$this->app->bind( 'aimeos.frontend.supplier', fn($app) => \Aimeos\Controller\Frontend::create( $app['aimeos.context'], 'supplier' ));


		$this->commands( [
			\Aimeos\Shop\Command\AccountCommand::class,
			\Aimeos\Shop\Command\ClearCommand::class,
			\Aimeos\Shop\Command\SetupCommand::class,
			\Aimeos\Shop\Command\JobsCommand::class,
		] );
	}


	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [
			\Aimeos\Shop\Base\Aimeos::class, \Aimeos\Shop\Base\I18n::class, \Aimeos\Shop\Base\Context::class,
			\Aimeos\Shop\Base\Config::class, \Aimeos\Shop\Base\Locale::class, \Aimeos\Shop\Base\View::class,
			\Aimeos\Shop\Base\Support::class, \Aimeos\Shop\Base\Shop::class,
			\Aimeos\Shop\Command\AccountCommand::class, \Aimeos\Shop\Command\ClearCommand::class,
			\Aimeos\Shop\Command\SetupCommand::class, \Aimeos\Shop\Command\JobsCommand::class,
		];
	}

}