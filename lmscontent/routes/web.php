<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

if(env('DB_DATABASE')=='')
{
   Route::get('/', 'InstallatationController@index');
   Route::get('/install', 'InstallatationController@index');
      Route::post('/update-details', 'InstallatationController@updateDetails');

   Route::post('/install', 'InstallatationController@installProject');
}

if(env('DEMO_MODE')) {
    Event::listen('eloquent.saving: *', function ($model) {
        if(urlHasString('finish-exam') || urlHasString('start-exam'))
          return true;
      return false;


    });
     
}
/*
if ( ! Auth::check() ) {
	if ( ! empty( $_COOKIE['kg_user'] ) ) {
		$user = getUserWithUserName( base64_decode( $_COOKIE['kg_user'] ) );
		if ( $user ) {
			Auth::loginUsingId( $user->id, true );
		}
	}
}
*/

Route::get('/', function () {
	if(Auth::check())
    {
        $user = getUserRecord();
		if( in_array($user->role_id, array( OWNER_ROLE_ID, ADMIN_ROLE_ID, EXECUTIVE_ROLE_ID )) ) {
			return redirect( 'dashboard' );
		} else {
			return redirect( URL_USERS_DASHBOARD );
		}
    }
	return redirect( URL_SYNC_WP_USERS );
});

Route::get('dashboard','DashboardController@index');
Route::get('user-dashboard','DashboardController@userDashboard');
Route::get('dashboard-learner','DashboardController@learner');
Route::get('dashboard-servant','DashboardController@servant');
Route::get('dashboard-servant-leader','DashboardController@servantLeader');


Route::get('auth/{slug}','Auth\LoginController@redirectToProvider');
Route::get('auth/{slug}/callback','Auth\LoginController@handleProviderCallback');



// Authentication Routes...
Route::get('login/{slug}/{redirect_url?}', 'Auth\LoginController@loginWpUser');
Route::get('login', 'Auth\LoginController@getLogin');
Route::get('logintest', 'Auth\LoginController@getLoginTest');
/*
Route::get('login', function() {
	if(Auth::check())
    {
        $user = getUserRecord();
		if( in_array($user->role_id, array( OWNER_ROLE_ID, ADMIN_ROLE_ID, EXECUTIVE_ROLE_ID )) ) {
			return redirect( 'dashboard' );
		} else {
			return redirect( URL_USERS_DASHBOARD );
		}
    } else {
		return redirect( URL_USERS_LOGIN );
	}
});
*/
// Route::post('login', 'Auth\LoginController@postLogin');
$this->post('login', 'Auth\LoginController@postLogin');

Route::get('logout', function(){

	if(Auth::check())
		flash(getPhrase('success'),getPhrase('logged_out_successfully'),'success');
	DB::table('users')->where('id', '=', get_current_user_id())->update(
		array(
			'is_lms_loggedin' => 'no',
		)
	);
	Auth::logout();
	
	return redirect( URL_WP_LOGOUT );
});

Route::get('lms-logout', function(){

	if(Auth::check())
		flash(getPhrase('success'),getPhrase('logged_out_successfully'),'success');
	
	DB::table('users')->where('id', '=', get_current_user_id())->update(
		array(
			'is_lms_loggedin' => 'no',
		)
	);
	
	Auth::logout();
	// Let us remove Cookie
	if ( ! empty( $_COOKIE['kg_user'] ) ) {
		unset($_COOKIE['kg_user']);
		setcookie('kg_user', null, -1, '/');
	}	
	return redirect( URL_USERS_LOGIN );
});

// Registration Routes...
// Route::get('register', 'Auth\LoginController@getRegister');
// Route::post('register', 'Auth\LoginController@postRegister');

Route::get('register', 'Auth\RegisterController@getRegister');
Route::post('register', 'Auth\RegisterController@postRegister');

// Forgot Password Routes...
// Route::get('forgot-password', 'PasswordController@postEmail');
Route::post('users/forgot-password', 'Auth\LoginController@forgotpasswordEmail');
// Route::get('password/reset/{slug?}', 'Auth\PasswordController@getReset');
// Route::post('password/reset', 'Auth\PasswordController@postReset');

Route::get('reset-password/{token}', 'Auth\LoginController@resetpassword');
Route::post('reset-password-email', 'Auth\LoginController@forgotpasswordEmail');
Route::post('reset-my-password', 'Auth\LoginController@resetmypassword');


Route::get('languages/list', 'NativeController@index');
Route::get('languages/getList', [ 'as'   => 'languages.dataTable',
     'uses' => 'NativeController@getDatatable']);
 
Route::get('languages/add', 'NativeController@create');
Route::post('languages/add', 'NativeController@store');
Route::get('languages/edit/{slug}', 'NativeController@edit');
Route::patch('languages/edit/{slug}', 'NativeController@update');
Route::delete('languages/delete/{slug}', 'NativeController@delete');
 
Route::get('languages/make-default/{slug}', 'NativeController@changeDefaultLanguage');
Route::get('languages/update-strings/{slug}', 'NativeController@updateLanguageStrings');
Route::patch('languages/update-strings/{slug}', 'NativeController@saveLanguageStrings');



