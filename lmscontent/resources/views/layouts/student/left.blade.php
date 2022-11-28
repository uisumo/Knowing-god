<?php
$category   = App\QuizCategory::select(['quizcategories.*'])->join('lmsseries', 'lmsseries.lms_category_id', '=', 'quizcategories.id')
->where( 'lmsseries.total_items', '>', '0' )
->inRandomOrder()->first();
$left_serieses   = App\LmsSeries::join('lmsseries_data AS lsd', 'lsd.lmsseries_id', '=', 'lmsseries.id')->where('lms_category_id','=',$category->id)->where( 'lmsseries.total_items', '>', '0' )->groupBy( 'lsd.lmsseries_id' )->inRandomOrder()->limit(4)->get();
if ( ! empty( $left_serieses ) ) {
	$cols = 3;
	$current_layout = DB::table( 'lmsmode' )->first();
	if ( ! $current_layout ) {
		$current_layout = 'bothsidebars';
	} else {
		$current_layout = $current_layout->layout;
	}
	if ( 'bothsidebars' === $current_layout ) {
		$cols = 2;
	}
?>
<div class="col-md-{{$cols}}">
    <div class="sidebar-left">
    <h3>{{$category->category}}</h3>
    @foreach( $left_serieses as $left_series )
    <div class="card mb-4">
      <a href="{{URL_FRONTEND_LMSLESSON . $left_series->slug}}">
        @if($left_series->image!='')
        <img class="card-img-top" src="{{IMAGE_PATH_UPLOAD_LMS_SERIES.$left_series->image}}" alt="">
        @else
        <img class="card-img-top" src="{{IMAGE_PATH_UPLOAD_LMS_DEFAULT}}" alt="">
        @endif
      <?php /* ?>
      <img class="card-img-top" src="http://108.61.222.129/img/homepage/c-mtn.jpg" alt="">
      <?php */ ?>
      </a>
      <div class="card-body">
          <h4 class="card-title">
          <a  href="{{URL_FRONTEND_LMSLESSON . $left_series->slug}}">{{$left_series->title}}</a>
          </h4>
          <p style="font-size:.9rem;" class="card-text">
          {!! $left_series->short_description !!}</p>
      </div>
  </div>
  @endforeach
  <?php /* ?>
  <div class="card mb-4">
      <a href="#"><img class="card-img-top" src="http://108.61.222.129/img/homepage/bw-lonely.jpg" alt=""></a>
      <div class="card-body">
          <h4 class="card-title">
          <a  href="#">Loneliness</a>
          </h4>
          <p style="font-size:.9rem;" class="card-text">PathwayPLus is all about giving you the resources, encouragement and training to honor God where ever you are.</p>
      </div>
  </div>
  <div class="card mb-4">
      <a href="#"><img class="card-img-top" src="http://108.61.222.129/img/homepage/pathwayforevereditted.jpg" alt=""></a>
      <div class="card-body">
          <h4 class="card-title">
          <a  href="#">Health</a>
          </h4>
          <p style="font-size:.9rem;" class="card-text">Join a group and learn to become a group facilitator! See God work through you to bless other people around the world!</p>
      </div>
  </div>
  <?php */ ?>
</div>
</div>
<?php } ?>
