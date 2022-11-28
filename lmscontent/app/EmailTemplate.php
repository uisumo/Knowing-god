<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Debug\Exception\FatalThrowableError;

// PHP mailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailerException;

class EmailTemplate extends Model
{
    protected $table = 'emailtemplates';

  

    public static function getRecordWithSlug($slug)
    {
        return EmailTemplate::where('slug', '=', $slug)->first();
    }

    /**
     * Common email function to send emails
     * @param  [type] $template [key of the template]
     * @param  [type] $data     [data to be passed to view]
     * @return [type]           [description]
     */
    public function sendEmail($template, $data)
    {	$template = EmailTemplate::where('title', '=', $template)->first();
    	$content = \Blade::compileString($this->getTemplate($template));
		$result = $this->render($content, $data);
		/*
		\Mail::send('emails.template', ['body' => $result], function ($message) use ($template, $data) 
        {
		    $message->from($template->from_email, $template->from_name);
		    $message->to($data['to_email'])->subject($template->subject);
		});
		*/
		// $this->SMTPClient( $template->from_email, $data['to_email'], $template->subject, $result );
		
		$this->sendWithPHPMailer( $result, $template, $data  );
	}
	
	public function sendWithPHPMailer( $body, $template, $data )
	{
		$status = FALSE;
		try {
			$mail = new PHPMailer(true); 
			$mail->isMail();
			$mail->setFrom($template->from_email, $template->from_name );
			$mail->addAddress($data['to_email']); // Add a recipient
			$mail->isHTML(true); 
			$mail->Subject = $template->subject;
			$mail->Body    = $body;
			$mail->send();
			$status = TRUE;
			} catch (Exception $e) {
				//echo 'Message could not be sent.';
				//echo 'Mailer Error: ' . $mail->ErrorInfo;
			}
		return $status;
	}
	
	function SMTPClient ($from, $to, $subject, $body)
	{
		$SmtpServer = 'smtp.elasticemail.com';
		$SmtpUser = base64_encode ('e23bc877-4581-4849-8ea2-8f8908758ed3');
		$SmtpPass = base64_encode ('e23bc877-4581-4849-8ea2-8f8908758ed3');
		
		$SmtpUser = base64_encode ('0115e874-4617-4a08-ad18-6400e4fac05c');
		$SmtpPass = base64_encode ('0115e874-4617-4a08-ad18-6400e4fac05c');
		$PortSMTP = 2525;
		
		if ($SMTPIN = fsockopen ($SmtpServer, $PortSMTP)) 
		{
		fputs ($SMTPIN, "EHLO ".HOST."\r\n"); 
		$talk["hello"] = fgets ( $SMTPIN, 1024 ); 
		fputs($SMTPIN, "auth login\r\n");
		$talk["res"]=fgets($SMTPIN,1024);
		fputs($SMTPIN, $SmtpUser."\r\n");
		$talk["user"]=fgets($SMTPIN,1024);
		fputs($SMTPIN, $SmtpPass."\r\n");
		$talk["pass"]=fgets($SMTPIN,256);
		fputs ($SMTPIN, "MAIL FROM: <".$from.">\r\n"); 
		$talk["From"] = fgets ( $SMTPIN, 1024 ); 
		fputs ($SMTPIN, "RCPT TO: <".$to.">\r\n"); 
		$talk["To"] = fgets ($SMTPIN, 1024); 
		fputs($SMTPIN, "DATA\r\n");
		$talk["data"]=fgets( $SMTPIN,1024 );
		fputs($SMTPIN, "To: <".$to.">\r\nFrom: <".$from.">\r\nSubject:".$subject."\r\n\r\n\r\n".$body."\r\n.\r\n");
		$talk["send"]=fgets($SMTPIN,256);
		//CLOSE CONNECTION AND EXIT ... 
		fputs ($SMTPIN, "QUIT\r\n"); 
		fclose($SMTPIN); 
		return TRUE;
		//
		} else {
			return FALSE;
		}
	}

	/**
	 * Returns the template html code by forming header, body and footer
	 * @param  [type] $template [description]
	 * @return [type]           [description]
	 */
	public function getTemplate($template)
	{
		$header = EmailTemplate::where('title', '=', 'header')->first();
    	$footer = EmailTemplate::where('title', '=', 'footer')->first();
    	
    	$view = \View::make('emails.template', [
    											'header' => $header->content, 
    											'footer' => $footer->content,
    											'body'  => $template->content, 
    											]);

		return $view->render();
	}

	/**
	 * Prepares the view from string passed along with data
	 * @param  [type] $__php  [description]
	 * @param  [type] $__data [description]
	 * @return [type]         [description]
	 */
    public function render($__php, $__data)
	{
	    $obLevel = ob_get_level();
	    ob_start();
	    extract($__data, EXTR_SKIP);
	    try {
	        eval('?' . '>' . $__php);
	    } catch (Exception $e) {
	        while (ob_get_level() > $obLevel) ob_end_clean();
	        throw $e;
	    } catch (Throwable $e) {
	        while (ob_get_level() > $obLevel) ob_end_clean();
	        throw new FatalThrowableError($e);
	    }
	    return ob_get_clean();
	}

}
