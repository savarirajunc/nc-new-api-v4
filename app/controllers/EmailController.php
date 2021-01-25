<?php
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
require BASE_PATH.'/vendor/Crypto.php';
require BASE_PATH.'/vendor/mailin.php';
require BASE_PATH.'/vendor/class.phpmailer.php';
class EmailController extends \Phalcon\Mvc\Controller {
	//CONST UniqId = 'ncp012gb';
	public function index() {
	}
	
	/**
	 * Fetch all Record from database :-
	 */
	public function viewall(){
		$users = Users::find();
		$userarray = array();
		$days = strtotime(date("Y-m-d"));
		foreach($users as $value){
			$ncproduct2 = $this->modelsManager->createBuilder ()->columns ( array (
					'NCProductPricing.id as ids',
					'NCOrderList.created_at as created_at',
					'NCProductPricing.product_type as product_type',
					'NCProduct.product_name as product_name',
					'NCProduct.product_img as product_img',
					'NCProductPricing.product_price as product_price',
					))->from("NCOrderList")
					->leftjoin('NCOrderProductList','NCOrderProductList.order_id = NCOrderList.id')
					->leftjoin('NCOrderStatus','NCOrderStatus.order_id = NCOrderList.id')
					->leftjoin('NCProductPricing','NCOrderProductList.product_id = NCProductPricing.id')
					->leftjoin('NCProduct','NCProductPricing.product_id = NCProduct.id')
					->inwhere("NCOrderList.user_id",array($value->id))
					->inwhere("NCOrderStatus.status",array('Success'))
					->getQuery ()->execute ();
			$flag = 0;
			foreach($ncproduct2 as $main){
		
		$daysnow = strtotime(date("Y-m-d")); 
		$dayscreate = strtotime($main->created_at);
		$today['days'] = round(($daysnow - $dayscreate)/(60 * 60 * 24)) + 1;
		
		
			if($today['days']  == 346 || $today['days'] == 338 || $today['days'] == 340){
				$ncproduct = $this->modelsManager->createBuilder ()->columns ( array (
					'NCProductPricing.id as ids',
					'NCProductPricing.product_type as product_type',
					'NCProduct.product_name as product_name',
					'NCProduct.product_img as product_img',
					'NCProductPricing.product_price as product_price',
					))->from("NCOrderList")
					->leftjoin('NCOrderProductList','NCOrderProductList.order_id = NCOrderList.id')
					->leftjoin('NCOrderStatus','NCOrderStatus.order_id = NCOrderList.id')
					->leftjoin('NCProductPricing','NCOrderProductList.product_id = NCProductPricing.id')
					->leftjoin('NCProduct','NCProductPricing.product_id = NCProduct.id')
					->inwhere("NCOrderList.user_id",array($value->id))
					->inwhere("NCOrderStatus.status",array('Success'))
					->getQuery ()->execute ();
					
					
					$emailvalue = '';
					foreach($ncproduct as $value2){
						$emailvalue .= '<div class="product-details"><div class="product-img">';
						$emailvalue .= '<img src="';
						$emailvalue .= $value2->product_img .'">';
						$emailvalue .= '</div><div class="product-cont"><h4>';
						$emailvalue .= $value2->product_name;
						$emailvalue .= '</h4><p>Rs. ';
						$emailvalue .= number_format($value2->product_price,2);
						$emailvalue .= '/ month for 12 months</p><p> <span style="float:right;padding-right:20px">Total:  Rs. ';
						$emailvalue .= number_format($value2->product_price,2);
						$emailvalue .= '</span></p></div>';
					}
					$emailvalue .= '';
					$mail = new PHPMailer;

						//$mail->SMTPDebug = 3;                               // Enable verbose debug output

						$mail->isSMTP();                                      // Set mailer to use SMTP
						$mail->Host = 'smtp-relay.sendinblue.com';  // Specify main and backup SMTP servers
						$mail->SMTPAuth = true;                               // Enable SMTP authentication
						$mail->Username = 'baskar@haselfre.com';                 // SMTP username
						$mail->Password = 'k6zcNyXJw27MCT0j';                           // SMTP password
						$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
						$mail->Port = 587;                                    // TCP port to connect to

						$mail->setFrom('customersupport@haselfre.com', 'Nidara-Children');
						$mail->addAddress($value->email, '');     // Add a recipient
																				// Name is optional
						$mail->addReplyTo('customersupport@haselfre.com', 'Information');

						//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
						//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
						$mail->isHTML(true);                                  // Set email format to HTML

						$mail->Subject = 'Continue giving your child a solid early foundation…';
						
						$mail->Body    = '
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
								</style></head>
								  <body bgcolor="#fff">
									
									<div class="sub-mail-vr">
									  <div class="main-page-mail">
										<div class="top-img">
										  <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/07/170x150_logo.jpg" alt="170x150_logo.jpg">
										  <p>THE BEST START IN LIFE</p>
										</div>
										<div class="main-title">
										  <h3>CONTINUE GIVING YOUR CHILD A SOLID EARLY FOUNDATION…
										  </h3>
										</div>
										<div class="sub-mail-cont">
										  <span>Hi <span class="first-name">'. $value->first_name .' '. $value->last_name .'</span>, </span>
										  <br>
										  <p>Extend your Nidara-Children subscription.  You have only One month left
										</p>
										'. $emailvalue .'
									   
									  </div>
									</div>
									<div class="sub-mail-but">
									  <p>
									  <a class="sub-but" href="'. $this->config->weburl .'/"><b>EXTEND
									  </b>
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
									<a class="email" href="'. $this->config->weburl .'/contact-us/" target="_blank"> <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/mail-mint.png" alt="mail-mint.png"></a>
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
						if(!$mail->send()) {

							$flag = 0;
		
						} 
						else{
							$flag = 1;
						}
				}
			}
		}
		if ($flag == 1)
			{
			return $this->response->setJsonContent ( [ 
				'status' => true,
				'message' => 'Message has been sent' 
			] );
			}else 
			{
			return $this->response->setJsonContent ( [ 
				'status' => false,
				'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo 
			] );
			}
		
	}
	public function finalcalluaser(){
		$users = $this->modelsManager->createBuilder ()->columns ( array (
			'NidaraKidProfile.date_of_birth as date_of_birth',
			'NidaraKidProfile.gender as gender',
			'NidaraKidProfile.expiry_date as expiry_date',
			'Users.first_name as first_name',
			'Users.last_name as last_name',
			'Users.email as email',
		))->from('KidParentsMap')
		->leftjoin('Users','KidParentsMap.users_id = Users.id')
		->leftjoin('NidaraKidProfile','KidParentsMap.nidara_kid_profile_id = NidaraKidProfile.id')
		->getQuery ()->execute ();
		$userarray = array();
		$days = strtotime(date("Y-m-d"));
		foreach($users as $value){
			$userarray[] = $value;
		$flag = 0;
		$daysnow = strtotime(date("Y-m-d")); 
		$dayscreate = strtotime($value->expiry_date);
		$today['days'] = round(($daysnow - $dayscreate)/(60 * 60 * 24)) + 1;
		
		
			if($today['days']  == 21 || $today['days'] == 24 || $today['days'] == 27){
				
					$mail = new PHPMailer;

						//$mail->SMTPDebug = 3;                               // Enable verbose debug output

						$mail->isSMTP();                                      // Set mailer to use SMTP
						$mail->Host = 'smtp-relay.sendinblue.com';  // Specify main and backup SMTP servers
						$mail->SMTPAuth = true;                               // Enable SMTP authentication
						$mail->Username = 'baskar@haselfre.com';                 // SMTP username
						$mail->Password = 'k6zcNyXJw27MCT0j';                           // SMTP password
						$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
						$mail->Port = 587;                                    // TCP port to connect to

						$mail->setFrom('customersupport@haselfre.com', 'Nidara-Children');
						$mail->addAddress($value->email, '');     // Add a recipient
																				// Name is optional
						$mail->addReplyTo('customersupport@haselfre.com', 'Information');

						//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
						//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
						$mail->isHTML(true);                                  // Set email format to HTML

						$mail->Subject = 'Final call: best start in life';
						
						$mail->Body    = '
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
									<div class="main-title">
									  <h3>FINAL CALL:  BEST START IN LIFE</h3>
									  <h4>EARLY CHILD DEVELOPMENT SYSTEM PREVIEW</h4>
									  <h5>HEALTH &amp; WELL BEING</h5>
									  <div class="top-img-2">
										<img src="https://gallery.mailchimp.com/e2c0982dd8b7d1a16f74d886d/images/5817b060-678d-496d-858f-7694730779eb.jpg" alt="5817b060-678d-496d-858f-7694730779eb.jpg">
									  </div>
									  
									  <h5>PERSONALIZED LEARNING</h5>
									  <div class="top-img-2">
										<img src="https://gallery.mailchimp.com/e2c0982dd8b7d1a16f74d886d/images/f720c3cd-e8f7-432d-bc6a-c8849fb745d1.png" alt="f720c3cd-e8f7-432d-bc6a-c8849fb745d1.png">
									  </div>
									</div>
									<div class="sub-mail-cont">
									<span>Hi <span class="first-name">'. $value->first_name .''. $value->last_name .'</span>, </span>
									<br>
									<p>10 % Off on your Nidara-Children System ends tonight!</p>
									<p style="color:#8bbdcb;">Enter CHECK OUT CODE: SOLID10</p>
									<!--<div class="product-details">
									  <div class="product-img">
										<img src="https://faq.nidarachildren.com/wp-content/uploads/2018/07/Nidara-Children-Web-1.jpeg" alt="Nidara-Children-Web-1.jpeg">
									  </div>
									  <div class="product-cont">
										<h4>NIDARA-CHILDREN EARLY CHILD DEVELOPMENT SYSTEM - GIRL</h4>
										<p>Rs. 1295 / month for 12 months</p>
										<p><span>Billing Cycle: Billed Monthly</span> <span style="float:right;padding-right:20px">Total:  Rs. XXXXX</span>
									  </p>
									</div>
									
								  </div>-->
								</div>
									<div class="sub-mail-but">
									  
									  <p>
									  <a class="sub-but" href="'. $this->config->weburl .'/"><b>GET NIDARA-CHILDREN NOW</b>
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
									<a class="email" href="'. $this->config->weburl .'/contact-us/" target="_blank"> <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/mail-mint.png" alt="mail-mint.png"></a>
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
						if(!$mail->send()) {
							$flag = 0;
		
						} 
						else{
							$flag = 1;
						}
					
			}
			
	}
	if ($flag == 1)
			{
				return $this->response->setJsonContent ( [ 
							'status' => true,
							'message' => 'Message has been sent' 
						] );
			}else 
			{
								return $this->response->setJsonContent ( [ 
								'status' => false,
								'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo 
							] );
			}
	
	}
	
	
	
