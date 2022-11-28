<?php
$base1 = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
$base1 .= '://'.$_SERVER['HTTP_HOST'] . str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);

$base = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
$base .= '://'.$_SERVER['HTTP_HOST'] . str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);

// $base1 = '/';
// $base = '/';

define('PREFIX1', $base1.'public/');
define('BASE_PATH', $base.'/');
define('PREFIX', $base);


// dd($_SERVER);
//Design Source File Paths
define('CSS', PREFIX1.'css/');
define('JS', PREFIX1.'js/');
define('FONTAWSOME', PREFIX1.'font-awesome/css/');
define('IMAGES', PREFIX1.'images/');
define('AJAXLOADER', IMAGES.'ajax-loader.svg');
define('AJAXLOADER_FADEIN_TIME', 100);
define('AJAXLOADER_FADEOUT_TIME', 100);


define('UPLOADS', PREFIX1.'uploads/');
define('EXAM_UPLOADS', UPLOADS.'exams/');
define('IMAGE_PATH_UPLOAD_SERIES', UPLOADS.'exams/series/');
define('IMAGE_PATH_UPLOAD_SERIES_THUMB', UPLOADS.'exams/series/thumb/');

define('IMAGE_PATH_UPLOAD_EXAMSERIES_DEFAULT', UPLOADS.'exams/series/default.png');

define('IMAGE_PATH_UPLOAD_LMS_CATEGORIES', UPLOADS.'lms/categories/');
define('IMAGE_PATH_UPLOAD_LMS_DEFAULT', UPLOADS.'lms/categories/default.png');
define('IMAGE_PATH_UPLOAD_LMS_CONTENTS', UPLOADS.'lms/content/');

define('IMAGE_PATH_UPLOAD_LMS_SERIES', UPLOADS.'lms/series/');
define('IMAGE_PATH_UPLOAD_LMS_SERIES_THUMB', UPLOADS.'lms/series/thumb/');

define('IMAGE_PATH_PROFILE', UPLOADS.'users/');
define('IMAGE_PATH_PROFILE_THUMBNAIL', UPLOADS.'users/thumbnail/');
define('IMAGE_PATH_PROFILE_THUMBNAIL_DEFAULT', UPLOADS.'users/thumbnail/default.png');

define('IMAGE_PATH_SETTINGS', UPLOADS.'settings/');



define('DOWNLOAD_LINK_USERS_IMPORT_EXCEL', PREFIX.'downloads/excel-templates/users_template.xlsx');
define('DOWNLOAD_LINK_SUBJECTS_IMPORT_EXCEL', PREFIX.'downloads/excel-templates/subjects_template.xlsx');
define('DOWNLOAD_LINK_TOPICS_IMPORT_EXCEL', PREFIX.'downloads/excel-templates/topics_template.xlsx');
define('DOWNLOAD_LINK_QUESTION_IMPORT_EXCEL', PREFIX.'downloads/excel-templates/');


define('DOWNLOAD_EMPTY_DATA_DATABASE', PREFIX.'downloads/database/install.sql');
define('DOWNLOAD_SAMPLE_DATA_DATABASE', PREFIX.'downloads/database/install_dummy_data.sql');



define('CURRENCY_CODE', '$ ');
define('RECORDS_PER_PAGE', '8');
define('STUDENT_ROLE_ID', '5');

define('GOOGLE_TRANSLATE_LANGUAGES_LINK', 'https://cloud.google.com/translate/docs/languages');

define('PAYMENT_STATUS_CANCELLED', 'cancelled');
define('PAYMENT_STATUS_SUCCESS', 'success');
define('PAYMENT_STATUS_PENDING', 'pending');
define('PAYMENT_STATUS_ABORTED', 'aborted');
define('PAYMENT_RECORD_MAXTIME', '30'); //TIME IN MINUTES
//define('SUPPORTED_GATEWAYS', ['paypal','payu']);

define('URL_INSTALL_SYSTEM', PREFIX.'install');
define('URL_UPDATE_INSTALLATATION_DETAILS', PREFIX.'update-details');
define('URL_FIRST_USER_REGISTER', PREFIX.'install/register');

//MASTER SETTINGS MODULE
define('URL_MASTERSETTINGS_SETTINGS', PREFIX.'mastersettings/settings');
define('URL_MASTERSETTINGS_EMAIL_TEMPLATES', PREFIX.'email/templates');
define('URL_MASTERSETTINGS_TOPICS', PREFIX.'mastersettings/topics');
define('URL_MASTERSETTINGS_SUBJECTS', PREFIX.'mastersettings/subjects');

//QUIZ MODULE
define('URL_QUIZZES', PREFIX.'exams/quizzes');
define('URL_QUIZ_QUESTIONBANK', PREFIX.'exams/questionbank');
define('URL_QUIZ_ADD', PREFIX.'exams/quiz/add');
define('URL_QUIZ_EDIT', PREFIX.'exams/quiz/edit');
define('URL_QUIZ_DELETE', PREFIX.'exams/quiz/delete/');
define('URL_QUIZ_GETLIST', PREFIX.'exams/quiz/getList');
define('URL_QUIZ_UPDATE_QUESTIONS', PREFIX.'exams/quiz/update-questions/');
define('URL_QUIZ_GET_QUESTIONS', PREFIX.'exams/quiz/get-questions');

