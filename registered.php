<?php
	require ('PHPMailer/src/Exception.php');
	require ('PHPMailer/src/PHPMailer.php');
	require ('PHPMailer/src/SMTP.php');
	
	function sendMail($email,$eAddress)
	{
		$mail = new PHPMailer\PHPMailer\PHPMailer();
		$mail->isSMTP();
		$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'k152230@nu.edu.pk';                 // SMTP username
		$mail->Password = 't/nigahiga2';                           // SMTP password
		$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 587;                                    // TCP port to connect to
		$mail->setFrom('k152230@nu.edu.pk', 'Time Table Notifier');
		$mail->addAddress($eAddress, 'receiver');     // Add a recipient
		//$mail->addAddress('ellen@example.com');               // Name is optional
		$mail->addReplyTo('k152230@nu.edu.pk', 'Information');
		//$mail->addCC('cc@example.com');
		//$mail->addBCC('bcc@example.com');
		//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
		$mail->isHTML(true);
		$mail->Subject = 'TimeTable Notifier';
		$mail->Body    = $email;
					
		$mail->smtpConnect( array( "ssl" => array(
				"verify_peer" => false,
				"verify_peer_name" => false,
				"allow_self_signed" => true
				)
			)
		);	
		if(!$mail->send()){
			echo "Not Sent: ";
			$mail->SMTPDebug = 2;
		} else {
			echo 'Please verify your account by clicking on the link sent to your email address.';
		}
	}
    if(isset($_POST["id"]) && !empty($_POST["id"]) && isset($_POST["email"]) && !empty($_POST["email"]))
    {
    	$con=mysqli_connect('localhost','id5977010_rashid','admin','id5977010_notifier');
    	//DATABASE CONNECTION			
    	if($con)
    	{
    		$sql="select * from course_names";
    		$courses=$con->query($sql);
    		if($courses->num_rows > 0)
    		{
    		    $id=$_POST["id"];
    			$email=$_POST["email"];
    			
    			$hash = md5( rand(0,1000) );
    			$con->query("INSERT INTO `students` (`ID`, `Email`, `hash`) VALUES ('$id', '$email', '$hash');");
    			while($row=$courses->fetch_assoc())
    			{
    				$name=$row['name'];
    				if($_POST["$name"] != "Sec")
    				{
    					$sec=$_POST["$name"];
    					$con->query("INSERT INTO `courses` (`student_id`, `course_name`, `section`) VALUES ('$id', '$name', '$sec')");
    				}
    			}
    			$msg = "
    				 
    				Thanks for signing up!<br>
    				Your account has been created, your daily time table will be emailed to you everyday.<br>
    				<br>
    				------------------------<br>
    				<b>Username:</b> $id<br>
    				<b>Email:</b> $email<br>
    				------------------------<br>
    				 <br>
    				Please click this link to activate your account:<br>
    				http://rashidabbasi17.000webhostapp.com/verify.php?email=$email&hash=$hash<br>
    				 
    				";
    			sendMail($msg,$email);
    		}
    	}
    }
    else
        echo "Registeration Failed, try again";
	
?>