<?php

namespace App\Http\Controllers;
use \App;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Lmscategory;
use App\LmsContent;
use App\Plan;
use Yajra\Datatables\Datatables;
use DB;
use Auth;
use App\Paypal; 
use App\Donations;
use Carbon;

class DonationsController extends Controller
{
     
    public function __construct()
    {
    	// $this->middleware('auth');
    }

    public function index()
    {
        $data['title']              = getPhrase('donate');
        $data['layout']             = getLayout( 'exams' );
    	return view('donations.index', $data);
    }

    /**
     * This method does the subscription process
     * If User slug is passed as parameter the subscription will be done to 
     * the specified slug user
     * If the slug is empty, the subscription will be done to the loggedin user
     * @param  Request $request   [description]
     * @param  [type]  $plan_slug [description]
     * @return [type]             [description]
     */
    public function processDonation( Request $request )
    {   
		$rules = [ 
			'donation_amount' => 'required',
			'donation_amount_enter' => 'required_if:donation_amount,==,other|numeric'
		];
		if ( ! Auth::check() ) {
			$rules['first_name'] = 'required|max:50';
			$rules['last_name'] = 'required|max:50';
		}
		
		$customMessages = [
			'donation_amount.required' => getPhrase( 'Please enter donation amount' ),
			'donation_amount_enter.required_if' => getPhrase( 'Please enter donation amount' ),
			'donation_amount_enter.numeric' => getPhrase( 'Please enter valid amount' ),
		];
		$this->validate($request, $rules, $customMessages);
		
		$payment_gateway = $request->gateway;
		if($payment_gateway=='paypal') {
			if(!getSetting('paypal', 'module'))
			{
				flash('Ooops...!', 'this_payment_gateway_is_not_available', 'error');          
				return back();
			}
			$details = array();
			if ( ! Auth::check() ) {
				$details['email_address'] = $request->email_address;
				$details['first_name'] = $request->first_name;
				$details['last_name'] = $request->last_name;
			} else {
				$details['email_address'] = Auth::User()->email;
				$details['first_name'] = Auth::User()->first_name;
				$details['last_name'] = Auth::User()->last_name;
			}
			if ( $request->donation_amount == 'other' ) {
				$details['cost'] = $request->donation_amount_enter;
			} else {
				$details['cost'] = $request->donation_amount;
			}
			$details['payment_gateway'] = $payment_gateway;
			$details['payment_status'] = PAYMENT_STATUS_PENDING;
			
			$token = $this->preserveBeforeSave( $details );
			
			$paypal = new Paypal();
			$paypal->config['return'] 		= URL_PAYPAL_DONATION_SUCCESS.'?token='.$token;
			$paypal->config['cancel_return'] 	= URL_PAYPAL_DONATION_CANCEL.'?token='.$token;
			$paypal->invoice = $token;
			$paypal->add('Donation', $details['cost']); //ADD  item
			$paypal->pay(); //Proccess the payment
		}		
		dd( getPhrase( 'please wait...' ) );
    }
	
	public function preserveBeforeSave( $details )
	{
		$donation = new Donations();
		$donation->slug = $donation::makeSlug(getHashCode());
		if ( Auth::check() ) {
			$user = Auth::User();
			$donation->user_id = $user->id;
			$donation->email_address = $user->email;	
			$donation->first_name = $user->first_name;	
			$donation->last_name = $user->last_name;	
		} else {
			$donation->user_id = 0;	
			$donation->email_address = $details['email_address'];	
			$donation->first_name = $details['first_name'];	
			$donation->last_name = $details['last_name'];				
		}
		foreach( $details as $key => $val ) {
			$donation->$key = $val;
		}
		$donation->save();
		return $donation->slug;
	}
	