//QUIZ CATEGORIES
define('URL_QUIZ_CATEGORIES', PREFIX.'global/categories');
define('URL_QUIZ_CATEGORY_EDIT', PREFIX.'global/categories/edit');
define('URL_QUIZ_CATEGORY_ADD', PREFIX.'global/categories/add');
define('URL_QUIZ_CATEGORY_DELETE', PREFIX.'global/categories/delete/');

//QUESTIONSBANK MODULE
define('URL_QUESTIONBANK_VIEW', PREFIX.'exams/questionbank/view/');
define('URL_QUESTIONBANK_ADD_QUESTION', PREFIX.'exams/questionbank/add-question/');
define('URL_QUESTIONBANK_EDIT_QUESTION', PREFIX.'exams/questionbank/edit-question/');
define('URL_QUESTIONBANK_EDIT', PREFIX.'exams/questionbank/edit');
define('URL_QUESTIONBANK_ADD', PREFIX.'exams/questionbank/add');
define('URL_QUESTIONBANK_GETLIST', PREFIX.'exams/questionbank/getList');
define('URL_QUESTIONBANK_DELETE', PREFIX.'exams/questionbank/delete/');
define('URL_QUESTIONBANK_GETQUESTION_LIST', PREFIX.'exams/questionbank/getquestionslist/');

define('URL_QUESTIONBAMK_IMPORT', PREFIX.'exams/questionbank/import');

//SUBJECTS MODULE
define('URL_SUBJECTS', PREFIX.'mastersettings/subjects');
define('URL_SUBJECTS_ADD', PREFIX.'mastersettings/subjects/add');
define('URL_SUBJECTS_EDIT', PREFIX.'mastersettings/subjects/edit');
define('URL_SUBJECTS_DELETE', PREFIX.'mastersettings/subjects/delete/');

define('URL_SUBJECTS_IMPORT', PREFIX.'mastersettings/subjects/import');


//TOPICS MODULE
define('URL_TOPICS', PREFIX.'mastersettings/topics');
define('URL_TOPICS_LIST', PREFIX.'mastersettings/topics/list');
define('URL_TOPICS_ADD', PREFIX.'mastersettings/topics/add');
define('URL_TOPICS_EDIT', PREFIX.'mastersettings/topics/edit');
define('URL_TOPICS_DELETE', PREFIX.'mastersettings/topics/delete/');
define('URL_TOPICS_GET_PARENT_TOPICS', PREFIX.'mastersettings/topics/get-parents-topics/');

define('URL_TOPICS_IMPORT', PREFIX.'mastersettings/topics/import');
//EMAIL TEMPLATES MODULE
define('URL_EMAIL_TEMPLATES', PREFIX.'email/templates');
define('URL_EMAIL_TEMPLATES_ADD', PREFIX.'email/templates/add');
define('URL_EMAIL_TEMPLATES_EDIT', PREFIX.'email/templates/edit');
define('URL_EMAIL_TEMPLATES_DELETE', PREFIX.'email/templates/delete/');

//INSTRUCTIONS MODULE
define('URL_INSTRUCTIONS', PREFIX.'exam/instructions/list');
define('URL_INSTRUCTIONS_ADD', PREFIX.'exams/instructions/add');
define('URL_INSTRUCTIONS_EDIT', PREFIX.'exams/instructions/edit/');
define('URL_INSTRUCTIONS_DELETE', PREFIX.'exams/instructions/delete/');
define('URL_INSTRUCTIONS_GETLIST', PREFIX.'exams/instructions/getList');

//LANGUAGES MODULE
define('URL_LANGUAGES_LIST', PREFIX.'languages/list');
define('URL_LANGUAGES_ADD', PREFIX.'languages/add');
define('URL_LANGUAGES_EDIT', PREFIX.'languages/edit');
define('URL_LANGUAGES_UPDATE_STRINGS', PREFIX.'languages/update-strings/');
define('URL_LANGUAGES_DELETE', PREFIX.'languages/delete/');
define('URL_LANGUAGES_GETLIST', PREFIX.'languages/getList/');
define('URL_LANGUAGES_MAKE_DEFAULT', PREFIX.'languages/make-default/');

//SETTINGS MODULE
define('URL_SETTINGS_LIST', PREFIX.'mastersettings/settings');
define('URL_SETTINGS_VIEW', PREFIX.'mastersettings/settings/view/');
define('URL_SETTINGS_ADD', PREFIX.'mastersettings/settings/add');
define('URL_SETTINGS_EDIT', PREFIX.'mastersettings/settings/edit/');
define('URL_SETTINGS_DELETE', PREFIX.'mastersettings/settings/delete/');
define('URL_SETTINGS_GETLIST', PREFIX.'mastersettings/settings/getList/');
define('URL_SETTINGS_ADD_SUBSETTINGS', PREFIX.'mastersettings/settings/add-sub-settings/');

