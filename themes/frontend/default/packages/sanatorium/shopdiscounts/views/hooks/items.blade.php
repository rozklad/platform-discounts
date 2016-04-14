@if ( $conditions )
	@if ( isset($conditions['discount']) )
		@foreach( $conditions['discount'] as $name => $condition )
			<tr class="condition-row condition-row-discount">
				<td class="summary-col-image text-center">
					<i class="fa fa-money fa-2x"></i>
				</td>
				<td class="col-xs-3 col-sm-4 summary-col-name">
					{{ $name }}
				</td>
				<td class="col-xs-1 summary-col-quantity">

				</td>
				<td class="text-right summary-col-price_single_vat">

				</td>
				<td class="text-right summary-col-price_vat">
					{{ Sanatorium\Shoppricing\Models\Currency::format($condition) }}
				</td>
				<td>

				</td>
			</tr>
		@endforeach
	@endif
@endif