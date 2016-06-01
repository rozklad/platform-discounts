@if ( $conditions )
	@if ( isset($conditions['discount']) )
		@foreach( $conditions['discount'] as $name => $condition )
			<tr class="condition-row condition-row-discount">
				<td>
					<i class="fa fa-money fa-2x"></i>
				</td>
				<td>
					{{ $name }}
				</td>
				<td>
					{{ Sanatorium\Pricing\Models\Currency::format($condition) }}
				</td>
			</tr>
		@endforeach
	@endif
@endif