//CONSTANST FOR USERS MODULE
define('URL_USERS', PREFIX.'users');
define('URL_USERS_ROLE', PREFIX.'users/staff/');
define('URL_USERS_GETLIST', PREFIX . 'users/list/getList/');
define('URL_USER_DETAILS', PREFIX.'users/details/');
define('URL_USER_DETAILS_COACH', PREFIX.'users/details-coach/');
define('URL_USER_DETAILS_FACILITATOR', PREFIX.'users/details-facilitator/');
define('URL_USERS_EDIT', PREFIX.'users/edit/');
define('URL_USERS_ADD', PREFIX.'users/create');
define('URL_USERS_DELETE', PREFIX.'users/delete/');
define('URL_USERS_SETTINGS', PREFIX.'users/settings/');
define('URL_USERS_CHANGE_PASSWORD', PREFIX.'users/change-password/');
define('URL_USERS_LOGOUT', PREFIX.'logout');
define('URL_PARENT_LOGOUT', PREFIX.'parent-logout');
define('URL_USERS_REGISTER', PREFIX.'register');
define('URL_USERS_UPDATE_PARENT_DETAILS', PREFIX.'users/parent-details/');
define('URL_SEARCH_PARENT_RECORDS', PREFIX . 'users/search/parent');
define('URL_USERS_IMPORT', PREFIX . 'users/import');
define('URL_USERS_IMPORT_REPORT', PREFIX . 'users/import-report');
define('URL_FORGOT_PASSWORD', PREFIX . 'users/forgot-password');

define('URL_USERS_COACH_REQUESTS', PREFIX . 'users/coach-requests');
define('URL_USERS_COACH_REQUESTS_GETLIST', PREFIX . 'users/coach-requests/getList/');



///////////////////
//STUDENT MODULE //
///////////////////

//STUDENT NAVIGATION
define('URL_STUDENT_EXAM_CATEGORIES', PREFIX.'exams/student/categories');
define('URL_STUDENT_EXAM_ATTEMPTS', PREFIX.'exams/student/exam-attempts/');
define('URL_STUDENT_ANALYSIS_SUBJECT', PREFIX.'student/analysis/subject/');
define('URL_STUDENT_ANALYSIS_BY_EXAM', PREFIX.'student/analysis/by-exam/');
define('URL_STUDENT_SUBSCRIPTIONS_PLANS', PREFIX.'subscription/plans');
define('URL_STUDENT_LIST_INVOICES', PREFIX.'subscription/list-invoices/');


///////////////////
// STUDENT EXAMS //
///////////////////
define('URL_STUDENT_EXAM_ALL', PREFIX.'exams/student/exams/all');
define('URL_STUDENT_EXAMS', PREFIX.'exams/student/exams/');
define('URL_STUDENT_QUIZ_GETLIST', PREFIX.'exams/student/quiz/getList/');
define('URL_STUDENT_QUIZ_GETLIST_ALL', PREFIX.'exams/student/quiz/getList/all');
define('URL_STUDENT_EXAM_GETATTEMPTS', PREFIX.'exams/student/get-exam-attempts/');
define('URL_STUDENT_EXAM_ANALYSIS_BYSUBJECT', PREFIX.'student/analysis/by-subject/');
define('URL_STUDENT_EXAM_ANALYSIS_BYEXAM', PREFIX.'student/analysis/get-by-exam/');
define('URL_STUDENT_EXAM_FINISH_EXAM', PREFIX.'exams/student/finish-exam/');


//PARENT NAVIGATION
define('URL_PARENT_CHILDREN', PREFIX.'parent/children');
define('URL_PARENT_CHILDREN_LIST', PREFIX.'parent/children_list');
define('URL_PARENT_CHILDREN_GETLIST', PREFIX.'parent/children/getList/');
define('URL_SUBSCRIBE', PREFIX.'subscription/subscribe/');

define('URL_PARENT_ANALYSIS_FOR_STUDENTS', PREFIX.'children/analysis');


//STUDENT BOOKMARKS
define('URL_BOOKMARKS', PREFIX.'student/bookmarks/');
define('URL_BOOKMARK_ADD', PREFIX.'student/bookmarks/add');
define('URL_BOOKMARK_DELETE', PREFIX.'student/bookmarks/delete/');
define('URL_BOOKMARK_DELETE_BY_ID', PREFIX.'student/bookmarks/delete_id/');
define('URL_BOOKMARK_AJAXLIST', PREFIX.'student/bookmarks/getList/');
define('URL_BOOKMARK_SAVED_BOOKMARKS', PREFIX.'student/bookmarks/getSavedList');


//EXAM SERIES
define('URL_EXAM_SERIES', PREFIX.'exams/exam-series');
define('URL_EXAM_SERIES_ADD', PREFIX.'exams/exam-series/add');
define('URL_EXAM_SERIES_DELETE', PREFIX.'exams/exam-series/delete/');
define('URL_EXAM_SERIES_EDIT', PREFIX.'exams/exam-series/edit/');
define('URL_EXAM_SERIES_AJAXLIST', PREFIX.'exams/exam-series/getList');
define('URL_EXAM_SERIES_UPDATE_SERIES', PREFIX.'exams/exam-series/update-series/');
define('URL_EXAM_SERIES_GET_EXAMS', PREFIX.'exams/exam-series/get-exams');


