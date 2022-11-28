<?php
$text_class = '';
if ( ! empty( $dashboard ) && $dashboard == 'leader' ) {
	$text_class = " class='text-white'";
}
?>
<div >
    <p {!!$text_class!!}>{{Auth::user()->name}}</p>
    <?php
    $date_format = Corcel\Model\Option::get('date_format');
    $joined = groups_count( 'joined' );

    $facilitated = groups_count( 'facilitated' );
    $donations = groups_count( 'donations' );
    ?>
    <p {!!$text_class!!}>{{ getPhrase('joined') }} {{date($date_format, strtotime(Auth::user()->created_at))}}</p>
    <?php
    $completed_contents = completed_pieces_new();
	
    if ( count($completed_contents) > 0 ) {
    ?>
    <p {!!$text_class!!}>
	@if(count($completed_contents) == 1)
		{{count($completed_contents)}} Completed piece of content
	@else
		{{count($completed_contents)}} Completed piece of contents
	@endif
	</p>
    <?php } else { ?>
    <p {!!$text_class!!}>Complete a piece of content!</p>
    <?php } ?>

    @if ( $joined->count() > 0 )
    <p {!!$text_class!!}>
		@if ( $joined->count() == 1 )
			{{$joined->count()}} {{ getPhrase('Group Joined') }}
		@else
			{{$joined->count()}} {{ getPhrase('Groups Joined') }}
		@endif
	</p>
    @else
        <p {!!$text_class!!}>Join a <a href="{{URL_STUDENT_OTHER_LMS_GROUPS}}">Group!</a></p>
    @endif

    @if ( $facilitated->count() > 0 )
        <p {!!$text_class!!}>{{$facilitated->count()}} {{ getPhrase('Group Facilitated') }}</p>
    @else
        <p {!!$text_class!!}>{{ getPhrase('facilitate_a_group') }}</p>
    @endif
    <p {!!$text_class!!}>$ {{$donations}} {{getPhrase('Donated')}}</p>
</div>
