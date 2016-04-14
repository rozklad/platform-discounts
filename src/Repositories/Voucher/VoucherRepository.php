<?php namespace Sanatorium\Discounts\Repositories\Voucher;

use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;

class VoucherRepository implements VoucherRepositoryInterface {

	use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

	/**
	 * The Data handler.
	 *
	 * @var \Sanatorium\Discounts\Handlers\Voucher\VoucherDataHandlerInterface
	 */
	protected $data;

	/**
	 * The Eloquent discounts model.
	 *
	 * @var string
	 */
	protected $model;

	/**
	 * Constructor.
	 *
	 * @param  \Illuminate\Container\Container  $app
	 * @return void
	 */
	public function __construct(Container $app)
	{
		$this->setContainer($app);

		$this->setDispatcher($app['events']);

		$this->data = $app['sanatorium.discounts.voucher.handler.data'];

		$this->setValidator($app['sanatorium.discounts.voucher.validator']);

		$this->setModel(get_class($app['Sanatorium\Discounts\Models\Voucher']));
	}

	/**
	 * {@inheritDoc}
	 */
	public function grid()
	{
		return $this
			->createModel();
	}

	/**
	 * {@inheritDoc}
	 */
	public function findAll()
	{
		return $this->container['cache']->rememberForever('sanatorium.discounts.voucher.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('sanatorium.discounts.voucher.'.$id, function() use ($id)
		{
			return $this->createModel()->find($id);
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function validForCreation(array $input)
	{
		return $this->validator->on('create')->validate($input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function validForUpdate($id, array $input)
	{
		return $this->validator->on('update')->validate($input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function store($id, array $input)
	{
		return ! $id ? $this->create($input) : $this->update($id, $input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function create(array $input)
	{
		// Create a new voucher
		$voucher = $this->createModel();

		// Fire the 'sanatorium.discounts.voucher.creating' event
		if ($this->fireEvent('sanatorium.discounts.voucher.creating', [ $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForCreation($data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Save the voucher
			$voucher->fill($data)->save();

			// Fire the 'sanatorium.discounts.voucher.created' event
			$this->fireEvent('sanatorium.discounts.voucher.created', [ $voucher ]);
		}

		return [ $messages, $voucher ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the voucher object
		$voucher = $this->find($id);

		// Fire the 'sanatorium.discounts.voucher.updating' event
		if ($this->fireEvent('sanatorium.discounts.voucher.updating', [ $voucher, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($voucher, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Update the voucher
			$voucher->fill($data)->save();

			// Fire the 'sanatorium.discounts.voucher.updated' event
			$this->fireEvent('sanatorium.discounts.voucher.updated', [ $voucher ]);
		}

		return [ $messages, $voucher ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the voucher exists
		if ($voucher = $this->find($id))
		{
			// Fire the 'sanatorium.discounts.voucher.deleted' event
			$this->fireEvent('sanatorium.discounts.voucher.deleted', [ $voucher ]);

			// Delete the voucher entry
			$voucher->delete();

			return true;
		}

		return false;
	}

	/**
	 * {@inheritDoc}
	 */
	public function enable($id)
	{
		$this->validator->bypass();

		return $this->update($id, [ 'enabled' => true ]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function disable($id)
	{
		$this->validator->bypass();

		return $this->update($id, [ 'enabled' => false ]);
	}

}