	public function attendance(){
		$users = $this->modelsManager->createBuilder ()->columns ( array (
			'NidaraKidProfile.date_of_birth as date_of_birth',
			'NidaraKidProfile.gender as gender',
			'NidaraKidProfile.expiry_date as expiry_date',
			'Users.first_name as first_name',
			'Users.last_name as last_name',
			'Users.email as email',
			'DailyRoutineAttendance.attendanceDate as attendanceDate',
		))->from('KidParentsMap')
		->leftjoin('Users','KidParentsMap.users_id = Users.id')
		->leftjoin('NidaraKidProfile','KidParentsMap.nidara_kid_profile_id = NidaraKidProfile.id')
		->leftjoin('DailyRoutineAttendance','DailyRoutineAttendance.nidara_kid_profile_id = NidaraKidProfile.id')
		->orderBy('DailyRoutineAttendance.attendanceDate DESC LIMIT 1')
		->getQuery ()->execute ();
		$userarray = array();
		$days = strtotime(date("Y-m-d"));
		foreach($users as $value){
			$userarray[] = $value;
		$flag = 0;
		$daysnow = strtotime(date("Y-m-d")); 
		$dayscreate = strtotime($value->attendanceDate);
		$today['days'] = round(($daysnow - $dayscreate)/(60 * 60 * 24)) + 1;
		
		
			if($today['days']  == 7 || $today['days'] == 10 || $today['days'] == 13){
				
					$mail = new PHPMailer;

						//$mail->SMTPDebug = 3;                               // Enable verbose debug output

						$mail->isSMTP();                                      // Set mailer to use SMTP
						$mail->Host = 'smtp-relay.sendinblue.com';  // Specify main and backup SMTP servers
						$mail->SMTPAuth = true;                               // Enable SMTP authentication
						$mail->Username = 'baskar@haselfre.com';                 // SMTP username
						$mail->Password = 'k6zcNyXJw27MCT0j';                           // SMTP password
						$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
						$mail->Port = 587;                                    // TCP port to connect to

						$mail->setFrom('customersupport@haselfre.com', 'Nidara-Children');
						$mail->addAddress($value->email, '');     // Add a recipient
																				// Name is optional
						$mail->addReplyTo('customersupport@haselfre.com', 'Information');

						//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
						//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
						$mail->isHTML(true);                                  // Set email format to HTML

						$mail->Subject = 'Hope everything is alright';
						
						$mail->Body    = '
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
									<span>Hi <span class="first-name">'. $value->first_name .''. $value->last_name .'</span>, </span>
									<br>
									<p>Your little angel has not logged into Nidara-Children for their development.  Is everything alright?</p>
									<br>
									<p>Please do reach out to us via chat or email for any assistance.</p>
									<br>
									<p>Alternatively, just sign back in for a gentle start.</p>
									
								</div>
									<div class="sub-mail-but">
									  
									  <p>
									  <a class="sub-but" href="'. $this->config->weburl .'/signin"><b>SIGNIN</b>
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
									<a class="email" href="'. $this->config->weburl .'/contact-us/" target="_blank"> <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/mail-mint.png" alt="mail-mint.png"></a>
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
						if(!$mail->send()) {
							$flag = 0;
		
						} 
						else{
							$flag = 1;
						}
					
			}
			
	}
	if ($flag == 1)
			{
				return $this->response->setJsonContent ( [ 
							'status' => true,
							'message' => 'Message has been sent' 
						] );
			}else 
			{
								return $this->response->setJsonContent ( [ 
								'status' => false,
								'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo 
							] );
			}
	
	}
	
	
	
	
	public function missedyoufirst(){
		$users = $this->modelsManager->createBuilder ()->columns ( array (
			'NidaraKidProfile.date_of_birth as date_of_birth',
			'NidaraKidProfile.gender as gender',
			'NidaraKidProfile.expiry_date as expiry_date',
			'Users.first_name as first_name',
			'Users.last_name as last_name',
			'Users.email as email',
		))->from('KidParentsMap')
		->leftjoin('Users','KidParentsMap.users_id = Users.id')
		->leftjoin('NidaraKidProfile','KidParentsMap.nidara_kid_profile_id = NidaraKidProfile.id')
		->getQuery ()->execute ();
		$userarray = array();
		$days = strtotime(date("Y-m-d"));
		foreach($users as $value){
		$flag = 0;
		$daysnow = strtotime(date("Y-m-d")); 
		$dayscreate = strtotime($value->expiry_date);
		$today['days'] = round(($daysnow - $dayscreate)/(60 * 60 * 24)) + 1;
			if($value->gender == 'male'){
				$link =''. $this->config->weburl .'/boy';
			}else if($value->gender == 'female'){
				$link =''. $this->config->weburl .'/girl';
			}
		
			if($today['days']  == 8 || $today['days'] == 10 || $today['days'] == 12){
					$mail = new PHPMailer;

						//$mail->SMTPDebug = 3;                               // Enable verbose debug output

						$mail->isSMTP();                                      // Set mailer to use SMTP
						$mail->Host = 'smtp-relay.sendinblue.com';  // Specify main and backup SMTP servers
						$mail->SMTPAuth = true;                               // Enable SMTP authentication
						$mail->Username = 'baskar@haselfre.com';                 // SMTP username
						$mail->Password = 'k6zcNyXJw27MCT0j';                           // SMTP password
						$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
						$mail->Port = 587;                                    // TCP port to connect to

						$mail->setFrom('customersupport@haselfre.com', 'Nidara-Children');
						$mail->addAddress($value->email, '');     // Add a recipient
																				// Name is optional
						$mail->addReplyTo('customersupport@haselfre.com', 'Information');

						//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
						//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
						$mail->isHTML(true);                                  // Set email format to HTML

						$mail->Subject = 'It’s all about your little angel!';
						
						$mail->Body    = '
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
</style></head>
  <body bgcolor="#fff">
    
    <div class="sub-mail-vr">
      <div class="main-page-mail">
        <div class="top-img">
          <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/07/170x150_logo.jpg" alt="170x150_logo.jpg">
          <p>THE BEST START IN LIFE</p>
        </div>
        <div class="main-title">
          <h3>BECAUSE BEHIND EVERY INCREDIBLE CHILD IS AN INCREDIBLE MOM &amp; DAD!</h3>
        </div>
        <div class="sub-mail-cont">
          <span>Hi <span class="first-name">'. $value->first_name .''. $value->last_name .'</span>, </span>
          <br>
          <p>Our premium early child development system just got better. Learn more.
        </p>
        <h4>Enter code RENEW0</h4>
        <!-- <div class="product-details">
        <div class="product-img">
          <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/07/Nidara-Children-Web-1.jpeg" alt="Nidara-Children-Web-1.jpeg">
        </div>
        <div class="product-cont">
          <h4>NIDARA-CHILDREN EARLY CHILD DEVELOPMENT SYSTEM - GIRL</h4>
          <p>Rs. 1295 / month for 12 months</p>
          <p><span>Billing Cycle: Billed Monthly</span> <span style="float:right;padding-right:20px">Total:  Rs. XXXXX</span>
        </p>
      </div>
      
    </div>-->
      </div>
      <div class="sub-mail-but">
        <p>
        <a class="sub-but" href="'. $link .'"><b>GET NIDARA-CHILDREN NOW</b></a>
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
        <a class="email" href="'. $this->config->weburl .'/contact-us/" target="_blank"> <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/mail-mint.png" alt="mail-mint.png"></a>
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
						if(!$mail->send()) {
							$flag = 0;
		
						} 
						else{
							$flag = 1;
						}
			}		
			}
			if ($flag == 1)
			{
				return $this->response->setJsonContent ( [ 
							'status' => true,
							'message' => 'Message has been sent' 
						] );
			}else 
			{
								return $this->response->setJsonContent ( [ 
								'status' => false,
								'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo 
							] );
			}
	}
	
	
	
	
	public function deactiveparent(){
		$users = Users::find();
		$userarray = array();
		$days = strtotime(date("Y-m-d"));
		foreach($users as $value){
		$daysnow = strtotime(date("Y-m-d")); 
		$dayscreate = strtotime($value->modified_at);
		$today['days'] = round(($daysnow - $dayscreate)/(60 * 60 * 24)) + 1;
		$flag = 0;
		
			if($today['days'] == 2 && $value->status == 4){
					$emailvalue .= '';
					$mail = new PHPMailer;

						//$mail->SMTPDebug = 3;                               // Enable verbose debug output

						$mail->isSMTP();                                      // Set mailer to use SMTP
						$mail->Host = 'smtp-relay.sendinblue.com';  // Specify main and backup SMTP servers
						$mail->SMTPAuth = true;                               // Enable SMTP authentication
						$mail->Username = 'baskar@haselfre.com';                 // SMTP username
						$mail->Password = 'k6zcNyXJw27MCT0j';                           // SMTP password
						$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
						$mail->Port = 587;                                    // TCP port to connect to

						$mail->setFrom('customersupport@haselfre.com', 'Nidara-Children');
						$mail->addAddress($value->email, '');     // Add a recipient
																				// Name is optional
						$mail->addReplyTo('customersupport@haselfre.com', 'Information');

						//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
						//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
						$mail->isHTML(true);                                  // Set email format to HTML

						$mail->Subject = 'Continue giving your child a solid early foundation…';
						
						$mail->Body    = '
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
           <h3 style="font-weight: 500;">BIRTHDAY BUMPS&hellip;</h3>
         </div>
         <div class="sub-mail-cont" style="width: 100%;">
           <span>Hi <span class="first-name" style="text-transform: capitalize;">'. $value->first_name .''. $value->last_name .'</span>, </span>
           <br /><p style="line-height: 18px;">Get Nidara-Children for your little one now! Use the discount code you signed up for!
         </p>
         <h3>Enter code BESTSTART10</h3>
          </div>
          <div class="sub-mail-but" style="width: 100%; text-align: center; padding-top: 30px; float: left;">
         <p style="line-height: 18px;">
         <a class="sub-but" href="'. $this->config->weburl .'/" style="text-decoration: none; color: #333; padding: 10px 50px; border: 1px solid;"><b>GET NIDARA-CHILDREN NOW</b></a>
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
         <a class="email" href="'. $this->config->weburl .'/contact-us/" target="_blank"> <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/mail-mint.png" alt="mail-mint.png" /></a>
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
						if(!$mail->send()) {
							$flag = 0;
		
						} 
						else{
							$flag = 1;
						}
			}
		}
		if ($flag == 1)
			{
				return $this->response->setJsonContent ( [ 
							'status' => true,
							'message' => 'Message has been sent' 
						] );
			}else 
			{
								return $this->response->setJsonContent ( [ 
								'status' => false,
								'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo 
							] );
			}
		
	}
	
	
	