//Users
Route::get('users/staff/{role?}', 'UsersController@index');
Route::get('users/create', 'UsersController@create');
Route::delete('users/delete/{slug}', 'UsersController@delete');
Route::post('users/create/{role?}', 'UsersController@store');
Route::get('users/edit/{slug}/{operation?}', 'UsersController@edit');
Route::patch('users/edit/{slug}/{operation?}', 'UsersController@update');
Route::get('users/profile/{slug}', 'UsersController@show');
Route::get('users', 'UsersController@index');
Route::get('users/profile/{slug}', 'UsersController@show');
Route::get('users/details/{slug}', 'UsersController@details');
Route::get('users/details-coach/{slug}', 'UsersController@detailsCoach'); // Coach viewing user (Facilitator) profile
Route::get('users/details-facilitator/{slug}', 'UsersController@detailsFacilitator'); // Facilitator viewing coach details


Route::get('users/settings/{slug}', 'UsersController@settings');
Route::patch('users/settings/{slug}', 'UsersController@updateSettings');

Route::get('users/change-password/{slug}', 'UsersController@changePassword');
Route::patch('users/change-password/{slug}', 'UsersController@updatePassword');

Route::get('users/import','UsersController@importUsers');
Route::post('users/import','UsersController@readExcel');

Route::get('users/import-report','UsersController@importResult');
Route::get('users/list/getList/{role_name?}', [ 'as'   => 'users.dataTable',
    'uses' => 'UsersController@getDatatable']);


Route::get('users/parent-details/{slug}', 'UsersController@viewParentDetails');
Route::patch('users/parent-details/{slug}', 'UsersController@updateParentDetails');
Route::post('users/search/parent', 'UsersController@getParentsOnSearch');

Route::post('users/chagne-status/{slug}/{status}','UsersController@changeStatus');

Route::get('users/coach-requests', 'UsersController@coachRequests');
Route::get('users/coach-requests/getList', [ 'as'   => 'users.coachesDataTable',
    'uses' => 'UsersController@getCoachRequests']);

// Facilitators
Route::get('users/my-facilitators', 'UsersController@myFacilitators');
Route::get('users/my-facilitators/getList', [ 'as'   => 'users.myFacilitatorsDataTable',
    'uses' => 'UsersController@getMyFacilitators']);
	
Route::get('users/assign-facilitators/{slug}', 'UsersController@assignFacilitators');
Route::get('users/assign-facilitators-list/getList/{coach_slug?}', [ 'as'   => 'users.assignFacilitatorsDataTable',
    'uses' => 'UsersController@getAssignFacilitators']);
Route::post('users/facilitators/add', 'UsersController@addToBag');
Route::post('users/facilitators/remove', 'UsersController@removeFromBag');
//////////////////////
//Parent Controller //
//////////////////////
Route::get('parent/children', 'ParentsController@index');
Route::get('parent/children/list', 'ParentsController@index');
Route::get('parent/children/getList/{slug}', 'ParentsController@getDatatable');
Route::get('children/analysis', 'ParentsController@childrenAnalysis');
   
                    /////////////////////
                    // Master Settings //
                    /////////////////////
 

//subjects
Route::get('mastersettings/subjects', 'SubjectsController@index');
Route::get('mastersettings/subjects/add', 'SubjectsController@create');
Route::post('mastersettings/subjects/add', 'SubjectsController@store');
Route::get('mastersettings/subjects/edit/{slug}', 'SubjectsController@edit');
Route::patch('mastersettings/subjects/edit/{slug}', 'SubjectsController@update');
Route::delete('mastersettings/subjects/delete/{id}', 'SubjectsController@delete');
Route::get('mastersettings/subjects/getList', [ 'as'   => 'subjects.dataTable',
    'uses' => 'SubjectsController@getDatatable']);

Route::get('mastersettings/subjects/import', 'SubjectsController@import');
Route::post('mastersettings/subjects/import', 'SubjectsController@readExcel');
 
//Topics 
Route::get('mastersettings/topics', 'TopicsController@index');
Route::get('mastersettings/topics/add', 'TopicsController@create');
Route::post('mastersettings/topics/add', 'TopicsController@store');
Route::get('mastersettings/topics/edit/{slug}', 'TopicsController@edit');
Route::patch('mastersettings/topics/edit/{slug}', 'TopicsController@update');
Route::delete('mastersettings/topics/delete/{id}', 'TopicsController@delete');
Route::get('mastersettings/topics/getList', [ 'as'   => 'topics.dataTable',
    'uses' => 'TopicsController@getDatatable']);

Route::get('mastersettings/topics/get-parents-topics/{subject_id}', 'TopicsController@getParentTopics');

Route::get('mastersettings/topics/import', 'TopicsController@import');
Route::post('mastersettings/topics/import', 'TopicsController@readExcel');

                    ////////////////////////
                    // EXAMINATION SYSTEM //
                    ////////////////////////

//Question bank
Route::get('exams/questionbank', 'QuestionBankController@index');
Route::get('exams/questionbank/add-question/{slug}', 'QuestionBankController@create');
Route::get('exams/questionbank/view/{slug}', 'QuestionBankController@show');

