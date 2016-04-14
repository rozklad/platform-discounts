<?php namespace Sanatorium\Discounts\Handlers\Voucher;

use Illuminate\Events\Dispatcher;
use Sanatorium\Discounts\Models\Voucher;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class VoucherEventHandler extends BaseEventHandler implements VoucherEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('sanatorium.discounts.voucher.creating', __CLASS__.'@creating');
		$dispatcher->listen('sanatorium.discounts.voucher.created', __CLASS__.'@created');

		$dispatcher->listen('sanatorium.discounts.voucher.updating', __CLASS__.'@updating');
		$dispatcher->listen('sanatorium.discounts.voucher.updated', __CLASS__.'@updated');

		$dispatcher->listen('sanatorium.discounts.voucher.deleted', __CLASS__.'@deleted');
	}

	/**
	 * {@inheritDoc}
	 */
	public function creating(array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function created(Voucher $voucher)
	{
		$this->flushCache($voucher);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(Voucher $voucher, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(Voucher $voucher)
	{
		$this->flushCache($voucher);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(Voucher $voucher)
	{
		$this->flushCache($voucher);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \Sanatorium\Discounts\Models\Voucher  $voucher
	 * @return void
	 */
	protected function flushCache(Voucher $voucher)
	{
		$this->app['cache']->forget('sanatorium.discounts.voucher.all');

		$this->app['cache']->forget('sanatorium.discounts.voucher.'.$voucher->id);
	}

}
