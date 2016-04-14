<?php namespace Sanatorium\Discounts\Providers;

use Cartalyst\Support\ServiceProvider;

class VoucherServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register the attributes namespace
		$this->app['platform.attributes.manager']->registerNamespace(
			$this->app['Sanatorium\Discounts\Models\Voucher']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('sanatorium.discounts.voucher.handler.event');

		// Register the hooks
		$this->registerHooks();

		$this->prepareResources();
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('sanatorium.discounts.voucher', 'Sanatorium\Discounts\Repositories\Voucher\VoucherRepository');

		// Register the data handler
		$this->bindIf('sanatorium.discounts.voucher.handler.data', 'Sanatorium\Discounts\Handlers\Voucher\VoucherDataHandler');

		// Register the event handler
		$this->bindIf('sanatorium.discounts.voucher.handler.event', 'Sanatorium\Discounts\Handlers\Voucher\VoucherEventHandler');

		// Register the validator
		$this->bindIf('sanatorium.discounts.voucher.validator', 'Sanatorium\Discounts\Validator\Voucher\VoucherValidator');
	}

	public function registerHooks()
	{
		$hooks = [
            [
            	'position' => 'cart.summary',
            	'hook' => 'sanatorium/discounts::hooks.voucher',
            ],
            [
            	'position' => 'cart.summary.items',
            	'hook' => 'sanatorium/discounts::hooks.items',
            ],
            [
            	'position' => 'cart.summary.items.lite',
            	'hook' => 'sanatorium/discounts::hooks.itemsLite',
            ],
            [
            	'position' => 'cart.summary.items.async',
            	'hook' => 'sanatorium/discounts::hooks.itemsAsync',
            ],
        ];

        $manager = $this->app['sanatorium.hooks.manager'];

        foreach ($hooks as $item) {
        	extract($item);
            $manager->registerHook($position, $hook);
        }
	}

	/**
     * Prepare the package resources.
     *
     * @return void
     */
    protected function prepareResources()
    {
        $config = realpath(__DIR__.'/../../config/config.php');

        $this->mergeConfigFrom($config, 'sanatorium-discounts');

        $this->publishes([
            $config => config_path('sanatorium-discounts.php'),
        ], 'config');
    }

}
