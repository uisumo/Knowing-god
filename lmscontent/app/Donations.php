<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use DB;

class Donations extends Model
{
	protected $table = 'donations';  

	public static function getRecordWithSlug( $slug )
	{
		return Donations::where( 'slug', '=', $slug )->first();
	}
	
	public function updateTransactionRecords($records_type)
    {
        $records = \DB::table('donations')
        ->where('updated_at', '>', 'DATE_SUB(NOW(),INTERVAL -1 HOUR)')
        ->where('payment_status', '=', PAYMENT_STATUS_PENDING);
        
        if($records_type=='online')
        {
            $records->where('payment_gateway','!=','offline');
        }
        else if($records_type=='offline')
        {
            $records->where('payment_gateway','=','offline');   
        }
        else {
            $records->where('user_id','=',$records_type);      
        }
        
        return $records->get();
    }
	
	/**
     * This method returns the overall success, pending and failed records as summary
     * @return [type] [description]
     */
    public function getSuccessFailedCount()
    {
        $data = [];
        $data['success_count']      = Donations::where('payment_status','=','success')->count();
        $data['cancelled_count']    = Donations::where('payment_status','=','cancelled')->count();
        $data['pending_count']      = Donations::where('payment_status','=','pending')->count();
        return $data;
    }

    /**
     * This method gets the overall reports of the payments group by monthly
     * @param  string $year           [description]
     * @param  string $gateway        [description]
     * @param  string $payment_status [description]
     * @return [type]                 [description]
     */
    public function getSuccessMonthlyData($year='', $gateway='',$symbol='=' ,$payment_status='success')
    {
        if($year=='')
            $year = date('Y');

        $query = 'select sum(paid_amount) as total, sum(cost) as cost, MONTHNAME(created_at) as month from donations  where YEAR(created_at) = '.$year.' and payment_status = "'.$payment_status.'" group by YEAR(created_at), MONTH(created_at)';
		// echo $query;die();
        if($gateway!='')
        {
            $query = 'select sum(paid_amount) as total, sum(cost) as cost, MONTHNAME(created_at) as month from donations  where YEAR(created_at) = '.$year.' and payment_status = "'.$payment_status.'" and payment_gateway '.$symbol.' "'.$gateway.'" group by YEAR(created_at), MONTH(created_at)';
        }
// dd($query);
        $result = DB::select($query);
        // dd($result);
        return $result;
    }
}