	public function paypal_success(Request $request)
    {
       if(env('DEMO_MODE')) {
        flash('success', 'your_subscription_was_successfull', 'overlay');
      }
	  
		$params = explode('?token=',$_SERVER['REQUEST_URI']) ;

		if(!count($params))
		return FALSE;

		$slug = $params[1];

		$response = $request->all();


    	if($this->paymentSuccess($request))
    	{
    		
			$payment_record = Donations::where('slug', '=', $slug)->first();
			
			//PAYMENT RECORD UPDATED SUCCESSFULLY
    		//PREPARE SUCCESS MESSAGE
			flash('success', 'your_donation_was_successfull', 'overlay');
			$email_template = 'subscription_success';
           try{
			  sendEmail($email_template, array('username'=>$payment_record->first_name . ' ' . $payment_record->last_name, 
			  'plan' => '',
			  'to_email' => $payment_record->email_address));
			}
        catch(Exception $ex)
       {
        $message .= getPhrase('\ncannot_send_email_to_user, please_check_your_server_settings');
        $exception = 1;
       }

    	}
    	else {
    	//PAYMENT RECORD IS NOT VALID
    	//PREPARE METHOD FOR FAILED CASE
    	  pageNotFound();
    	}
		//REDIRECT THE USER BY LOADING A VIEW
		if ( Auth::check() ) {
			return redirect(URL_DONATIONS_LIST.Auth::User()->slug);
		} else {
			return redirect( URL_STUDENT_DONATION_SUCCESS );
		}
    	
    }
	
	/**
     * Common method to handle success payments
     * @return [type] [description]
     */
    protected function paymentSuccess(Request $request)
    {

		if(env('DEMO_MODE')) {
			return TRUE;
		}

    	$params = explode('?token=',$_SERVER['REQUEST_URI']) ;
    
		if(!count($params))
			return FALSE;
    
		$slug = $params[1];
    	
    	 
   
    	$payment_record = Donations::where('slug', '=', $slug)->first();
    	
    	if($this->processPaymentRecord($payment_record))
    	{
    		$payment_record->payment_status = PAYMENT_STATUS_SUCCESS;
			//Capcture all the response from the payment.
			//In case want to view total details, we can fetch this record
			$payment_record->transaction_record = json_encode($request->request->all());
			
			$payment_record->save();

			return TRUE;
        }
      return FALSE;
    }
	
	/**
     * This method Process the payment record by validating through 
     * the payment status and the age of the record and returns boolen value
     * @param  Payment $payment_record [description]
     * @return [type]                  [description]
     */
    protected  function processPaymentRecord(Donations $payment_record)
    {

    	if(!$this->isValidPaymentRecord($payment_record))
    	{
    		flash('Oops','invalid_record', 'error');
    		return FALSE;
    	}

    	if($this->isExpired($payment_record))
    	{
    		flash('Oops','time_out', 'error');
    		return FALSE;
    	}

    	return TRUE;
    }
	
	/**
     * This method validates the payment record before update the payment status
     * @param  [type]  $payment_record [description]
     * @return boolean                 [description]
     */
    protected function isValidPaymentRecord(Donations $payment_record)
    {
    	$valid = FALSE;
    	if($payment_record)
    	{
    		if($payment_record->payment_status == PAYMENT_STATUS_PENDING )
    			$valid = TRUE;
    	}
    	return $valid;
    }
	
	/**
     * This method checks the age of the payment record
     * If the age is > than MAX TIME SPECIFIED (30 MINS), it will update the record to aborted state
     * @param  payment $payment_record [description]
     * @return boolean                 [description]
     */
    protected function isExpired(Donations $payment_record)
    {

    	$is_expired = FALSE;
    	$to_time = strtotime(Carbon\Carbon::now());
		$from_time = strtotime($payment_record->updated_at);
		$difference_time = round(abs($to_time - $from_time) / 60,2);

		if($difference_time > PAYMENT_RECORD_MAXTIME)
		{
			$payment_record->payment_status = PAYMENT_STATUS_CANCELLED;
			$payment_record->save();
			return $is_expired =  TRUE;
		}
		return $is_expired;
    }
	
	public function paypal_cancel()
    {

    	if($this->paymentFailed())
    	{
    		//FAILED PAYMENT RECORD UPDATED SUCCESSFULLY
    		//PREPARE SUCCESS MESSAGE
    		  flash('Ooops...!', 'your_payment_was cancelled', 'overlay');
    	}
    	else {
    	//PAYMENT RECORD IS NOT VALID
    	//PREPARE METHOD FOR FAILED CASE
    	  pageNotFound();
    	}

    	//REDIRECT THE USER BY LOADING A VIEW
		if ( Auth::check() ) {
			$user = Auth::user();
			return redirect(URL_DONATIONS_LIST.$user->slug);
		} else {
			return redirect(URL_STUDENT_DONATION_FAILED);
		}
    	 
    }
	