Route::post('exams/questionbank/add', 'QuestionBankController@store');
Route::get('exams/questionbank/edit-question/{slug}', 'QuestionBankController@edit');
Route::patch('exams/questionbank/edit/{slug}', 'QuestionBankController@update');
Route::delete('exams/questionbank/delete/{id}', 'QuestionBankController@delete');
Route::get('exams/questionbank/getList',  'QuestionBankController@getDatatable');

Route::get('exams/questionbank/getquestionslist/{slug}', 
     'QuestionBankController@getQuestions');
Route::get('exams/questionbank/import',  'QuestionBankController@import');
Route::post('exams/questionbank/import',  'QuestionBankController@readExcel');


//Quiz Categories
Route::get('global/categories', 'QuizCategoryController@index');
Route::get('global/categories/add', 'QuizCategoryController@create');
Route::post('global/categories/add', 'QuizCategoryController@store');
Route::get('global/categories/edit/{slug}', 'QuizCategoryController@edit');
Route::patch('global/categories/edit/{slug}', 'QuizCategoryController@update');
Route::delete('global/categories/delete/{slug}', 'QuizCategoryController@delete');
Route::get('global/categories/getList', [ 'as'   => 'quizcategories.dataTable',
    'uses' => 'QuizCategoryController@getDatatable']);

// Quiz Student Categories 
Route::get('exams/student/categories', 'StudentQuizController@index');
Route::get('exams/student/exams/{slug?}', 'StudentQuizController@exams');
Route::get('exams/student/quiz/getList/{slug?}', 'StudentQuizController@getDatatable');

Route::post('exams/student/start-exam/{slug}', 'StudentQuizController@startExam');
Route::get('exams/student/start-exam/{slug}', 'StudentQuizController@index');

Route::post('exams/student/finish-exam/{slug}', 'StudentQuizController@finishExam');
Route::get('exams/student/reports/{slug}', 'StudentQuizController@reports');


Route::get('exams/student/exam-attempts/{user_slug}/{exam_slug?}', 'StudentQuizController@examAttempts');
Route::get('exams/student/get-exam-attempts/{user_slug}/{exam_slug?}', 'StudentQuizController@getExamAttemptsData');

Route::get('student/analysis/by-exam/{user_slug}', 'StudentQuizController@examAnalysis');
Route::get('student/analysis/get-by-exam/{user_slug}', 'StudentQuizController@getExamAnalysisData');

Route::get('student/analysis/by-subject/{user_slug}/{exam_slug?}/{results_slug?}', 'StudentQuizController@subjectAnalysisInExam');
Route::get('student/analysis/subject/{user_slug}', 'StudentQuizController@overallSubjectAnalysis');

//Student Reports
Route::get('student/exam/answers/{quiz_slug}/{result_slug}', 'ReportsController@viewExamAnswers');


//Quiz 
Route::get('exams/quizzes', 'QuizController@index');
Route::get('exams/quiz/add', 'QuizController@create');
Route::post('exams/quiz/add', 'QuizController@store');
Route::get('exams/quiz/edit/{slug}', 'QuizController@edit');
Route::patch('exams/quiz/edit/{slug}', 'QuizController@update');
Route::delete('exams/quiz/delete/{slug}', 'QuizController@delete');
Route::get('exams/quiz/getList/{slug?}', 'QuizController@getDatatable');

Route::get('exams/quiz/update-questions/{slug}', 'QuizController@updateQuestions');
Route::post('exams/quiz/update-questions/{slug}', 'QuizController@storeQuestions');


Route::post('exams/quiz/get-questions', 'QuizController@getSubjectData');

//Certificates controller
Route::get('result/generate-certificate/{slug}', 'CertificatesController@getCertificate');


//Exam Series 
Route::get('exams/exam-series', 'ExamSeriesController@index');
Route::get('exams/exam-series/add', 'ExamSeriesController@create');
Route::post('exams/exam-series/add', 'ExamSeriesController@store');
Route::get('exams/exam-series/edit/{slug}', 'ExamSeriesController@edit');
Route::patch('exams/exam-series/edit/{slug}', 'ExamSeriesController@update');
Route::delete('exams/exam-series/delete/{slug}', 'ExamSeriesController@delete');
Route::get('exams/exam-series/getList', 'ExamSeriesController@getDatatable');

//EXAM SERIES STUDENT LINKS
Route::get('exams/student-exam-series/list', 'ExamSeriesController@listSeries');
Route::get('exams/student-exam-series/{slug}', 'ExamSeriesController@viewItem');




Route::get('exams/exam-series/update-series/{slug}', 'ExamSeriesController@updateSeries');
Route::post('exams/exam-series/update-series/{slug}', 'ExamSeriesController@storeSeries');
Route::post('exams/exam-series/get-exams', 'ExamSeriesController@getExams');
Route::get('payment/cancel', 'ExamSeriesController@cancel');
Route::post('payment/success', 'ExamSeriesController@success');

            /////////////////////
            // PAYMENT REPORTS //
            /////////////////////
