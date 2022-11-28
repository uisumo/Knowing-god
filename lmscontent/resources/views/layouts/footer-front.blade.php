<div class="footer mb-3" style="background-color:black; border-bottom: 1px solid grey;">
	<div class="container">
		<div class="row mt-2">
			<div id="text-1" class="col-sm-6 col-md-3 widget widget_text"><h6 class="text-center pathway_green pb-2 widget-title">{{getPhrase('our_mandate')}}</h6>			<div class="textwidget"><p>{{getPhrase('our_mandate_description')}}</p>
			</div>
			</div>
		
		<?php
		$pathway = Corcel\Model\Menu::slug('pathway')->first();
		if ( $pathway ) {
		?>
		<div id="nav_menu-1" class="col-sm-6 col-md-3 widget widget_nav_menu"><h6 class="text-center pathway_green pb-2 widget-title">{{getPhrase('pathway')}}</h6>
		
		<div class="menu-pathway-container"><ul id="menu-pathway" class="menu">
		@foreach ($pathway->items as $item )
		<?php
		$url = HOST . $item->instance()->slug;
		if ( ! empty( $item->meta->_menu_item_url ) ) {
			$url = $item->meta->_menu_item_url;
		}
		?>
		<li id="menu-item-{{$item->ID}}" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-{{$item->ID}}"><a href="{{$url}}">{!! $item->instance()->title !!}</a></li>
		@endforeach		
		</ul>
		</div>
		
		</div>
		<?php } ?>
		
		<?php
		$trending = Corcel\Model\Menu::slug('trending')->first();
		if ( $trending ) {
		?>
		<div id="nav_menu-2" class="col-sm-6 col-md-3 widget widget_nav_menu"><h6 class="text-center pathway_green pb-2 widget-title">{{getPhrase('trending')}}</h6>
			<div class="menu-trending-container">
				<ul id="menu-trending" class="menu">
				@foreach ($trending->items as $item )
				<?php
				$url = HOST . $item->instance()->slug;
				if ( ! empty( $item->meta->_menu_item_url ) ) {
					$url = $item->meta->_menu_item_url;
				}
				?>
				<li id="menu-item-{{$item->ID}}" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-{{$item->ID}}"><a href="{{$url}}">{!! $item->instance()->title !!}</a></li>
				@endforeach
				</ul>
			</div>
		</div>
		<?php } ?>
		
		<?php
		$get_in_touch = Corcel\Model\Menu::slug('get-in-touch')->first();
		if ( $get_in_touch ) {
		?>
		<div id="nav_menu-3" class="col-sm-6 col-md-3 widget widget_nav_menu"><h6 class="text-center pathway_green pb-2 widget-title">{{getPhrase('get_in_touch')}}!</h6>
			<div class="menu-get-in-touch-container">
				<ul id="menu-get-in-touch" class="menu">
				@foreach ($get_in_touch->items as $item )
				<?php
				$url = HOST . $item->instance()->slug;
				if ( ! empty( $item->meta->_menu_item_url ) ) {
					$url = $item->meta->_menu_item_url;
				}
				?>
				<li id="menu-item-{{$item->ID}}" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-{{$item->ID}}">
				@if ( $item->instance()->title == 'Report a Problem' )
					<a href="#siteissuesModal" data-toggle="modal">{{getPhrase('report_a_problem')}}</a>
				@else
				<a href="{{$url}}">{!! $item->instance()->title !!}</a>
				@endif
			</li>
				@endforeach
				</ul>
			</div>
		</div>
		<?php } ?>
	</div>
	</div>
</div>

