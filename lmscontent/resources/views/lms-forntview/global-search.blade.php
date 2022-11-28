@extends('layouts.student.right-menu-layout')
@section('content')
<h2 class="mt-4 mb-3">{{$title}}
@if ( ! empty( $search_string ) )
	<i>{{$search_string}}</i>
@endif
</h2>
<!-- Page Heading/Breadcrumbs -->
<ol class="breadcrumb mt-3">
	@if( Auth::check() )
	<li class="breadcrumb-item"> <a href="{{URL_USERS_DASHBOARD_USER}}"><?php echo getPhrase( 'Dashboard' ); ?></a> </li>
	@endif
	
	<li class="breadcrumb-item"><strong class="text-green">{{$title}} 
		@if ( $remove_search_type )
			{!! $remove_search_type !!} | 
		@endif
		@if ( $remove_search_term )
			{!!$remove_search_term!!} | 
		@endif
		@if ( $remove_search_posts )
			{!!$remove_search_posts!!} | 
		@endif
		@if ( $remove_search_articles )
			{!!$remove_search_articles!!} | 
		@endif
		@if ( $remove_search_courses )
			{!!$remove_search_courses!!} | 
		@endif
	
	</strong></li>
</ol>
<!-- Intro Content -->
<?php /* ?>
<div class="row mt-2">
	<div class="col-sm-12">
	<form method="get" action="{{URL_GLOBAL_SEARCH}}">
	<div class="input-group kg-advanced-search">
  <input type="text" class="form-control" placeholder="Search …" aria-label="Search …" aria-describedby="basic-addon2" name="s" value="<?php echo $search_term; ?>">
   <div class="btn-group">
    <button type="button" class="  kg-filter-dropdown-arrow dropdown-toggle dropdown-toggle-split" data-toggle="dropdown">
      <span class="caret"></span>
    </button>
    <div class="dropdown-menu kg-filter-dropdown-menu">
     <div class="form-check">
      <label class="form-check-label">
        <input type="checkbox" class="form-check-input" value="true" name="posts" <?php if ( $is_posts ) echo ' checked';?>>Posts
      </label>
    </div>
    <div class="form-check">
      <label class="form-check-label">
        <input type="checkbox" class="form-check-input" value="true" name="courses" <?php if ( $is_courses ) echo ' checked';?>>Courses
      </label>
    </div>
    <div class="form-check ">
      <label class="form-check-label">
        <input type="checkbox" class="form-check-input" value="true" name="articles" <?php if ( $is_articles ) echo ' checked';?>>Articles
      </label>
    </div>
    </div>
     <button type="submit" class="btn btn-secondary">Go !</button>
  </div>
	</div>
	</form>
</div>
</div>
<?php */ 

?>

<div class="row mt-4">
	<div class="col-sm-12">
	@if ( $search_results && $search_results->count() > 0 ) 
	@foreach($search_results as $item)