Route::get('payments-report/', 'PaymentsController@overallPayments');

Route::get('payments-report/online/', 'PaymentsController@onlinePaymentsReport');
Route::get('payments-report/online/{slug}', 'PaymentsController@listOnlinePaymentsReport');

Route::get('payments-report/online/', 'PaymentsController@onlinePaymentsReport');
Route::get('payments-report/online/{slug}', 'PaymentsController@listOnlinePaymentsReport');


Route::get('payments-report/online/getList/{slug}', 'PaymentsController@getOnlinePaymentReportsDatatable');

Route::get('payments-report/offline/', 'PaymentsController@offlinePaymentsReport');
Route::get('payments-report/offline/{slug}', 'PaymentsController@listOfflinePaymentsReport');
Route::get('payments-report/offline/getList/{slug}', 'PaymentsController@getOfflinePaymentReportsDatatable');
Route::get('payments-report/export', 'PaymentsController@exportPayments');
Route::post('payments-report/export', 'PaymentsController@doExportPayments');

Route::post('payments-report/getRecord', 'PaymentsController@getPaymentRecord');
Route::post('payments/approve-reject-offline-request', 'PaymentsController@approveOfflinePayment');

            //////////////////
            // INSTRUCTIONS  //
            //////////////////
            
Route::get('exam/instructions/list', 'InstructionsController@index');
Route::get('exam/instructions', 'InstructionsController@index');
Route::get('exams/instructions/add', 'InstructionsController@create');
Route::post('exams/instructions/add', 'InstructionsController@store');
Route::get('exams/instructions/edit/{slug}', 'InstructionsController@edit');
Route::patch('exams/instructions/edit/{slug}', 'InstructionsController@update');
Route::delete('exams/instructions/delete/{slug}', 'InstructionsController@delete');
Route::get('exams/instructions/getList', 'InstructionsController@getDatatable');

 
//BOOKMARKS MODULE
Route::get('student/bookmarks/{slug}', 'BookmarksController@index');
Route::post('student/bookmarks/add', 'BookmarksController@create');
Route::delete('student/bookmarks/delete/{id}', 'BookmarksController@delete');
Route::delete('student/bookmarks/delete_id/{id}', 'BookmarksController@deleteById');
Route::get('student/bookmarks/getList/{slug}',  'BookmarksController@getDatatable');
Route::post('student/bookmarks/getSavedList',  'BookmarksController@getSavedBookmarks');


                //////////////////////////
                // Notifications Module //
                /////////////////////////
Route::get('admin/notifications/list', 'NotificationsController@index');
Route::get('admin/notifications', 'NotificationsController@index');
Route::get('admin/notifications/add', 'NotificationsController@create');
Route::post('admin/notifications/add', 'NotificationsController@store');
Route::get('admin/notifications/edit/{slug}', 'NotificationsController@edit');
Route::patch('admin/notifications/edit/{slug}', 'NotificationsController@update');
Route::delete('admin/notifications/delete/{slug}', 'NotificationsController@delete');
Route::get('admin/notifications/getList', 'NotificationsController@getDatatable');

// NOTIFICATIONS FOR STUDENT
Route::get('notifications/list', 'NotificationsController@usersList');
Route::get('notifications/show/{slug}', 'NotificationsController@display');

 
//BOOKMARKS MODULE
Route::get('toppers/compare-with-topper/{user_result_slug}/{compare_slug?}', 'ExamToppersController@compare');

               
                        ////////////////
                        // LMS MODULE //
                        ////////////////

//LMS Categories
Route::get('lms/categories', 'LmsCategoryController@index');
Route::get('lms/categories/add', 'LmsCategoryController@create');
Route::post('lms/categories/add', 'LmsCategoryController@store');
Route::get('lms/categories/edit/{slug}', 'LmsCategoryController@edit');
Route::patch('lms/categories/edit/{slug}', 'LmsCategoryController@update');
Route::delete('lms/categories/delete/{slug}', 'LmsCategoryController@delete');
Route::get('lms/categories/getList', [ 'as'   => 'lmscategories.dataTable',
    'uses' => 'LmsCategoryController@getDatatable']);

//LMS Contents
Route::get('lms/content/add', 'LmsContentController@create');
Route::get('lms/content/{group?}', 'LmsContentController@index');

Route::post('lms/content/add', 'LmsContentController@store');
Route::get('lms/content/edit/{slug}', 'LmsContentController@edit');
Route::patch('lms/content/edit/{slug}', 'LmsContentController@update');
Route::delete('lms/content/delete/{slug}', 'LmsContentController@delete');
Route::get('lms-content/getList', [ 'as' => 'lmscontent.dataTable', 'uses' => 'LmsContentController@getDatatable'] );
Route::get('lms-content/getListSlug/{group_slug}', 'LmsContentController@getDatatable' );
Route::get('course-lessons/getListSlug/{course_slug}', 'LmsSeriesController@getCourseLessonsDatatable' );

