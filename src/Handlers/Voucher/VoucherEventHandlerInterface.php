<?php namespace Sanatorium\Discounts\Handlers\Voucher;

use Sanatorium\Discounts\Models\Voucher;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface VoucherEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a voucher is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a voucher is created.
	 *
	 * @param  \Sanatorium\Discounts\Models\Voucher  $voucher
	 * @return mixed
	 */
	public function created(Voucher $voucher);

	/**
	 * When a voucher is being updated.
	 *
	 * @param  \Sanatorium\Discounts\Models\Voucher  $voucher
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Voucher $voucher, array $data);

	/**
	 * When a voucher is updated.
	 *
	 * @param  \Sanatorium\Discounts\Models\Voucher  $voucher
	 * @return mixed
	 */
	public function updated(Voucher $voucher);

	/**
	 * When a voucher is deleted.
	 *
	 * @param  \Sanatorium\Discounts\Models\Voucher  $voucher
	 * @return mixed
	 */
	public function deleted(Voucher $voucher);

}
