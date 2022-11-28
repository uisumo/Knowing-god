<?php
$search_action = URL_GLOBAL_SEARCH;
if ( ! empty( $is_search_form ) ) {
	if ( ! empty( $query_string ) ) {
		$search_action = LMS_HOST . 'global-search'. $query_string;
	} else {
		$search_action = LMS_HOST . 'global-search';
	}
	
}
?>
<form method="get" action="{{$search_action}}">
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