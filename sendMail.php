<html>

	<body>
		<style>
								table 
								{
									margin:auto;font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;border-collapse: collapse;width: 50%;
								} 
								
								#table td, #table th {
									border: 1px solid #ddd;padding: 12px;text-align: center;
								}
								#table th#lol{
									background-color: #2E8B57;
									border-collapse: collapse;
								}
								#table tr:nth-child(even){background-color: #f2f2f2;} 
								#table tr:hover {background-color: #ddd;} 
								#table th {
									background-color: #4CAF50;
									color: white;
			}
		</style>
	</body>

<?php

require("Libraries/reader.php");
require ('PHPMailer/src/Exception.php');
require ('PHPMailer/src/PHPMailer.php');
require ('PHPMailer/src/SMTP.php');


define("Monday",6);
define("Tuesday",7);
define("Wednesday",8);
define("Thursday",9);
define("Friday",10);
define("Saturday",99);
define("Sunday",98);

date_default_timezone_set('Asia/Karachi');

function clean($string) 
{
	return preg_replace('/[^A-Za-z0-9\-():, .]/', '', $string); // Removes special chars.
}

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
		echo 'Message has been sent';
	}
}


    $i=constant(date('l', strtotime("+1 Day")));
	$day=date('l', strtotime("+1 Day"));
	echo $day;
	$con=mysqli_connect('localhost','id5977010_rashid','admin','id5977010_notifier');
		if($con)
		{
			$sql="select id, email from students where status='1'";
			$query1=$con->query($sql);
			if($query1)
			{
				$excel = new Spreadsheet_Excel_Reader();
				$excel->read('Book.xls');
				if($query1->num_rows > -1)
				{
					while($wrow=$query1->fetch_assoc())
					{
						$id=$wrow["id"];
						$email="<h2 style='text-align: center;'><span style='text-decoration: underline;'><span style='color: #333333;'><strong>Following is the time table for your classes on $day: $id</strong></span></span></h2>
						<br><br><table id ='myTable' style='margin:auto;font-family:Trebuchet MS, Arial, Helvetica, sans-serif;border-collapse: collapse;width: 100%;height:75%'>
						<tr><th colspan=3 style='color: white;background-color: #2E8B57;border: 1px solid #ddd;padding: 12px;text-align: center;'>$day</th></tr><tr><th style='color: white;background-color: #4CAF50;border: 1px solid #ddd;padding: 12px;text-align: center;'>Class</th><th style='color: white;background-color: #4CAF50;border: 1px solid #ddd;padding: 12px;text-align: center;'>Room</th><th style='color: white;background-color: #4CAF50;border: 1px solid #ddd;padding: 12px;text-align: center;'>Time</th></tr>";
						
						$cour=array();
						$sections=array();
						
						$sql='select course_name, section from courses where student_id = "'.$wrow["id"].'"';
						$courses=$con->query($sql);
						if($courses)
						{
							while($row1=$courses->fetch_assoc())
							{
								array_push($cour,$row1["course_name"]);
								array_push($sections,$row1["section"]);
							}
						}
						for($q=0;$q<sizeof($cour);$q++)
						{	
							$col=2;
							while($col<$excel->sheets[$i]['numCols']) 
							{
								$row=4;
								while($row<$excel->sheets[$i]['numRows']) 
								{
									for($q=0;$q<sizeof($cour);$q++)
									{
										if(strpos($cour[$q],"Lab"))
											$new = $cour[$q]." ".$sections[$q] ;
										else
											$new = $cour[$q]."-".$sections[$q] ;
										$cell = clean(isset($excel->sheets[$i]['cells'][$row][$col]) ? clean($excel->sheets[$i]['cells'][$row][$col]): '');	
										if (strpos($cell, $new) !== false)
										{
											$class=clean($excel->sheets[$i]['cells'][$row][1]);
											$time=clean($excel->sheets[$i]['cells'][3][$col]);
											$email = $email."
											<tr>
											<td style='border: 1px solid #ddd;padding: 12px;text-align: center;'>$cell</td>
											<td style='border: 1px solid #ddd;padding: 12px;text-align: center;'>$class</td>
											<td style='border: 1px solid #ddd;padding: 12px;text-align: center;'>$time</td>
											</tr> ";
										}
									}
									$row++;
								}
								$col++;
							}
						}
						$email=$email."</table>";
						echo $email;
						sendMail($email,$wrow["email"]);
					}
				}	
			}
		}
?>

</html>