 public function cartabond(){
		$users = Users::find();
		$userarray = array();
		$days = strtotime(date("Y-m-d"));
		foreach($users as $value){
			$ncproduct2 = $this->modelsManager->createBuilder ()->columns ( array (
					'NCProductPricing.id as ids',
					'NCOrderList.created_at as created_at',
					'NCProductPricing.product_type as product_type',
					'NCProduct.product_name as product_name',
					'NCProduct.product_img as product_img',
					'NCProductPricing.product_price as product_price',
					))->from("NCOrderList")
					->leftjoin('NCOrderProductList','NCOrderProductList.order_id = NCOrderList.id')
					->leftjoin('NCOrderStatus','NCOrderStatus.order_id = NCOrderList.id')
					->leftjoin('NCProductPricing','NCOrderProductList.product_id = NCProductPricing.id')
					->leftjoin('NCProduct','NCProductPricing.product_id = NCProduct.id')
					->inwhere("NCOrderList.user_id",array($value->id))
					->inwhere("NCOrderStatus.status",array('Order New','Aborted','Failure'))
					->getQuery ()->execute ();
			$flag = 0;
			foreach($ncproduct2 as $main){
			$daysnow = strtotime(date("Y-m-d")); 
			$dayscreate = strtotime($main->created_at);
			$today['days'] = round(($daysnow - $dayscreate)/(60 * 60 * 24))+1;
			if($today['days']  == 9 || $today['days'] == 11 || $today['days'] == 13 || $today['days']  == 15 || $today['days'] == 17 || $today['days'] == 19 || $today['days'] == 21 || ($today['days'] >= 1 && $today['days'] <= 7)){
				$ncproduct = $this->modelsManager->createBuilder ()->columns ( array (
					'NCProductPricing.id as ids',
					'NCProductPricing.product_type as product_type',
					'NCProduct.product_name as product_name',
					'NCProduct.product_img as product_img',
					'NCProductPricing.product_price as product_price',
					))->from("NCOrderList")
					->leftjoin('NCOrderProductList','NCOrderProductList.order_id = NCOrderList.id')
					->leftjoin('NCOrderStatus','NCOrderStatus.order_id = NCOrderList.id')
					->leftjoin('NCProductPricing','NCOrderProductList.product_id = NCProductPricing.id')
					->leftjoin('NCProduct','NCProductPricing.product_id = NCProduct.id')
					->inwhere("NCOrderList.user_id",array($value->id))
					->inwhere("NCOrderStatus.status",array('Order New','Aborted','Failure'))
					->getQuery ()->execute ();
					$emailvalue = '';
					foreach($ncproduct as $value2){
						$emailvalue .= '<div class="product-details"><div class="product-img" style="width:20%;float:left;padding-right:20px;">';
						$emailvalue .= '<img style="width:100%" src="';
						$emailvalue .= $value2->product_img .'">';
						$emailvalue .= '</div><div class="product-cont"><h4>';
						$emailvalue .= $value2->product_name;
						$emailvalue .= '</h4><p>Rs. ';
						$emailvalue .= number_format($value2->product_price,2);
						$emailvalue .= '/ month for 12 months</p><p> <span style="float:right;padding-right:20px">Total:  Rs. ';
						$emailvalue .= number_format($value2->product_price,2);
						$emailvalue .= '</span></p></div>';
					}
					$emailvalue .= '';
					$mail = new PHPMailer;

						//$mail->SMTPDebug = 3;                               // Enable verbose debug output

						$mail->isSMTP();                                      // Set mailer to use SMTP
						$mail->Host = 'smtp-relay.sendinblue.com';  // Specify main and backup SMTP servers
						$mail->SMTPAuth = true;                               // Enable SMTP authentication
						$mail->Username = 'baskar@haselfre.com';                 // SMTP username
						$mail->Password = 'k6zcNyXJw27MCT0j';                           // SMTP password
						$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
						$mail->Port = 587;                                    // TCP port to connect to

						$mail->setFrom('customersupport@haselfre.com', 'Nidara-Children');
						$mail->addAddress($value->email, '');     // Add a recipient
																				// Name is optional
						$mail->addReplyTo('customersupport@haselfre.com', 'Information');

						//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
						//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
						$mail->isHTML(true);                                  // Set email format to HTML

						$mail->Subject = 'Did you forget something?';
						
						$mail->Body    = '
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
            <h3 style="font-weight: 500;">DID YOU FORGET SOMETHING?
            </h3>
          </div>
          <div class="sub-mail-cont" style="width: 100%;">
            <span>Hi <span class="first-name" style="text-transform: capitalize;">'. $value->first_name .' '. $value->last_name .'</span>, </span>
            <br /><p style="line-height: 18px;">You left the premium early child development system in your shopping basket!
          </p>
          '. $emailvalue .'
            
           </div>
         </div>
         <div class="sub-mail-but" style="width: 100%; text-align: center; padding-top: 30px; float: left;">
           <p style="line-height: 18px;">
           <a class="sub-but" href="http://dev.nidarachildren.com/" style="text-decoration: none; color: #333; padding: 10px 50px; border: 1px solid;"><b>BACK TO MY BASKET
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
         <a class="email" href="'. $this->config->weburl .'/contact-us/" target="_blank"> <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/mail-mint.png" alt="mail-mint.png" /></a>
          </li>
          </ul>
<span>Copyright &copy; Nidara-Children. All rights reserved.</span>
          <br /><span>You are receiving this email because you opted in at our website.
        </span>
        <br /><span><a href="https://faq.nidarachildren.com/wp-content/themes/nidara/Newsletter-contact/Nidara_Newslatter.vcf">Add us to your address book</a> | <a href="https://nidarachildren.us17.list-manage.com/unsubscribe?u=e2c0982dd8b7d1a16f74d886d&id=fae67dd82a&e=*%7CUNIQID%7C*">Unsubscribe from this list</a></span>
        </div>
        </div>
        
        
</td></tr></table>
</body>
</html>

							';
						$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
						if(!$mail->send()) {
							$flag = 0;
		
						} 
						else{
							$flag = 1;
						}
						
			}		
			}
	
		}
			if ($flag == 1)
			{
				return $this->response->setJsonContent ( [ 
							'status' => true,
							'message' => 'Message has been sent' 
						] );
			}else 
			{
								return $this->response->setJsonContent ( [ 
								'status' => false,
								'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo 
							] );
			}
	}

	
	