//LMS Series 
Route::get('lms/series', 'LmsSeriesController@index');
Route::get('lms/series/add/{course_type?}', 'LmsSeriesController@create');
Route::post('lms/series/add/{course_type?}', 'LmsSeriesController@store');
Route::get('lms/series/edit/{slug}', 'LmsSeriesController@edit');
Route::patch('lms/series/edit/{slug}', 'LmsSeriesController@update');
Route::delete('lms/series/delete/{slug}/{type?}', 'LmsSeriesController@delete');
Route::get('lms/series/getList/{parent}', 'LmsSeriesController@getDatatable');

//LMS SERIES STUDENT LINKS
Route::get('lms/exam-series/list', 'LmsSeriesController@listSeries');
Route::get('lms/exam-series/{slug}', 'LmsSeriesController@viewItem');




Route::get('lms/series/update-series/{slug}', 'LmsSeriesController@updateSeries');
Route::post('lms/series/update-series/{slug}', 'LmsSeriesController@storeSeries');
Route::post('lms/series/get-series', 'LmsSeriesController@getSeries');
Route::get('payment/cancel', 'LmsSeriesController@cancel');
Route::post('payment/success', 'LmsSeriesController@success');


//LMS Student view
Route::get('learning-management/categories', 'StudentLmsController@index');
Route::get('learning-management/view/{slug}', 'StudentLmsController@viewCategoryItems');
Route::get('learning-management/series', 'StudentLmsController@series');
Route::get('learning-management/series/{slug}/{content_slug?}', 'StudentLmsController@viewItem');
Route::get('user/paid/{slug}/{content_slug}', 'StudentLmsController@verifyPaidItem');
Route::get('learning-management/content/{req_content_type}', 'StudentLmsController@content');
Route::get('learning-management/content/show/{slug}', 'StudentLmsController@showContent');

 

//Payments Controller
Route::get('payments/list/{slug}', 'PaymentsController@index');
Route::get('payments/getList/{slug}', 'PaymentsController@getDatatable');

Route::get('donations/list/{slug}', 'DonationsController@paymentsList');
Route::get('donations/getList/{slug}', 'DonationsController@getDatatable');

Route::get('payments/checkout/{type}/{slug}', 'PaymentsController@checkout');
Route::get('payments/paynow/{slug}', 'DashboardController@index');
Route::post('payments/paynow/{slug}', 'PaymentsController@paynow');

Route::post('payments/paypal/status-success','PaymentsController@paypal_success');
Route::get('payments/paypal/status-cancel', 'PaymentsController@paypal_cancel');

// Route::post('payments/paypal/status-success','DonationsController@paypal_success');
// Route::get('payments/paypal/status-cancel', 'DonationsController@paypal_cancel');

Route::post('donation/paypal/status-success','DonationsController@paypal_success');
Route::get('donation/paypal/status-cancel', 'DonationsController@paypal_cancel');

Route::post('payments/payu/status-success','PaymentsController@payu_success');
Route::post('payments/payu/status-cancel', 'PaymentsController@payu_cancel');
Route::post('payments/offline-payment/update', 'PaymentsController@updateOfflinePayment');

                    
 

                        ////////////////////////////
                        // SETTINGS MODULE //
                        ///////////////////////////


//LMS Categories
Route::get('mastersettings/settings/', 'SettingsController@index');
Route::get('mastersettings/settings/index', 'SettingsController@index');
Route::get('mastersettings/settings/add', 'SettingsController@create');
Route::post('mastersettings/settings/add', 'SettingsController@store');
Route::get('mastersettings/settings/edit/{slug}', 'SettingsController@edit');
Route::patch('mastersettings/settings/edit/{slug}', 'SettingsController@update');
Route::get('mastersettings/settings/view/{slug}', 'SettingsController@viewSettings');
Route::get('mastersettings/settings/add-sub-settings/{slug}', 'SettingsController@addSubSettings');
Route::post('mastersettings/settings/add-sub-settings/{slug}', 'SettingsController@storeSubSettings');
Route::patch('mastersettings/settings/add-sub-settings/{slug}', 'SettingsController@updateSubSettings');
 
Route::get('mastersettings/settings/getList', [ 'as'   => 'mastersettings.dataTable',
     'uses' => 'SettingsController@getDatatable']);

                        ////////////////////////////
                        // EMAIL TEMPLATES MODULE //
                        ///////////////////////////

//LMS Categories
Route::get('email/templates', 'EmailTemplatesController@index');
Route::get('email/templates/add', 'EmailTemplatesController@create');
Route::post('email/templates/add', 'EmailTemplatesController@store');
Route::get('email/templates/edit/{slug}', 'EmailTemplatesController@edit');
Route::patch('email/templates/edit/{slug}', 'EmailTemplatesController@update');
Route::delete('email/templates/delete/{slug}', 'EmailTemplatesController@delete');
Route::get('email/templates/getList', [ 'as'   => 'emailtemplates.dataTable',
    'uses' => 'EmailTemplatesController@getDatatable']);


