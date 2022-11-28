<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App;
use App\Http\Requests;
use App\QuestionBank;

class UpdatesController extends Controller
{
     public function __construct()
    {
     
         $this->middleware('auth');
    
    }

    /**
     * This is the first patch which updates the currency code to
     * Site Settings module
     * This can be used by the existing users
     * To access this method, user need to type the following url
     * http://sitename/updates/patch1
     * @return [type] [description]
     */
    public function patch1()
    {

      if(!checkRole(getUserGrade(1)))
      {
        prepareBlockUserMessage();
        return back();
      }
    	$record                 = App\Settings::where('slug', 'site-settings')->first();
    	 
    	$settings_data = (array) json_decode($record->settings_data);
        
       $values = array(
                        'type'=>'text', 
                        'value'=>'$', 
                        'extra'=>'',
                        'tool_tip'=>'Enter currency symbol'
                       );
       $settings_data['currency_code'] = $values;
       $record->settings_data = json_encode($settings_data);
     
       $record->save();

       flash('success','system_upgraded_successfully', 'success');
       return redirect(URL_SETTINGS_VIEW.'site-settings');
    }




    public function patch2(){

      $total_records  = QuestionBank::where('question_type','=','para')->where('is_para','=',1)->get();

      foreach ($total_records as $record) {

       $para_questions   = json_decode($record->answers);
       $para_answers     = json_decode($record->correct_answers);
      
        // dd($para_answers); 
        // dd($para_questions); 
        foreach ($para_questions as $key=>$value) {
// dd($value);
        $data                          = new QuestionBank();
        $name                          = $record->question;
        $data->question                = $name;
        $data->slug                    = $data->makeSlug(getHashCode());
        $data->subject_id              = $record->subject_id;
        $data->topic_id                = $record->topic_id;
        $data->question                = $record->question;
        $data->difficulty_level        = $record->difficulty_level;
        $data->hint                    = $record->hint;
        $data->explanation             = $record->explanation;
        $data->question_type           = $record->question_type;
        $time_to_spend                 = ($record->time_to_spend/count($para_questions));
        $data->time_to_spend           = (int)$time_to_spend;
        $data->marks                   = ($record->marks/count($para_questions));
        $data->total_answers           = 1;
        $data->total_correct_answers   = 1;
        $list[0]['question']           = $value->question;
        $list[0]['total_options']      = $value->total_options;
        $list[0]['options']            = $value->options;
        $data->answers                 = json_encode($list);
        $co_answer                     = $value->options;
        
        foreach ($para_answers as $key1 => $value1) {

          if($key  == $key1){
             
          foreach ($co_answer as $co_key => $co_value) {
               
               foreach ($co_value as $s_key => $s_value) {
                  
                  if($value1->answer == $s_value){
                     
                     $correct_answer    = $s_key + 1;

                  }

               }

          }

            // $c_answer   = $value1;

          }
        }

        $answer[0]['answer']    = $correct_answer;
        $data->correct_answers  = json_encode($answer);
        $data->is_para          = 0;
        $data->save();



        }

      }


       flash('success','para_type_questions_modified_successfully','success');
       return redirect(URL_QUIZZES);

    }



    public function patch3(){

      QuestionBank::where('question_type','=','para')->where('is_para','=','1')->delete();

       flash('success','para_type_questions_modified_successfully','success');
       return redirect(URL_QUIZZES);
    }
}
