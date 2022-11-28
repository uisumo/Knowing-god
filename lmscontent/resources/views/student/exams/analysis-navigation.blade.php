<?php
$active_menu = 'pathway';
$pathway_active = 'active';
$byexam_active = '';
$history_active = '';
if ( $active_menu = 'byexam' ) {
	$pathway_active = '';
	$byexam_active = 'active';
	$history_active = '';
} elseif ( $active_menu = 'history' ) {
	$pathway_active = '';
	$byexam_active = '';
	$history_active = 'active';
}

?>
<div class="exam-analysis-btns">
   <a href="{{URL_STUDENT_ANALYSIS_SUBJECT . Auth::User()->slug}}" class="{{$pathway_active}}">Quiz Analysis by <span>Pathway</span></a>
   <a href="{{URL_STUDENT_ANALYSIS_BY_EXAM.Auth::user()->slug }}" class="{{$byexam_active}}">Quiz Analysis by <span>Exam</span></a>
   <a href="{{URL_STUDENT_EXAM_ATTEMPTS.Auth::user()->slug }}" class="{{$history_active}}">Quiz Analysis by <span>History</span></a>
</div>