<?php if ( $item instanceof App\LmsSeries ) { 
$link = URL_FRONTEND_LMSSERIES . $item->slug;
$date_format = Corcel\Model\Option::get('date_format');
$lessons_statistics = lessons_statistics( $item->id );
// dd( $item );
?>
<div id="post-{{$item->id}}" class="card mb-4">
	
	<div class="card-body">
		<h2 class="entry-title"><a href="{{$link}}" rel="bookmark">{{$item->title}}</a></h2>
		<div class="card-text"><p>{!! $item->short_description !!}</p></div>
		<div class="read"><a class="btn btn-info" href="{{$link}}" title="{{knowing_god_esc_attr( $item->title )}}">{{getPhrase('Read More')}}&rarr;</a></div>
	</div>
		<div class="card-footer text-muted download-link">
				<div>
		<ul class="card-icons">
			<?php
			$author_link = URL_GLOBAL_SEARCH . '/author/' . $item->username;			
			?>
			<li><i class="fa fa-user-circle pathway_gray"></i> <a class="pathway_gray" href="{{$author_link}}">{{$item->author}}</a></li>
			<li><i class="fa fa-clock-o pathway_gray"></i> <a href="{{$link}}" rel="bookmark" class="pathway_gray"><time class="entry-date published">{{date($date_format, strtotime($item->created_at))}}</time></a></li>
			<?php
			$category_link = URL_GLOBAL_SEARCH . '/category/' . $item->category_slug;
			?>
			<li><i class="fa fa-folder-open pathway_gray"></i> <a href="{{$category_link}}" rel="category tag">{{$item->category}}</a></li>
			<li><i class="fa fa-files-o pathway_gray"></i> <a href="{{$link}}" class="series-18" title="{{knowing_god_esc_attr( $item->title )}}">{{getPhrase('Lessons')}}</a>&nbsp;<span class="badge badge-dark tags">{{$lessons_statistics['total_lessons']}}</span></li>
								</ul>
		</div>
			</div>

		</div>
<?php
} else {
	$taxonomies = $item->taxonomies()->get();
	$categories = $series = 0;
	if ( $taxonomies ) {
		foreach( $taxonomies as $taxonomy ) {
			if ( $taxonomy->taxonomy == 'category' ) {
				$categories++;
			}
			if ( $taxonomy->taxonomy == 'series' ) {
				$series++;
			}
		}
	}
	
	$link = HOST . $item->slug;
?>
		<div id="post-{{$item->ID}}" class="card mb-4 post-{{$item->ID}}">
	
	<div class="card-body">
		<h2 class="entry-title"><a href="{{$link}}" rel="bookmark">{{$item->post_title}}</a></h2>
		<div class="card-text"><p>
			@if ( $item->excerpt )
				{{$item->excerpt}}
			@else
				{{knowing_god_get_excerpt( $item->content )}}
			@endif
		</p></div>
		<div class="read"><a class="btn btn-info" href="{{$link}}" title="{{knowing_god_esc_attr( $item->post_title )}}">{{getPhrase('Read More')}}&rarr;</a></div>
	</div>
		<div class="card-footer text-muted download-link">
				<div>
		<?php
		$author = $item->author;
		$date_format = Corcel\Model\Option::get('date_format');
		?>
		<ul class="card-icons">
			<?php
			// $author_link = HOST . '/author/' . $author->user_nicename;
			$author_link = URL_GLOBAL_SEARCH . '/author/' . $author->user_login;			
			?>
			<li><i class="fa fa-user-circle pathway_gray"></i> <a class="pathway_gray" href="{{$author_link}}">{{$author->display_name}}</a></li>
			<li><i class="fa fa-clock-o pathway_gray"></i> <a href="{{$link}}" rel="bookmark" class="pathway_gray"><time class="entry-date published">{{date($date_format, strtotime($item->post_date))}}</time></a></li>
			<?php if ( $categories > 0 ) : ?>
			<li><i class="fa fa-folder-open pathway_gray"></i>
			<?php
			$counter = 0;
			foreach( $taxonomies as $taxonomy ) {
				if ( $taxonomy->taxonomy == 'category' ) {
					if ( $counter > 0 ) {
						echo ', ';
					}
					// $category_link = HOST . '/category/' . $taxonomy->slug;
					$category_link = URL_GLOBAL_SEARCH . '/category/' . $taxonomy->slug;
					?>
					<a href="{{$category_link}}" rel="category tag"><?php echo $taxonomy->name; ?></a>
					<?php
					$counter++;
				}
			}
			?>
			 </li>
			<?php endif;
			if ( $series > 0 ) :
			$counter = 0;
			foreach( $taxonomies as $taxonomy ) {
				if ( $taxonomy->taxonomy == 'series' ) {
					
					if ( $counter > 0 ) {
						echo ', ';
					}
			?>
			<li><i class="fa fa-files-o pathway_gray"></i> <a href="{{HOST}}series/{{$taxonomy->slug}}" class="series-18" title="{{ strip_tags( $taxonomy->name )}}">{!! $taxonomy->name !!}</a>&nbsp;<span class="badge badge-dark tags">{{$taxonomy->count}}</span></li>
			<?php
			$counter++;
				}
			}
			else :
			?>
			<li><i class="fa fa-files-o pathway_gray"></i> {{getPhrase('No Series')}}
			<?php
			endif; ?>
								</ul>
		</div>
			</div>

		</div>
<?php } ?>
	@endforeach
	
	@elseif ( $search_results && $search_results->count() == 0 )
		<div class="col-sm-12 text-center"><div class="oops-msg">Ooops...! {{getPhrase('No_courses_available')}}</div></div>
	@else
		<div class="col-sm-12 text-center"><div class="oops-msg">Ooops...! {{getPhrase('No_courses_available')}}</div></div>
	@endif
	</div>
</div>
@if ( $search_results )
<div class="row">
        <div class="col-sm-12">
        <div class="custom-pagination">
            {!! $search_results->links() !!}
        </div>
        </div>
    </div>
@endif
@stop
@section('footer_scripts')
	@include('common.validations')
	@include('lms-forntview.scripts.js-scripts')
@stop