//Coupons Module
Route::get('coupons/list', 'CouponcodesController@index');
Route::get('coupons/add', 'CouponcodesController@create');
Route::post('coupons/add', 'CouponcodesController@store');
Route::get('coupons/edit/{slug}', 'CouponcodesController@edit');
Route::patch('coupons/edit/{slug}', 'CouponcodesController@update');
Route::delete('coupons/delete/{slug}', 'CouponcodesController@delete');
Route::get('coupons/getList/{slug?}', 'CouponcodesController@getDatatable');

Route::get('coupons/get-usage', 'CouponcodesController@getCouponUsage');
Route::get('coupons/get-usage-data', 'CouponcodesController@getCouponUsageData');
Route::post('coupons/update-questions/{slug}', 'CouponcodesController@storeQuestions');


Route::post('coupons/validate-coupon', 'CouponcodesController@validateCoupon');


//Feedback Module
Route::get('feedback/list', 'FeedbackController@index');
Route::get('feedback/view-details/{slug}', 'FeedbackController@details');
Route::get('feedback/send', 'FeedbackController@create');
Route::post('feedback/send', 'FeedbackController@store');
Route::delete('feedback/delete/{slug}', 'FeedbackController@delete');
Route::get('feedback/getlist', 'FeedbackController@getDatatable');

//SMS Module

Route::get('sms/index', 'SMSAgentController@index');
Route::post('sms/send', 'SMSAgentController@sendSMS');

                        /////////////////////
                        // MESSAGES MODULE //
                        /////////////////////


Route::group(['prefix' => 'messages'], function () {
    Route::get('/', ['as' => 'messages', 'uses' => 'MessagesController@index']);
	Route::get('/unread', ['as' => 'messages', 'uses' => 'MessagesController@unread']);
    Route::get('create', ['as' => 'messages.create', 'uses' => 'MessagesController@create']);
    Route::post('/', ['as' => 'messages.store', 'uses' => 'MessagesController@store']);
    Route::get('{id}', ['as' => 'messages.show', 'uses' => 'MessagesController@show']);
    Route::put('{id}', ['as' => 'messages.update', 'uses' => 'MessagesController@update']);
});


                         ////////////////////
                         // UPDATE PATCHES //
                         ////////////////////
 Route::get('updates/patch1', 'UpdatesController@patch1');
 Route::get('updates/patch2', 'UpdatesController@patch2');
 Route::get('updates/patch3', 'UpdatesController@patch3');

Route::get('refresh-csrf', function(){
    return csrf_token();
});


//Front End LMS Categies and Series
Route::get('lms/categories', 'LMSfrontViewController@viewCategories');
Route::get('lms/course/{slug}', 'LMSfrontViewController@showCourse');

Route::get('lms/course-lessons/{slug}', 'LMSfrontViewController@viewLesson');
Route::get('single-lesson/{series_slug}/{slug}/{piece_slug?}', 'LMSfrontViewController@singleLesson');
Route::post('single-lesson/save-data', 'LMSfrontViewController@saveData');
Route::post('single-lesson/get-data', 'LMSfrontViewController@getData');
Route::post('ajax-login', 'Auth\LoginController@ajaxLogin');
Route::get('exams/student/quiz/take-exam/{slug?}', 'LMSfrontViewController@instructions');
Route::get('my-courses/{slug?}', 'LMSfrontViewController@myCourses');

Route::get('lms/pathway-courses/{type}/{subject_slug}', 'LMSfrontViewController@subjectCourses');
Route::get('lms/category-courses/{type}/{subject_slug}', 'LMSfrontViewController@subjectCourses');
Route::get('lms/author-courses/{type}/{subject_slug}', 'LMSfrontViewController@subjectCourses');


Route::get('recomended-lms-courses/{slug?}', 'LMSfrontViewController@recommendedCourses');
Route::get('lms-course-list/{slug}', 'LMSfrontViewController@courseList');

Route::get('confirm/{confirmation_code}', 'Auth\LoginController@confirm');
Route::get('check-login/{user_name}', 'Auth\LoginController@checkLogin');

// LMS Groups
Route::get('lms/my-groups', 'LMSGroupsController@myGroups');
Route::get('lms/other-groups/{type?}', 'LMSGroupsController@otherGroups');

Route::get('lms/my-groups/getList/{type}/{is_joined}', 'LMSGroupsController@myGroupsgetList');

Route::delete('lms/my-groups/delete/{slug}', 'LMSGroupsController@delete');
Route::get('lms/groups', 'LMSGroupsController@index');
Route::get('lms/groups/add', 'LMSGroupsController@create');
Route::post('lms/groups/add', 'LMSGroupsController@store');
Route::get('lms/groups/edit/{slug}', 'LMSGroupsController@edit');
Route::patch('lms/groups/edit/{slug}', 'LMSGroupsController@update');
Route::delete('lms/groups/delete/{slug}', 'LMSGroupsController@delete');
Route::get('lms/group-dashboard/{slug}', 'LMSGroupsController@groupDashboard');

Route::get('lms/group-contents/{group_slug}/{user_slug?}', 'LMSGroupsController@groupContents');

Route::get('group-single-lesson/{group_slug}/{slug}/{piece_slug?}', 'LMSGroupsController@groupSingleLesson');
Route::get('lms-group-contents/add/{group_slug}', 'LMSGroupsController@addGroupContents');
Route::post('lms-group-contents/update/{group_slug}', 'LMSGroupsController@updateGroupContents');

