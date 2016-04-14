@section('scripts')
@parent
<script type="text/javascript">

$(function(){
	$('[data-discount-apply-route]').click(function(event){
		event.preventDefault();

		var route = $(this).data('discount-apply-route'),
			code = $('[data-discount-code]').val();

		$.ajax({
			url: route,
			type: 'POST',
			data: {voucher: code}
		}).success(function(data){
			location.reload();
		});
	});
});

</script>
@stop


	<div class="panel panel-default cart-panel cart-part">

		<header class="panel-heading">
			
			{{ trans('discounts') }}

		</header>

		<div class="well well-voucher">
			
			<div class="row">
				<div class="col-sm-6">
					<label for="voucher" class="control-label">
						{{ trans('discounts') }}
					</label>
				</div>
				<div class="col-sm-4 text-right">
					<input type="text" name="voucher" id="voucher" class="form-control" placeholder="{{ trans('discounts') }}" data-discount-code>
				</div>
				<div class="col-sm-2">
					<button type="submit" class="btn btn-primary" data-discount-apply-route="{{ route('discounts') }}">
						{{ trans('discounts') }}
					</button>
				</div>
			</div>

		</div>

	</div>
