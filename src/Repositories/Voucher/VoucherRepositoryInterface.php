<?php namespace Sanatorium\Discounts\Repositories\Voucher;

interface VoucherRepositoryInterface {

	/**
	 * Returns a dataset compatible with data grid.
	 *
	 * @return \Sanatorium\Discounts\Models\Voucher
	 */
	public function grid();

	/**
	 * Returns all the discounts entries.
	 *
	 * @return \Sanatorium\Discounts\Models\Voucher
	 */
	public function findAll();

	/**
	 * Returns a discounts entry by its primary key.
	 *
	 * @param  int  $id
	 * @return \Sanatorium\Discounts\Models\Voucher
	 */
	public function find($id);

	/**
	 * Determines if the given discounts is valid for creation.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Support\MessageBag
	 */
	public function validForCreation(array $data);

	/**
	 * Determines if the given discounts is valid for update.
	 *
	 * @param  int  $id
	 * @param  array  $data
	 * @return \Illuminate\Support\MessageBag
	 */
	public function validForUpdate($id, array $data);

	/**
	 * Creates or updates the given discounts.
	 *
	 * @param  int  $id
	 * @param  array  $input
	 * @return bool|array
	 */
	public function store($id, array $input);

	/**
	 * Creates a discounts entry with the given data.
	 *
	 * @param  array  $data
	 * @return \Sanatorium\Discounts\Models\Voucher
	 */
	public function create(array $data);

	/**
	 * Updates the discounts entry with the given data.
	 *
	 * @param  int  $id
	 * @param  array  $data
	 * @return \Sanatorium\Discounts\Models\Voucher
	 */
	public function update($id, array $data);

	/**
	 * Deletes the discounts entry.
	 *
	 * @param  int  $id
	 * @return bool
	 */
	public function delete($id);

}