Route::get('lms-group-invitations/add/{group_slug}', 'LMSGroupsController@groupInvitationsAdd');
Route::get('lms-group-invitations/add/get-users/{group_slug}', 'LMSGroupsController@groupInvitationsAddGetUsers');
Route::post('lms-group-invitations/add-remove-user', 'LMSGroupsController@groupInvitationsAddRemoveUser');


Route::delete('lms-group-invitations/delete/{slug}', 'LMSGroupsController@groupInvitationsDelete');
Route::get('lms-group-invitations/{group_slug}/{invitation_status?}', 'LMSGroupsController@groupInvitations');
Route::get('lms-group-invitations/getList/{group_slug}/{invitation_status?}/{is_joined?}', 'LMSGroupsController@groupInvitationsGetList');

Route::get('lms-groups/{group_slug}/{user_slug}', 'LMSGroupsController@groupUserStatus');

Route::get('admin/all-groups/{type?}/{user_slug?}', 'LMSGroupsController@otherGroups');
Route::post('lms-groups/chagne-status/{slug}/{status}','LMSGroupsController@changeStatus');

Route::get('invite-other-friends', 'LMSfrontViewController@inviteOtherFriends');
Route::post('invite-other-friends', 'LMSfrontViewController@sendMailOtherFriends');

// Donations routes.
Route::get('donation', 'DonationsController@index');
Route::post('donation/process', 'DonationsController@processDonation');
Route::get('lms/donations-report', 'DonationsController@onlinePaymentsReport');

Route::get('donations-report/online/getList/{slug}', 'DonationsController@getOnlinePaymentReportsDatatable');
Route::get('donations-report/online/{slug}', 'DonationsController@listOnlinePaymentsReport');
Route::get('donation-success', 'DonationsController@donationSuccess');
Route::get('donation-failed', 'DonationsController@donationFailed');

Route::get('testmail', 'Auth\LoginController@testmail');

Route::get('translation-request', 'LoginController@tranlationRequest');
Route::get('exams/student/quiz/instructions/{slug?}', 'StudentQuizController@instructions');

Route::get('get-login-form', 'Auth\LoginController@getLoginForm');

// LMS Modules
Route::get('lms/modules/{course?}', 'LmsSeriesController@modulesIndex');
Route::get('lms/show-modules/{slug}', 'LmsSeriesController@showModules');
Route::get('lms/modules-add/{course?}', 'LmsSeriesController@createModule');
Route::get('lms/modules/edit/{slug}', 'LmsSeriesController@editModule');

Route::get('lms/get-courses/{category}', 'LMSfrontViewController@getCourses');
Route::get('lms/get-courses-list/{category}', 'LMSfrontViewController@getCoursesList');

// Dashboard Promotion Settings
Route::get('dashboard-promotion','SettingsController@promotionCriteria');
Route::get('dashboard-add','SettingsController@addDashboard');
Route::post('dashboard-add','SettingsController@storeDashboard');
Route::get('dashboard-edit/{slug}','SettingsController@editDashboard');
Route::patch('dashboard-edit/{slug}','SettingsController@updateDashboard');

//LMS Series Master
Route::get('lms/master-series', 'LmsSeriesMasterController@index');
Route::get('lms/master-series/add', 'LmsSeriesMasterController@create');
Route::post('lms/master-series/add', 'LmsSeriesMasterController@store');
Route::get('lms/master-series/edit/{slug}', 'LmsSeriesMasterController@edit');
Route::patch('lms/master-series/edit/{slug}', 'LmsSeriesMasterController@update');
Route::delete('lms/master-series/delete/{slug}', 'LmsSeriesMasterController@delete');
Route::get('lms/master-series/getList/{category?}', 'LmsSeriesMasterController@getDatatable');
Route::get('lms/master-series-categories/{category}', 'LmsSeriesMasterController@getSerieses');

Route::get('lms/categories/serieses/{category}', 'LMSfrontViewController@getSerieses');

Route::get('lms/categories/serieses-courses/{category}/{series?}', 'LMSfrontViewController@showCourses');
Route::get('lms/categories/serieses-courses-modules-lessons/{course}', 'LMSfrontViewController@showLessonsModules');
Route::get('lms/categories/serieses-courses-module-lessons/{course}/{module}', 'LMSfrontViewController@showModuleLessons');

Route::get('master-settings/lmsmode', 'SettingsController@showLMSMode');
Route::patch('master-settings/lmsmode', 'SettingsController@updateLMSMode');

Route::get('master-settings/layout', 'SettingsController@showLayout');
Route::patch('master-settings/layout', 'SettingsController@updateLayout');

Route::post('course/modules', 'LmsContentController@getModules');

Route::get('users/send-email/{user_slug}', 'UsersController@getSendEmail');
Route::post('users/send-email/{user_slug}', 'UsersController@SendEmail');

