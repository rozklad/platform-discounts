<?php namespace Sanatorium\Discounts\Controllers\Frontend;

use Platform\Foundation\Controllers\Controller;
use Cart;
use Sanatorium\Shoppricing\Models\Currency;
use Cartalyst\Conditions\Condition;

class VouchersController extends Controller {

	/**
	 * Return the main view.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/discounts::index');
	}

	public function apply()
	{
		$code = request()->get('voucher');

		$vouchers = app('sanatorium.discounts.voucher');

		// Find voucher where code is 
		$voucher = $vouchers->whereCode($code)->first();

		if ( !$voucher )
			return redirect()->back()->withErrors(['Voucher code is invalid']);

		if ( $voucher->limit < 1 )
			return redirect()->back()->withErrors(['Voucher code uses limit reached']);

		$voucher->limit = ($voucher->limit - 1);
	
		$voucher->save();

		// Remove all other discount conditions from cart
		Cart::removeConditionByType('discount');

		if ( $voucher->absolute != 0 )
			$this->applyAbsolute($voucher);

		if ( $voucher->percentage != 0 )
			$this->applyPercentage($voucher);

		if ( request()->ajax() )
			return ['success' => true];

		return redirect()->back();
	}

	public function applyAbsolute($voucher)
	{
		$currency = Currency::getActiveCurrency();

		/**
         * Transform currency
         */
        $active_currency = Currency::getActiveCurrency();
		
		if ( $active_currency->code != 'usd' ) {
			$voucher_amount = Currency::convert($voucher->absolute, 'usd', $active_currency->code);
		} else {
			$voucher_amount = $voucher->absolute;
		}

		// Die demo voucher
        $conditionDiscount = new Condition([
            'name'   => trans('sanatorium/discounts::vouchers/common.voucher.name_absolute', [
            	'absolute' => Currency::format($voucher_amount)
            ]),
            'type'   => 'discount',
            'target' => 'subtotal',
        ]);

        $conditionDiscount->setActions([

            [
                'value' => '-'.$voucher_amount,
            ],

        ]);

        Cart::condition($conditionDiscount);
	}

	public function applyPercentage($voucher)
	{
		// Die demo voucher
        $conditionDiscount = new Condition([
            'name'   => trans('sanatorium/discounts::vouchers/common.voucher.name_percentage', ['percentage' => $voucher->percentage]),
            'type'   => 'discount',
            'target' => 'subtotal',
        ]);

        $conditionDiscount->setActions([

            [
                'value' => '-'.$voucher->percentage.'%',
            ],

        ]);

        Cart::condition($conditionDiscount);
	}

}
