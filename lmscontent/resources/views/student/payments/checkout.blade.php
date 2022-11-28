@extends('layouts.lmsgroups-add-content-layout')

@section('content')
<div id="page-wrapper" ng-init="intilizeData({{$item}})" ng-controller="couponsController">

{!! Form::open(array('url' => URL_PAYNOW.$item->slug, 'method' => 'POST', 'id'=>'payform')) !!}
<input type="hidden" name="item_name" id="item_name" ng-model="item_name" value="{{$item->slug}}">
<input type="hidden" name="gateway" id="gateway" value="" >
<input type="hidden" name="type" ng-model="item_type" value="{{$item_type}}" >
<input type="hidden" name="is_coupon_applied" id="is_coupon_applied"  value="0" >
<input type="hidden" name="coupon_id" id="coupon_id"  value="0" >
<input type="hidden" name="actual_cost" id="actual_cost" value="{{$item->cost}}" >
<input type="hidden" name="discount_availed" id="discount_availed"  value="0" >
<input type="hidden" name="after_discount" id="after_discount" value="{{$item->cost}}" >
<input type="hidden" name="parent_user" value="{{$parent_user}}">
<?php
$selected_child_id = 0;
if($parent_user) {
if(count($children))
{
$selected_child_id = $children[0]->id;
}
}
?>
<input type="hidden" name="parent_user" value="{{$parent_user}}">
<input type="hidden" id="selected_child_id" name="selected_child_id" value="{{$selected_child_id}}">
{!! Form::close() !!}

    <div class="container-fluid">
		<!-- Page Heading -->
		<div class="row mt-100 mt-r100">
			<div class="col-lg-12">
				<ol class="breadcrumb mt-2">
					<li class="breadcrumb-item"> <a href="{{URL_USERS_DASHBOARD}}"><?php echo getPhrase( 'Dashboard' ); ?></a> </li>

					<li class="breadcrumb-item"><a href="{{URL_ADMIN_ALL_LMSGROUPS}}">{{ getPhrase('groups')}}</a> </li>

					<li class="breadcrumb-item active">{{isset($title) ? $title : ''}}</li>
				</ol>
			</div>
        </div>

                    @include('errors.errors')

                <?php $settings = ($record) ? $settings : ''; ?>

                <div class="expand-card card-normal mt-3 ">

                    <div class="card datatable-card">
                    <div class="card-header">                        
                        <h4 class="mb-0 mt-3">{{ $title }} </h4>
                    </div>

                    <div class="card-body">
						<?php $button_name = getPhrase('create'); ?>
						<div class="row">
                        <?php $lmssettings = getSettings('lms');?>
                        <div class="col-md-12">

                            <div class="vertical-scroll" >

<div class="table-responsive">
                                <table class="table table-hover">
                                    <th>{{getPhrase('item')}}</th>
                                    <th>{{getPhrase('cost')}}</th>
                                    <th>{{getPhrase('total')}}</th>
                                    <tr>
                                        <td>
										<?php if($item_type=='combo' || $item_type=='lms')	{
										$image = IMAGE_PATH_UPLOAD_LMS_DEFAULT;
										if($item->image)
											$image = IMAGE_PATH_UPLOAD_SERIES_THUMB.$item->image;
										$image_path = $image;
										if($item_type=='lms') {
											if($item->image)
										$image_path = IMAGE_PATH_UPLOAD_LMS_SERIES_THUMB.$item->image;
									}
									?>
									<i class="icon"><img class="icon-images" src="{{$image_path}}" alt="{{$item->title}}" height="70" width="70" ></i>
									<?php } ?>
									<h3>{{$item->title}}</h3>
                                    </td>
                                    <td><strong>{{ getCurrencyCode().$item->cost }}</strong></td>
									<td>{{ getCurrencyCode().$item->cost }}</td>
                                    </tr>

                                </table>
                                    </div>
                                </div>
                                 </div>
                        </div>

						<?php

								$is_eligible_for_payment = TRUE;

								if($parent_user) {

									if(!count($children))

										$is_eligible_for_payment = FALSE;

								}



						?>
						@if($is_eligible_for_payment)
						<div class="row">
							<div class="col-md-12 text-center">
								<div class="payment-type">
									<div class="text-center">
									<?php
									$payu = getSetting('payu', 'module');
									$paypal = getSetting('paypal', 'module');
									$offline = getSetting('offline_payment', 'module');
									
									$payu = $offline = 0; // No need for this application.
									if($payu == '1') {
									?>
									<button type="submit" onclick="submitForm('payu');"  class="btn-lg btn button btn-card"><i class=" icon-credit-card"></i> {{getPhrase('payu')}}</button>
									<?php }
									if($paypal=='1') {
									?>
									<button type="submit" class="btn-lg btn button btn-paypal" onclick="submitForm('paypal');"><i class="icon-paypal"></i> {{getPhrase('paypal')}}</button>
									<?php }
									if($offline=='1') {
									?>
									<button type="submit" class="btn-lg btn button btn-info" onclick="submitForm('offline');" data-toggle="tooltip" data-placement="right" title="{{ getPhrase('click_here_to_update_payment_details') }}"><i class="fa fa-money" ></i> {{getPhrase('offline_payment')}}</button>
									<?php } ?>
									</div>
								</div>
							</div>
						</div>
					@endif
                    </div>



                </div>
                </div>

            </div>

            <!-- /.container-fluid -->

        </div>

        <!-- /#page-wrapper -->
<script type="text/javascript">
function submitForm(gatewayType) {
	$('#gateway').val(gatewayType);
	$('#payform').submit();
}
</script>

@stop
@section('footer_scripts')
    @include('coupons.scripts.js-scripts', array('item'=>$item))

	@include('common.alertify')
@stop
