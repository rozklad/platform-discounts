<?php namespace Sanatorium\Discounts\Controllers\Admin;

use Platform\Access\Controllers\AdminController;
use Sanatorium\Discounts\Repositories\Voucher\VoucherRepositoryInterface;

class VouchersController extends AdminController {

	/**
	 * {@inheritDoc}
	 */
	protected $csrfWhitelist = [
		'executeAction',
	];

	/**
	 * The Discounts repository.
	 *
	 * @var \Sanatorium\Discounts\Repositories\Voucher\VoucherRepositoryInterface
	 */
	protected $vouchers;

	/**
	 * Holds all the mass actions we can execute.
	 *
	 * @var array
	 */
	protected $actions = [
		'delete',
		'enable',
		'disable',
	];

	/**
	 * Constructor.
	 *
	 * @param  \Sanatorium\Discounts\Repositories\Voucher\VoucherRepositoryInterface  $vouchers
	 * @return void
	 */
	public function __construct(VoucherRepositoryInterface $vouchers)
	{
		parent::__construct();

		$this->vouchers = $vouchers;
	}

	/**
	 * Display a listing of voucher.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/discounts::vouchers.index');
	}

	/**
	 * Datasource for the voucher Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid()
	{
		$data = $this->vouchers->grid();

		$columns = [
			'id',
			'code',
			'limit',
			'absolute',
			'percentage',
			'created_at',
		];

		$settings = [
			'sort'      => 'created_at',
			'direction' => 'desc',
		];

		$transformer = function($element)
		{
			$element->edit_uri = route('admin.sanatorium.discounts.vouchers.edit', $element->id);

			return $element;
		};

		return datagrid($data, $columns, $settings, $transformer);
	}

	/**
	 * Show the form for creating new voucher.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return $this->showForm('create');
	}

	/**
	 * Handle posting of the form for creating new voucher.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating voucher.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function edit($id)
	{
		return $this->showForm('update', $id);
	}

	/**
	 * Handle posting of the form for updating voucher.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Remove the specified voucher.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		$type = $this->vouchers->delete($id) ? 'success' : 'error';

		$this->alerts->{$type}(
			trans("sanatorium/discounts::vouchers/message.{$type}.delete")
		);

		return redirect()->route('admin.sanatorium.discounts.vouchers.all');
	}

	/**
	 * Executes the mass action.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function executeAction()
	{
		$action = request()->input('action');

		if (in_array($action, $this->actions))
		{
			foreach (request()->input('rows', []) as $row)
			{
				$this->vouchers->{$action}($row);
			}

			return response('Success');
		}

		return response('Failed', 500);
	}

	/**
	 * Shows the form.
	 *
	 * @param  string  $mode
	 * @param  int  $id
	 * @return mixed
	 */
	protected function showForm($mode, $id = null)
	{
		// Do we have a voucher identifier?
		if (isset($id))
		{
			if ( ! $voucher = $this->vouchers->find($id))
			{
				$this->alerts->error(trans('sanatorium/discounts::vouchers/message.not_found', compact('id')));

				return redirect()->route('admin.sanatorium.discounts.vouchers.all');
			}
		}
		else
		{
			$voucher = $this->vouchers->createModel();
		}

		// Show the page
		return view('sanatorium/discounts::vouchers.form', compact('mode', 'voucher'));
	}

	/**
	 * Processes the form.
	 *
	 * @param  string  $mode
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected function processForm($mode, $id = null)
	{
		// Store the voucher
		list($messages) = $this->vouchers->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("sanatorium/discounts::vouchers/message.success.{$mode}"));

			return redirect()->route('admin.sanatorium.discounts.vouchers.all');
		}

		$this->alerts->error($messages, 'form');

		return redirect()->back()->withInput();
	}

}