<footer class="footer-bottom py-1 bg-dark">
	<div class="container">
		<div class="row">
			
			<?php
			$footer_menu = Corcel\Model\Menu::slug('footer-menu')->first();
			if ( $footer_menu ) {
			?>
			<div class="col-md-4 mt-1 ml-auto">
			<ul id="menu-footer-menu" class="m-0 text-center">
			@foreach ($footer_menu->items as $item )
				<?php
				$url = HOST . $item->instance()->slug;
				if ( ! empty( $item->meta->_menu_item_url ) ) {
					$url = $item->meta->_menu_item_url;
				}
				?>
				<li id="menu-item-{{$item->ID}}" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-{{$item->ID}}"><a href="{{$url}}">{!! $item->instance()->title !!}</a></li>
			@endforeach
			</ul>				
			</div>	
			<?php } ?>
			
			<div class="col-md-4">
			<p class="mt-1 mb-0" style="color:#888; text-align:center;">
			<?php
			$theme_options = Corcel\Model\Option::get( 'theme_mods_knowing-god' );

			if ( empty( $theme_options['footer-credits'] ) ) {
			$footer_credits = sprintf( '%s &copy; All Rights Reserved', date( 'Y' ) );
			} else {
			$footer_credits = $theme_options['footer-credits'];
			}
			?>
			{{$footer_credits}}</p>
			</div>
			
			<?php if ( ! empty( $theme_options['footer-socialicons-show-hide'] ) && $theme_options['footer-socialicons-show-hide'] == 'show' ) { ?>				
			<div class="col-md-4 mt-1">
				<p class="text-center">
				<?php if ( ! empty( $theme_options['knowing_god_facebook'] ) ) { ?>
				<a href="{{$theme_options['knowing_god_facebook']}}" target="_blank" class="mr-2"><i class="fa fa-facebook"></i></a>
				<?php } ?>
				
				<?php if ( ! empty( $theme_options['knowing_god_linkedin'] ) ) { ?>
				<a href="{{$theme_options['knowing_god_linkedin']}}" target="_blank" class="mr-2"><i class="fa fa-linkedin"></i></a>
				<?php } ?>
				
				<?php if ( ! empty( $theme_options['knowing_god_pinterest'] ) ) { ?>
				<a href="{{$theme_options['knowing_god_pinterest']}}" target="_blank" class="mr-2"><i class="fa fa-pinterest"></i></a>
				<?php } ?>
				
				<?php if ( ! empty( $theme_options['knowing_god_skype'] ) ) { ?>
				<a href="{{$theme_options['knowing_god_skype']}}" target="_blank" class="mr-2"><i class="fa fa-skype"></i></a>
				<?php } ?>
				
				<?php if ( ! empty( $theme_options['knowing_god_twitter'] ) ) { ?>
				<a href="{{$theme_options['knowing_god_twitter']}}" target="_blank" class="mr-2"><i class="fa fa-twitter"></i></a>
				<?php } ?>
				</p>
			</div>
			<?php } ?>
			
			</div><!--terms of use etc and social links-->
	</div>
</footer>

<!-- Modal -->
<div class="modal fade" id="siteissuesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">{{getPhrase('Enter Issue Description')}}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			{!! Form::open(array('url' => '', 'method' => 'POST', 'novalidate'=>'','name'=>'formComments')) !!}
			<div class="modal-body">						
				<fieldset class="form-group">
				<?php
				$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				?>
				{{ Form::text('issue_url', $value = $actual_link , $attributes = array('class'=>'form-control', 'placeholder' => 'Enter URL where you find issue',
				'id' => 'issue_url',
						'required'=> 'true',
						)) }}
				</fieldset>
				@if( ! Auth::check() )
					<fieldset class="form-group">
						{{ Form::label('full_name', getphrase('full_name')) }}
						<span class="text-red">*</span>
						{{ Form::text('full_name', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => 'Jack',
							'ng-model'=>'full_name',
							'required'=> 'true', 
							'ng-pattern' => getRegexPattern("name"),
							'ng-minlength' => '2',
							'ng-maxlength' => '60',
							'ng-class'=>'{"has-error": formUsers.full_name.$touched && formUsers.full_name.$invalid}',
						)) }}
						<div class="validation-error" ng-messages="formUsers.full_name.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('minlength')!!}
	    					{!! getValidationMessage('maxlength')!!}
	    					{!! getValidationMessage('pattern')!!}
						</div>
					</fieldset>
					<fieldset class="form-group">
						{{ Form::label('email', getphrase('email')) }}
						<span class="text-red">*</span>
						{{ Form::text('email', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => 'email@gmail.com',
							'ng-model'=>'email',
							'id' => 'email',
							'required'=> 'true',
							'ng-class'=>'{"has-error": formUsers.email.$touched && formUsers.email.$invalid}',
						)) }}
						<div class="validation-error" ng-messages="formUsers.email.$error" >
	    					{!! getValidationMessage()!!}
						</div>
					</fieldset>
				@endif
				<fieldset class="form-group">
				{{ Form::textarea('issue_description', $value = null , $attributes = array('class'=>'form-control', 'ng-model' => 'issue_description', 'id' => 'issue_description', 'rows'=>'5', 'placeholder' => getPhrase('Enter your description here'))) }}
				</fieldset>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">{{getPhrase('Close')}}</button>
				<button type="button" class="btn btn-primary" onclick="saveIssue()">{{getPhrase('submit_issue')}}</button>
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>
<!-- /Modal -->