define('URL_STUDENT_EXAM_SERIES_LIST', PREFIX.'exams/student-exam-series/list');
define('URL_STUDENT_EXAM_SERIES_VIEW_ITEM', PREFIX.'exams/student-exam-series/');



define('URL_PAYMENTS_CHECKOUT', PREFIX.'payments/checkout/');


define('URL_PAYMENTS_LIST', PREFIX.'payments/list/');

define('URL_DONATIONS_LIST', PREFIX.'donations/list/');

define('URL_PAYNOW', PREFIX.'payments/paynow/');
define('URL_PAYPAL_PAYMENT_SUCCESS', PREFIX.'payments/paypal/status-success');
define('URL_PAYPAL_PAYMENT_CANCEL', PREFIX.'payments/paypal/status-cancel');

define('URL_PAYPAL_DONATION_SUCCESS', PREFIX.'donation/paypal/status-success');
define('URL_PAYPAL_DONATION_CANCEL', PREFIX.'donation/paypal/status-cancel');

define('URL_PAYPAL_PAYMENTS_AJAXLIST', PREFIX.'payments/getList/');
define('URL_PAYPAL_DONATIONS_AJAXLIST', PREFIX.'donations/getList/');

define('URL_PAYU_PAYMENT_SUCCESS', PREFIX.'payments/payu/status-success');
define('URL_PAYU_PAYMENT_CANCEL', PREFIX.'payments/payu/status-cancel');
define('URL_UPDATE_OFFLINE_PAYMENT', PREFIX.'payments/offline-payment/update');

//COUPONS MODULE
define('URL_COUPONS', PREFIX.'coupons/list');
define('URL_COUPONS_ADD', PREFIX.'coupons/add');
define('URL_COUPONS_EDIT', PREFIX.'coupons/edit/');
define('URL_COUPONS_DELETE', PREFIX.'coupons/delete/');
define('URL_COUPONS_GETLIST', PREFIX.'coupons/getList');

define('URL_COUPONS_VALIDATE', PREFIX.'coupons/validate-coupon');
define('URL_COUPONS_USAGE', PREFIX.'coupons/get-usage');
define('URL_COUPONS_USAGE_AJAXDATA', PREFIX.'coupons/get-usage-data');



// Notifications Module
define('URL_ADMIN_NOTIFICATIONS', PREFIX.'admin/notifications');
define('URL_ADMIN_NOTIFICATIONS_ADD', PREFIX.'admin/notifications/add');
define('URL_ADMIN_NOTIFICATIONS_EDIT', PREFIX.'admin/notifications/edit/');
define('URL_ADMIN_NOTIFICATIONS_DELETE', PREFIX.'admin/notifications/delete/');
define('URL_ADMIN_NOTIFICATIONS_GETLIST', PREFIX.'admin/notifications/getList');

//Notifications Student
define('URL_NOTIFICATIONS', PREFIX.'notifications/list');
define('URL_NOTIFICATIONS_VIEW', PREFIX.'notifications/show/');




//LMS MODULE
define('URL_LMS_CATEGORIES', PREFIX.'lms/categories');
define('URL_LMS_CATEGORIES_ADD', PREFIX.'lms/categories/add');
define('URL_LMS_CATEGORIES_EDIT', PREFIX.'lms/categories/edit/');
define('URL_LMS_CATEGORIES_DELETE', PREFIX.'lms/categories/delete/');
define('URL_LMS_CATEGORIES_GETLIST', PREFIX.'lms/categories/getList');

// LMS CONTENT
define('URL_LMS_CONTENT', PREFIX.'lms/content');
define('URL_LMS_CONTENT_ADD', PREFIX.'lms/content/add');
define('URL_LMS_CONTENT_EDIT', PREFIX.'lms/content/edit/');
define('URL_LMS_CONTENT_DELETE', PREFIX.'lms/content/delete/');
define('URL_LMS_CONTENT_GETLIST', PREFIX.'lms/content/getList');
define('URL_LMS_CONTENT_GETLIST_SLUG', PREFIX.'lms/content/getList/');


//LMS SERIES
define('URL_LMS_SERIES', PREFIX.'lms/series');
define('URL_LMS_SERIES_ADD', PREFIX.'lms/series/add');
define('URL_LMS_SERIES_DELETE', PREFIX.'lms/series/delete/');
define('URL_LMS_SERIES_EDIT', PREFIX.'lms/series/edit/');
define('URL_LMS_SERIES_AJAXLIST', PREFIX.'lms/series/getList');
define('URL_LMS_SERIES_UPDATE_SERIES', PREFIX.'lms/series/update-series/');
define('URL_LMS_SERIES_GET_SERIES', PREFIX.'lms/series/get-series');
define('VALID_IS_PAID_TYPE', PREFIX.'user/paid/');

