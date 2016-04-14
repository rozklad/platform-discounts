<?php namespace Sanatorium\Discounts\Widgets;

// written 6.2.2016 at Kastiel, Fricovce, Slovakia in Koder Code Editor mobile app
use Cart;
use Cartalyst\Conditions\Condition;
use Sanatorium\Shoppricing\Models\Currency;

class Hooks {

    /**
     * Display Enter discount code form
     * @return [type] [description]
     */
    public function voucher()
    {
        $conditions = Cart::conditionsTotal('discount');

        // Do not let user use multiple vouchers
        if ( $conditions && !config('sanatorium-discounts.multiple_vouchers') )
            return null;

        return view('sanatorium/discounts::hooks/voucher');
    }

    /**
     * Display Discounts in cart summary
     * @return [type] [description]
     */
    public function items($view = null)
    {
        $conditions = Cart::conditionsTotal();

        $currency = Currency::getActiveCurrency();

        return view( $view ? $view : 'sanatorium/discounts::hooks/items', compact('conditions', 'currency'));
    }

    /**
     * Display Discounts in cart summary lite
     * @return [type] [description]
     */
    public function itemsLite()
    {
        return $this->items('sanatorium/discounts::hooks/items_lite');
    }

    /**
     * Display Discounts in cart summary lite
     * @return [type] [description]
     */
    public function itemsAsync()
    {
        return $this->items('sanatorium/discounts::hooks/items_async');
    }

}