	/**
     * Common method to handle payment failed records
     * @return [type] [description]
     */
    protected function paymentFailed()
    {
      if(env('DEMO_MODE')) {
       return TRUE;
      }

    	$params = explode('?token=',$_SERVER['REQUEST_URI']) ;
    
     if(!count($params))
      return FALSE;
    
    $slug = $params[1];
    	$payment_record = Donations::where('slug', '=', $slug)->first();
     

     	if(!$this->processPaymentRecord($payment_record))
     	{
    		return FALSE;
     	}
	
		$payment_record->payment_status = PAYMENT_STATUS_CANCELLED;
    	$payment_record->save();
    	
    	return TRUE;
    	 
    }

    public function paymentsList( $slug )
	{
		if ( ! Auth::check() ) {
			prepareBlockUserMessage();
			return back();
		}
		if(!isEligible($slug))
		  return back();

		$user = getUserWithSlug($slug);

		$data['is_parent']           = 0;
		$user = getUserWithSlug($slug);

		if(getRoleData($user->role_id)=='parent')
		$data['is_parent']           = 1;

		$data['user']       		= $user;
		$data['active_class']       = 'subscriptions';
		$data['title']              = getPhrase('donations_list');
		$data['layout']              = getLayout();

		$payment = new Donations();
		$records = $payment->updateTransactionRecords($user->id);
		foreach($records as $record)
		{
		$rec = Donations::where('id',$record->id)->first();
		$this->isExpired($rec);
		}

		return view('donations.list', $data);
	}
	
	public function getDatatable($slug)
    {

		$user = getUserWithSlug($slug);


		 
     $records = Donations::select([ 'cost', 'payment_gateway', 'updated_at','payment_status','id' ])
		 ->where('user_id', '=', $user->id)
            ->orderBy('updated_at', 'desc');

      
        $dta = Datatables::of($records)
        
        ->addColumn('action', function ($records) {
        	if(!($records->payment_status==PAYMENT_STATUS_CANCELLED || $records->payment_status==PAYMENT_STATUS_PENDING)) { 
          		$link_data = ' <a >View More</a>';
            	return $link_data;
        	}
        	return ;
        })
        ->editColumn('payment_status',function($records){

        	$rec = '';
        	if($records->payment_status==PAYMENT_STATUS_CANCELLED)
        	 $rec = '<span class="label label-danger">'.ucfirst($records->payment_status).'</span>';
        	elseif($records->payment_status==PAYMENT_STATUS_PENDING)
        		$rec = '<span class="label label-info">'.ucfirst($records->payment_status).'</span>';
        	elseif($records->payment_status==PAYMENT_STATUS_SUCCESS)
        		$rec = '<span class="label label-success">'.ucfirst($records->payment_status).'</span>';
        	return $rec;
        })
        ->editColumn('payment_gateway', function($records)
        {
          $text =  ucfirst($records->payment_gateway);

         if($records->payment_status==PAYMENT_STATUS_SUCCESS) {
          $extra = '<ul class="list-unstyled payment-col clearfix"><li>'.$text.'</li>';
          // $extra .='<li><p>Donation:'.$records->cost.'</p></li>';
		  // $extra .='<li><p>Aftr Dis.:'.$records->after_discount.'</p><p>Paid:'.$records->paid_amount.'</p></li>';
		  $extra .='</ul>';
          return $extra;
        }
          return $text;
        })
        ->removeColumn('id')
        ->removeColumn('action');                 
        return $dta->make();    	
    }
	
	/**
     * This method redirects the user to view the onlinepayments reports dashboard
     * It contains an optional slug, if slug is null it will redirect the user to dashboard
     * If the slug is success/failed/cancelled/all it will show the appropriate result based on slug status from payments table
     * @param  string $slug [description]
     * @return [type]       [description]
     */
    public function onlinePaymentsReport()
    {

      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }


