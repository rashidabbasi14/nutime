<?php
	$con=mysqli_connect('localhost','id5977010_rashid','admin','id5977010_notifier');
	if($con)
	{
		if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash']))
		{
			// Verify data
			$email = $_GET['email']; 
			$hash = $_GET['hash'];
			
			$sql="SELECT email, hash, status FROM students WHERE email='".$email."' AND hash='".$hash."' AND status='0'";
			$courses=$con->query($sql);
			if($courses->num_rows > 0)
			{
				$sql="UPDATE students SET status='1' WHERE email='".$email."' AND hash='".$hash."' AND status='0'";
				$con->query($sql);
				echo '<script>alert("Your account has been activated, Thank you")</script>';
			}else
			{
				echo '<script>alert("The url is either invalid or you already have activated your account.")</script>';
			}
		}else
		{
			echo '<script>alert("Invalid approach, please use the link that has been send to your email.")</script>';
		}
	}
?>