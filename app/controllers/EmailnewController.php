<?php
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
require BASE_PATH . '/vendor/Crypto.php';
require BASE_PATH . '/vendor/mailin.php';
require BASE_PATH . '/vendor/class.phpmailer.php';

require BASE_PATH . '/vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

class EmailnewController extends \Phalcon\Mvc\Controller
{
    //CONST UniqId = 'ncp012gb';
    public function index()
    {
    }

    public function sendtestmail()
    {
        $topset = file_get_contents('../public/email/topmail.html');
        $bottomset = file_get_contents('../public/email/bottom.html');
        $mail = new PHPMailer;
        //$mail->SMTPDebug = 3;                               // Enable verbose debug output
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = 'smtp-relay.sendinblue.com'; // Specify main and backup SMTP servers
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = 'contact@haselfre.com'; // SMTP username
        $mail->Password = 'DW6a42NFsPUCgcjA'; // SMTP password
        $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; // TCP port to connect to
        $mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
        $mail->addAddress('savariraju@haselfre.com', ''); // Add a recipient
        // Name is optional
        $mail->addReplyTo('customersupport@nidarachildren.com', 'Information');
        //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Welcome to Nidara Children ';
        $mail->Body = $topset . '' . $bottomset;
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        if (!$mail->send())
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
        }
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => true, 'message' => 'Message hase be sent.']);
        }
    }
	
	public function sendregisteremail(){
		$topset = file_get_contents('../public/email/topmail.html');
        $bottomset = file_get_contents('../public/email/bottom.html');
		$input_data = $this->request->getJsonRawBody ();
		$getInfo = SendRegisterEmail::findFirstByemail($input_data -> email);
		if($getInfo){
			 return $this
                ->response
                ->setJsonContent(['status' => false, 'message' => 'This Email alrady registerd']);
		} else {
			$getInfo = new SendRegisterEmail();
			$getInfo -> first_name = $input_data->first_name;
			$getInfo -> last_name = $input_data->last_name;
			$getInfo -> email = $input_data->email;
			$getInfo -> partner_type = $input_data->partner_type;
			$getInfo -> user_type = $input_data->user_type;
			if(!$getInfo -> save()){
				 return $this
                ->response
                ->setJsonContent(['status' => false, 'message' => $getInfo ]);
			}
			$emailcontent = '<div>';
			$emailcontent .= '<div class="page-title"><h3>COMPLETE PARTNER REGISTRATION</h3></div>';
			$emailcontent .= '<div class="page-content">';
			$emailcontent .= '<p>Dear ' . ucfirst($input_data->first_name) . ' ' . ucfirst($input_data->last_name) .' ,</p> <p>Please click on the link below and complete the form.  This will help us register you into our system</p>';
			$emailcontent .= '<p></p>';
			$emailcontent .= '</div>';
			$emailcontent .= '<div class="click-but">
					<div class="but">
						<a href="' . $this
					->config->weburl . '/center-registration?email=' . $input_data->email . '"> <span>Registration</span> </a>
					</div>
				</div>
				<p>We look forward to working with you in giving children the best start in life.</p>';
			$emailcontent .= '</div>';
			
			$mail = new PHPMailer;
			//$mail->SMTPDebug = 3;                               // Enable verbose debug output
			$mail->isSMTP(); // Set mailer to use SMTP
			$mail->Host = 'smtp-relay.sendinblue.com'; // Specify main and backup SMTP servers
			$mail->SMTPAuth = true; // Enable SMTP authentication
			$mail->Username = 'contact@haselfre.com'; // SMTP username
			$mail->Password = 'DW6a42NFsPUCgcjA'; // SMTP password
			$mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 587; // TCP port to connect to
			$mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
			$mail->addAddress($input_data -> email, ''); // Add a recipient
			// Name is optional
			$mail->addReplyTo('customersupport@nidarachildren.com', 'Information');
			//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
			//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
			$mail->isHTML(true); // Set email format to HTML
			$mail->Subject = 'Complete Partner Registration';
			$mail->Body = $topset . '' . $emailcontent . '' . $bottomset;
			$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
			if (!$mail->send())
			{
				return $this
					->response
					->setJsonContent(['status' => false, 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
			} else {
				return $this
					->response
					->setJsonContent(['status' => true, 'message' => 'Message hase be sent.']);
			}
		}
	}
	
	public function getusertype(){
		$input_data = $this->request->getJsonRawBody ();
		$getInfo = SendRegisterEmail::findFirstByemail($input_data -> email);
		if($getInfo){
			 return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $getInfo]);
		} else {
			 return $this
                ->response
                ->setJsonContent(['status' => false, 'message' => 'This Email alrady registerd']);
		}
	}
	
	
	public function sendPartnersemail(){
		$topset = file_get_contents('../public/email/topmail.html');
        $bottomset = file_get_contents('../public/email/bottom.html');
		$input_data = $this->request->getJsonRawBody ();
		$emailcontent = '<div>';
		$emailcontent .= '<div class="page-title"><h3>COPY OF YOUR NIDARA-CHILDREN ENQUIRY</h3></div>';
		$emailcontent .= '<div class="page-content">';
		$emailcontent .= '<p>Dear ' . ucfirst($input_data->first_name) . ' ' . ucfirst($input_data->last_name) .' ,</p> <p>Thank you for interest in Nidara-Children. We will get back to you as soon as possible.  A copy of the information you entered is below for your convenience:</p>';
		$emailcontent .= '<p>First name: ' . ucfirst($input_data->first_name) .' </p>';
		$emailcontent .= '<p>Last name: ' . ucfirst($input_data->last_name) .'</p>';
		$emailcontent .= '<p>Email Address: ' . $input_data->email . '</p>';
		$emailcontent .= '<p>Mobile Number: ' . $input_data->mobile . '</p>';
		$emailcontent .= '<p>Company Address: ' . ucfirst($input_data->company_name) .' </p>';
		$emailcontent .= '<p>Comments/ Questions: ' . $input_data->comments_questions . '</p>';
		$emailcontent .= '<p></p>';
		$emailcontent .= '</div>';
		$emailcontent .= '</div>';
		
		
		$emailcontent2 = '<div>';
		$emailcontent2 .= '<div class="page-title"><h3>NEW PARTNER ENQUIRY</h3></div>';
		$emailcontent2 .= '<div class="page-content">';
		$emailcontent2 .= '<p>Hello ' . ucfirst($input_data->first_name) . ' ' . ucfirst($input_data->last_name) .' ,</p><p>A copy of the new partner enquiry is below :</p>';
		$emailcontent2 .= '<p>First name: ' . ucfirst($input_data->first_name) .' </p>';
		$emailcontent2 .= '<p>Last name: ' . ucfirst($input_data->last_name) .'</p>';
		$emailcontent2 .= '<p>Email Address: ' . $input_data->email . '</p>';
		$emailcontent2 .= '<p>Mobile Number: ' . $input_data->mobile . '</p>';
		$emailcontent2 .= '<p>Company Address: ' . ucfirst($input_data->company_name) .' </p>';
		$emailcontent2 .= '<p>Comments/ Questions: ' . $input_data->comments_questions . '</p>';
		$emailcontent2 .= '<p></p>';
		$emailcontent2 .= '</div>';
		$emailcontent2 .= '</div>';
		
		
        $mail = new PHPMailer;
        //$mail->SMTPDebug = 3;                               // Enable verbose debug output
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = 'smtp-relay.sendinblue.com'; // Specify main and backup SMTP servers
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = 'contact@haselfre.com'; // SMTP username
        $mail->Password = 'DW6a42NFsPUCgcjA'; // SMTP password
        $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; // TCP port to connect to
        $mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
        $mail->addAddress($input_data -> email, ''); // Add a recipient
        // Name is optional
        $mail->addReplyTo('customersupport@nidarachildren.com', 'Information');
        //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'COPY OF YOUR NIDARA-CHILDREN ENQUIRY';
        $mail->Body = $topset . '' . $emailcontent . '' . $bottomset;
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
		if (!$mail->send())
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
        }
       
		
		$mail2 = new PHPMailer;
        //$mail->SMTPDebug = 3;                               // Enable verbose debug output
        $mail2->isSMTP(); // Set mailer to use SMTP
        $mail2->Host = 'smtp-relay.sendinblue.com'; // Specify main and backup SMTP servers
        $mail2->SMTPAuth = true; // Enable SMTP authentication
        $mail2->Username = 'contact@haselfre.com'; // SMTP username
        $mail2->Password = 'DW6a42NFsPUCgcjA'; // SMTP password
        $mail2->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
        $mail2->Port = 587; // TCP port to connect to
        $mail2->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
        $mail2->addAddress('customersupport@nidarachildren.com', ''); // Add a recipient
        // Name is optional
        $mail2->addReplyTo('customersupport@nidarachildren.com', 'Information');
        //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        $mail2->isHTML(true); // Set email format to HTML
        $mail2->Subject = 'NEW PARTNER ENQUIRY';
        $mail2->Body = $topset . '' . $emailcontent2 . '' . $bottomset;
        $mail2->AltBody = 'This is the body in plain text for non-HTML mail clients';
		if (!$mail2->send())
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
        }
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => true, 'message' => 'Message hase be sent.']);
        }
	}

    public function sendEmail()
    {
        $school = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'Schools.id as school_id',
            'Users.id as id',
            'Users.first_name as first_name',
            'Users.email as email',
            'Users.status as status',
            'UserTemPassword.password as password',
        ))
            ->from("SchoolRegistrationDate")
            ->leftjoin('Schools', 'Schools.id = SchoolRegistrationDate.school_id')
            ->leftjoin('SchoolParentMap', 'Schools.id = SchoolParentMap.school_id')
            ->leftjoin('Users', 'Users.id = SchoolParentMap.user_id')
            ->leftjoin('UserTemPassword', 'Users.id = UserTemPassword.user_id')
            ->inwhere('SchoolRegistrationDate.start_date', array(
            date('Y-m-d')
        ))
            ->getQuery()
            ->execute();

        foreach ($school as $value)
        {
            $mail = new PHPMailer;
            //$mail->SMTPDebug = 3;                               // Enable verbose debug output
            $mail->isSMTP(); // Set mailer to use SMTP
            $mail->Host = 'smtp-relay.sendinblue.com'; // Specify main and backup SMTP servers
            $mail->SMTPAuth = true; // Enable SMTP authentication
            $mail->Username = 'contact@haselfre.com'; // SMTP username
            $mail->Password = 'DW6a42NFsPUCgcjA'; // SMTP password
            $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587; // TCP port to connect to
            $mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
            $mail->addAddress($value->email, ''); // Add a recipient
            // Name is optional
            $mail->addReplyTo('customersupport@nidarachildren.com', 'Information');
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'Welcome to Nidara Children ';
            $mail->Body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<style type="text/css">
.ReadMsgBody {width: 100%;}
.ExternalClass {width: 100%;}

body {
	color: #666666;
}
		.page-title {
			text-align: center;
			width: 100%;
			float: left;
			color: #8bbdcb;
		}
           body,td{
            font-family:verdana,geneva;
            font-size:12px;
           }
           body{
            background:#fff;
            padding:20px;
           }
           .top-img{
            width:100%;
            text-align:center;
            padding-bottom:0;
            font-size:10px;
           }
           .sub-mail-cont{
            width:100%;
           }
           .sub-mail-vr{
            width:580px;
            margin:auto;
            float:none;
           }
           .main-page-mail{
            width:100%;
            float:left;
            padding:20px;
            border:1px solid #999;
           }
           .sub-mail-but{
            width:100%;
            text-align:center;
            padding-top:30px;
            float:left;
           }
           a.sub-but{
            text-decoration:none;
            color:#333;
            padding:10px 50px;
            border:1px solid;
           }
           .sub-but-cont{
            width:100%;
            padding-top:20px;
            float:left;
           }
           .footer{
            width:100%;
            text-align:center;
            font-size:10px;
            padding-top:20px;
            float:left;
           }
           .footer ul{
            list-style:none;
            float:left;
            margin:15px 10px;
            width:100%;
            padding:0;
           }
           .footer ul li{
            display:inline-flex;
            padding-left:5px;
           }
           p{
            line-height:18px;
           }
           .small{
            font-size:11px;
           }
           .main-title{
            text-align:center;
            color:#aed7d3;
            float:left;
            width:100%;
           }
           .main-title h3{
            font-weight:500;
           }
           .first-name{
            text-transform:capitalize;
           }
           .product-img{
            width:20%;
            float:left;
            padding-right:20px;
           }
           .product-img img{
            width:100%;
           }
           .product-cont{
            width:75%;
            float:left;
           }
           .product-details{
            width:100%;
            float:left;
           }
		   .page-content {
				width: 100%;
				float: left;
				color: #666666;
			}
			.click-but {
				width: 100%;
				float: left;
				text-align: center;
			}
			.click-but .but{
				width:250px;
				display:block;
				margin:auto;
			}
			.click-but .but a {
				text-decoration: none;
				padding: 10px;
				color: #fff;
			}
			.click-but .but {
				display: block;
				margin: auto;
				width: 200px;
				padding: 20px;
				background: #333333;
				font-size: 18px;
			}
			.click-but .but:hover{
				background: #8bbdcb;
			}
         
span.yshortcuts { color:#000; background-color:none; border:none;}
span.yshortcuts:hover,
span.yshortcuts:active,
span.yshortcuts:focus {color:#000; background-color:none; border:none;}
</style>
</head>
<body bgcolor="#fff" style="font-family: verdanaWELCOME TO NIDARA-CHILDREN
,geneva; font-size: 12px; background: #fff; padding: 20px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="" bgcolor="#fff"><tr><td>

          
          <div class="sub-mail-vr" style="width: 580px; margin: auto; float: none;">
            <div class="main-page-mail" style="width: 100%; float: left; padding: 20px; border: 1px solid #999;">
           <div class="top-img" style="width: 100%; text-align: center; padding-bottom: 0; font-size: 10px;">
             <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/09/logo-old.jpg" alt="170x150_logo.jpg" style="width:30%" /><p style="line-height: 18px;">GIVE YOUR CHILD THE BEST START IN LIFE</p>
           </div>
		   <div class="page-title">
			<h3>WELCOME TO NIDARA-CHILDREN</h3>
		   </div>
			<div class="page-content">
				
				<p>Dear ' . $value->first_name . ' ,</p> 

				<p>Welcome to Nidara-Children, a pioneering early child development system dedicated to providing children the best start in life.</p>

				<p>Start raising your child with Nidara-Children with 3 simple steps:</p>

				<p> Step 1: Sign in and complete registration using the credentials below </p>

				<p> Email address: ' . $value->email . ' </p>
				<p> Temporary password: ' . $value->password . '  </p>

				<p> Step 2: Complete the Early Childhood Questionnaire 
				(An NC Program Early Childhood Questionnaire Guide will be sent to you after completing Step 1) </p> 

				<p> Step 3: Start program </p>

				<p> We look forward to helping you give your child the best start in life.. </p>

			</div>
			<div class="click-but">
				<div class="but">
					<a href="' . $this
                ->config->weburl . '/signin"> <span>SIGN IN</span> </a>
				</div>
			</div>
            
            <div class="sub-but-cont" style="width: 100%; padding-top: 20px; float: left;">
           <p style="line-height: 18px;">Best regards,</p>
           <p style="line-height: 18px;">
            </p>
            <p style="line-height: 18px;">Nidara-Children</p>
          </div>
          <div class="footer" style="width: 100%; text-align: center; font-size: 10px; padding-top: 20px; float: left;">
            <ul style="list-style: none; float: left; margin: 15px 10px; width: 100%; padding: 0;">
				<li style="display: inline-flex; padding-left: 5px;">
				 <a class="fb" href="https://www.facebook.com/NidaraChildren/" target="_blank"><img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/facebook-mint-unsmushed-1.png" alt="facebook-mint.png" /></a>
			   </li>
			   <li style="display: inline-flex; padding-left: 5px;">
				 <a class="twt" href="https://twitter.com/nidarachildren" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/twitter-mint.png" alt="twitter-mint.png" /></a>
			   </li>
			   <li style="display: inline-flex; padding-left: 5px;">
				 <a class="ins" href="https://www.instagram.com/nidarachildren/" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/instagram-mint-1.png" alt="instagram-mint.png" /></a>
			   </li>
			   <li style="display: inline-flex; padding-left: 5px;">
				 <a class="email" href="' . $this
                ->config->weburl . '/contact-us/" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/mail-mint.png" alt="mail-mint.png" /></a>
			   </li>
           </ul>
			<span>Copyright &copy; Nidara-Children. All rights reserved.</span>
				<br /><span>You are receiving this email because you opted in at our website.
            </span>
            <br /><span>
			<a href="https://faq.nidarachildren.com/wp-content/themes/nidara/Newsletter-contact/Nidara_Newslatter.vcf">Add us to your address book</a> | <a href="https://nidarachildren.us17.list-manage.com/unsubscribe?u=e2c0982dd8b7d1a16f74d886d&id=fae67dd82a&e=*%7CUNIQID%7C*">Unsubscribe from this list</a>
			</span>
          </div>
           </div>
         </div>
         
</td></tr></table>
</body>
</html>
';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            if (!$mail->send())
            {
                return $this
                    ->response
                    ->setJsonContent(['status' => false, 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
            }
            else
            {
                // return $this->response->setJsonContent ( [
                // 	'status' => true,
                // 	'message' => 'Message hase be sent.'
                // ] );
                

                
            }
        }
        return $this
            ->response
            ->setJsonContent(['status' => true, 'message' => 'Email send successfully', ]);
    }

    public function sendUserEmail()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();
        $getuservalue = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'Users.id as id',
            'Users.first_name as first_name',
            'Users.email as email',
            'Users.status as status',
            'UserTemPassword.password as password',
        ))
            ->from("NcSalesmanParentMap")
            ->leftjoin('Users', 'Users.id = NcSalesmanParentMap.user_id')
            ->leftjoin('UserTemPassword', 'Users.id = UserTemPassword.user_id')
            ->inwhere('NcSalesmanParentMap.salesman_id', array(
            $input_data->salesman_id
        ))
            ->inwhere('NcSalesmanParentMap.mail_status', array(
            0
        ))
            ->getQuery()
            ->execute();

        foreach ($getuservalue as $value)
        {
            $userinfo = NcSalesmanParentMap::findFirstByuser_id($value->id);
            $userinfo->mail_status = 1;
            $userinfo->save();
            $mail = new PHPMailer;
            //$mail->SMTPDebug = 3;                               // Enable verbose debug output
            $mail->isSMTP(); // Set mailer to use SMTP
            $mail->Host = 'smtp-relay.sendinblue.com'; // Specify main and backup SMTP servers
            $mail->SMTPAuth = true; // Enable SMTP authentication
            $mail->Username = 'contact@haselfre.com'; // SMTP username
            $mail->Password = 'DW6a42NFsPUCgcjA'; // SMTP password
            $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587; // TCP port to connect to
            $mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
            $mail->addAddress($value->email, ''); // Add a recipient
            // Name is optional
            $mail->addReplyTo('customersupport@nidarachildren.com', 'Information');
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'Welcome to Nidara Children ';
            $mail->Body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<style type="text/css">
.ReadMsgBody {width: 100%;}
.ExternalClass {width: 100%;}

body {
	color: #666666;
}
		.page-title {
			text-align: center;
			width: 100%;
			float: left;
			color: #8bbdcb;
		}
           body,td{
            font-family:verdana,geneva;
            font-size:12px;
           }
           body{
            background:#fff;
            padding:20px;
           }
           .top-img{
            width:100%;
            text-align:center;
            padding-bottom:0;
            font-size:10px;
           }
           .sub-mail-cont{
            width:100%;
           }
           .sub-mail-vr{
            width:580px;
            margin:auto;
            float:none;
           }
           .main-page-mail{
            width:100%;
            float:left;
            padding:20px;
            border:1px solid #999;
           }
           .sub-mail-but{
            width:100%;
            text-align:center;
            padding-top:30px;
            float:left;
           }
           a.sub-but{
            text-decoration:none;
            color:#333;
            padding:10px 50px;
            border:1px solid;
           }
           .sub-but-cont{
            width:100%;
            padding-top:20px;
            float:left;
           }
           .footer{
            width:100%;
            text-align:center;
            font-size:10px;
            padding-top:20px;
            float:left;
           }
           .footer ul{
            list-style:none;
            float:left;
            margin:15px 10px;
            width:100%;
            padding:0;
           }
           .footer ul li{
            display:inline-flex;
            padding-left:5px;
           }
           p{
            line-height:18px;
           }
           .small{
            font-size:11px;
           }
           .main-title{
            text-align:center;
            color:#aed7d3;
            float:left;
            width:100%;
           }
           .main-title h3{
            font-weight:500;
           }
           .first-name{
            text-transform:capitalize;
           }
           .product-img{
            width:20%;
            float:left;
            padding-right:20px;
           }
           .product-img img{
            width:100%;
           }
           .product-cont{
            width:75%;
            float:left;
           }
           .product-details{
            width:100%;
            float:left;
           }
		   .page-content {
				width: 100%;
				float: left;
				color: #666666;
			}
			.click-but {
				width: 100%;
				float: left;
				text-align: center;
			}
			.click-but .but{
				width:250px;
				display:block;
				margin:auto;
			}
			.click-but .but a {
				text-decoration: none;
				padding: 10px;
				color: #fff;
			}
			.click-but .but {
				display: block;
				margin: auto;
				width: 200px;
				padding: 20px;
				background: #333333;
				font-size: 18px;
			}
			.click-but .but:hover{
				background: #8bbdcb;
			}
         
span.yshortcuts { color:#000; background-color:none; border:none;}
span.yshortcuts:hover,
span.yshortcuts:active,
span.yshortcuts:focus {color:#000; background-color:none; border:none;}
</style>
</head>
<body bgcolor="#fff" style="font-family: verdanaWELCOME TO NIDARA-CHILDREN
,geneva; font-size: 12px; background: #fff; padding: 20px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="" bgcolor="#fff"><tr><td>

          
          <div class="sub-mail-vr" style="width: 580px; margin: auto; float: none;">
            <div class="main-page-mail" style="width: 100%; float: left; padding: 20px; border: 1px solid #999;">
           <div class="top-img" style="width: 100%; text-align: center; padding-bottom: 0; font-size: 10px;">
             <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/09/logo-old.jpg" alt="170x150_logo.jpg" style="width:30%" /><p style="line-height: 18px;">GIVE YOUR CHILD THE BEST START IN LIFE</p>
           </div>
		   <div class="page-title">
			<h3>WELCOME TO NIDARA-CHILDREN</h3>
		   </div>
			<div class="page-content">
				
				<p>Dear ' . $value->first_name . ' ,</p> 

				<p>Welcome to Nidara-Children, a pioneering early child development system dedicated to providing children the best start in life.</p>

				<p>Start raising your child with Nidara-Children with 3 simple steps:</p>

				<p> Step 1: Sign in and complete registration using the credentials below </p>

				<p> Email address: ' . $value->email . ' </p>
				<p> Temporary password: ' . $value->password . '  </p>

				<p> Step 2: Complete the Early Childhood Questionnaire 
				(An NC Program Early Childhood Questionnaire Guide will be sent to you after completing Step 1) </p> 

				<p> Step 3: Start program </p>

				<p> We look forward to helping you give your child the best start in life.. </p>

			</div>
			<div class="click-but">
				<div class="but">
					<a href="' . $this
                ->config->weburl . '/signin"> <span>SIGN IN</span> </a>
				</div>
			</div>
            
            <div class="sub-but-cont" style="width: 100%; padding-top: 20px; float: left;">
           <p style="line-height: 18px;">Best regards,</p>
           <p style="line-height: 18px;">
            </p>
            <p style="line-height: 18px;">Nidara-Children</p>
          </div>
          <div class="footer" style="width: 100%; text-align: center; font-size: 10px; padding-top: 20px; float: left;">
            <ul style="list-style: none; float: left; margin: 15px 10px; width: 100%; padding: 0;">
				<li style="display: inline-flex; padding-left: 5px;">
				 <a class="fb" href="https://www.facebook.com/NidaraChildren/" target="_blank"><img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/facebook-mint-unsmushed-1.png" alt="facebook-mint.png" /></a>
			   </li>
			   <li style="display: inline-flex; padding-left: 5px;">
				 <a class="twt" href="https://twitter.com/nidarachildren" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/twitter-mint.png" alt="twitter-mint.png" /></a>
			   </li>
			   <li style="display: inline-flex; padding-left: 5px;">
				 <a class="ins" href="https://www.instagram.com/nidarachildren/" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/instagram-mint-1.png" alt="instagram-mint.png" /></a>
			   </li>
			   <li style="display: inline-flex; padding-left: 5px;">
				 <a class="email" href="' . $this
                ->config->weburl . '/contact-us/" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/mail-mint.png" alt="mail-mint.png" /></a>
			   </li>
           </ul>
			<span>Copyright &copy; Nidara-Children. All rights reserved.</span>
				<br /><span>You are receiving this email because you opted in at our website.
            </span>
            <br /><span>
			<a href="https://faq.nidarachildren.com/wp-content/themes/nidara/Newsletter-contact/Nidara_Newslatter.vcf">Add us to your address book</a> | <a href="https://nidarachildren.us17.list-manage.com/unsubscribe?u=e2c0982dd8b7d1a16f74d886d&id=fae67dd82a&e=*%7CUNIQID%7C*">Unsubscribe from this list</a>
			</span>
          </div>
           </div>
         </div>
         
</td></tr></table>
</body>
</html>
';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            if (!$mail->send())
            {
                return $this
                    ->response
                    ->setJsonContent(['status' => false, 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
            }
            else
            {
                // return $this->response->setJsonContent ( [
                // 	'status' => true,
                // 	'message' => 'Message hase be sent.'
                // ] );
                

                
            }
        }
        return $this
            ->response
            ->setJsonContent(['status' => true, 'message' => 'Email send successfully', ]);
    }

    public function freetrial()
    {
        $users = Users::find();
        $userarray = array();
        $days = strtotime(date("Y-m-d"));
        foreach ($users as $value)
        {
            if ($value->status == '2')
            {
                $daysnow = strtotime(date("Y-m-d"));
                $dayscreate = strtotime($value->created_at);
                $today['days'] = round(($daysnow - $dayscreate) / (60 * 60 * 24)) + 1;
                $users2 = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                    'NidaraKidProfile.date_of_birth as date_of_birth',
                    'NidaraKidProfile.gender as gender',
                    'Users.first_name as first_name',
                    'Users.last_name as last_name',
                    'Users.email as email',
                ))
                    ->from('KidParentsMap')
                    ->leftjoin('Users', 'KidParentsMap.users_id = Users.id')
                    ->leftjoin('NidaraKidProfile', 'KidParentsMap.nidara_kid_profile_id = NidaraKidProfile.id')
                    ->inwhere('KidParentsMap.users_id', array(
                    $value->value
                ))
                    ->inwhere('Users.status', array(
                    2
                ))
                    ->getQuery()
                    ->execute();
                $flag = 0;
                foreach ($users2 as $value2)
                {
                }
                if ($value2->gender === 'male')
                {
                    $gender = 'boy';
                }
                else if ($value2->gender === 'female')
                {
                    $gender = 'girl';
                }
                if ($today['days'] == 2 || $today['days'] == 4 || $today['days'] == 6 || $today['days'] == 8 || $today['days'] == 10 || $today['days'] == 12 || $today['days'] == 14 || $today['days'] == 16)
                {
                    if ($today['days'] == 2)
                    {
                        $headind = 'Greetings';
                        $emailcontent = '
						<div class="sub-mail-cont">
						   <span>Hi <span class="first-name">' . $value->first_name . ' ' . $value->last_name . '</span>, </span>
						   <p>Hope your first day of Nidara-Children went well.  Using our premium child development system will help you raise your child as per milestones during the early childhood years. </p>
						   <br>
						   <p>Initially, it will help your child get used you routines in a timely manner.  It is important to maintain continuity for your child’s development.  Please sign in and upgrade your account to our paid premium version. </p>
						   <br>
						   <p>Do reach out to us via chat or email for assistance with anything else. </p>
						</div>
						<br>
						<div class="sub-mail-but">
						   <p>
							  <a class="sub-but" style="text-decoration: none; color: #333; padding: 10px 50px; border: 1px solid;" href="' . $this
                            ->config->weburl . '/' . $gender . '"><b>SIGN IN & UPGRADE</b>
							  </a>
						   </p>
						</div>
						';
                    }
                    else if ($today['days'] == 4)
                    {
                        $headind = 'Hope your child is setting in well';
                        $emailcontent = '
						<div class="sub-mail-cont">
						   <span>Hi <span class="first-name">' . $value->first_name . ' ' . $value->last_name . '</span>, </span>
						   <p>
							  Hope your experience with Nidara-Children is going well.  Your child might be getting used to routines in a timely manner with our NC system.   
						   </p>
						   <br>
						   <p>
							  It is important to maintain continuity for your child’s development.  Your free trial will be over four days.  Please do sign in to you account and upgrade to the paid version of our premium version. 
						   </p>
						   <br>
						   <p>
							  We are always here for you and you can reach out to us via chat or email.
						   </p>
						</div>
						<br>
						<div class="sub-mail-but">
						   <p>
							  <a class="sub-but" style="text-decoration: none; color: #333; padding: 10px 50px; border: 1px solid;" href="' . $this
                            ->config->weburl . '/' . $gender . '"><b>SIGN IN & UPGRADE</b>
							  </a>
						   </p>
						</div>
						';
                    }
                    else if ($today['days'] == 6)
                    {
                        $headind = 'It’s important to maintain continuity for your child';
                        $emailcontent = '
						<div class="sub-mail-cont">
						   <span>Hi <span class="first-name">' . $value->first_name . ' ' . $value->last_name . '</span>, </span>
						   <p>
							  Hope your child is setting in with our Nidara-Children system.
						   </p>
						   <br>
						   <p>
							  It is important to maintain continuity for your child’s development.  Your free trial will be over two days.  Please do sign in to you account and upgrade to the paid version of our premium version. 
						   </p>
						   <br>
						   <p>
							  We are always here for you and you can reach out to us via chat or email.
						   </p>
						</div>
						<br>
						<div class="sub-mail-but">
						   <p>
							  <a class="sub-but" style="text-decoration: none; color: #333; padding: 10px 50px; border: 1px solid;" href="' . $this
                            ->config->weburl . '/' . $gender . '"><b>SIGN IN & UPGRADE</b>
							  </a>
						   </p>
						</div>
						';
                    }
                    else if ($today['days'] == 8)
                    {
                        $headind = 'It’s important to maintain continuity for your child';
                        $emailcontent = '
						<div class="sub-mail-cont">
						   <span>Hi <span class="first-name">' . $value->first_name . ' ' . $value->last_name . '</span>, </span>
						   <p>
							  Hope your experience with Nidara-Children is going well.  It is important to maintain continuity for your child’s development. 
						   </p>
						   <br>
						   <p>
							  You have no more access left for our premium child development system.  Please do sign in to you account and upgrade to the paid version of our premium version. 
						   </p>
						   <br>
						   <p>
							  We are always here for you and you can reach out to us via chat or email.
						   </p>
						</div>
						<br>
						<div class="sub-mail-but">
						   <p>
							  <a class="sub-but" style="text-decoration: none; color: #333; padding: 10px 50px; border: 1px solid;" href="' . $this
                            ->config->weburl . '/' . $gender . '"><b>SIGN IN & UPGRADE</b>
							  </a>
						   </p>
						</div>
						';
                    }
                    else if ($today['days'] == 10 || $today['days'] == 12 || $today['days'] == 14 || $today['days'] == 16)
                    {
                        $headind = 'It’s important to maintain continuity for your child';
                        $emailcontent = '
						<div class="sub-mail-cont">
						   <span>Hi <span class="first-name">' . $value->first_name . ' ' . $value->last_name . '</span>, </span>
						   <p>
							  Hope your experience with Nidara-Children is was helpful in raising your child as per development and learning milestones.  It is important to maintain continuity for your child’s development. 

						   </p>
						   <br>
						   <p>
							  You have no more access left for our premium child development system.  Please sign in to you account and upgrade to the paid version of our premium version. 

						   </p>
						   <br>
						   <p>
							  We are always here for you and you can reach out to us via chat or email.
						   </p>
						</div>
						<br>
						<div class="sub-mail-but">
						   <p>
							  <a class="sub-but" style="text-decoration: none; color: #333; padding: 10px 50px; border: 1px solid;" href="' . $this
                            ->config->weburl . '/' . $gender . '"><b>SIGN IN & UPGRADE</b>
							  </a>
						   </p>
						</div>
						';
                    }

                    $mail = new PHPMailer;
                    //$mail->SMTPDebug = 3;                               // Enable verbose debug output
                    $mail->isSMTP(); // Set mailer to use SMTP
                    $mail->Host = 'smtp-relay.sendinblue.com'; // Specify main and backup SMTP servers
                    $mail->SMTPAuth = true; // Enable SMTP authentication
                    $mail->Username = 'contact@haselfre.com'; // SMTP username
                    $mail->Password = 'DW6a42NFsPUCgcjA'; // SMTP password
                    $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
                    $mail->Port = 587; // TCP port to connect to
                    $mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
                    $mail->addAddress($value->email, ''); // Add a recipient
                    // Name is optional
                    $mail->addReplyTo('customersupport@nidarachildren.com', 'Information');
                    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
                    $mail->isHTML(true); // Set email format to HTML
                    $mail->Subject = $headind;
                    $mail->Body = '
					<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
					<html>
					<head>
					<meta http-equiv="content-type" content="text/html; charset=utf-8" />
					<style type="text/css">
					.ReadMsgBody {width: 100%;}
					.ExternalClass {width: 100%;}

					body {}

							body,td{
							font-family:verdana,geneva;
							font-size:12px;
							}
							body{
							background:#fff;
							padding:20px;
							}
							.top-img{
							width:100%;
							text-align:center;
							padding-bottom:0;
							font-size:10px;
							}
							.sub-mail-cont{
							width:100%;
							}
							.sub-mail-vr{
							width:580px;
							margin:auto;
							float:none;
							}
							.main-page-mail{
							width:100%;
							float:left;
							padding:20px;
							border:1px solid #999;
							}
							.sub-mail-but{
							width:100%;
							text-align:center;
							padding-top:30px;
							float:left;
							}
							a.sub-but{
							text-decoration:none;
							color:#333;
							padding:10px 50px;
							border:1px solid;
							}
							.sub-but-cont{
							width:100%;
							padding-top:20px;
							float:left;
							}
							.footer{
							width:100%;
							text-align:center;
							font-size:10px;
							padding-top:20px;
							float:left;
							}
							.footer ul{
							list-style:none;
							float:left;
							margin:15px 10px;
							width:100%;
							padding:0;
							}
							.footer ul li{
							display:inline-flex;
							padding-left:5px;
							}
							p{
							line-height:18px;
							}
							.small{
							font-size:11px;
							}
							.main-title{
							text-align:center;
							color:#aed7d3;
							float:left;
							width:100%;
							}
							.main-title h3{
							font-weight:500;
							}
							.first-name{
							text-transform:capitalize;
							}
							.product-img{
							width:20%;
							float:left;
							padding-right:20px;
							}
							.product-img img{
							width:100%;
							}
							.product-cont{
							width:75%;
							float:left;
							}
							.product-details{
							width:100%;
							float:left;
							}
							
					span.yshortcuts { color:#000; background-color:none; border:none;}
					span.yshortcuts:hover,
					span.yshortcuts:active,
					span.yshortcuts:focus {color:#000; background-color:none; border:none;}
					</style>
					</head>
					<body bgcolor="#fff" style="font-family: verdana,geneva; font-size: 12px; background: #fff; padding: 20px;">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" style="" bgcolor="#fff"><tr><td>

							<div class="sub-mail-vr" style="width: 580px; margin: auto; float: none;">
							<div class="main-page-mail" style="width: 100%; float: left; padding: 20px; border: 1px solid #999;">
							<div class="top-img" style="width: 100%; text-align: center; padding-bottom: 0; font-size: 10px;">
							   <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/07/170x150_logo.jpg" alt="170x150_logo.jpg" /><p style="line-height: 18px;">THE BEST START IN LIFE</p>
							</div>
							' . $emailcontent . '
							<div class="sub-but-cont" style="width: 100%; padding-top: 20px; float: left;">
							   <p style="line-height: 18px;">Best regards,</p>
							   <p style="line-height: 18px;">
							   </p>
							   <p style="line-height: 18px;">Nidara Children</p>
							</div>
							<div class="footer" style="width: 100%; text-align: center; font-size: 10px; padding-top: 20px; float: left;">
							   <ul style="list-style: none; float: left; margin: 15px 10px; width: 100%; padding: 0;">
					<li style="display: inline-flex; padding-left: 5px;">
							   <a class="fb" href="https://www.facebook.com/NidaraChildren/" target="_blank"><img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/facebook-mint.png" alt="facebook-mint.png" /></a>
							   </li>
							   <li style="display: inline-flex; padding-left: 5px;">
							   <a class="twt" href="https://twitter.com/nidarachildren" target="_blank"> <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/twitter-mint.png" alt="twitter-mint.png" /></a>
							   </li>
							   <li style="display: inline-flex; padding-left: 5px;">
							   <a class="ins" href="https://www.instagram.com/nidarachildren/" target="_blank"> <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/instagram-mint.png" alt="instagram-mint.png" /></a>
							   </li>
							   <li style="display: inline-flex; padding-left: 5px;">
							   <a class="email" href="' . $this
                        ->config->weburl . '/contact-us/" target="_blank"> <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/mail-mint.png" alt="mail-mint.png" /></a>
							   </li>
							   </ul>
					<span>Copyright &copy; Nidara-Children. All rights reserved.</span>
							   <br /><span>You are receiving this email because you opted in at our website.
							   </span>
							   <br /><span><a href="https://faq.nidarachildren.com/wp-content/themes/nidara/Newsletter-contact/Nidara_Newslatter.vcf">Add us to your address book</a> | <a href="https://nidarachildren.us17.list-manage.com/unsubscribe?u=e2c0982dd8b7d1a16f74d886d&id=fae67dd82a&e=*%7CUNIQID%7C*">Unsubscribe from this list</a></span>
							</div>
							</div>
							</div>
							
					</td></tr></table>
					</body>
					</html>
					';
                    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                    if (!$mail->send())
                    {
                        $flag = 0;
                    }
                    else
                    {
                        $flag = 1;
                    }
                }
            }
        }
        if ($flag == 1)
        {
            return $this
                ->response
                ->setJsonContent(['status' => true, 'message' => 'Message has been sent']);
        }
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
        }
    }

    public function cartsucssthank()
    {
        $users = Users::find();
        $userarray = array();
        $days = strtotime(date("Y-m-d"));
        foreach ($users as $value)
        {
            $ncproduct2 = $this
                ->modelsManager
                ->createBuilder()
                ->columns(array(
                'NCProductPricing.id as ids',
                'NCOrderList.created_at as created_at',
                'NCProductPricing.product_type as product_type',
                'NCProduct.product_name as product_name',
                'NCProduct.product_img as product_img',
                'NCProductPricing.product_price as product_price',
            ))
                ->from("NCOrderList")
                ->leftjoin('NCOrderProductList', 'NCOrderProductList.order_id = NCOrderList.id')
                ->leftjoin('NCOrderStatus', 'NCOrderStatus.order_id = NCOrderList.id')
                ->leftjoin('NCProductPricing', 'NCOrderProductList.product_id = NCProductPricing.id')
                ->leftjoin('NCProduct', 'NCProductPricing.product_id = NCProduct.id')
                ->inwhere("NCOrderList.user_id", array(
                $value->id
            ))
                ->inwhere("NCOrderStatus.status", array(
                'Success'
            ))
                ->orderBy("NCOrderList.created_at DESC")
                ->getQuery()
                ->execute();
            $flag = 0;
            if (count($ncproduct2) > 1)
            {
                foreach ($ncproduct2 as $main)
                {
                    $daysnow = strtotime(date("Y-m-d"));
                    $dayscreate = strtotime($main->created_at);

                    $today = round(($daysnow - $dayscreate) / (60 * 60 * 24)) + 1;

                    if ($today == 2)
                    {
                        /* return $this->response->setJsonContent ( [
                        'status' => true,
                        'message' => $today
                        ] ); */
                        $mail = new PHPMailer;

                        //$mail->SMTPDebug = 3;                               // Enable verbose debug output
                        $mail->isSMTP(); // Set mailer to use SMTP
                        $mail->Host = 'smtp-relay.sendinblue.com'; // Specify main and backup SMTP servers
                        $mail->SMTPAuth = true; // Enable SMTP authentication
                        $mail->Username = 'contact@haselfre.com'; // SMTP username
                        $mail->Password = 'DW6a42NFsPUCgcjA'; // SMTP password
                        $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
                        $mail->Port = 587; // TCP port to connect to
                        $mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
                        $mail->addAddress($value->email, ''); // Add a recipient
                        // Name is optional
                        $mail->addReplyTo('customersupport@nidarachildren.com', 'Information');

                        //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
                        $mail->isHTML(true); // Set email format to HTML
                        $mail->Subject = 'Thank you for raising your child with Nidara-Children…';

                        $mail->Body = ' <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<style type="text/css">
.ReadMsgBody {width: 100%;}
.ExternalClass {width: 100%;}

body {}

           body,td{
            font-family:verdana,geneva;
            font-size:12px;
           }
           body{
            background:#fff;
            padding:20px;
           }
           .top-img{
            width:100%;
            text-align:center;
            padding-bottom:0;
            font-size:10px;
           }
           .sub-mail-cont{
            width:100%;
           }
           .sub-mail-vr{
            width:580px;
            margin:auto;
            float:none;
           }
           .main-page-mail{
            width:100%;
            float:left;
            padding:20px;
            border:1px solid #999;
           }
           .sub-mail-but{
            width:100%;
            text-align:center;
            padding-top:30px;
            float:left;
           }
           a.sub-but{
            text-decoration:none;
            color:#333;
            padding:10px 50px;
            border:1px solid;
           }
           .sub-but-cont{
            width:100%;
            padding-top:20px;
            float:left;
           }
           .footer{
            width:100%;
            text-align:center;
            font-size:10px;
            padding-top:20px;
            float:left;
           }
           .footer ul{
            list-style:none;
            float:left;
            margin:15px 10px;
            width:100%;
            padding:0;
           }
           .footer ul li{
            display:inline-flex;
            padding-left:5px;
           }
           p{
            line-height:18px;
           }
           .small{
            font-size:11px;
           }
           .main-title{
            text-align:center;
            color:#aed7d3;
            float:left;
            width:100%;
           }
           .main-title h3{
            font-weight:500;
           }
           .first-name{
            text-transform:capitalize;
           }
           .product-img{
            width:20%;
            float:left;
            padding-right:20px;
           }
           .product-img img{
            width:100%;
           }
           .product-cont{
            width:75%;
            float:left;
           }
           .product-details{
            width:100%;
            float:left;
           }
         
span.yshortcuts { color:#000; background-color:none; border:none;}
span.yshortcuts:hover,
span.yshortcuts:active,
span.yshortcuts:focus {color:#000; background-color:none; border:none;}
</style>
</head>
<body bgcolor="#fff" style="font-family: verdana,geneva; font-size: 12px; background: #fff; padding: 20px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="" bgcolor="#fff"><tr><td>

          
          <div class="sub-mail-vr" style="width: 580px; margin: auto; float: none;">
            <div class="main-page-mail" style="width: 100%; float: left; padding: 20px; border: 1px solid #999;">
           <div class="top-img" style="width: 100%; text-align: center; padding-bottom: 0; font-size: 10px;">
             <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/07/170x150_logo.jpg" alt="170x150_logo.jpg" /><p style="line-height: 18px;">THE BEST START IN LIFE</p>
           </div>
           <div class="sub-mail-cont" style="width: 100%;">
         <span>Hi <span class="first-name" style="text-transform: capitalize;">' . $value->first_name . '' . $value->last_name . '</span>, </span>
         <p style="line-height: 18px;">We are grateful for your trust in us. Here&rsquo;s wishing you another wonderful year raising your child!</p>
         <br /><p style="line-height: 18px;">Do reach out to us via chat or email for assistance with anything else.</p>
        </div>
        <br /><div class="sub-mail-but" style="width: 100%; text-align: center; padding-top: 30px; float: left;">
         <p style="line-height: 18px;">
           <a class="sub-but" href="' . $this
                            ->config->weburl . '/signin" style="text-decoration: none; color: #333; padding: 10px 50px; border: 1px solid;"><b>SIGNIN</b>
          </a>
          </p>
         </div>
            
            <div class="sub-but-cont" style="width: 100%; padding-top: 20px; float: left;">
           <p style="line-height: 18px;">Best regards,</p>
           <p style="line-height: 18px;">
            </p>
            <p style="line-height: 18px;">Nidara Children</p>
          </div>
          <div class="footer" style="width: 100%; text-align: center; font-size: 10px; padding-top: 20px; float: left;">
            <ul style="list-style: none; float: left; margin: 15px 10px; width: 100%; padding: 0;">
<li style="display: inline-flex; padding-left: 5px;">
             <a class="fb" href="https://www.facebook.com/NidaraChildren/" target="_blank"><img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/facebook-mint.png" alt="facebook-mint.png" /></a>
           </li>
           <li style="display: inline-flex; padding-left: 5px;">
             <a class="twt" href="https://twitter.com/nidarachildren" target="_blank"> <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/twitter-mint.png" alt="twitter-mint.png" /></a>
           </li>
           <li style="display: inline-flex; padding-left: 5px;">
             <a class="ins" href="https://www.instagram.com/nidarachildren/" target="_blank"> <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/instagram-mint.png" alt="instagram-mint.png" /></a>
           </li>
           <li style="display: inline-flex; padding-left: 5px;">
             <a class="email" href="' . $this
                            ->config->weburl . '/contact-us/" target="_blank"> <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/mail-mint.png" alt="mail-mint.png" /></a>
           </li>
           </ul>
<span>Copyright &copy; Nidara-Children. All rights reserved.</span>
           <br /><span>You are receiving this email because you opted in at our website.
            </span>
            
            <br /><span><a href="https://faq.nidarachildren.com/wp-content/themes/nidara/Newsletter-contact/Nidara_Newslatter.vcf">Add us to your address book</a> | <a href="https://nidarachildren.us17.list-manage.com/unsubscribe?u=e2c0982dd8b7d1a16f74d886d&id=fae67dd82a&e=*%7CUNIQID%7C*">Unsubscribe from this list</a></span>
          </div>
           </div>
         </div>
         
</td></tr></table>
</body>
</html>

							';
                        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                        if (!$mail->send())
                        {
                            $flag = 0;

                        }
                        else
                        {
                            $flag = 1;
                        }
                    }
                }
            }

        }
        if ($flag == 1)
        {
            return $this
                ->response
                ->setJsonContent(['status' => true, 'message' => 'Message has been sent']);
        }
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
        }
    }

    public function viewall()
    {
        $users = Users::find();
        $userarray = array();
        $days = strtotime(date("Y-m-d"));
        foreach ($users as $value)
        {
            $ncproduct2 = $this
                ->modelsManager
                ->createBuilder()
                ->columns(array(
                'NCProductPricing.id as ids',
                'NCOrderList.created_at as created_at',
                'NCOrderList.order_id as order_ids',
                'NCProductPricing.product_type as product_type',
                'NCProduct.product_name as product_name',
                'NCProduct.product_img as product_img',
                'NCProductPricing.product_price as product_price',
            ))
                ->from("NCOrderList")
                ->leftjoin('NCOrderProductList', 'NCOrderProductList.order_id = NCOrderList.id')
                ->leftjoin('NCOrderStatus', 'NCOrderStatus.order_id = NCOrderList.id')
                ->leftjoin('NCProductPricing', 'NCOrderProductList.product_id = NCProductPricing.id')
                ->leftjoin('NCProduct', 'NCProductPricing.product_id = NCProduct.id')
                ->inwhere("NCOrderList.user_id", array(
                $value->id
            ))
                ->inwhere("NCOrderStatus.status", array(
                'Success'
            ))
                ->getQuery()
                ->execute();
            $flag = 0;
            foreach ($ncproduct2 as $main)
            {

                $daysnow = strtotime(date("Y-m-d"));
                $dayscreate = strtotime($main->created_at);
                $today['days'] = round(($daysnow - $dayscreate) / (60 * 60 * 24)) + 1;

                $contentdays = (366 - $today['days']);

                if ($today['days'] == 336 || $today['days'] == 339 || $today['days'] == 342 || $today['days'] == 345 || $today['days'] == 348 || $today['days'] == 351 || $today['days'] == 354 || $today['days'] == 358 || $today['days'] == 361 || $today['days'] == 364 || $today['days'] == 366)
                {

                    $ncproduct = $this
                        ->modelsManager
                        ->createBuilder()
                        ->columns(array(
                        'NCProductPricing.id as ids',
                        'NCProductPricing.product_type as product_type',
                        'NCProduct.product_name as product_name',
                        'NCProduct.product_img as product_img',
                        'NCProductPricing.product_price as product_price',
                    ))
                        ->from("NCOrderList")
                        ->leftjoin('NCOrderProductList', 'NCOrderProductList.order_id = NCOrderList.id')
                        ->leftjoin('NCOrderStatus', 'NCOrderStatus.order_id = NCOrderList.id')
                        ->leftjoin('NCProductPricing', 'NCOrderProductList.product_id = NCProductPricing.id')
                        ->leftjoin('NCProduct', 'NCProductPricing.product_id = NCProduct.id')
                        ->inwhere("NCOrderList.order_id", array(
                        $main->order_ids
                    ))
                        ->inwhere("NCOrderStatus.status", array(
                        'Success'
                    ))
                        ->getQuery()
                        ->execute();

                    $emailvalue = '';
                    foreach ($ncproduct as $value2)
                    {
                        $emailvalue .= '<div class="product-details"><div class="product-img">';
                        $emailvalue .= '<img src="';
                        $emailvalue .= $value2->product_img . '">';
                        $emailvalue .= '</div><div class="product-cont"><h4>';
                        $emailvalue .= $value2->product_name;
                        $emailvalue .= '</h4><p>Rs. ';
                        $emailvalue .= number_format($value2->product_price, 2);
                        $emailvalue .= '/ month for 12 months</p><p> <span style="float:right;padding-right:20px">Total:  Rs. ';
                        $emailvalue .= number_format($value2->product_price, 2);
                        $emailvalue .= '</span></p></div>';
                    }
                    $emailvalue .= '';
                    $mail = new PHPMailer;

                    //$mail->SMTPDebug = 3;                               // Enable verbose debug output
                    $mail->isSMTP(); // Set mailer to use SMTP
                    $mail->Host = 'smtp-relay.sendinblue.com'; // Specify main and backup SMTP servers
                    $mail->SMTPAuth = true; // Enable SMTP authentication
                    $mail->Username = 'contact@haselfre.com'; // SMTP username
                    $mail->Password = 'DW6a42NFsPUCgcjA'; // SMTP password
                    $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
                    $mail->Port = 587; // TCP port to connect to
                    $mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
                    $mail->addAddress($value->email, ''); // Add a recipient
                    // Name is optional
                    $mail->addReplyTo('customersupport@nidarachildren.com', 'Information');

                    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
                    $mail->isHTML(true); // Set email format to HTML
                    $mail->Subject = 'Continue giving your child a solid early foundation…';

                    $mail->Body = '
							<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<style type="text/css">
.ReadMsgBody {width: 100%;}
.ExternalClass {width: 100%;}

body {}

          body,td{
           font-family:verdana,geneva;
           font-size:12px;
          }
          body{
           background:#fff;
           padding:20px;
          }
          .top-img{
           width:100%;
           text-align:center;
           padding-bottom:0;
           font-size:10px;
          }
          .sub-mail-cont{
           width:100%;
          }
          .sub-mail-vr{
           width:580px;
           margin:auto;
           float:none;
          }
          .main-page-mail{
           width:100%;
           float:left;
           padding:20px;
           border:1px solid #999;
          }
          .sub-mail-but{
           width:100%;
           text-align:center;
           padding-top:30px;
           float:left;
          }
          a.sub-but{
           text-decoration:none;
           color:#333;
           padding:10px 50px;
           border:1px solid;
          }
          .sub-but-cont{
           width:100%;
           padding-top:20px;
           float:left;
          }
          .footer{
           width:100%;
           text-align:center;
           font-size:10px;
           padding-top:20px;
           float:left;
          }
          .footer ul{
           list-style:none;
           float:left;
           margin:15px 10px;
           width:100%;
           padding:0;
          }
          .footer ul li{
           display:inline-flex;
           padding-left:5px;
          }
          p{
           line-height:18px;
          }
          .small{
           font-size:11px;
          }
          .main-title{
           text-align:center;
           color:#aed7d3;
           float:left;
           width:100%;
          }
          .main-title h3{
           font-weight:500;
          }
          .first-name{
           text-transform:capitalize;
          }
          .product-img{
           width:20%;
           float:left;
           padding-right:20px;
          }
          .product-img img{
           width:100%;
          }
          .product-cont{
           width:75%;
           float:left;
          }
          .product-details{
           width:100%;
           float:left;
          }
        
span.yshortcuts { color:#000; background-color:none; border:none;}
span.yshortcuts:hover,
span.yshortcuts:active,
span.yshortcuts:focus {color:#000; background-color:none; border:none;}
</style>
</head>
<body bgcolor="#fff" style="font-family: verdana,geneva; font-size: 12px; background: #fff; padding: 20px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="" bgcolor="#fff"><tr><td>

         
         <div class="sub-mail-vr" style="width: 580px; margin: auto; float: none;">
           <div class="main-page-mail" style="width: 100%; float: left; padding: 20px; border: 1px solid #999;">
          <div class="top-img" style="width: 100%; text-align: center; padding-bottom: 0; font-size: 10px;">
            <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/07/170x150_logo.jpg" alt="170x150_logo.jpg" /><p style="line-height: 18px;">THE BEST START IN LIFE</p>
          </div>
          <div class="main-title" style="text-align: center; color: #aed7d3; float: left; width: 100%;">
            <h3 style="font-weight: 500;">CONTINUE GIVING YOUR CHILD A SOLID EARLY FOUNDATION &hellip;
            </h3>
          </div>
          <div class="sub-mail-cont" style="width: 100%;">
            <span>Hi <span class="first-name" style="text-transform: capitalize;">' . $value->first_name . ' ' . $value->last_name . '</span>, </span>
            <br /><p style="line-height: 18px;">Extend your Nidara-Children subscription.  You have only ' . $contentdays . ' days left
          </p>
          ' . $emailvalue . '
            
           </div>
         
         <div class="sub-mail-but" style="width: 100%; text-align: center; padding-top: 30px; float: left;">
           <p style="line-height: 18px;">
           <a class="sub-but" href="' . $this
                        ->config->weburl . '/signin" style="text-decoration: none; color: #333; padding: 10px 50px; border: 1px solid;"><b>EXTEND
           </b>
         </a>
          </p>
        </div>
        <div class="sub-but-cont" style="width: 100%; padding-top: 20px; float: left;">
          <p style="line-height: 18px;">Best regards,</p>
          <p style="line-height: 18px;">
        </p>
        <p style="line-height: 18px;">Nidara Children</p>
        </div>
        <div class="footer" style="width: 100%; text-align: center; font-size: 10px; padding-top: 20px; float: left;">
        <ul style="list-style: none; float: left; margin: 15px 10px; width: 100%; padding: 0;">
<li style="display: inline-flex; padding-left: 5px;">
         <a class="fb" href="https://www.facebook.com/NidaraChildren/" target="_blank"><img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/facebook-mint.png" alt="facebook-mint.png" /></a>
          </li>
          <li style="display: inline-flex; padding-left: 5px;">
         <a class="twt" href="https://twitter.com/nidarachildren" target="_blank"> <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/twitter-mint.png" alt="twitter-mint.png" /></a>
          </li>
          <li style="display: inline-flex; padding-left: 5px;">
         <a class="ins" href="https://www.instagram.com/nidarachildren/" target="_blank"> <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/instagram-mint.png" alt="instagram-mint.png" /></a>
          </li>
          <li style="display: inline-flex; padding-left: 5px;">
         <a class="email" href="' . $this
                        ->config->weburl . '/contact-us/" target="_blank"> <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/mail-mint.png" alt="mail-mint.png" /></a>
          </li>
          </ul>
<span>Copyright &copy; Nidara-Children. All rights reserved.</span>
          <br /><span>You are receiving this email because you opted in at our website.
        </span>
        <br /><span><a href="https://faq.nidarachildren.com/wp-content/themes/nidara/Newsletter-contact/Nidara_Newslatter.vcf">Add us to your address book</a> | <a href="https://nidarachildren.us17.list-manage.com/unsubscribe?u=e2c0982dd8b7d1a16f74d886d&id=fae67dd82a&e=*%7CUNIQID%7C*">Unsubscribe from this list</a></span>
        </div>
        </div>
        </div>
        
</td></tr></table>
</body>
</html>

							';
                    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                    if (!$mail->send())
                    {

                        $flag = 0;

                    }
                    else
                    {
                        $flag = 1;
                    }
                }
            }
        }
        if ($flag == 1)
        {
            return $this
                ->response
                ->setJsonContent(['status' => true, 'message' => 'Message has been sent']);
        }
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
        }

    }

    public function missedyoufirst()
    {
        $users = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'NidaraKidProfile.date_of_birth as date_of_birth',
            'NidaraKidProfile.gender as gender',
            'NidaraKidProfile.expiry_date as expiry_date',
            'Users.first_name as first_name',
            'Users.last_name as last_name',
            'Users.email as email',
        ))
            ->from('KidParentsMap')
            ->leftjoin('Users', 'KidParentsMap.users_id = Users.id')
            ->leftjoin('NidaraKidProfile', 'KidParentsMap.nidara_kid_profile_id = NidaraKidProfile.id')
            ->getQuery()
            ->execute();
        $userarray = array();
        $days = strtotime(date("Y-m-d"));
        foreach ($users as $value)
        {
            $flag = 0;
            $daysnow = strtotime(date("Y-m-d"));
            $dayscreate = strtotime($value->expiry_date);
            $today['days'] = round(($daysnow - $dayscreate) / (60 * 60 * 24)) + 1;
            if ($value->gender == 'male')
            {
                $link = '' . $this
                    ->config->weburl . '/boy';
            }
            else if ($value->gender == 'female')
            {
                $link = '' . $this
                    ->config->weburl . '/girl';
            }

            if ($today['days'] == 7 || $today['days'] == 10 || $today['days'] == 13)
            {
                $mail = new PHPMailer;

                //$mail->SMTPDebug = 3;                               // Enable verbose debug output
                $mail->isSMTP(); // Set mailer to use SMTP
                $mail->Host = 'smtp-relay.sendinblue.com'; // Specify main and backup SMTP servers
                $mail->SMTPAuth = true; // Enable SMTP authentication
                $mail->Username = 'contact@haselfre.com'; // SMTP username
                $mail->Password = 'DW6a42NFsPUCgcjA'; // SMTP password
                $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 587; // TCP port to connect to
                $mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
                $mail->addAddress($value->email, ''); // Add a recipient
                // Name is optional
                $mail->addReplyTo('customersupport@nidarachildren.com', 'Information');

                //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
                $mail->isHTML(true); // Set email format to HTML
                $mail->Subject = 'It’s all about your little angel!';

                $mail->Body = '
							<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<style type="text/css">
.ReadMsgBody {width: 100%;}
.ExternalClass {width: 100%;}

body {}

  body,td{
   font-family:verdana,geneva;
   font-size:12px;
  }
  body{
   background:#fff;
   padding:20px;
  }
  .top-img{
   width:100%;
   text-align:center;
   padding-bottom:0;
   font-size:10px;
  }
  .sub-mail-cont{
   width:100%;
  }
  .sub-mail-vr{
   width:580px;
   margin:auto;
   float:none;
  }
  .main-page-mail{
   width:100%;
   float:left;
   padding:20px;
   border:1px solid #999;
  }
  .sub-mail-but{
   width:100%;
   text-align:center;
   padding-top:30px;
   float:left;
  }
  a.sub-but{
   text-decoration:none;
   color:#333;
   padding:10px 50px;
   border:1px solid;
  }
  .sub-but-cont{
   width:100%;
   padding-top:20px;
   float:left;
  }
  .footer{
   width:100%;
   text-align:center;
   font-size:10px;
   padding-top:20px;
   float:left;
  }
  .footer ul{
   list-style:none;
   float:left;
   margin:15px 10px;
   width:100%;
   padding:0;
  }
  .footer ul li{
   display:inline-flex;
   padding-left:5px;
  }
  p{
   line-height:18px;
  }
  .small{
   font-size:11px;
  }
  .main-title{
   text-align:center;
   color:#aed7d3;
   float:left;
   width:100%;
  }
  .main-title h3{
   font-weight:500;
  }
  .first-name{
   text-transform:capitalize;
  }
  .product-img{
   width:20%;
   float:left;
   padding-right:20px;
  }
  .product-img img{
   width:100%;
  }
  .product-cont{
   width:75%;
   float:left;
  }
  .product-details{
   width:100%;
   float:left;
  }

span.yshortcuts { color:#000; background-color:none; border:none;}
span.yshortcuts:hover,
span.yshortcuts:active,
span.yshortcuts:focus {color:#000; background-color:none; border:none;}
</style>
</head>
<body bgcolor="#fff" style="font-family: verdana,geneva; font-size: 12px; background: #fff; padding: 20px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="" bgcolor="#fff"><tr><td>

    
    <div class="sub-mail-vr" style="width: 580px; margin: auto; float: none;">
      <div class="main-page-mail" style="width: 100%; float: left; padding: 20px; border: 1px solid #999;">
        <div class="top-img" style="width: 100%; text-align: center; padding-bottom: 0; font-size: 10px;">
          <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/07/170x150_logo.jpg" alt="170x150_logo.jpg" /><p style="line-height: 18px;">THE BEST START IN LIFE</p>
        </div>
        <div class="main-title" style="text-align: center; color: #aed7d3; float: left; width: 100%;">
          <h3 style="font-weight: 500;"></h3>
        </div>
        <div class="sub-mail-cont" style="width: 100%;">
          <span>Hi <span class="first-name" style="text-transform: capitalize;">' . $value->first_name . ' ' . $value->last_name . '</span>, </span>
          <br /><p style="line-height: 18px;">Our premium early child development system just got better. Learn more.
        </p>
      </div>
      <div class="sub-mail-but" style="width: 100%; text-align: center; padding-top: 30px; float: left;">
        <p style="line-height: 18px;">
        <a class="sub-but" href="' . $link . '" style="text-decoration: none; color: #333; padding: 10px 50px; border: 1px solid;"><b>GET NIDARA-CHILDREN NOW</b></a>
      </p>
    </div>
    <div class="sub-but-cont" style="width: 100%; padding-top: 20px; float: left;">
      <p style="line-height: 18px;">Best regards,</p>
      <p style="line-height: 18px;">
    </p>
    <p style="line-height: 18px;">Nidara Children</p>
  </div>
  <div class="footer" style="width: 100%; text-align: center; font-size: 10px; padding-top: 20px; float: left;">
    <ul style="list-style: none; float: left; margin: 15px 10px; width: 100%; padding: 0;">
<li style="display: inline-flex; padding-left: 5px;">
        <a class="fb" href="https://www.facebook.com/NidaraChildren/" target="_blank"><img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/facebook-mint.png" alt="facebook-mint.png" /></a>
      </li>
      <li style="display: inline-flex; padding-left: 5px;">
        <a class="twt" href="https://twitter.com/nidarachildren" target="_blank"> <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/twitter-mint.png" alt="twitter-mint.png" /></a>
      </li>
      <li style="display: inline-flex; padding-left: 5px;">
        <a class="ins" href="https://www.instagram.com/nidarachildren/" target="_blank"> <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/instagram-mint.png" alt="instagram-mint.png" /></a>
      </li>
      <li style="display: inline-flex; padding-left: 5px;">
        <a class="email" href="' . $this
                    ->config->weburl . '/contact-us/" target="_blank"> <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/mail-mint.png" alt="mail-mint.png" /></a>
      </li>
      </ul>
<span>Copyright &copy; Nidara-Children. All rights reserved.</span>
      <br /><span>You are receiving this email because you opted in at our website.
    </span>
    <br /><span><a href="https://faq.nidarachildren.com/wp-content/themes/nidara/Newsletter-contact/Nidara_Newslatter.vcf">Add us to your address book</a> | <a href="https://nidarachildren.us17.list-manage.com/unsubscribe?u=e2c0982dd8b7d1a16f74d886d&id=fae67dd82a&e=*%7CUNIQID%7C*">Unsubscribe from this list</a></span>
  </div>
</div>
</div>

</td></tr></table>
</body>
</html>
';
                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                if (!$mail->send())
                {
                    $flag = 0;

                }
                else
                {
                    $flag = 1;
                }
            }
        }
        if ($flag == 1)
        {
            return $this
                ->response
                ->setJsonContent(['status' => true, 'message' => 'Message has been sent']);
        }
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
        }
    }

    public function finalcalluaser()
    {
        $users = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'NidaraKidProfile.date_of_birth as date_of_birth',
            'NidaraKidProfile.gender as gender',
            'NidaraKidProfile.expiry_date as expiry_date',
            'Users.first_name as first_name',
            'Users.last_name as last_name',
            'Users.email as email',
        ))
            ->from('KidParentsMap')
            ->leftjoin('Users', 'KidParentsMap.users_id = Users.id')
            ->leftjoin('NidaraKidProfile', 'KidParentsMap.nidara_kid_profile_id = NidaraKidProfile.id')
            ->getQuery()
            ->execute();
        $userarray = array();
        $days = strtotime(date("Y-m-d"));
        foreach ($users as $value)
        {
            $userarray[] = $value;
            $flag = 0;
            $daysnow = strtotime(date("Y-m-d"));
            $dayscreate = strtotime($value->expiry_date);
            $today['days'] = round(($daysnow - $dayscreate) / (60 * 60 * 24)) + 1;

            if ($today['days'] == 20 || $today['days'] == 23 || $today['days'] == 26)
            {

                $mail = new PHPMailer;

                //$mail->SMTPDebug = 3;                               // Enable verbose debug output
                $mail->isSMTP(); // Set mailer to use SMTP
                $mail->Host = 'smtp-relay.sendinblue.com'; // Specify main and backup SMTP servers
                $mail->SMTPAuth = true; // Enable SMTP authentication
                $mail->Username = 'contact@haselfre.com'; // SMTP username
                $mail->Password = 'DW6a42NFsPUCgcjA'; // SMTP password
                $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 587; // TCP port to connect to
                $mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
                $mail->addAddress($value->email, ''); // Add a recipient
                // Name is optional
                $mail->addReplyTo('customersupport@nidarachildren.com', 'Information');

                //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
                $mail->isHTML(true); // Set email format to HTML
                $mail->Subject = 'Final call: Best Start in Life';

                $mail->Body = '
							<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<style type="text/css">
.ReadMsgBody {width: 100%;}
.ExternalClass {width: 100%;}

body {}

  body,td{
   font-family:verdana,geneva;
   font-size:12px;
  }
  body{
   background:#fff;
   padding:20px;
  }
  .top-img{
   width:100%;
   text-align:center;
   padding-bottom:0;
   font-size:10px;
  }
  .sub-mail-cont{
   width:100%;
  }
  .sub-mail-vr{
   width:580px;
   margin:auto;
   float:none;
  }
  .main-page-mail{
   width:100%;
   float:left;
   padding:20px;
   border:1px solid #999;
  }
  .sub-mail-but{
   width:100%;
   text-align:center;
   padding-top:30px;
   float:left;
  }
  a.sub-but{
   text-decoration:none;
   color:#333;
   padding:10px 50px;
   border:1px solid;
  }
  .sub-but-cont{
   width:100%;
   padding-top:20px;
   float:left;
  }
  .footer{
   width:100%;
   text-align:center;
   font-size:10px;
   padding-top:20px;
   float:left;
  }
  .footer ul{
   list-style:none;
   float:left;
   margin:15px 10px;
   width:100%;
   padding:0;
  }
  .footer ul li{
   display:inline-flex;
   padding-left:5px;
  }
  p{
   line-height:18px;
  }
  .small{
   font-size:11px;
  }
  .main-title{
   text-align:center;
   float:left;
   width:100%;
  }
  .main-title h3{
   font-weight:500;
   color:#aed7d3;
  }
  .first-name{
   text-transform:capitalize;
  }
  .product-img{
   width:20%;
   float:left;
   padding-right:20px;
  }
  .product-img img{
   width:100%;
  }
  .product-cont{
   width:75%;
   float:left;
  }
  .product-details{
   width:100%;
   float:left;
  }
  .top-img-2{
   width:100%;
   float:none;
   margin:auto;
  }
  .top-img-2 img{
   width:100%;
  }
  .main-title {
   text-align: center;
   float: left;
   width: 100%;
  }
  .main-title ul {
   width: 100%;
   float: left;
   padding: 0px;
   list-style: none;
  }
  .main-title ul li {
   float: left;
   width: 29%;
   padding-left: 10px;
   margin: auto;
   padding-right: 10px;
  }

span.yshortcuts { color:#000; background-color:none; border:none;}
span.yshortcuts:hover,
span.yshortcuts:active,
span.yshortcuts:focus {color:#000; background-color:none; border:none;}
</style>
</head>
<body bgcolor="#fff" style="font-family: verdana,geneva; font-size: 12px; background: #fff; padding: 20px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="" bgcolor="#fff"><tr><td>

    
    <div class="sub-mail-vr" style="width: 580px; margin: auto; float: none;">
      <div class="main-page-mail" style="width: 100%; float: left; padding: 20px; border: 1px solid #999;">
        <div class="top-img" style="width: 100%; text-align: center; padding-bottom: 0; font-size: 10px;">
          <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/07/170x150_logo.jpg" alt="170x150_logo.jpg" /><p style="line-height: 18px;">THE BEST START IN LIFE</p>
        </div>
        <div class="main-title" style="text-align: center; float: left; width: 100%;">
          
          <h4>OUR PREMIUM EARLY CHILD DEVELOPMENT SYSTEM</h4>
    <ul style="width: 100%; float: left; padding: 0px; list-style: none;">
<li style="float: left; width: 29%; padding-left: 10px; margin: auto; padding-right: 10px;">
     
     <div class="top-img-2" style="width: 100%; float: none; margin: auto;">
    <img src="https://gallery.mailchimp.com/e2c0982dd8b7d1a16f74d886d/images/5817b060-678d-496d-858f-7694730779eb.jpg" alt="5817b060-678d-496d-858f-7694730779eb.jpg" style="width: 100%;" />
</div>
     <h5>HEALTH & WELL BEING</h5>
   </li>
   <li style="float: left; width: 29%; padding-left: 10px; margin: auto; padding-right: 10px;">
     
     <div class="top-img-2" style="width: 100%; float: none; margin: auto;">
    <img src="https://gallery.mailchimp.com/e2c0982dd8b7d1a16f74d886d/images/f720c3cd-e8f7-432d-bc6a-c8849fb745d1.png" alt="f720c3cd-e8f7-432d-bc6a-c8849fb745d1.png" style="width: 100%;" />
</div>
     <h5>PERSONALIZED LEARNING</h5>
   </li>
   <li style="float: left; width: 29%; padding-left: 10px; margin: auto; padding-right: 10px;">
    
     <div class="top-img-2" style="width: 100%; float: none; margin: auto;">
    <img src="http://blog.nidarachildren.com/wp-content/uploads/2018/04/Nidara-Children-Web-3-1.png" alt="f720c3cd-e8f7-432d-bc6a-c8849fb745d1.png" style="width: 100%;" />
</div>
      <h5>INTEREST EXPLORATION</h5>
   </li>
    <ul style="width: 100%; float: left; padding: 0px; list-style: none;"></ul>
</ul>
</div>
        <div class="sub-mail-but" style="width: 100%; text-align: center; padding-top: 30px; float: left;">
          
          <p style="line-height: 18px;">
          <a class="sub-but" href="' . $this
                    ->config->weburl . '/" style="text-decoration: none; color: #333; padding: 10px 50px; border: 1px solid;"><b>GET NIDARA-CHILDREN NOW</b>
        </a>
      </p>
    </div>
    <div class="sub-but-cont" style="width: 100%; padding-top: 20px; float: left;">
      <p style="line-height: 18px;">Best regards,</p>
      <p style="line-height: 18px;">
    </p>
    <p style="line-height: 18px;">Nidara Children</p>
  </div>
  <div class="footer" style="width: 100%; text-align: center; font-size: 10px; padding-top: 20px; float: left;">
    <ul style="list-style: none; float: left; margin: 15px 10px; width: 100%; padding: 0;">
<li style="display: inline-flex; padding-left: 5px;">
        <a class="fb" href="https://www.facebook.com/NidaraChildren/" target="_blank"><img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/facebook-mint.png" alt="facebook-mint.png" /></a>
      </li>
      <li style="display: inline-flex; padding-left: 5px;">
        <a class="twt" href="https://twitter.com/nidarachildren" target="_blank"> <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/twitter-mint.png" alt="twitter-mint.png" /></a>
      </li>
      <li style="display: inline-flex; padding-left: 5px;">
        <a class="ins" href="https://www.instagram.com/nidarachildren/" target="_blank"> <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/instagram-mint.png" alt="instagram-mint.png" /></a>
      </li>
      <li style="display: inline-flex; padding-left: 5px;">
        <a class="email" href="' . $this
                    ->config->weburl . '/contact-us/" target="_blank"> <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/mail-mint.png" alt="mail-mint.png" /></a>
      </li>
      </ul>
<span>Copyright &copy; Nidara-Children. All rights reserved.</span>
      <br /><span>You are receiving this email because you opted in at our website.
    </span>
    <br /><span><a href="https://faq.nidarachildren.com/wp-content/themes/nidara/Newsletter-contact/Nidara_Newslatter.vcf">Add us to your address book</a> | <a href="https://nidarachildren.us17.list-manage.com/unsubscribe?u=e2c0982dd8b7d1a16f74d886d&id=fae67dd82a&e=*%7CUNIQID%7C*">Unsubscribe from this list</a></span>
  </div>
</div>
</div>

</td></tr></table>
</body>
</html>

							';
                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                if (!$mail->send())
                {
                    $flag = 0;

                }
                else
                {
                    $flag = 1;
                }

            }

        }
        if ($flag == 1)
        {
            return $this
                ->response
                ->setJsonContent(['status' => true, 'message' => 'Message has been sent']);
        }
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
        }

    }

    public function attendance()
    {
        $users = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'NidaraKidProfile.date_of_birth as date_of_birth',
            'NidaraKidProfile.gender as gender',
            'NidaraKidProfile.expiry_date as expiry_date',
            'Users.first_name as first_name',
            'Users.last_name as last_name',
            'Users.email as email',
            'DailyRoutineAttendance.attendanceDate as attendanceDate',
        ))
            ->from('KidParentsMap')
            ->leftjoin('Users', 'KidParentsMap.users_id = Users.id')
            ->leftjoin('NidaraKidProfile', 'KidParentsMap.nidara_kid_profile_id = NidaraKidProfile.id')
            ->leftjoin('DailyRoutineAttendance', 'DailyRoutineAttendance.nidara_kid_profile_id = NidaraKidProfile.id')
            ->orderBy('DailyRoutineAttendance.attendanceDate DESC LIMIT 1')
            ->getQuery()
            ->execute();
        $userarray = array();
        $days = strtotime(date("Y-m-d"));
        foreach ($users as $value)
        {
            $userarray[] = $value;
            $flag = 0;
            $daysnow = strtotime(date("Y-m-d"));
            $dayscreate = strtotime($value->attendanceDate);
            $today['days'] = round(($daysnow - $dayscreate) / (60 * 60 * 24)) + 1;

            if ($today['days'] == 7 || $today['days'] == 10 || $today['days'] == 13)
            {

                $mail = new PHPMailer;

                //$mail->SMTPDebug = 3;                               // Enable verbose debug output
                $mail->isSMTP(); // Set mailer to use SMTP
                $mail->Host = 'smtp-relay.sendinblue.com'; // Specify main and backup SMTP servers
                $mail->SMTPAuth = true; // Enable SMTP authentication
                $mail->Username = 'contact@haselfre.com'; // SMTP username
                $mail->Password = 'DW6a42NFsPUCgcjA'; // SMTP password
                $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 587; // TCP port to connect to
                $mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
                $mail->addAddress($value->email, ''); // Add a recipient
                // Name is optional
                $mail->addReplyTo('customersupport@nidarachildren.com', 'Information');

                //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
                $mail->isHTML(true); // Set email format to HTML
                $mail->Subject = 'Hope everything is alright';

                $mail->Body = '
							<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<style type="text/css">
.ReadMsgBody {width: 100%;}
.ExternalClass {width: 100%;}

body {}

         body,td{
          font-family:verdana,geneva;
          font-size:12px;
         }
         body{
          background:#fff;
          padding:20px;
         }
         .top-img{
          width:100%;
          text-align:center;
          padding-bottom:0;
          font-size:10px;
         }
         .sub-mail-cont{
          width:100%;
         }
         .sub-mail-vr{
          width:580px;
          margin:auto;
          float:none;
         }
         .main-page-mail{
          width:100%;
          float:left;
          padding:20px;
          border:1px solid #999;
         }
         .sub-mail-but{
          width:100%;
          text-align:center;
          padding-top:30px;
          float:left;
         }
         a.sub-but{
          text-decoration:none;
          color:#333;
          padding:10px 50px;
          border:1px solid;
         }
         .sub-but-cont{
          width:100%;
          padding-top:20px;
          float:left;
         }
         .footer{
          width:100%;
          text-align:center;
          font-size:10px;
          padding-top:20px;
          float:left;
         }
         .footer ul{
          list-style:none;
          float:left;
          margin:15px 10px;
          width:100%;
          padding:0;
         }
         .footer ul li{
          display:inline-flex;
          padding-left:5px;
         }
         p{
          line-height:18px;
         }
         .small{
          font-size:11px;
         }
         .main-title{
          text-align:center;
          float:left;
          width:100%;
         }
         .main-title h3{
          font-weight:500;
          color:#aed7d3;
         }
         .first-name{
          text-transform:capitalize;
         }
         .product-img{
          width:20%;
          float:left;
          padding-right:20px;
         }
         .product-img img{
          width:100%;
         }
         .product-cont{
          width:75%;
          float:left;
         }
         .product-details{
          width:100%;
          float:left;
         }
         .top-img-2{
          width:200px;
          float:none;
          margin:auto;
         }
         .top-img-2 img{
          width:100%;
         }
       
span.yshortcuts { color:#000; background-color:none; border:none;}
span.yshortcuts:hover,
span.yshortcuts:active,
span.yshortcuts:focus {color:#000; background-color:none; border:none;}
</style>
</head>
<body bgcolor="#fff" style="font-family: verdana,geneva; font-size: 12px; background: #fff; padding: 20px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="" bgcolor="#fff"><tr><td>

        
        <div class="sub-mail-vr" style="width: 580px; margin: auto; float: none;">
          <div class="main-page-mail" style="width: 100%; float: left; padding: 20px; border: 1px solid #999;">
         <div class="top-img" style="width: 100%; text-align: center; padding-bottom: 0; font-size: 10px;">
           <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/07/170x150_logo.jpg" alt="170x150_logo.jpg" /><p style="line-height: 18px;">THE BEST START IN LIFE</p>
         </div>
         
         <div class="sub-mail-cont" style="width: 100%;">
         <span>Hi <span class="first-name" style="text-transform: capitalize;">' . $value->first_name . '  ' . $value->last_name . '</span>, </span>
         <br /><p style="line-height: 18px;">Your little angel has not logged into Nidara-Children for their development.  Is everything alright?</p>
         <br /><p style="line-height: 18px;">Please do reach out to us via chat or email for any assistance.</p>
         <br /><p style="line-height: 18px;">Alternatively, just sign back in for a gentle start.</p>
         
        </div>
         <div class="sub-mail-but" style="width: 100%; text-align: center; padding-top: 30px; float: left;">
           
           <p style="line-height: 18px;">
           <a class="sub-but" href="' . $this
                    ->config->weburl . '/signin" style="text-decoration: none; color: #333; padding: 10px 50px; border: 1px solid;"><b>SIGN IN</b>
         </a>
          </p>
        </div>
        <div class="sub-but-cont" style="width: 100%; padding-top: 20px; float: left;">
          <p style="line-height: 18px;">Best regards,</p>
          <p style="line-height: 18px;">
        </p>
        <p style="line-height: 18px;">Nidara Children</p>
         </div>
         <div class="footer" style="width: 100%; text-align: center; font-size: 10px; padding-top: 20px; float: left;">
        <ul style="list-style: none; float: left; margin: 15px 10px; width: 100%; padding: 0;">
<li style="display: inline-flex; padding-left: 5px;">
         <a class="fb" href="https://www.facebook.com/NidaraChildren/" target="_blank"><img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/facebook-mint.png" alt="facebook-mint.png" /></a>
          </li>
          <li style="display: inline-flex; padding-left: 5px;">
         <a class="twt" href="https://twitter.com/nidarachildren" target="_blank"> <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/twitter-mint.png" alt="twitter-mint.png" /></a>
          </li>
          <li style="display: inline-flex; padding-left: 5px;">
         <a class="ins" href="https://www.instagram.com/nidarachildren/" target="_blank"> <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/instagram-mint.png" alt="instagram-mint.png" /></a>
          </li>
          <li style="display: inline-flex; padding-left: 5px;">
         <a class="email" href="' . $this
                    ->config->weburl . '/contact-us/" target="_blank"> <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/mail-mint.png" alt="mail-mint.png" /></a>
          </li>
          </ul>
<span>Copyright &copy; Nidara-Children. All rights reserved.</span>
          <br /><span>You are receiving this email because you opted in at our website.
        </span>
        <br /><span><a href="https://faq.nidarachildren.com/wp-content/themes/nidara/Newsletter-contact/Nidara_Newslatter.vcf">Add us to your address book</a> | <a href="https://nidarachildren.us17.list-manage.com/unsubscribe?u=e2c0982dd8b7d1a16f74d886d&id=fae67dd82a&e=*%7CUNIQID%7C*">Unsubscribe from this list</a></span>
         </div>
       </div>
       </div>
       
</td></tr></table>
</body>
</html>

							';
                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                if (!$mail->send())
                {
                    $flag = 0;

                }
                else
                {
                    $flag = 1;
                }

            }

        }
        if ($flag == 1)
        {
            return $this
                ->response
                ->setJsonContent(['status' => true, 'message' => 'Message has been sent']);
        }
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
        }

    }

    public function parentqus()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();
        /* return $this->response->setJsonContent ( [
        'status' => true,
        'message' => $input_data
        			] ); */
        $users = Users::findFirstByid($input_data->user_id);
        $kid_info = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'NidaraKidProfile.date_of_birth as date_of_birth',
            'NidaraKidProfile.id as id',
            'NidaraKidProfile.first_name as first_name',
        ))
            ->from('KidParentsMap')
            ->leftjoin('Users', 'KidParentsMap.users_id = Users.id')
            ->leftjoin('NidaraKidProfile', 'KidParentsMap.nidara_kid_profile_id = NidaraKidProfile.id')
            ->inwhere('KidParentsMap.users_id', array(
            $users->id
        ))
            ->getQuery()
            ->execute();
        foreach ($kid_info as $value)
        {
            $mail = new PHPMailer;

            //$mail->SMTPDebug = 3;                               // Enable verbose debug output
            $mail->isSMTP(); // Set mailer to use SMTP
            $mail->Host = 'smtp-relay.sendinblue.com'; // Specify main and backup SMTP servers
            $mail->SMTPAuth = true; // Enable SMTP authentication
            $mail->Username = 'contact@haselfre.com'; // SMTP username
            $mail->Password = 'DW6a42NFsPUCgcjA'; // SMTP password
            $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587; // TCP port to connect to
            $mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
            $mail->addAddress($users->email, ''); // Add a recipient
            // Name is optional
            $mail->addReplyTo('customersupport@nidarachildren.com', 'Information');

            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'Parent Questionnaire';

            $mail->Body = '
							<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
							<html>
							  <head>
								<meta http-equiv="content-type" content="text/html; charset=utf-8">
								
							  <style type="text/css">
									body,td{
										font-family:verdana,geneva;
										font-size:12px;
									}
									body{
										background:#fff;
										padding:20px;
									}
									.top-img{
										width:100%;
										text-align:center;
										padding-bottom:0;
										font-size:10px;
									}
									.sub-mail-cont{
										width:100%;
									}
									.sub-mail-vr{
										width:580px;
										margin:auto;
										float:none;
									}
									.main-page-mail{
										width:100%;
										float:left;
										padding:20px;
										border:1px solid #999;
									}
									.sub-mail-but{
										width:100%;
										text-align:center;
										padding-top:30px;
										float:left;
									}
									a.sub-but{
										text-decoration:none;
										color:#333;
										padding:10px 50px;
										border:1px solid;
										text-transform:uppercase
									}
									.sub-but-cont{
										width:100%;
										padding-top:20px;
										float:left;
									}
									.footer{
										width:100%;
										text-align:center;
										font-size:10px;
										padding-top:20px;
										float:left;
									}
									.footer ul{
										list-style:none;
										float:left;
										margin:15px 10px;
										width:100%;
										padding:0;
									}
									.footer ul li{
										display:inline-flex;
										padding-left:5px;
									}
									p{
										line-height:18px;
									}
									.small{
										font-size:11px;
									}
									.main-title{
										text-align:center;
										float:left;
										width:100%;
									}
									.main-title h3{
										font-weight:500;
										color:#aed7d3;
									}
									.first-name{
										text-transform:capitalize;
									}
									.product-img{
										width:20%;
										float:left;
										padding-right:20px;
									}
									.product-img img{
										width:100%;
									}
									.product-cont{
										width:75%;
										float:left;
									}
									.product-details{
										width:100%;
										float:left;
									}
									.top-img-2{
										width:200px;
										float:none;
										margin:auto;
									}
									.top-img-2 img{
										width:100%;
									}
							</style></head>
							  <body bgcolor="#fff">
								
								<div class="sub-mail-vr">
								  <div class="main-page-mail">
									<div class="top-img">
									  <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/07/170x150_logo.jpg" alt="170x150_logo.jpg">
									  <p>THE BEST START IN LIFE</p>
									</div>
									
									<div class="sub-mail-cont">
									<span>Hi <span class="first-name">' . $users->first_name . '  ' . $users->last_name . '</span>, </span>
									<br>
									<p>Please click the button below and complete the NC Questionnaire to complete registration.</p>
									<p>The purpose of the NC Questionnaire is to understand your child’s development before starting the Nidara-Children system.</p>
								</div>
								<div class="sub-mail-cont">
									
									
									<p>Note: We request you to complete this questionnaire in its entirety.  Your responses will not be saved if you leave it half done.</p>
									
								</div>
									<div class="sub-mail-but">
									  
									  <p>
									  <p><a class="sub-but" href="' . $this
                ->config->weburl . '//parent-question/parent-qus/' . $value->id . '">COMPLETE NC QUESTIONNAIRE FOR ' . $value->first_name . '</a>
									 
									</a>
								  </p>
								</div>
								<div class="sub-but-cont">
								  <p>Best regards,</p>
								  <p>
								</p>
								<p>Nidara Children</p>
							  </div>
							  <div class="footer">
								<ul>
								  <li>
									<a class="fb" href="https://www.facebook.com/NidaraChildren/" target="_blank"><img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/facebook-mint.png" alt="facebook-mint.png"></a>
								  </li>
								  <li>
									<a class="twt" href="https://twitter.com/nidarachildren" target="_blank"> <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/twitter-mint.png" alt="twitter-mint.png"></a>
								  </li>
								  <li>
									<a class="ins" href="https://www.instagram.com/nidarachildren/" target="_blank"> <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/instagram-mint.png" alt="instagram-mint.png"></a>
								  </li>
								  <li>
									<a class="email" href="' . $this
                ->config->weburl . '/contact-us/" target="_blank"> <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/mail-mint.png" alt="mail-mint.png"></a>
								  </li>
								  </ul><span>Copyright © Nidara-Children. All rights reserved.</span>
								  <br><span>You are receiving this email because you opted in at our website.
								</span>
								<br><span><a href="https://faq.nidarachildren.com/wp-content/themes/nidara/Newsletter-contact/Nidara_Newslatter.vcf">Add us to your address book</a> | <a href="https://nidarachildren.us17.list-manage.com/unsubscribe?u=e2c0982dd8b7d1a16f74d886d&amp;id=fae67dd82a&amp;e=*|UNIQID|*">Unsubscribe from this list</a></span>
							  </div>
							</div>
							</div>
							</body>
							</html>
							
							';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            if (!$mail->send())
            {
                $flag = 0;

            }
            else
            {
                $flag = 1;
            }
        }

        if ($flag == 1)
        {
            return $this
                ->response
                ->setJsonContent(['status' => true, 'message' => 'Message has been sent']);
        }
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
        }

    }

    public function companydata()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();

        $emaildata = '<div>';
        $emaildata .= '<h2>Company Enquiry</h2>';
        $emaildata .= '<h3>First Name:' . $input_data->first_name;
        $emaildata .= '</h3><h3>Lest Name:' . $input_data->last_name;
        $emaildata .= '</h3><h3>Email:' . $input_data->email;
        $emaildata .= '</h3><h3>Phone Number:' . $input_data->phone_number;
        $emaildata .= '</h3><h3>Enquiry Type:' . $input_data->enquiry_type;
        $emaildata .= '</h3><h3>Company Name:' . $input_data->company_name;
        $emaildata .= '</h3><h3>Position:' . $input_data->position;
        $emaildata .= '</h3><p>Message:' . $input_data->help;
        $emaildata .= '</p></div>';

        $mail = new PHPMailer;
        //$mail->SMTPDebug = 3;                               // Enable verbose debug output
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = 'smtp-relay.sendinblue.com'; // Specify main and backup SMTP servers
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = 'contact@haselfre.com'; // SMTP username
        $mail->Password = 'DW6a42NFsPUCgcjA'; // SMTP password
        $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; // TCP port to connect to
        $mail->setFrom($input_data->email, $input_data->first_name);
        $mail->addAddress('customersupport@nidarachildren.com', 'Nidara-Children'); // Add a recipient
        // Name is optional
        $mail->addReplyTo($input_data->email, $input_data->first_name);

        //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Company Enquiry';

        $mail->Body = '<!DOCTYPE html>
							<html>
							  <head>
								<meta http-equiv="content-type" content="text/html; charset=utf-8">
								
							  <style type="text/css">
									body,td{
										font-family:verdana,geneva;
										font-size:12px;
									}
									body{
										background:#fff;
										padding:20px;
									}
									.top-img{
										width:100%;
										text-align:center;
										padding-bottom:0;
										font-size:10px;
									}
									.sub-mail-cont{
										width:100%;
									}
									.sub-mail-vr{
										width:580px;
										margin:auto;
										float:none;
									}
									.main-page-mail{
										width:100%;
										float:left;
										padding:20px;
										border:1px solid #999;
									}
									.sub-mail-but{
										width:100%;
										text-align:center;
										padding-top:30px;
										float:left;
									}
									a.sub-but{
										text-decoration:none;
										color:#333;
										padding:10px 50px;
										border:1px solid;
										text-transform:uppercase
									}
									.sub-but-cont{
										width:100%;
										padding-top:20px;
										float:left;
									}
									.footer{
										width:100%;
										text-align:center;
										font-size:10px;
										padding-top:20px;
										float:left;
									}
									.footer ul{
										list-style:none;
										float:left;
										margin:15px 10px;
										width:100%;
										padding:0;
									}
									.footer ul li{
										display:inline-flex;
										padding-left:5px;
									}
									p{
										line-height:18px;
									}
									.small{
										font-size:11px;
									}
									.main-title{
										text-align:center;
										float:left;
										width:100%;
									}
									.main-title h3{
										font-weight:500;
										color:#aed7d3;
									}
									.first-name{
										text-transform:capitalize;
									}
									.product-img{
										width:20%;
										float:left;
										padding-right:20px;
									}
									.product-img img{
										width:100%;
									}
									.product-cont{
										width:75%;
										float:left;
									}
									.product-details{
										width:100%;
										float:left;
									}
									.top-img-2{
										width:200px;
										float:none;
										margin:auto;
									}
									.top-img-2 img{
										width:100%;
									}
							</style></head>
							  <body bgcolor="#fff">
								' . $emaildata . '
							</body>
							</html>
							
							';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        if (!$mail->send())
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);

        }
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => true, 'message' => 'Message has been sent']);
        }
    }

    public function invoceemailsend()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();

        $userinfo = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'Users.first_name as first_name',
            'Users.last_name as last_name',
            'Users.email as email',
            'NCOrderAmount.total_amount as total_amount',
            'NCOrderAmount.tax_amount as tax_amount',
            'NCOrderAmount.discoun_amount as discoun_amount',
            'NCOrderAmount.cart_amount as cart_amount',
            'NCOrderAmount.order_id as order_ids',
            'UsersAddress.address_1 as address_1',
            'UsersAddress.address_2 as address_2',
            'UsersAddress.city as city',
            'UsersAddress.state as state',
            'UsersAddress.country as country',
            'UsersAddress.post_code as post_code',
        ))
            ->from('NCOrderList')
            ->leftjoin('NCOrderAmount', 'NCOrderAmount.order_id = NCOrderList.order_id')
            ->leftjoin('NCOrderStatus', 'NCOrderStatus.order_id = NCOrderList.id')
            ->leftjoin('Users', 'NCOrderAmount.user_id = Users.id')
            ->leftjoin('UsersAddress', 'UsersAddress.user_id = Users.id')
            ->inwhere('NCOrderAmount.order_id', array(
            $input_data->order_id
        ))
            ->inwhere("NCOrderStatus.status", array(
            'Success'
        ))
            ->getQuery()
            ->execute();
        foreach ($userinfo as $user)
        {
            $ncproduct = $this
                ->modelsManager
                ->createBuilder()
                ->columns(array(
                'NCProductPricing.id as ids',
                'NCProductPricing.product_type as product_type',
                'NCProduct.product_name as product_name',
                'NCProduct.product_img as product_img',
                'NCProductPricing.product_price as product_price',
            ))
                ->from("NCOrderList")
                ->leftjoin('NCOrderProductList', 'NCOrderProductList.order_id = NCOrderList.id')
                ->leftjoin('NCOrderStatus', 'NCOrderStatus.order_id = NCOrderList.id')
                ->leftjoin('NCProductPricing', 'NCOrderProductList.product_id = NCProductPricing.id')
                ->leftjoin('NCProduct', 'NCProductPricing.product_id = NCProduct.id')
                ->inwhere("NCOrderList.order_id", array(
                $user->order_ids
            ))
                ->getQuery()
                ->execute();
            $emailvalue = '';
            foreach ($ncproduct as $value)
            {
                $emailvalue .= '<tr>';
                $emailvalue .= '<td>';
                $emailvalue .= $value->product_name;
                $emailvalue .= '</td>';
                $emailvalue .= '<td>';
                $emailvalue .= '</td>';
                $emailvalue .= '<td>';
                $emailvalue .= '</td>';
                $emailvalue .= '<td>';
                $emailvalue .= $value->product_price;
                $emailvalue .= '</td>';
                $emailvalue .= '</tr>';
            }
            $emailvalue .= '';

            $number = $user->cart_amount;
            $no = floor($number);
            $point = ($number - $no) * 100;
            /* return $this->response->setJsonContent ( [
            'status' => true,
            'message' => $no,$point
            ] ); */
            $hundred = null;
            $digits_1 = strlen($no);
            $i = 0;
            $str = array();
            $words = array(
                '0' => '',
                '1' => 'one',
                '2' => 'two',
                '3' => 'three',
                '4' => 'four',
                '5' => 'five',
                '6' => 'six',
                '7' => 'seven',
                '8' => 'eight',
                '9' => 'nine',
                '10' => 'ten',
                '11' => 'eleven',
                '12' => 'twelve',
                '13' => 'thirteen',
                '14' => 'fourteen',
                '15' => 'fifteen',
                '16' => 'sixteen',
                '17' => 'seventeen',
                '18' => 'eighteen',
                '19' => 'nineteen',
                '20' => 'twenty',
                '30' => 'thirty',
                '40' => 'forty',
                '50' => 'fifty',
                '60' => 'sixty',
                '70' => 'seventy',
                '80' => 'eighty',
                '90' => 'ninety'
            );
            $digits = array(
                '',
                'hundred',
                'thousand',
                'lakh',
                'crore'
            );
            while ($i < $digits_1)
            {
                $divider = ($i == 2) ? 10 : 100;
                $number = floor($no % $divider);
                $no = floor($no / $divider);
                $i .= ($divider == 10) ? 1 : 2;
                if ($number)
                {
                    $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                    $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                    $str[] = ($number < 21) ? $words[$number] . " " . $digits[$counter] . $plural . " " . $hundred : $words[floor($number / 10) * 10] . " " . $words[$number % 10] . " " . $digits[$counter] . $plural . " " . $hundred;
                }
                else $str[] = null;
            }
            $str = array_reverse($str);
            $result = implode('', $str);
            $points = ($point) ? "." . $words[$point] . " " . $words[$point = $point % 10] : '';
            echo $result . "Rupees  " . $points . " Paise";
            if (!$points)
            {
                $amonut = $result . "Rupees";
            }
            else
            {
                $amonut = $result . "Rupees  " . $points . " Paise";
            }

            $date = date('Y-m-d');
            $mail = new PHPMailer;

            //$mail->SMTPDebug = 3;                               // Enable verbose debug output
            $mail->isSMTP(); // Set mailer to use SMTP
            $mail->Host = 'smtp-relay.sendinblue.com'; // Specify main and backup SMTP servers
            $mail->SMTPAuth = true; // Enable SMTP authentication
            $mail->Username = 'contact@haselfre.com'; // SMTP username
            $mail->Password = 'DW6a42NFsPUCgcjA'; // SMTP password
            $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587; // TCP port to connect to
            $mail->setFrom('invoice@haselfre.com', 'Nidara-Children');
            $mail->addAddress($user->email, ''); // Add a recipient
            // Name is optional
            $mail->addReplyTo('invoice@haselfre.com', 'Information');

            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'Invoice';

            $mail->Body = '
			
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
								<html>
								  <head>
									<meta http-equiv="content-type" content="text/html; charset=utf-8">
									
								  <style type="text/css">
										body,td{
											font-family:verdana,geneva;
											font-size:12px;
										}
										body{
											background:#fff;
											padding:20px;
										}
										.top-img{
											width:100%;
											text-align:center;
											padding-bottom:0;
											font-size:10px;
										}
										.sub-mail-cont{
											width:100%;
										}
										.sub-mail-vr{
											width:900px;
											margin:auto;
											float:none;
										}
										.main-page-mail{
											width:100%;
											float:left;
											padding:20px;
											border:1px solid #999;
										}
										.sub-mail-but{
											width:100%;
											text-align:center;
											padding-top:30px;
											float:left;
										}
										a.sub-but{
											text-decoration:none;
											color:#333;
											padding:10px 50px;
											border:1px solid;
										}
										.sub-but-cont{
											width:100%;
											padding-top:20px;
											float:left;
										}
										.footer{
											width:100%;
											text-align:center;
											font-size:10px;
											padding-top:20px;
											float:left;
										}
										.footer ul{
											list-style:none;
											float:left;
											margin:15px 10px;
											width:100%;
											padding:0;
										}
										.footer ul li{
											display:inline-flex;
											padding-left:5px;
										}
										p{
											line-height:18px;
										}
										.small{
											font-size:11px;
										}
										.main-title{
											text-align:center;
											color:#aed7d3;
											float:left;
											width:100%;
										}
										.main-title h3{
											font-weight:500;
										}
										.first-name{
											text-transform:capitalize;
										}
										.product-img{
											width:20%;
											float:left;
											padding-right:20px;
										}
										.product-img img{
											width:100%;
										}
										.product-cont{
											width:75%;
											float:left;
										}
										.product-details{
											width:100%;
											float:left;
										}
										table {
											font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
											border-collapse: collapse;
											width: 100%;
										}

										table td {
											border-right: 1px solid #ddd;
											border-left: 1px solid #ddd;
											border-bottom: 0px;
											padding: 15px;
										}
										 table th {
											border: 1px solid #ddd;
											padding: 8px;
										}


										table th {
											padding-top: 12px;
											padding-bottom: 12px;
											text-align: left;
											color: #000;
										}
										.invoic{
											width: 900px;
											margin: auto;
										}
										.text-align{
											text-align: center;
										}
								</style></head>
								  <body bgcolor="#fff">
									
									<div class="sub-mail-vr">
									  <div class="main-page-mail">
									   <table id="customers">
		<tr>
			<th class="text-align">TAX INVOICE</th>	
		</tr> 
  </table>
  <table id="customers">
		<tr>
			<th class="text-align">
				<h3>NIDARA CHILDREN PRIVATE LIMITED	</h3>		
				<h4>(Formerly Haselfre Solutions Private Limited)</h4>			
				<p>Admin off: 1A, KINGSTON BUILDING,</p>  			
				<p>29/17, M.G.R. ROAD, KALAKSHETRA COLONY,</p> 			
				<h4>BESANT NAGAR, CHENNAI- 600090</h4>			
				<h4>CIN: U72900DL2003PTC119476	</h4>		
				<h4>GSTIN No 33AABCH2587H2ZM</h4>	
				</th>	
		</tr> 
  </table>
	<table id="customers">
	  <tr>
		  <th>
				<h3>Bill to</h3>
				<p></p>
				<p>Customer`s Name :' . $user->first_name . ' ' . $user->last_name . '</p>
				<p>Customer`s Address </p>
				<p>' . $user->address_1 . '</p>
				<p>' . $user->address_2 . '</p>
				<p>' . $user->city . '</p>
				<p>' . $user->state . '</p>
				<p>' . $user->country . '</p>
				<p>' . $user->post_code . '</p>
				<p></p>
				<p>GSTIN of Customer (If registered)</p>
		  </th>
		  <th>
				<h3>Place of Supply</h3>
				<p></p>
				<p>Invoice No ' . $user->order_ids . ' </p>
				<p>Date ' . $date . '</p>      
		  </th>
	  </tr>
  </table>
   <table id="customers">
		<tr>
		  <th>Description of Services</th>
		  <th></th>
		  <th>SAC Code</th>
		  <th>Amount(Rs)</th>
		</tr>
		' . $emailvalue . '
		<tr>
				<td>Total</td>
				<td></td>
				<td></td>
				<td>' . $user->total_amount . '</td>
		</tr>
		<tr>
				<td>Less Discount</td>
				<td></td>
				<td></td>
				<td>' . $user->discoun_amount . '</td>
		</tr>
		<tr>
				<td>Taxable Value</td>
				<td></td>
				<td></td>
				<td>' . $user->total_amount . '</td>
		</tr>
		<tr>
				<td>Add IGST </td>
				<td></td>
				<td>18%</td>
				<td> ' . $user->tax_amount . '</td>
		</tr>
		<tr>
				<th>Totals </th>
				<th></th>
				<th></th>
				<th>' . $user->cart_amount . '</th>
		</tr>
  </table>
  <table id="customers">
		<tr>
		  <th>
			  <div class="style="text-align: left;">
					<h4>Amount Chargeable (in words)</h4>
					<p style="text-transform: capitalize;">' . $amonut . ' only</p>
					<h3>Company PAN: AABCH2587H</h3>
					<h4>Reg Off:211HANS BHAWAN, 1 BAHADURSHAH ZAFAR MARG,</h4>
					<h4> NEW DELHI, Delhi, India, 110002</h4>
			  </div>
			  <div  style="text-align: right;">
					Authorised Signatory
			  </div>
		  </th>
		</tr>
</table>

										
									   
									  </div>
									</div>
								
								</div>
								</div>
								</body>
								</html>';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            if (!$mail->send())
            {
                return $this
                    ->response
                    ->setJsonContent(['status' => false, 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
            }
            else
            {
                return $this
                    ->response
                    ->setJsonContent(['status' => true, 'message' => 'Message has been sent', 'data' => $user->email]);
            }
        }
    }

    public function custominvoceemailsend()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();

        $userinfo = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'Users.first_name as first_name',
            'Users.last_name as last_name',
            'Users.email as email',
            'NCOrderAmount.total_amount as total_amount',
            'NCOrderAmount.tax_amount as tax_amount',
            'NCOrderAmount.discoun_amount as discoun_amount',
            'NCOrderAmount.cart_amount as cart_amount',
            'NCOrderAmount.order_id as order_ids',
            'UsersAddress.address_1 as address_1',
            'UsersAddress.address_2 as address_2',
            'UsersAddress.city as city',
            'UsersAddress.state as state',
            'UsersAddress.country as country',
            'UsersAddress.post_code as post_code',
        ))
            ->from('NCOrderList')
            ->leftjoin('NCOrderAmount', 'NCOrderAmount.order_id = NCOrderList.order_id')
            ->leftjoin('NCOrderStatus', 'NCOrderStatus.order_id = NCOrderList.id')
            ->leftjoin('Users', 'NCOrderAmount.user_id = Users.id')
            ->leftjoin('UsersAddress', 'UsersAddress.user_id = Users.id')
            ->inwhere('NCOrderAmount.order_id', array(
            $input_data->order_id
        ))
            ->getQuery()
            ->execute();

        foreach ($userinfo as $user)
        {
            $ncproduct = $this
                ->modelsManager
                ->createBuilder()
                ->columns(array(
                'NCProductPricing.id as ids',
                'NCProductPricing.product_type as product_type',
                'NCProduct.product_name as product_name',
                'NCProduct.product_img as product_img',
                'NCProductPricing.product_price as product_price',
            ))
                ->from("NCOrderList")
                ->leftjoin('NCOrderProductList', 'NCOrderProductList.order_id = NCOrderList.id')
                ->leftjoin('NCOrderStatus', 'NCOrderStatus.order_id = NCOrderList.id')
                ->leftjoin('NCProductPricing', 'NCOrderProductList.product_id = NCProductPricing.id')
                ->leftjoin('NCProduct', 'NCProductPricing.product_id = NCProduct.id')
                ->inwhere("NCOrderList.order_id", array(
                $user->order_ids
            ))
                ->getQuery()
                ->execute();

            $emailvalue = '';
            foreach ($ncproduct as $value)
            {
                $emailvalue .= '<tr>';
                $emailvalue .= '<td>';
                $emailvalue .= $value->product_name;
                $emailvalue .= '</td>';
                $emailvalue .= '<td>';
                $emailvalue .= '</td>';
                $emailvalue .= '<td>';
                $emailvalue .= '</td>';
                $emailvalue .= '<td>';
                $emailvalue .= $value->product_price;
                $emailvalue .= '</td>';
                $emailvalue .= '</tr>';
            }
            $emailvalue .= '';
            $taxabelvalue = ($user->total_amount - $user->discoun_amount);
            $number = $user->cart_amount;
            $no = floor($number);
            $point = ($number - $no) * 100;
            /* return $this->response->setJsonContent ( [
            'status' => true,
            'message' => $no,$point
            ] ); */
            $hundred = null;
            $digits_1 = strlen($no);
            $i = 0;
            $str = array();
            $words = array(
                '0' => '',
                '1' => 'one',
                '2' => 'two',
                '3' => 'three',
                '4' => 'four',
                '5' => 'five',
                '6' => 'six',
                '7' => 'seven',
                '8' => 'eight',
                '9' => 'nine',
                '10' => 'ten',
                '11' => 'eleven',
                '12' => 'twelve',
                '13' => 'thirteen',
                '14' => 'fourteen',
                '15' => 'fifteen',
                '16' => 'sixteen',
                '17' => 'seventeen',
                '18' => 'eighteen',
                '19' => 'nineteen',
                '20' => 'twenty',
                '30' => 'thirty',
                '40' => 'forty',
                '50' => 'fifty',
                '60' => 'sixty',
                '70' => 'seventy',
                '80' => 'eighty',
                '90' => 'ninety'
            );
            $digits = array(
                '',
                'hundred',
                'thousand',
                'lakh',
                'crore'
            );
            while ($i < $digits_1)
            {
                $divider = ($i == 2) ? 10 : 100;
                $number = floor($no % $divider);
                $no = floor($no / $divider);
                $i .= ($divider == 10) ? 1 : 2;
                if ($number)
                {
                    $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                    $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                    $str[] = ($number < 21) ? $words[$number] . " " . $digits[$counter] . $plural . " " . $hundred : $words[floor($number / 10) * 10] . " " . $words[$number % 10] . " " . $digits[$counter] . $plural . " " . $hundred;
                }
                else $str[] = null;
            }
            $str = array_reverse($str);
            $result = implode('', $str);
            $points = ($point) ? "." . $words[$point] . " " . $words[$point = $point % 10] : '';
            echo $result . "Rupees  " . $points . " Paise";
            if (!$points)
            {
                $amonut = $result . "Rupees";
            }
            else
            {
                $amonut = $result . "Rupees  " . $points . " Paise";
            }

            $date = date('Y-m-d');
            $mail = new PHPMailer;

            //$mail->SMTPDebug = 3;                               // Enable verbose debug output
            $mail->isSMTP(); // Set mailer to use SMTP
            $mail->Host = 'smtp-relay.sendinblue.com'; // Specify main and backup SMTP servers
            $mail->SMTPAuth = true; // Enable SMTP authentication
            $mail->Username = 'contact@haselfre.com'; // SMTP username
            $mail->Password = 'DW6a42NFsPUCgcjA'; // SMTP password
            $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587; // TCP port to connect to
            $mail->setFrom('invoice@haselfre.com', 'Nidara-Children');
            $mail->addAddress('customersupport@nidarachildren.com', ''); // Add a recipient
            // Name is optional
            $mail->addReplyTo('invoice@haselfre.com', 'Information');

            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'Invoice';

            $mail->Body = 'This is body';
            $content = '<h1>My PDF header</h1><br>This is my PDF content<br>';
            /*			$content    = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
            <html>
            <head>
            <meta http-equiv="content-type" content="text/html; charset=utf-8">
            
            <style type="text/css">
            body,td{
            font-family:verdana,geneva;
            font-size:12px;
            }
            body{
            background:#fff;
            padding:20px;
            }
            .top-img{
            width:100%;
            text-align:center;
            padding-bottom:0;
            font-size:10px;
            }
            .sub-mail-cont{
            width:100%;
            }
            .sub-mail-vr{
            width:900px;
            margin:auto;
            float:none;
            }
            .main-page-mail{
            width:100%;
            float:left;
            padding:20px;
            border:1px solid #999;
            }
            .sub-mail-but{
            width:100%;
            text-align:center;
            padding-top:30px;
            float:left;
            }
            a.sub-but{
            text-decoration:none;
            color:#333;
            padding:10px 50px;
            border:1px solid;
            }
            .sub-but-cont{
            width:100%;
            padding-top:20px;
            float:left;
            }
            .footer{
            width:100%;
            text-align:center;
            font-size:10px;
            padding-top:20px;
            float:left;
            }
            .footer ul{
            list-style:none;
            float:left;
            margin:15px 10px;
            width:100%;
            padding:0;
            }
            .footer ul li{
            display:inline-flex;
            padding-left:5px;
            }
            p{
            line-height:18px;
            }
            .small{
            font-size:11px;
            }
            .main-title{
            text-align:center;
            color:#aed7d3;
            float:left;
            width:100%;
            }
            .main-title h3{
            font-weight:500;
            }
            .first-name{
            text-transform:capitalize;
            }
            .product-img{
            width:20%;
            float:left;
            padding-right:20px;
            }
            .product-img img{
            width:100%;
            }
            .product-cont{
            width:75%;
            float:left;
            }
            .product-details{
            width:100%;
            float:left;
            }
            table {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
            }
            
            table td {
            border-right: 1px solid #ddd;
            border-left: 1px solid #ddd;
            border-bottom: 0px;
            padding: 15px;
            }
            table th {
            border: 1px solid #ddd;
            padding: 8px;
            }
            
            
            table th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            color: #000;
            }
            .invoic{
            width: 900px;
            margin: auto;
            }
            .text-align{
            text-align: center;
            }
            </style></head>
            <body bgcolor="#fff">
            
            <div class="sub-mail-vr">
            <div class="main-page-mail">
            <table id="customers">
            <tr>
            <th class="text-align">TAX INVOICE</th>
            </tr>
            </table>
            <table id="customers">
            <tr>
            <th class="text-align">
            <h3>NIDARA CHILDREN PRIVATE LIMITED	</h3>
            <h4>(Formerly Haselfre Solutions Private Limited)</h4>
            <p>Admin off: 1A, KINGSTON BUILDING,</p>
            <p>29/17, M.G.R. ROAD, KALAKSHETRA COLONY,</p>
            <h4>BESANT NAGAR, CHENNAI- 600090</h4>
            <h4>CIN: U72900DL2003PTC119476	</h4>
            <h4>GSTIN No 33AABCH2587H2ZM</h4>
            </th>
            </tr>
            </table>
            <table id="customers">
            <tr>
            <th>
            <h3>Bill to</h3>
            <p></p>
            <p>Customer`s Name :'. $user-> first_name . ' ' . $user-> last_name .  '</p>
            <p>Customer`s Address </p>
            <p>' . $user-> address_1 . '</p>
            <p>' . $user-> address_2 . '</p>
            <p>' . $user-> city . '</p>
            <p>' . $user-> state . '</p>
            <p>' . $user-> country . '</p>
            <p>' . $user-> post_code . '</p>
            <p></p>
            <p>GSTIN of Customer (If registered)</p>
            </th>
            <th>
            <h3>Place of Supply</h3>
            <p></p>
            <p>Invoice No ' . $user-> order_ids .' </p>
            <p>Date ' . $date .'</p>
            </th>
            </tr>
            </table>
            <table id="customers">
            <tr>
            <th>Description of Services</th>
            <th></th>
            <th>SAC Code</th>
            <th>Amount(Rs)</th>
            </tr>
            '. $emailvalue  .'
            <tr>
            <td>Total</td>
            <td></td>
            <td></td>
            <td>'. $user->total_amount .'</td>
            </tr>
            <tr>
            <td>Less Discount</td>
            <td></td>
            <td></td>
            <td> -' . $user->discoun_amount .'</td>
            </tr>
            <tr>
            <td>Taxable Value</td>
            <td></td>
            <td></td>
            <td>' . $taxabelvalue .'</td>
            </tr>
            <tr>
            <td>Add IGST </td>
            <td></td>
            <td>18%</td>
            <td> ' . $user->tax_amount . '</td>
            </tr>
            <tr>
            <th>Totals </th>
            <th></th>
            <th></th>
            <th>' . $user->cart_amount . '</th>
            </tr>
            </table>
            <table id="customers">
            <tr>
            <th>
            <div style="text-align: left;">
            <h4>Amount Chargeable (in words)</h4>
            <p style="text-transform: capitalize;">'.$amonut.' only</p>
            <h3>Company PAN: AABCH2587H</h3>
            <h4>Reg Off:211HANS BHAWAN, 1 BAHADURSHAH ZAFAR MARG,</h4>
            <h4> NEW DELHI, Delhi, India, 110002</h4>
            </div>
            <div  style="text-align: right;">
            Authorised Signatory
            </div>
            </th>
            </tr>
            </table>
            
            
            
            </div>
            </div>
            
            </div>
            </div>
            </body>
            </html>';
            */
            $html2pdf = new HTML2PDF('P', 'A4', 'en');
            $html2pdf->WriteHTML($content);
            $contentPdf = $html2pdf->Output('my_doc.pdf', 'S');
            $attachment_content = chunk_split(base64_encode($contentPdf));
            $attachment = array(
                'myfilename.pdf' => $attachment_content
            );

            $mail->attachment = $contentPdf;
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            if (!$mail->send())
            {
                return $this
                    ->response
                    ->setJsonContent(['status' => false, 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
            }
            else
            {
                return $this
                    ->response
                    ->setJsonContent(['status' => true, 'message' => 'Message has been sent', 'data' => $user->email]);
            }
        }
    }

    public function sendcontact()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();

        $emaildata = '<div>';
        $emaildata .= '<h1>New Customer Query</h1>';
        $emaildata .= '<h3>First Name:' . $input_data->first_name;
        $emaildata .= '</h3><h3>Lest Name:' . $input_data->last_name;
        $emaildata .= '</h3><h3>Email:' . $input_data->email;
        $emaildata .= '</h3><h3>Phone Number:' . $input_data->mobile;
        $emaildata .= '</h3><h3>Enquiry :' . $input_data->inquiry;
        $emaildata .= '</h3><p>Message:' . $input_data->message;
        $emaildata .= '</p></div>';

        $mail = new PHPMailer;
        //$mail->SMTPDebug = 3;                               // Enable verbose debug output
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = 'smtp-relay.sendinblue.com'; // Specify main and backup SMTP servers
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = 'contact@haselfre.com'; // SMTP username
        $mail->Password = 'DW6a42NFsPUCgcjA'; // SMTP password
        $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; // TCP port to connect to
        $mail->setFrom($input_data->email, $input_data->first_name);
        $mail->addAddress('customersupport@nidarachildren.com', 'Nidara-Children'); // Add a recipient
        // Name is optional
        $mail->addReplyTo($input_data->email, $input_data->firstname);

        //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'New Customer Query';

        $mail->Body = '<!DOCTYPE html>
							<html>
							  <head>
								<meta http-equiv="content-type" content="text/html; charset=utf-8">
								
							  <style type="text/css">
									body,td{
										font-family:verdana,geneva;
										font-size:12px;
									}
									body{
										background:#fff;
										padding:20px;
									}
									.top-img{
										width:100%;
										text-align:center;
										padding-bottom:0;
										font-size:10px;
									}
									.sub-mail-cont{
										width:100%;
									}
									.sub-mail-vr{
										width:580px;
										margin:auto;
										float:none;
									}
									.main-page-mail{
										width:100%;
										float:left;
										padding:20px;
										border:1px solid #999;
									}
									.sub-mail-but{
										width:100%;
										text-align:center;
										padding-top:30px;
										float:left;
									}
									a.sub-but{
										text-decoration:none;
										color:#333;
										padding:10px 50px;
										border:1px solid;
										text-transform:uppercase
									}
									.sub-but-cont{
										width:100%;
										padding-top:20px;
										float:left;
									}
									.footer{
										width:100%;
										text-align:center;
										font-size:10px;
										padding-top:20px;
										float:left;
									}
									.footer ul{
										list-style:none;
										float:left;
										margin:15px 10px;
										width:100%;
										padding:0;
									}
									.footer ul li{
										display:inline-flex;
										padding-left:5px;
									}
									p{
										line-height:18px;
									}
									.small{
										font-size:11px;
									}
									.main-title{
										text-align:center;
										float:left;
										width:100%;
									}
									.main-title h3{
										font-weight:500;
										color:#aed7d3;
									}
									.first-name{
										text-transform:capitalize;
									}
									.product-img{
										width:20%;
										float:left;
										padding-right:20px;
									}
									.product-img img{
										width:100%;
									}
									.product-cont{
										width:75%;
										float:left;
									}
									.product-details{
										width:100%;
										float:left;
									}
									.top-img-2{
										width:200px;
										float:none;
										margin:auto;
									}
									.top-img-2 img{
										width:100%;
									}
							</style></head>
							  <body bgcolor="#fff">
								' . $emaildata . '
							</body>
							</html>
							
							';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        if (!$mail->send())
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);

        }
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => true, 'message' => 'We have successfully received your message. We will get back to you via email as soon as possible.']);
        }

    }

    public function senddoctorinfo()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();

        $emaildata = '<div>';
        $emaildata .= '<h1>Doctor Information</h1>';
        $emaildata .= '<h3>Name:' . $input_data->firstname;
        $emaildata .= '</h3><h3>RegisterNo:' . $input_data->regNo;
        $emaildata .= '</h3><h3>Email:' . $input_data->email;
        $emaildata .= '</h3><h3>JobTitle:' . $input_data->job;
        $emaildata .= '</h3><h3>Phone Number:' . $input_data->phone_number;
        $emaildata .= '</h3><h3>Enquiry Type:' . $input_data->enquiry;
        $emaildata .= '</h3><h3>BestTime_Callyou :' . $input_data->best_time;
        $emaildata .= '</h3><h3>DecisionAuthority_Institution :' . $input_data->institute;
        $emaildata .= '</h3><h3>HowSoonExpect_Nidara :' . $input_data->client;
        $emaildata .= '</h3><h3>Issue_practice_Nidara :' . $input_data->issues;
        $emaildata .= '</h3><h1>Doctor Institute Information</h1>';
        $emaildata .= '<h3>Institution :' . $input_data->nameInst;
        $emaildata .= '</h3><h3>Address :' . $input_data->address . ',' . $input_data->address_1;
        $emaildata .= '</h3><h3>City :' . $input_data->city;
        $emaildata .= '</h3><h3>Country :' . $input_data->country;
        $emaildata .= '</h3><h3>InstitutePhoneNo :' . $input_data->teleNo;
        $emaildata .= '</h3><h3>website :' . $input_data->website;
        $emaildata .= '</h3><h3>InstituteEmail :' . $input_data->Instemail;
        $emaildata .= '</h3><h3>Fax :' . $input_data->fax;
        $emaildata .= '</h3><h1>Doctor Context Information</h1>';
        $emaildata .= '<h3>Services :' . $input_data->services;
        $emaildata .= '<h3>PrivatePractice :' . $input_data->practice;
        $emaildata .= '<h3>FileCV :' . $input_data->imageUpload;
        $emaildata .= '</h3></div>';

        $mail = new PHPMailer;
        //$mail->SMTPDebug = 3;                               // Enable verbose debug output
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = 'smtp-relay.sendinblue.com'; // Specify main and backup SMTP servers
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = 'contact@haselfre.com'; // SMTP username
        $mail->Password = 'DW6a42NFsPUCgcjA'; // SMTP password
        $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; // TCP port to connect to
        $mail->setFrom($input_data->email, $input_data->firstname);
        $mail->addAddress('customersupport@nidarachildren.com', 'Nidara-Children'); // Add a recipient
        // Name is optional
        $mail->addReplyTo($input_data->email, $input_data->firstname);

        //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Doctor Information';

        $mail->Body = '<!DOCTYPE html>
							<html>
							  <head>
								<meta http-equiv="content-type" content="text/html; charset=utf-8">
								
							  <style type="text/css">
									body,td{
										font-family:verdana,geneva;
										font-size:12px;
									}
									body{
										background:#fff;
										padding:20px;
									}
									.top-img{
										width:100%;
										text-align:center;
										padding-bottom:0;
										font-size:10px;
									}
									.sub-mail-cont{
										width:100%;
									}
									.sub-mail-vr{
										width:580px;
										margin:auto;
										float:none;
									}
									.main-page-mail{
										width:100%;
										float:left;
										padding:20px;
										border:1px solid #999;
									}
									.sub-mail-but{
										width:100%;
										text-align:center;
										padding-top:30px;
										float:left;
									}
									a.sub-but{
										text-decoration:none;
										color:#333;
										padding:10px 50px;
										border:1px solid;
										text-transform:uppercase
									}
									.sub-but-cont{
										width:100%;
										padding-top:20px;
										float:left;
									}
									.footer{
										width:100%;
										text-align:center;
										font-size:10px;
										padding-top:20px;
										float:left;
									}
									.footer ul{
										list-style:none;
										float:left;
										margin:15px 10px;
										width:100%;
										padding:0;
									}
									.footer ul li{
										display:inline-flex;
										padding-left:5px;
									}
									p{
										line-height:18px;
									}
									.small{
										font-size:11px;
									}
									.main-title{
										text-align:center;
										float:left;
										width:100%;
									}
									.main-title h3{
										font-weight:500;
										color:#aed7d3;
									}
									.first-name{
										text-transform:capitalize;
									}
									.product-img{
										width:20%;
										float:left;
										padding-right:20px;
									}
									.product-img img{
										width:100%;
									}
									.product-cont{
										width:75%;
										float:left;
									}
									.product-details{
										width:100%;
										float:left;
									}
									.top-img-2{
										width:200px;
										float:none;
										margin:auto;
									}
									.top-img-2 img{
										width:100%;
									}
							</style></head>
							  <body bgcolor="#fff">
								' . $emaildata . '
							</body>
							</html>
							
							';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        if (!$mail->send())
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);

        }
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => true, 'message' => 'Message has been sent']);
        }

    }

 public function sendEmailforappointment()
    {
    	$input_data = $this->request->getJsonRawBody ();

        $school = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'SalesmanAppointment.id',
            'SalesmanAppointment.first_name',
            'SalesmanAppointment.last_name',
            'SalesmanAppointment.email',
            'SalesmanAppointment.mno',
            'SalesmanDayAvailability.choose_date',
            'SalesmanDayAvailability.start_time',
           
            
        ))
            ->from("SalesmanAppointment")
            ->leftjoin('SalesmanDayAvailability', 'SalesmanDayAvailability.id = SalesmanAppointment.day_id')
             ->inwhere('SalesmanAppointment.status', array(
            1
        ))
          ->inwhere('SalesmanAppointment.email', array(
            $input_data->email
        ))
            ->getQuery()
            ->execute();


        foreach ($school as $value)
        {
          $timestamp = strtotime($value->choose_date);
 
// Creating new date format from that timestamp
$new_date = date("d-m-Y", $timestamp);


            $mail = new PHPMailer;
            //$mail->SMTPDebug = 3;                               // Enable verbose debug output
            $mail->isSMTP(); // Set mailer to use SMTP
            $mail->Host = 'smtp-relay.sendinblue.com'; // Specify main and backup SMTP servers
            $mail->SMTPAuth = true; // Enable SMTP authentication
            $mail->Username = 'contact@haselfre.com'; // SMTP username
            $mail->Password = 'DW6a42NFsPUCgcjA'; // SMTP password
            $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587; // TCP port to connect to
            $mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
            $mail->addAddress($value->email, ''); // Add a recipient
            // Name is optional
            $mail->addReplyTo('customersupport@nidarachildren.com', 'Information');
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'Welcome to Nidara Children ';
            $mail->Body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<style type="text/css">
.ReadMsgBody {width: 100%;}
.ExternalClass {width: 100%;}

body {
	color: #666666;
}
		.page-title {
			text-align: center;
			width: 100%;
			float: left;
			color: #8bbdcb;
		}
           body,td{
            font-family:verdana,geneva;
            font-size:12px;
           }
           body{
            background:#fff;
            padding:20px;
           }
           .top-img{
            width:100%;
            text-align:center;
            padding-bottom:0;
            font-size:10px;
           }
           .sub-mail-cont{
            width:100%;
           }
           .sub-mail-vr{
            width:580px;
            margin:auto;
            float:none;
           }
           .main-page-mail{
            width:100%;
            float:left;
            padding:20px;
            border:1px solid #999;
           }
           .sub-mail-but{
            width:100%;
            text-align:center;
            padding-top:30px;
            float:left;
           }
           a.sub-but{
            text-decoration:none;
            color:#333;
            padding:10px 50px;
            border:1px solid;
           }
           .sub-but-cont{
            width:100%;
            padding-top:20px;
            float:left;
           }
           .footer{
            width:100%;
            text-align:center;
            font-size:10px;
            padding-top:20px;
            float:left;
           }
           .footer ul{
            list-style:none;
            float:left;
            margin:15px 10px;
            width:100%;
            padding:0;
           }
           .footer ul li{
            display:inline-flex;
            padding-left:5px;
           }
           p{
            line-height:18px;
           }
           .small{
            font-size:11px;
           }
           .main-title{
            text-align:center;
            color:#aed7d3;
            float:left;
            width:100%;
           }
           .main-title h3{
            font-weight:500;
           }
           .first-name{
            text-transform:capitalize;
           }
           .product-img{
            width:20%;
            float:left;
            padding-right:20px;
           }
           .product-img img{
            width:100%;
           }
           .product-cont{
            width:75%;
            float:left;
           }
           .product-details{
            width:100%;
            float:left;
           }
		   .page-content {
				width: 100%;
				float: left;
				color: #666666;
			}
			.click-but {
				width: 100%;
				float: left;
				text-align: center;
			}
			.click-but .but{
				width:250px;
				display:block;
				margin:auto;
			}
			.click-but .but a {
				text-decoration: none;
				padding: 10px;
				color: #fff;
			}
			.click-but .but {
				display: block;
				margin: auto;
				width: 265px;
				padding: 15px;
				background: #333333;
				font-size: 15px;
        float: left;
			}
			.click-but .but:hover{
				background: #8bbdcb;
			}
         
span.yshortcuts { color:#000; background-color:none; border:none;}
span.yshortcuts:hover,
span.yshortcuts:active,
span.yshortcuts:focus {color:#000; background-color:none; border:none;}
</style>
</head>
<body bgcolor="#fff" style="font-family: verdanaWELCOME TO NIDARA-CHILDREN
,geneva; font-size: 12px; background: #fff; padding: 20px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="" bgcolor="#fff"><tr><td>

          
          <div class="sub-mail-vr" style="width: 580px; margin: auto; float: none;">
            <div class="main-page-mail" style="width: 100%; float: left; padding: 20px; border: 1px solid #999;">
           <div class="top-img" style="width: 100%; text-align: center; padding-bottom: 0; font-size: 10px;">
             <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/09/logo-old.jpg" alt="170x150_logo.jpg" style="width:30%" /><p style="line-height: 18px;">GIVE YOUR CHILD THE BEST START IN LIFE</p>
           </div>
		   <div class="page-title">
			<h3>YOUR NIDARA-CHILDREN VIRTUAL INFORMATION SESSION APPOINTMENT CONFIRMATION</h3>
		   </div>
			<div class="page-content">
				
				<p>Dear ' . $value->first_name . ' ,</p> 

				<p>Your NC Virtual Information Session has been confirmed at as below:</p>

				<p> Date : ' . $new_date . ' </p>

				<p> Time : ' . $value->start_time . ' </p>

				<p>We will send you a separate virtual meeting invite in your email.</p>
				<p>To Modify Your Appointment, click the button below:</p>

        <div class="click-but">
        <div class="but">
          <a href="' . $this
                ->config->weburl . '/signin"> <span>MODIFY MY APPOINTMENT</span> </a>
        </div>
      </div>

				<p>To Cancel Your Appointment, click the button below:</p> 
         <div class="click-but">
        <div class="but">
          <a href="' . $this
                ->config->weburl . '/signin"> <span>CANCEL MY APPOINTMENT</span> </a>
        </div>
      </div>


				<p>We look forward to helping you give your child the best start in life.</p>

				

			</div>
			
            
            <div class="sub-but-cont" style="width: 100%; padding-top: 20px; float: left;">
           <p style="line-height: 18px;">Best regards,</p>
           <p style="line-height: 18px;">
            </p>
            <p style="line-height: 18px;">Nidara-Children</p>
          </div>
          <div class="footer" style="width: 100%; text-align: center; font-size: 10px; padding-top: 20px; float: left;">
            <ul style="list-style: none; float: left; margin: 15px 10px; width: 100%; padding: 0;">
				<li style="display: inline-flex; padding-left: 5px;">
				 <a class="fb" href="https://www.facebook.com/NidaraChildren/" target="_blank"><img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/facebook-mint-unsmushed-1.png" alt="facebook-mint.png" /></a>
			   </li>
			   <li style="display: inline-flex; padding-left: 5px;">
				 <a class="twt" href="https://twitter.com/nidarachildren" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/twitter-mint.png" alt="twitter-mint.png" /></a>
			   </li>
			   <li style="display: inline-flex; padding-left: 5px;">
				 <a class="ins" href="https://www.instagram.com/nidarachildren/" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/instagram-mint-1.png" alt="instagram-mint.png" /></a>
			   </li>
			   <li style="display: inline-flex; padding-left: 5px;">
				 <a class="email" href="' . $this
                ->config->weburl . '/contact-us/" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/mail-mint.png" alt="mail-mint.png" /></a>
			   </li>
           </ul>
			<span>Copyright &copy; Nidara-Children. All rights reserved.</span>
				<br /><span>You are receiving this email because you opted in at our website.
            </span>
            <br /><span>
			<a href="https://faq.nidarachildren.com/wp-content/themes/nidara/Newsletter-contact/Nidara_Newslatter.vcf">Add us to your address book</a> | <a href="https://nidarachildren.us17.list-manage.com/unsubscribe?u=e2c0982dd8b7d1a16f74d886d&id=fae67dd82a&e=*%7CUNIQID%7C*">Unsubscribe from this list</a>
			</span>
          </div>
           </div>
         </div>
         
</td></tr></table>
</body>
</html>
';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            if (!$mail->send())
            {
                return $this
                    ->response
                    ->setJsonContent(['status' => false, 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
            }
            else
            {
                // return $this->response->setJsonContent ( [
                // 	'status' => true,
                // 	'message' => 'Message hase be sent.'
                // ] );
                

                
            }
        }
        return $this
            ->response
            ->setJsonContent(['status' => true, 'message' => 'Email send successfully', ]);
    }

}