      $data['active_class']       = 'reports';
      $data['title']              = getPhrase('donations');
      $data['payments']           = (object)$this->prepareSummaryRecord('online');
      $data['payments_chart_data']= (object)$this->getPaymentStats($data['payments']);
      $data['payments_monthly_data'] = (object)$this->getPaymentMonthlyStats();
      $data['payment_mode']      = 'online';
      $data['layout']             = getLayout();
      return view('donations.reports.payments-report', $data);  
    }
	
	/**
     * This method prepares different variations of reports based on the type
     * This is a common method to prepare online, offline and overall reports
     * @param  string $type [description]
     * @return [type]       [description]
     */
    public function prepareSummaryRecord($type='overall')
    {

      $payments = [];
      if($type=='online') {
        $payments['all'] = $this->getRecordsCount('online');

        $payments['success'] = $this->getRecordsCount('online', 'success');
        $payments['cancelled'] = $this->getRecordsCount('online', 'cancelled');
        $payments['pending'] = $this->getRecordsCount('online', 'pending');
      }
      else if($type=='offline') {
        $payments['all'] = $this->getRecordsCount('offline');

        $payments['success'] = $this->getRecordsCount('offline', 'success');
        $payments['cancelled'] = $this->getRecordsCount('offline', 'cancelled');
        $payments['pending'] = $this->getRecordsCount('offline', 'pending');
      }

      return $payments;
    }
	
	/**
     * This is a helper method for fetching the data and preparing payment records count
     * @param  [type] $type   [description]
     * @param  string $status [description]
     * @return [type]         [description]
     */
    public function getRecordsCount($type, $status='')
    {
      $count = 0;
      if($type=='online') {
        if($status=='')
          $count = Donations::where('payment_gateway', '!=', 'offline')->count();

        else
        {
          $count = Donations::where('payment_gateway', '!=', 'offline')
                            ->where('payment_status', '=', $status)
                            ->count();
        }
      }      
      else if($type=='offline')
      {
         if($status=='')
          $count = Donations::where('payment_gateway', '=', 'offline')->count();

        else
        {
          $count = Donations::where('payment_gateway', '=', 'offline')
                            ->where('payment_status', '=', $status)
                            ->count();
        } 
      }


      return $count;
    }
	
	/**
     * This method prepares the chart data for success and failed records
     * @param  [type] $payment_data [description]
     * @return [type]               [description]
     */
    public function getPaymentStats($payment_data)
    {
      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }
        
            $payment_dataset = [$payment_data->success, $payment_data->cancelled, $payment_data->pending];
            $payment_labels = [getPhrase('success'), getPhrase('cancelled'), getPhrase('pending')];
            $payment_dataset_labels = [getPhrase('total')];

            $payment_bgcolor = [getColor('',4),getColor('',9),getColor('',18)];
            $payment_border_color = [getColor('background',4),getColor('background',9),getColor('background',18)]; 

          $payments_stats['data']    = (object) array(
                                        'labels'            => $payment_labels,
                                        'dataset'           => $payment_dataset,
                                        'dataset_label'     => $payment_dataset_labels,
                                        'bgcolor'           => $payment_bgcolor,
                                        'border_color'      => $payment_border_color
                                        );
           $payments_stats['type'] = 'bar'; 
             $payments_stats['title'] = getPhrase('overall_statistics');

           return $payments_stats;
    }
	
	/**
     * This method returns the overall monthly summary of the payments made with status success
     * @return [type] [description]
     */
    public function getPaymentMonthlyStats($type = 'offline',$symbol='!=')
    {
      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }
          $paymentObject = new Donations();
            $payment_data = (object)$paymentObject->getSuccessMonthlyData('',$type, $symbol);
            

            $payment_dataset = [];
            $payment_labels = [];
            $payment_dataset_labels = [getPhrase('total')];
            $payment_bgcolor = [];
            $payment_border_color = []; 


            foreach($payment_data as $record)
            {
              $color_number = rand(0,999);
              // $payment_dataset[] = $record->total;
			  $payment_dataset[] = $record->cost;
              $payment_labels[]  = $record->month;
              $payment_bgcolor[] = getColor('',$color_number);
              $payment_border_color[] = getColor('background', $color_number);

            }

          $payments_stats['data']    = (object) array(
                                        'labels'            => $payment_labels,
                                        'dataset'           => $payment_dataset,
                                        'dataset_label'     => $payment_dataset_labels,
                                        'bgcolor'           => $payment_bgcolor,
                                        'border_color'      => $payment_border_color
                                        );
           $payments_stats['type'] = 'line'; 
           $payments_stats['title'] = getPhrase('payments_reports_in').' '.getCurrencyCode(); 

           return $payments_stats;
    }
	
	/**
     * This method list the details of the records
     * @param  [type] $slug [description]
     * @return [type]       [description]
     */
    public function listOnlinePaymentsReport( $slug )
    {
     if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }
      if(!in_array($slug, ['all','pending', 'success','cancelled']))
      {
        pageNotFound();
        return back();
      }

      $payment = new Donations();
       $this->updatePaymentTransactionRecords($payment->updateTransactionRecords('online'));

        $data['active_class']       = 'reports';
        $data['payments_mode']      = getPhrase('donations');
        if($slug=='all'){
           $data['title']              = getPhrase('all_payments');
       
        }
        elseif($slug=='success'){
        $data['title']              = getPhrase('success_list');
          }
        elseif($slug=='pending'){
        $data['title']              = getPhrase('pending_list');
          }
       elseif($slug='cancelled'){
           $data['title']              = getPhrase('cancelled_list');
         }
        $data['layout']             = getLayout();
        $data['ajax_url']           = URL_ONLINE_DONATIONS_REPORT_DETAILS_AJAX.$slug;
        $data['payment_mode']       = 'online';
        return view('donations.reports.payments-report-list', $data);   
    }
	
	public function updatePaymentTransactionRecords($records)
    {

      foreach($records as $record)
      {
        $rec = Donations::where('id',$record->id)->first();
        $this->isExpired($rec);
      }
    }
	
	public function getOnlinePaymentReportsDatatable($slug)
    {

      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }
    
     $records = Donations::join('users', 'users.id','=','donations.user_id')

     ->select(['users.image', 'users.name', 'donations.created_at', 'payment_gateway','donations.updated_at','payment_status','donations.cost',  'donations.paid_amount','donations.id' ])
     ->where('payment_gateway', '!=', 'offline')
     ->orderBy('updated_at', 'desc');
     if($slug!='all')
      $records->where('payment_status', '=', $slug);
      return Datatables::of($records)
      
        ->editColumn('payment_status',function($records){

          $rec = '';
          if($records->payment_status==PAYMENT_STATUS_CANCELLED)
           $rec = '<span class="label label-danger">'.ucfirst($records->payment_status).'</span>';
          elseif($records->payment_status==PAYMENT_STATUS_PENDING)
            $rec = '<span class="label label-info">'.ucfirst($records->payment_status).'</span>';
          elseif($records->payment_status==PAYMENT_STATUS_SUCCESS)
            $rec = '<span class="label label-success">'.ucfirst($records->payment_status).'</span>';
          return $rec;
        })
        ->editColumn('image', function($records) {
           return '<img src="'.getProfilePath($records->image).'"  /> '; 
        })
        ->editColumn('name', function($records)
        {
          return ucfirst($records->name);
        })        
        ->editColumn('created_at', function($records)
        {
          //if($records->payment_status==PAYMENT_STATUS_CANCELLED || $records->payment_status==PAYMENT_STATUS_PENDING)
          //  return '-';
          return $records->created_at;
        }) 
        ->editColumn('payment_gateway', function($records)
        {
          $text =  ucfirst($records->payment_gateway);

         if($records->payment_status==PAYMENT_STATUS_SUCCESS) {
          $extra = '<ul class="list-unstyled payment-col clearfix"><li>'.$text.'</li>';
          //$extra .='<li><p>Donation:'.$records->cost.'</p></li>';
		  
		 // $extra .='<li><p>Aftr Dis.:'.$records->after_discount.'</p><p>Paid:'.$records->paid_amount.'</p></li>';
		  
		  $extra .='</ul>';
          return $extra;
        }
          return $text;
        })
              
        
        ->removeColumn('id')
        // ->removeColumn('users.image')
        // ->removeColumn('action')
		->removeColumn('updated_at')
        ->make();     
    }
	
	public function donationSuccess()
	{
		$data['title']              = getPhrase('donate');
        $data['layout']             = getLayout( 'exams' );
    	return view('donations.success', $data);
	}
	
	public function donationFailed()
	{
		$data['title']              = getPhrase('donate');
        $data['layout']             = getLayout( 'exams' );
    	return view('donations.failed', $data);
	}
	
}