//LMS STUDENT SERIES
define('URL_STUDENT_LMS_CATEGORIES', PREFIX.'learning-management/categories');
define('URL_STUDENT_LMS_CATEGORIES_VIEW', PREFIX.'learning-management/view/');
define('URL_STUDENT_LMS_SERIES', PREFIX.'learning-management/series');
define('URL_STUDENT_LMS_SERIES_VIEW', PREFIX.'learning-management/series/');


//Results Constants
define('URL_RESULTS_VIEW_ANSWERS', PREFIX.'student/exam/answers/');

 define('URL_COMPARE_WITH_TOPER', PREFIX.'toppers/compare-with-topper/');

// FEEDBACK SYSTEM
define('URL_FEEDBACK_SEND', PREFIX.'feedback/send');
define('URL_FEEDBACKS', PREFIX.'feedback/list');
define('URL_FEEDBACK_VIEW', PREFIX.'feedback/view-details/');
define('URL_FEEDBACK_DELETE', PREFIX.'feedback/delete/');
define('URL_FEEDBACKS_GETLIST', PREFIX.'feedback/getlist');

//MESSAGES
define('URL_MESSAGES', PREFIX.'messages');
define('URL_MESSAGES_SHOW', PREFIX.'messages/');
define('URL_MESSAGES_CREATE', PREFIX.'messages/create');


define('URL_GENERATE_CERTIFICATE', PREFIX.'result/generate-certificate/');


define('URL_PAYMENT_REPORTS', PREFIX.'payments-report/');
define('URL_ONLINE_PAYMENT_REPORTS', PREFIX.'payments-report/online');
define('URL_ONLINE_PAYMENT_REPORT_DETAILS', PREFIX.'payments-report/online/');
define('URL_ONLINE_PAYMENT_REPORT_DETAILS_AJAX', PREFIX.'payments-report/online/getList/');
define('URL_OFFLINE_PAYMENT_REPORTS', PREFIX.'payments-report/offline');
define('URL_OFFLINE_PAYMENT_REPORT_DETAILS', PREFIX.'payments-report/offline/');
define('URL_OFFLINE_PAYMENT_REPORT_DETAILS_AJAX', PREFIX.'payments-report/offline/getList/');

define('URL_PAYMENT_REPORT_EXPORT', PREFIX.'payments-report/export');
define('URL_GET_PAYMENT_RECORD', PREFIX.'payments-report/getRecord');
define('URL_PAYMENT_APPROVE_OFFLINE_PAYMENT', PREFIX.'payments/approve-reject-offline-request');


define('URL_SEND_SMS', PREFIX.'sms/index');
define('URL_SEND_SMS_NOW', PREFIX.'sms/send');

define('URL_FACEBOOK_LOGIN', PREFIX.'auth/facebook');
define('URL_GOOGLE_LOGIN', PREFIX.'auth/google');

// Missed Constants
define( 'OWNER_ROLE_ID', 1 );
define( 'ADMIN_ROLE_ID', 2 );
define( 'EXECUTIVE_ROLE_ID', 3 );
define( 'USER_ROLE_ID', 5 );