	public function cartabondtow(){
		$users = Users::find();
		$userarray = array();
		$days = strtotime(date("Y-m-d"));
		foreach($users as $value){
			$ncproduct2 = $this->modelsManager->createBuilder ()->columns ( array (
					'NCProductPricing.id as ids',
					'NCOrderList.created_at as created_at',
					'NCProductPricing.product_type as product_type',
					'NCProduct.product_name as product_name',
					'NCProduct.product_img as product_img',
					'NCProductPricing.product_price as product_price',
					))->from("NCOrderList")
					->leftjoin('NCOrderProductList','NCOrderProductList.order_id = NCOrderList.id')
					->leftjoin('NCOrderStatus','NCOrderStatus.order_id = NCOrderList.id')
					->leftjoin('NCProductPricing','NCOrderProductList.product_id = NCProductPricing.id')
					->leftjoin('NCProduct','NCProductPricing.product_id = NCProduct.id')
					->inwhere("NCOrderList.user_id",array($value->id))
					->inwhere("NCOrderStatus.status",array('Order New','Aborted','Failure'))
					->getQuery ()->execute ();
			$flag = 0;
			foreach($ncproduct2 as $main){
			$daysnow = strtotime(date("Y-m-d")); 
			$dayscreate = strtotime($main->created_at);
			$today['days'] = round(($daysnow - $dayscreate)/(60 * 60 * 24))+1;
			if($today['days']  == 23){
				$ncproduct = $this->modelsManager->createBuilder ()->columns ( array (
					'NCProductPricing.id as ids',
					'NCProductPricing.product_type as product_type',
					'NCProduct.product_name as product_name',
					'NCProduct.product_img as product_img',
					'NCProductPricing.product_price as product_price',
					))->from("NCOrderList")
					->leftjoin('NCOrderProductList','NCOrderProductList.order_id = NCOrderList.id')
					->leftjoin('NCOrderStatus','NCOrderStatus.order_id = NCOrderList.id')
					->leftjoin('NCProductPricing','NCOrderProductList.product_id = NCProductPricing.id')
					->leftjoin('NCProduct','NCProductPricing.product_id = NCProduct.id')
					->inwhere("NCOrderList.user_id",array($value->id))
					->inwhere("NCOrderStatus.status",array('Order New','Aborted','Failure'))
					->getQuery ()->execute ();
					$emailvalue = '';
					foreach($ncproduct as $value2){
						$emailvalue .= '<div class="product-details"><div class="product-img" style="width:20%;float:left;padding-right:20px;">';
						$emailvalue .= '<img style="width:100%" src="';
						$emailvalue .= $value2->product_img .'">';
						$emailvalue .= '</div><div class="product-cont"><h4>';
						$emailvalue .= $value2->product_name;
						$emailvalue .= '</h4><p>Rs. ';
						$emailvalue .= number_format($value2->product_price,2);
						$emailvalue .= '/ month for 12 months</p><p> <span style="float:right;padding-right:20px">Total:  Rs. ';
						$emailvalue .= number_format($value2->product_price,2);
						$emailvalue .= '</span></p></div>';
					}
					$emailvalue .= '';
					$mail = new PHPMailer;

						//$mail->SMTPDebug = 3;                               // Enable verbose debug output

						$mail->isSMTP();                                      // Set mailer to use SMTP
						$mail->Host = 'smtp-relay.sendinblue.com';  // Specify main and backup SMTP servers
						$mail->SMTPAuth = true;                               // Enable SMTP authentication
						$mail->Username = 'baskar@haselfre.com';                 // SMTP username
						$mail->Password = 'k6zcNyXJw27MCT0j';                           // SMTP password
						$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
						$mail->Port = 587;                                    // TCP port to connect to

						$mail->setFrom('customersupport@haselfre.com', 'Nidara-Children');
						$mail->addAddress($value->email, '');     // Add a recipient
																				// Name is optional
						$mail->addReplyTo('customersupport@haselfre.com', 'Information');

						//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
						//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
						$mail->isHTML(true);                                  // Set email format to HTML

						$mail->Subject = 'Last chance: 10% off child dvelopment system ends tonight!';
						
						$mail->Body    = '
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
           <h3 style="font-weight: 500;">LAST CHANCE: 10% OFF CHILD DVELOPMENT SYSTEM ENDS TONIGHT!</h3>
         </div>
         <div class="sub-mail-cont" style="width: 100%;">
           <span>Hi <span class="first-name" style="text-transform: capitalize;">' . $value -> first_name . '' . $value -> last_name . '</span>, </span>
           <br /><p style="line-height: 18px;">10 % Off on your Nidara-Children System ends tonight!</p>
           <p style="color: #8bbdcb; line-height: 18px;">Enter CHECK OUT CODE: SOLID10</p>
           '. $emailvalue .'
          </div>
          <div class="sub-mail-but" style="width: 100%; text-align: center; padding-top: 30px; float: left;">
         <h4>Rs. ' . number_format($value2->product_price,2) . ' / month for 12 months</h4>
         <p style="line-height: 18px;">
         <a class="sub-but" href="'. $this->config->weburl .'/" style="text-decoration: none; color: #333; padding: 10px 50px; border: 1px solid;"><b>GET STARTED  NOW</b>
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
          <a class="email" href="'. $this->config->weburl .'/contact-us/" target="_blank"> <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/mail-mint.png" alt="mail-mint.png" /></a>
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
						if(!$mail->send()) {
							$flag = 0;
		
						} 
						else{
							$flag = 1;
						}
						
			}
		}
			
		}
		if ($flag == 1)
			{
				return $this->response->setJsonContent ( [ 
							'status' => true,
							'message' => 'Message has been sent' 
						] );
			}else 
			{
								return $this->response->setJsonContent ( [ 
								'status' => false,
								'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo 
							] );
			}
	}


