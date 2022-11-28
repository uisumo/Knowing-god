<?php
$menu = Corcel\Model\Menu::slug('primary-menu')->first();
$main_menu = array();
$sub_menu_items = array();
if ( $menu ) {
	foreach ($menu->items as $item ) {
		if ( $item->meta->_menu_item_menu_item_parent == 0 ) {
			$main_menu[] = $item;
			foreach ($menu->items as $sub_item ) {
				if ( $item->ID == $sub_item->meta->_menu_item_menu_item_parent ) {
					$sub_menu_items[ $item->ID ][] = $sub_item;
				}
			}
		}
	}
}
// dd( $sub_menu_items );
?>
<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container">
        <a class="navbar-brand" href="/"><img src="<?php echo IMAGES; ?>logo.svg" width="145" height="30"></a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          @if ( ! empty( $main_menu ) )
		  <ul class="navbar-nav mr-auto">
			@foreach( $main_menu as $menu )
				<li class="nav-item dropdown">
				  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownPortfolio" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				  {!! $menu->instance()->title !!}
				  </a>
				  @if ( ! empty( $sub_menu_items[ $menu->ID ] ) )
				  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownPortfolio">
					@foreach( $sub_menu_items[ $menu->ID ] as $sub )
						<?php
						$url = HOST . $sub->instance()->slug;
						if ( ! empty( $sub->meta->_menu_item_url ) ) {
							$url = $sub->meta->_menu_item_url;
						}
						?>
						<a class="dropdown-item" href="<?php echo $url; ?>">{!! $sub->instance()->title !!}</a>
					@endforeach
				  </div>
				  @endif
				</li>
			@endforeach
		  </ul>
		  @endif
		  		 

            <ul class="navbar-nav">
            @if(Auth::check())
                @include('layouts.student.user-menu')
            @else
            <li class="nav-item"><a style="color:#aaa;" class="nav-link" href="<?php echo URL_USERS_LOGIN; ?>">Login</a></li>
            <li class="nav-item"><a style="color:#aaa;" class="nav-link" href="<?php echo URL_USERS_REGISTER; ?>">Register</a></li>
            @endif
            </ul>
         </div>
      </div>
    </nav>