// Translation Issues
Route::get('translation-requests', 'UsersController@translationRequests');
Route::get('get-translation-requests', [ 'as'   => 'translation.dataTable',
    'uses' => 'UsersController@getTranslationRequests']);
Route::delete('translation-requests/delete/{slug}', 'UsersController@deleteTranslationRequests');
Route::get('translation-requests/view/{slug}', 'UsersController@viewTranslationRequest');
Route::post('translation-requests/view/{slug}', 'UsersController@processTranslationRequest');

Route::get('send-translation-request/{type?}/{id?}', 'LMSfrontViewController@sendTranslationRequest');
Route::post('send-translation-request', 'LMSfrontViewController@processTranslationRequest');

// Site Issues
Route::get('site-issues', 'UsersController@siteIssues');
Route::get('get-site-issues', [ 'as'   => 'site-issues.dataTable',
    'uses' => 'UsersController@getSiteIssues']);
Route::delete('site-issues/delete/{slug}', 'UsersController@deleteSiteIssues');
Route::get('site-issues/view/{slug}', 'UsersController@viewSiteIssues');
Route::post('site-issues/view/{slug}', 'UsersController@processSiteIssues');

Route::get('send-site-issue', 'LMSfrontViewController@sendSiteIssue');
Route::post('send-site-issue', 'LMSfrontViewController@sendSiteIssue');

Route::post('start-course', 'LMSfrontViewController@startCourse');
Route::post('search-course', 'LMSfrontViewController@searchCourse');

Route::get('global-search/{type?}/{slug?}', 'LMSfrontViewController@globalSearch');
// Route::post('global-search', 'LMSfrontViewController@globalSearch');

Route::get('special-courses', 'LmsSeriesController@specialCourses');

Route::get('send-activation-mail', 'Auth\LoginController@sendActivationMail');
Route::post('send-activation-mail', 'Auth\LoginController@sendActivationMail');

// News letter
Route::get('newsletter-subscriptions', 'UsersController@newsLetterSubscriptions');
Route::get('get-newsletter-subscriptions', [ 'as'   => 'newsletter-subscriptions.dataTable',
    'uses' => 'UsersController@getNewsLetterSubscriptions']);
Route::delete('newsletter-subscriptions/delete/{slug}', 'UsersController@deleteNewsLetterSubscriptions');

Route::get('users/change-email', 'UsersController@changeEmail');
Route::post('users/change-email', 'UsersController@changeEmail');
Route::get('users/confirm/change-email/{slug}', 'Auth\LoginController@confirmChangeEmail');

Route::get('course/lessons/{slug}', 'LmsSeriesController@courseLessons');

Route::get('course-summary', 'LMSfrontViewController@testSummary');

Route::get('manage-groups/{group_slug}/{user_id}/{action}/{thread?}', 'LMSGroupsController@manage_groups_requests');
Route::get('manage-groups-requests/{group_slug}/{user_id}/{action}', 'LMSGroupsController@manage_groups_requests_direct');
Route::get('users/profile-privacy-settings', 'UsersController@profilePrivacySettings');
Route::post('users/profile-privacy-settings', 'UsersController@updatePrivacySettings');

Route::get('lms/group/courses/{group_slug}/{course_slug?}', 'LMSGroupsController@showGroupCourses');
Route::get('lms/add-group/courses/{group_slug}', 'LMSGroupsController@addGroupCourses2');
Route::post('lms/add-group/courses/{group_slug}', 'LMSGroupsController@storeGroupCourses');
Route::get('lms/group/getCoursesList/{group_slug}', 'LMSGroupsController@getDatatable');

// Posts in Groups
Route::get('lms/group/posts/{group_slug}/{post_slug?}', 'LMSGroupsController@showGroupPosts');
Route::get('lms/add-group/posts/{group_slug}', 'LMSGroupsController@addGroupPosts');
Route::post('lms/add-group/posts/{group_slug}', 'LMSGroupsController@storeGroupPosts');
Route::get('lms/group/getPostsList/{group_slug}', 'LMSGroupsController@getDatatablePosts');

Route::post('course/coach-request', 'LMSfrontViewController@postCoachRequest');
Route::post('course/withdraw-coach-request', 'LMSfrontViewController@withdrawCoachRequest');
Route::get('groups/get-comments/{group_slug}', 'LMSGroupsController@getComments');
Route::post('groups/save-comments/{group_slug}', 'LMSGroupsController@saveComments');
Route::get('content/get-comments/{content_slug}', 'LMSfrontViewController@getContentComments');
Route::post('content/save-comments/{content_slug}', 'LMSfrontViewController@saveContentComments');

// Special Roles
Route::get('special-role/role', 'SpecialroleController@index');
Route::get('special-role/role/add', 'SpecialroleController@create');
Route::post('special-role/role/add', 'SpecialroleController@store');
Route::get('special-role/role/edit/{slug}', 'SpecialroleController@edit');
Route::patch('special-role/role/edit/{slug}', 'SpecialroleController@update');
Route::delete('special-role/role/delete/{id}', 'SpecialroleController@delete');
Route::get('special-role/role/getList', [ 'as'   => 'role.dataTable',
    'uses' => 'SpecialroleController@getDatatable']);