	public function birthday(){
		$users = $this->modelsManager->createBuilder ()->columns ( array (
			'NidaraKidProfile.date_of_birth as date_of_birth',
			'Users.first_name as first_name',
			'Users.last_name as last_name',
			'Users.email as email',
		))->from('KidParentsMap')
		->leftjoin('Users','KidParentsMap.users_id = Users.id')
		->leftjoin('NidaraKidProfile','KidParentsMap.nidara_kid_profile_id = NidaraKidProfile.id')
		->getQuery ()->execute ();
		$flag = 0;
		foreach($users as $value){
			
			$daysnow = strtotime(date("m-d"));
			$dayscreate = strtotime(date("m-d"),$value->date_of_birth);
			if($daysnow === $dayscreate){
					$mail = new PHPMailer;

						//$mail->SMTPDebug = 3;                               // Enable verbose debug output

						$mail->isSMTP();                                      // Set mailer to use SMTP
						$mail->Host = 'smtp-relay.sendinblue.com';  // Specify main and backup SMTP servers
						$mail->SMTPAuth = true;                               // Enable SMTP authentication
						$mail->Username = 'baskar@haselfre.com';                 // SMTP username
						$mail->Password = 'k6zcNyXJw27MCT0j';                           // SMTP password
						$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
						$mail->Port = 587;                                    // TCP port to connect to

						$mail->setFrom('customersupport@haselfre.com', 'Nidara-Children');
						$mail->addAddress($value->email, '');     // Add a recipient
																				// Name is optional
						$mail->addReplyTo('customersupport@haselfre.com', 'Information');

						//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
						//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
						$mail->isHTML(true);                                  // Set email format to HTML

						$mail->Subject = 'Happy birthday to your little angel';
						
						$mail->Body    = '
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
              <h3 style="font-weight: 500;">HAPPY BIRTHDAY TO YOUR LITTLE ANGEL</h3>
           </div>
           <div class="sub-mail-cont" style="width: 100%;">
              <span>Hi <span class="first-name" style="text-transform: capitalize;">' . $value->first_name . '' . $value->last_name . '</span> </span>
              
              <p style="line-height: 18px;">On your little child&rsquo;s special day, we are here to help you celebrate! Login to your child&rsquo;s account and have your little one enjoy special birthday present!
              </p>
           </div>
           <div class="sub-mail-but" style="width: 100%; text-align: center; padding-top: 30px; float: left;">
              <p style="line-height: 18px;">
              <a class="sub-but" href="'. $this->config->weburl .'/signin" style="text-decoration: none; color: #333; padding: 10px 50px; border: 1px solid;"><b>GET GIFT</b>
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
              <a class="email" href="'. $this->config->weburl .'/contact-us/" target="_blank"> <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/mail-mint.png" alt="mail-mint.png" /></a>
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
						if(!$mail->send()) {
							$flag = 0;
		
						} 
						else{
							$flag = 1;
						}
			}
			

			
		}
					if ($flag == 1)
			{
				return $this->response->setJsonContent ( [ 
							'status' => true,
							'message' => 'Message has been sent' 
						] );
			}else 
			{
								return $this->response->setJsonContent ( [ 
								'status' => false,
								'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo 
							] );
			}
	}
	public function cartsucssthank(){
		$users = Users::();
		$userarray = array();
		$days = strtotime(date("Y-m-d"));
		foreach($users as $value){
			$ncproduct2 = $this->modelsManager->createBuilder ()->columns ( array (
					'NCProductPricing.id as ids',
					'MAX(NCOrderList.created_at as created_at)',
					'NCProductPricing.product_type as product_type',
					'NCProduct.product_name as product_name',
					'NCProduct.product_img as product_img',
					'COUNT(NCOrderList.order_id) as order_ids',
					'NCProductPricing.product_price as product_price',
					))->from("NCOrderList")
					->leftjoin('NCOrderProductList','NCOrderProductList.order_id = NCOrderList.id')
					->leftjoin('NCOrderStatus','NCOrderStatus.order_id = NCOrderList.id')
					->leftjoin('NCProductPricing','NCOrderProductList.product_id = NCProductPricing.id')
					->leftjoin('NCProduct','NCProductPricing.product_id = NCProduct.id')
					->inwhere("NCOrderList.user_id",array($value->id))
					->inwhere("NCOrderStatus.status",array('Success'))
					->getQuery ()->execute ();
			$flag = 0;
			foreach($ncproduct2 as $main){
			$daysnow = strtotime(date("Y-m-d")); 
			$dayscreate = strtotime($main->created_at);
			$today['days'] = round(($daysnow - $dayscreate)/(60 * 60 * 24)) + 1;
			if($today['days'] === 2 && $main->order_ids > 1){
				$ncproduct = $this->modelsManager->createBuilder ()->columns ( array (
					'NCProductPricing.id as ids',
					'NCProductPricing.product_type as product_type',
					'NCProduct.product_name as product_name',
					'NCProduct.product_img as product_img',
					'NCProductPricing.product_price as product_price',
					'SUM (NCProductPricing.product_price) as subtotal',
					'COUNT (NCOrderProductList.product_id) as item',
					'COUNT (NCOrderProductList.product_id) as qty',
					))->from("NCOrderList")
					->leftjoin('NCOrderProductList','NCOrderProductList.order_id = NCOrderList.id')
					->leftjoin('NCOrderStatus','NCOrderStatus.order_id = NCOrderList.id')
					->leftjoin('NCProductPricing','NCOrderProductList.product_id = NCProductPricing.id')
					->leftjoin('NCProduct','NCProductPricing.product_id = NCProduct.id')
					->inwhere("NCOrderList.user_id",array($value->id))
					->inwhere("NCOrderStatus.status",array('Success'))
					->groupBy("NCOrderProductList.product_id")
					->getQuery ()->execute ();
					foreach($ncproduct as $value2){
					$mail = new PHPMailer;

						//$mail->SMTPDebug = 3;                               // Enable verbose debug output

						$mail->isSMTP();                                      // Set mailer to use SMTP
						$mail->Host = 'smtp-relay.sendinblue.com';  // Specify main and backup SMTP servers
						$mail->SMTPAuth = true;                               // Enable SMTP authentication
						$mail->Username = 'baskar@haselfre.com';                 // SMTP username
						$mail->Password = 'k6zcNyXJw27MCT0j';                           // SMTP password
						$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
						$mail->Port = 587;                                    // TCP port to connect to

						$mail->setFrom('customersupport@haselfre.com', 'Nidara-Children');
						$mail->addAddress($value->email, '');     // Add a recipient
																				// Name is optional
						$mail->addReplyTo('customersupport@haselfre.com', 'Information');

						//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
						//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
						$mail->isHTML(true);                                  // Set email format to HTML

						$mail->Subject = 'Thank you for raising your child with Nidara-Children…';
						
						$mail->Body    = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
           <a class="sub-but" href="'. $this->config->weburl .'/signin" style="text-decoration: none; color: #333; padding: 10px 50px; border: 1px solid;"><b>SIGNIN</b>
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
             <a class="email" href="'. $this->config->weburl .'/contact-us/" target="_blank"> <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/mail-mint.png" alt="mail-mint.png" /></a>
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
						if(!$mail->send()) {
							$flag = 0;
		
						} 
						else{
							$flag = 1;
						}						
					}
				}
			}
		}
		if ($flag == 1)
			{
				return $this->response->setJsonContent ( [ 
							'status' => true,
							'message' => 'Message has been sent' 
						] );
			}else 
			{
								return $this->response->setJsonContent ( [ 
								'status' => false,
								'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo 
							] );
			}
	}
	
	
	
	
	
	
	