// New URLS
define( 'ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
$folder = explode( '/', $_SERVER[ 'SCRIPT_NAME' ] );
$folder_name = '';
if ( ! empty( $folder ) ) {
	foreach ( $folder as $f ) {
		if ( ! empty( $f ) ) {
			$folder_name = $f . '/'; break;
		}
	}
}

$host = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
define( 'HOST', $host.'://' . $_SERVER['HTTP_HOST'] . '/' );
define( 'LMS_HOST', $host.'://' . $_SERVER['HTTP_HOST'] . '/' . $folder_name );
// define( 'HOST', '' );
// define( 'URL_USERS_DASHBOARD', HOST . 'my-account' );
define( 'URL_USERS_DASHBOARD', HOST . $folder_name . 'dashboard' );
define( 'URL_USERS_DASHBOARD_USER', HOST . $folder_name . 'user-dashboard' );
define( 'URL_SYNC_WP_USERS', HOST . 'wp-laravel-sync' );
define( 'URL_LOGIN_WP_USER', HOST . 'login-wp-user' );
// define( 'URL_USERS_LOGIN', HOST . 'sign-in' );
define('URL_USERS_LOGIN', PREFIX.'login');
// define( 'URL_USERS_REGISTER', HOST . 'registration' );
define( 'URL_DASHBOARD', HOST . $folder_name . 'dashboard' );
define( 'URL_WP_LOGOUT', HOST . 'wp-logout' );
define( 'URL_LMS_LOGOUT', HOST . $folder_name . '/lms-logout' );
define( 'URL_WP_LOGIN', HOST . 'wp-login-redirect/' );
define( 'NEW_CSS', PREFIX1.'css/newcss/');
define( 'NEW_JS', PREFIX1.'js/newjs/');

//Front End LMS Categies and Series
define('URL_FRONTEND_LMSCATEGORIES', PREFIX . 'lms/categories');
define('URL_FRONTEND_LMSSERIES', PREFIX . 'lms/course/');
define('URL_FRONTEND_LMSLESSON', PREFIX . 'lms/course-lessons/');
define('URL_FRONTEND_LMSSINGLELESSON', PREFIX . 'single-lesson/');
define('URL_FRONTEND_GROUP_LMSSINGLELESSON', PREFIX . 'group-single-lesson/');
define('URL_FRONTEND_SAVE_DATA', PREFIX . 'single-lesson/save-data');
define('URL_FRONTEND_GET_DATA', PREFIX . 'single-lesson/get-data');

define( 'URL_FRONTEND_SUBJECT_COURSES', PREFIX . 'lms/pathway-courses/pathway/' );
define( 'URL_FRONTEND_CATEGORY_COURSES', PREFIX . 'lms/category-courses/category/' );
define( 'URL_FRONTEND_AUTHOR_COURSES', PREFIX . 'lms/author-courses/author/' );

define( 'URL_FRONTEND_RECOMMENDED_COURSES', PREFIX . 'recomended-lms-courses' );
define( 'URL_FRONTEND_COURSE_LIST', PREFIX . 'lms-course-list/' );

define('URL_FRONTEND_AJAXLOGIN', PREFIX . 'ajax-login');

define( 'UPLOADS_PATH', ROOT_PATH . $folder_name . 'public/uploads/' );
define( 'IMAGE_PATH_UPLOAD_LMS_CONTENTS_PATH', UPLOADS_PATH . 'lms/content/' );
define( 'URL_STUDENT_START_EXAM', PREFIX . 'exams/student/start-exam' );
define( 'URL_STUDENT_EXAM_INSTRUCTIONS', PREFIX . 'exams/student/quiz/take-exam' );
define( 'URL_STUDENT_MY_COURSES', PREFIX . 'my-courses' );
define( 'URL_STUDENT_MY_GROUPS_AJAXLIST', PREFIX.'lms/my-groups/getList');
define( 'URL_STUDENT_MY_GROUPS_DELETE', PREFIX . 'lms/my-groups/delete/' );
define( 'URL_ADMIN_ALL_LMSGROUPS', PREFIX . 'admin/all-groups' );

// LMS Groups Start
define( 'URL_STUDENT_MY_GROUPS', PREFIX . 'lms/my-groups' );
define( 'URL_STUDENT_OTHER_LMS_GROUPS', PREFIX . 'lms/other-groups' );
define( 'URL_STUDENT_ADD_GROUP', PREFIX . 'lms/groups/add' );
define( 'URL_STUDENT_UPDATE_GROUP', PREFIX . 'lms/groups/edit/' );
define( 'URL_STUDENT_DELETE_GROUP', PREFIX . 'lms/groups/delete/' );
define( 'URL_STUDENT_DASHBOARD_GROUP', PREFIX . 'lms/group-dashboard/' );
define( 'URL_STUDENT_ADD_GROUP_CONTENTS', PREFIX . 'lms-group-contents/add/' );
define( 'URL_STUDENT_UPDATE_GROUP_CONTENTS', PREFIX.'lms-group-contents/update/');
define( 'URL_STUDENT_LMS_CONTENT_GETLIST', PREFIX . 'lms-content/getListSlug/' );

define( 'URL_COURSE_LESSONS_GETLIST', PREFIX . 'course-lessons/getListSlug/' );


define( 'URL_STUDENT_UPDATE_GROUP_INVITATIONS', PREFIX . 'lms-group-invitations/' );
define( 'URL_STUDENT_UPDATE_GROUP_INVITATIONS_ADD', PREFIX . 'lms-group-invitations/add/' );
define( 'URL_STUDENT_UPDATE_GROUP_INVITATIONS_GETLIST', PREFIX . 'lms-group-invitations/getList/' );
define( 'URL_STUDENT_UPDATE_GROUP_INVITATIONS_ADD_GETUSERS', PREFIX . 'lms-group-invitations/add/get-users/' );
define( 'URL_STUDENT_UPDATE_GROUP_INVITATIONS_ADD_REMOVE_USERS', PREFIX . 'lms-group-invitations/add-remove-user' );
define( 'URL_STUDENT_GROUP_USER_STATUS', PREFIX . 'lms-groups/' );
define('URL_STUDENT_GROUP_CHANGE_STATUS', PREFIX.'lms-groups/chagne-status/');

define('IMAGE_PATH_UPLOAD_LMS_GROUPS', UPLOADS.'lms/groups/');
define('IMAGE_PATH_UPLOAD_LMS_GROUPS_THUMB', UPLOADS.'lms/groups/thumb/');
// LMS Groups END

define( 'URL_INVITE_OTHER_FRIENDS', PREFIX . 'invite-other-friends' );
define( 'URL_SEND_MAIL_OTHER_FRIENDS', PREFIX . 'invite-other-friends' );


define( 'WP_DOCUMENT', HOST . '/KG-Documentation/pages/index.html' );
define( 'LMS_DOCUMENT', HOST . '/LMS-Documentation/index.html' );

define('URL_USERS_CONFIRM', PREFIX.'confirm');

define( 'URL_SYNC_LARAVEL_USERS', HOST . 'sync-laravel-users?user=' );
define('URL_USERS_FORGOTPASSWORD', PREFIX.'forgot-password');
define('URL_USERS_RESETPASSWORD', PREFIX.'reset-password');

// Donations
define( 'URL_STUDENT_DONATIONS_INDEX', PREFIX . 'donation' );
define( 'URL_STUDENT_DONATIONS_PROCESS', PREFIX . 'donation/process' );
define( 'URL_STUDENT_DONATIONS_SUMMARY', PREFIX . 'lms/donations-report' );
define( 'URL_STUDENT_DONATIONS_LIST', PREFIX . 'lms/donations-list' );
define('URL_ONLINE_DONATIONS_REPORT_DETAILS', PREFIX.'donations-report/online/');
define('URL_ONLINE_DONATIONS_REPORT_DETAILS_AJAX', PREFIX.'donations-report/online/getList/');
define('URL_USER_CHANGE_STATUS', PREFIX.'users/chagne-status/');
define( 'URL_STUDENT_DONATION_SUCCESS', PREFIX . 'donation-success' );
define( 'URL_STUDENT_DONATION_FAILED', PREFIX . 'donation-failed' );

// LMS Modules
define('URL_LMS_MODULES', PREFIX.'lms/modules');
define('URL_LMS_MODULES_ADD', PREFIX.'lms/modules-add');

define('URL_LMS_GET_COURSES', PREFIX.'lms/get-courses/');
define('URL_LMS_GET_COURSES_LIST', PREFIX.'lms/get-courses-list/');


// Dashboard
define('URL_ADMIN_DASHBOARDS', PREFIX.'dashboard-promotion');
define('URL_ADMIN_DASHBOARDS_ADD', PREFIX.'dashboard-add');
define('URL_ADMIN_DASHBOARDS_EDIT', PREFIX.'dashboard-edit');

//LMS SERIES Master
define('URL_LMS_SERIES_MASTER', PREFIX.'lms/master-series');
define('URL_LMS_SERIES_MASTER_ADD', PREFIX.'lms/master-series/add');
define('URL_LMS_SERIES_MASTER_DELETE', PREFIX.'lms/master-series/delete/');
define('URL_LMS_SERIES_MASTER_EDIT', PREFIX.'lms/master-series/edit/');
define('URL_LMS_SERIES_MASTER_AJAXLIST', PREFIX.'lms/master-series/getList');
define('URL_LMS_SERIES_MASTER_UPDATE_SERIES', PREFIX.'lms/master-series/update-series/');
define('URL_LMS_SERIES_MASTER_GET_SERIES', PREFIX.'lms/master-series/get-series');
define('URL_LMS_SERIES_MASTER_GET_SERIES_CATEGORY', PREFIX.'lms/master-series-categories/');

//define('URL_LMS_MODULES', PREFIX.'lms/modules');
//define('URL_LMS_MODULES_ADD', PREFIX.'lms/modules-add');
define( 'URL_LMS_SHOW_SERIESES', PREFIX . 'lms/categories/serieses/' );
define( 'URL_LMS_SHOW_COUSES', PREFIX . 'lms/categories/serieses-courses/' );
define( 'URL_LMS_SHOW_MODULES_LESSONS', PREFIX . 'lms/categories/serieses-courses-modules-lessons/' );
define( 'URL_LMS_SHOW_MODULE_LESSONS', PREFIX . 'lms/categories/serieses-courses-module-lessons/' );

define( 'URL_SHOW_LMSMODE', PREFIX . 'master-settings/lmsmode' );
define( 'URL_SHOW_LAYOUT', PREFIX . 'master-settings/layout' );

define( 'URL_GET_COURSE_MODULES', PREFIX . 'course/modules' );
define( 'URL_GET_USER_SEND_EMAIL', PREFIX . 'users/send-email' );

// Translation Issues
define( 'URL_TRANSLATION_REQUESTS', PREFIX . 'translation-requests' );
define('URL_TRANSLATION_REQUESTS_DELETE', PREFIX.'translation-requests/delete/');
define('URL_TRANSLATION_REQUEST_VIEW', PREFIX.'translation-requests/view/');
define('URL_SEND_TRANSLATION_REQUEST', PREFIX.'send-translation-request');

// Site Issues
define( 'URL_SITE_ISSUES', PREFIX . 'site-issues' );
define('URL_SITE_ISSUES_DELETE', PREFIX.'site-issues/delete/');
define('URL_SITE_ISSUES_VIEW', PREFIX.'site-issues/view/');
define( 'URL_NEWSLETTER_SUBSCRIPTIONS', PREFIX . 'newsletter-subscriptions' );

define( 'FRONT_PAGE_LENGTH', 6 );

define( 'PATHWAY_START_TITLE', 'PathwayStart' );
define( 'PATHWAY_FORWARD_TITLE', 'PathwayForward' );
define( 'PATHWAY_FOREVER_TITLE', 'PathwayForever' );

define( 'PATHWAY_START_ID', '17' );
define( 'PATHWAY_FORWARD_ID', '18' );
define( 'PATHWAY_FOREVER_ID', '19' );

define( 'URL_START_COURSE', PREFIX . 'start-course' );
define( 'URL_GLOBAL_SEARCH', PREFIX . 'global-search' );

define( 'URL_SPECIAL_COURSES', PREFIX . 'special-courses' );

define( 'URL_SEND_ACTIVATION_MAIL', PREFIX . 'send-activation-mail' );

define( 'URL_CHANGE_EMAIL', PREFIX . 'users/change-email' );
define('URL_CONFIRM_CHANGE_EMAIL', PREFIX.'users/confirm/change-email/');

define( 'URL_COURSE_LESSONS', PREFIX . 'course/lessons/' );

define( 'URL_MANAGE_GROUP_REQUESTS', PREFIX . 'manage-groups/' );
define( 'URL_MANAGE_GROUP_REQUESTS_DIRECT', PREFIX . 'manage-groups-requests/' );
define( 'URL_PROFILE_PRIVACY_SETTINGS', PREFIX . 'users/profile-privacy-settings' );

define('URL_LMS_SHOW_GROUP_COURSES', PREFIX.'lms/group/courses/');
define('URL_LMS_ADD_GROUP_COURSES', PREFIX.'lms/add-group/courses/');
define('URL_LMS_GROUP_COURSES', PREFIX.'lms/add-group/courses/');
define('URL_LMS_GROUP_GET_COURSES', PREFIX.'lms/group/getCoursesList');

// Group Posts
define('URL_LMS_SHOW_GROUP_POSTS', PREFIX.'lms/group/posts/');
define('URL_LMS_ADD_GROUP_POSTS', PREFIX.'lms/add-group/posts/');
define('URL_LMS_GROUP_POSTS', PREFIX.'lms/add-group/posts/');
define('URL_LMS_GROUP_GET_POSTS', PREFIX.'lms/group/getPostsList');

define('URL_COURSE_COACH_REQUEST', PREFIX . 'course/coach-request');
define('URL_WITHDRAW_COACH_REQUEST', PREFIX . 'course/withdraw-coach-request');

define('URL_MY_FACILITATORS', PREFIX . 'users/my-facilitators');
define('URL_MY_FACILITATORS_GETLIST', PREFIX.'users/my-facilitators/getMyFacilitators');
define('URL_ASSIGN_FACILITATORS', PREFIX . 'users/assign-facilitators/');
define('URL_ASSIGN_FACILITATORS_ADDTOBAG', PREFIX . 'users/facilitators/add');
define('URL_ASSIGN_FACILITATORS_REMOVEFROMBAG', PREFIX . 'users/facilitators/remove');
define('URL_ASSIGN_FACILITATORS_LIST_GETLIST', PREFIX . 'users/assign-facilitators-list/getList');
define('URL_GROUP_COMMENTS_GETLIST', PREFIX . 'groups/get-comments/');
define('URL_COACH_COMMENT_SAVE', PREFIX . 'groups/save-comments/');

define('URL_CONTENT_COMMENTS_GETLIST', PREFIX . 'content/get-comments/');
define('URL_CONTENT_COMMENT_SAVE', PREFIX . 'content/save-comments/');

//Special Role MODULE
define('URL_SPECIALROLE', PREFIX.'special-role/role');
define('URL_SPECIALROLE_ADD', PREFIX.'special-role/role/add');
define('URL_SPECIALROLE_EDIT', PREFIX.'special-role/role/edit');
define('URL_SPECIALROLE_DELETE', PREFIX.'special-role/role/delete/');

define('LESSONS_ON_COURSE_PAGE', 6);

// WP Tables
define('WP_TABLE_PREFIX', 'wp_' );
define('TBL_WP_USERS', WP_TABLE_PREFIX . 'users');
define('TBL_WP_POSTS', WP_TABLE_PREFIX . 'posts');
define('TBL_WP_POSTMETA', WP_TABLE_PREFIX . 'postmeta');
define('TBL_WP_TERM_TAXONOMY', WP_TABLE_PREFIX . 'term_taxonomy');
define('TBL_WP_TERMS', WP_TABLE_PREFIX . 'terms');
define('TBL_WP_TERMMETA', WP_TABLE_PREFIX . 'termmeta');
define('TBL_LMS_USERS_MY_COURSES', 'users_my_courses');
define('TBL_LMS_USERS_COMPLETED_COURSES', 'users_completed_courses');

define('PATHWAYSTART_ID', 17);
define('PATHWAYFORWARD_ID', 18);
define('PATHWAYFOREVER_ID', 19);
define('PATHWAYFORWARD_AWAYFORWARD_ID', 71); // "A Way Forward" Course Id.
define('PATHWAYFORWARD_7CS_BEGINNER_ID', 93); // "The 7C's - beginner" Course Id.
define('PATHWAYFORWARD_7CS_ID', 100); // "7C's" Course Id.
define('PATHWAYFORWARD_SERVEINGOTHERS_ID', 94); // "The 7C's - beginner" Course Id.