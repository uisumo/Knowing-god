<?php $donations = groups_count( 'donations' ); ?>
<a class="btn btn-yellow btn-fund" href="{{URL_STUDENT_DONATIONS_INDEX}}">
	@if ( $donations > 0 )
		Fund another <br>part of the Pathway!
	@else
		{!! getPhrase('Help fund <br> the Pathway!') !!}
	@endif
</a>