	public function referralemail(){
		$users = Users::find();
		$userarray = array();
		$days = strtotime(date("Y-m-d"));
		$flag = 0;
		foreach($users as $value){
			$ncproduct2 = $this->modelsManager->createBuilder ()->columns ( array (
					'NCProductPricing.id as ids',
					'NCOrderList.created_at as created_at',
					'NCProductPricing.product_type as product_type',
					'NCProduct.product_name as product_name',
					'NCProduct.product_img as product_img',
					'NCProductPricing.product_price as product_price',
					))->from("NCOrderList")
					->leftjoin('NCOrderProductList','NCOrderProductList.order_id = NCOrderList.id')
					->leftjoin('NCOrderStatus','NCOrderStatus.order_id = NCOrderList.id')
					->leftjoin('NCProductPricing','NCOrderProductList.product_id = NCProductPricing.id')
					->leftjoin('NCProduct','NCProductPricing.product_id = NCProduct.id')
					->inwhere("NCOrderList.user_id",array($value->id))
					->inwhere("NCOrderStatus.status",array('Success'))
					->getQuery ()->execute ();
			foreach($ncproduct2 as $main){
			$daysnow = strtotime(date("Y-m-d")); 
			$dayscreate = strtotime($main->created_at);
			$today['days'] = round(($daysnow - $dayscreate)/(60 * 60 * 24)) + 1;
			if($today['days'] === 30){
					$mail = new PHPMailer;

						//$mail->SMTPDebug = 3;                               // Enable verbose debug output

						$mail->isSMTP();                                      // Set mailer to use SMTP
						$mail->Host = 'smtp-relay.sendinblue.com';  // Specify main and backup SMTP servers
						$mail->SMTPAuth = true;                               // Enable SMTP authentication
						$mail->Username = 'baskar@haselfre.com';                 // SMTP username
						$mail->Password = 'k6zcNyXJw27MCT0j';                           // SMTP password
						$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
						$mail->Port = 587;                                    // TCP port to connect to

						$mail->setFrom('customersupport@haselfre.com', 'Nidara-Children');
						$mail->addAddress($value->email, '');     // Add a recipient
																				// Name is optional
						$mail->addReplyTo('customersupport@haselfre.com', 'Information');

						//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
						//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
						$mail->isHTML(true);                                  // Set email format to HTML

						$mail->Subject = 'Here’s a sweet treat…';
						
						$mail->Body    = '
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
            <h3 style="font-weight: 500;">HERE&rsquo;S A SWEET TREAT&hellip;</h3>
          </div>
          <div class="sub-mail-cont" style="width: 100%;">
            <span>Hi <span class="first-name" style="text-transform: capitalize;">' . $value->first_name . '' .  $value->last_name  . '</span>, </span>
            <br /><p style="line-height: 18px;">Refer another mom or dad and get additional NC features to help raise your child!
          </p>
          
           </div>
           <div class="sub-mail-but" style="width: 100%; text-align: center; padding-top: 30px; float: left;">
          <p style="line-height: 18px;">
          <a class="sub-but" href="#" style="text-decoration: none; color: #333; padding: 10px 50px; border: 1px solid;"><b>REFER</b></a>
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
          <a class="email" href="'. $this->config->weburl .'/contact-us/" target="_blank"> <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/mail-mint.png" alt="mail-mint.png" /></a>
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
						if(!$mail->send()) {
							$flag = 0;
		
						} 
						else{
							$flag = 1;
						}
				}
			}
			
		}
		if ($flag == 1)
			{
				return $this->response->setJsonContent ( [ 
							'status' => true,
							'message' => 'Message has been sent' 
						] );
			}else 
			{
								return $this->response->setJsonContent ( [ 
								'status' => false,
								'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo 
							] );
			}
	}
	
	public function invoceemailsend(){
		$input_data = $this->request->getJsonRawBody ();
		
		$userinfo = $this->modelsManager->createBuilder ()->columns ( array (
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
		))->from('NCOrderList')
		->leftjoin('NCOrderAmount','NCOrderAmount.order_id = NCOrderList.order_id')
		->leftjoin('NCOrderStatus','NCOrderStatus.order_id = NCOrderList.id')
		->leftjoin('Users','NCOrderAmount.user_id = Users.id')
		->leftjoin('UsersAddress','UsersAddress.user_id = Users.id')
		->inwhere('NCOrderAmount.order_id',array($input_data->order_id))
		->inwhere("NCOrderStatus.status",array('Success'))
		->getQuery ()->execute ();
		foreach($userinfo as $user){
			$ncproduct = $this->modelsManager->createBuilder ()->columns ( array (
				'NCProductPricing.id as ids',
				'NCProductPricing.product_type as product_type',
				'NCProduct.product_name as product_name',
				'NCProduct.product_img as product_img',
				'NCProductPricing.product_price as product_price',
			))->from("NCOrderList")
			->leftjoin('NCOrderProductList','NCOrderProductList.order_id = NCOrderList.id')
			->leftjoin('NCOrderStatus','NCOrderStatus.order_id = NCOrderList.id')
			->leftjoin('NCProductPricing','NCOrderProductList.product_id = NCProductPricing.id')
			->leftjoin('NCProduct','NCProductPricing.product_id = NCProduct.id')
			->inwhere("NCOrderList.order_id",array($user->order_ids))
			->getQuery ()->execute ();
			$emailvalue ='';
			foreach($ncproduct as $value){
				$emailvalue .='<tr>';
				$emailvalue .='<td>';
				$emailvalue .= $value->product_name;
				$emailvalue .='</td>';
				$emailvalue .='<td>';
				$emailvalue .='</td>';
				$emailvalue .='<td>';
				$emailvalue .='</td>';
				$emailvalue .='<td>';
				$emailvalue .= $value->product_price;
				$emailvalue .='</td>';
				$emailvalue .='</tr>';
			}
			$emailvalue .= '';
			$date = date('Y-m-d');
			$mail = new PHPMailer;

			//$mail->SMTPDebug = 3;                               // Enable verbose debug output

			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = 'smtp-relay.sendinblue.com';  // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = 'baskar@haselfre.com';                 // SMTP username
			$mail->Password = 'k6zcNyXJw27MCT0j';                           // SMTP password
			$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 587;                                    // TCP port to connect to

			$mail->setFrom('invoice@haselfre.com', 'Nidara-Children');
			$mail->addAddress($user->email,'');     // Add a recipient
																				// Name is optional
			$mail->addReplyTo('invoice@haselfre.com', 'Information');

						//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
						//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
			$mail->isHTML(true);                                  // Set email format to HTML

			$mail->Subject = 'Invoice';
						
			$mail->Body    = '
			
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
				<p>Customer`s Name'. $user-> first_name . ' ' . $user-> last_name .  '</p>
				<p>Customer`s Address</p>
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
				<td>' . $user->discoun_amount .'</td>
		</tr>
		<tr>
				<td>Taxable Value</td>
				<td></td>
				<td></td>
				<td>' . $user->total_amount .'</td>
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
					<p>One Two Six Only</p>
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
			if(!$mail->send()) {
				return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo 
				] );													
			} 
			else{
				return $this->response->setJsonContent ( [ 
					'status' => true,
					'message' => 'Message has been sent',
					'data' => $user->email
				] );
			}
		}
	} 
	
	
}



