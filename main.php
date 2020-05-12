<html>
	<head>
	    <meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
		<!-- Optional Bootstrap theme >
		<link rel="stylesheet" href="css/bootstrap-theme.min.css" -->
		<title> Time Table Notifier</title>
		
		<!-- script src="js/jquery-1.11.3.min.js"></script -->
        <script src="js/bootstrap.min.js"></script>
	</head>
    
	<body>
        <!--  -->
        <?php
			$con=mysqli_connect('localhost','id5977010_rashid','admin','id5977010_notifier');
			//DATABASE CONNECTION			
			if($con)
			{
				$sql="CREATE  TABLE IF NOT EXISTS `id5977010_notifier`.`students` (
				`ID` VARCHAR(7) NOT NULL,
				`Email` VARCHAR(45) NOT NULL,
				`hash` VARCHAR(32) NOT NULL DEFAULT '0',
				`status` INT(1) NOT NULL ,
				PRIMARY KEY (`ID`) )";
				$con->query($sql);
				$sql="CREATE  TABLE IF NOT EXISTS `id5977010_notifier`.`courses` (
				`student_id` VARCHAR(7) NOT NULL,
				`course_name` VARCHAR(45) NOT NULL,
				`section` VARCHAR(45) NOT NULL ,
				FOREIGN KEY (`student_id` )
				REFERENCES `TimeTable`.`students`(ID)
				ON DELETE CASCADE,
				CONSTRAINT PK_Person PRIMARY KEY (course_name,student_id) )";
				$con->query($sql);
				$sql="CREATE  TABLE IF NOT EXISTS `id5977010_notifier`.`course_names` (
				`name` VARCHAR(10) NOT NULL,
				`full_name` VARCHAR(45) NOT NULL ,
				PRIMARY KEY (`name`) )";
				$con->query($sql);				
			}
	
	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	
	$idErr = $idErr2 = $emailErr = "";
	$id = $id2 = $email = "";

	if (isset($_POST['button2'])) 
	{
		if($con)
		{
			if (empty($_POST["ID2"])) {
				$idErr2 = " is required<br>";
			} else {
				$id2 = test_input($_POST["ID2"]);
				if(strlen($id2)!=7 && strpos($id2,'k')== 0)
					$idErr2 =" *  Invalid ID format";
				else {
					$sql="select ID from students where ID='$id2';";
						$result=$con->query($sql);
					if($result->num_rows <= 0)
						$idErr2=" *  This user is not registered.";
				}
			}
			if($idErr2 == '')
			{
				$sql="delete from students where id='$id2'";
				$con->query($sql);
				echo "<script>alert('User have been removed.')</script>";
			}
		}
	}
	if (isset($_POST['button1'])) 
	{
		if($con)
		{
			if (empty($_POST["ID"])) {
				$idErr = " is required<br>";
			} else {
				$id = test_input($_POST["ID"]);
				if(strlen($id)!=7 && strpos($id,'k')== 0)
					$idErr =" *  Invalid ID format";
				else {
					$sql="select ID from students where ID='$id';";
					$result=$con->query($sql);
					if($result->num_rows > 0)
						$idErr=" *  This user ID is already registered.";
				}
			}
		    if (empty($_POST["email"])) {
				$emailErr = " is required<br>";
			} else {
				$email = test_input($_POST["email"]);
				if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$emailErr = " *  Wrong email format<br>";
				}
				$sql="select email from students where email='$email';";
				$result=$con->query($sql);
				if($result->num_rows > 0)
					$emailErr=" *  This email is already registered";
			}
			if($idErr == '' && $emailErr == '')
			{
				echo "<script>location.href = 'register.php?id=$id&email=$email';</script>";
			}
		}
	}
	?>
	<div class="container">
	<h1 style="color: #5e9ca0; text-align: center;"><img src="https://html-online.com/img/6-table-div-html.png" alt="html table div" width="150" />
	 TimeTable Notifier!</h1>
	</div>
	<br><br>
	<div class="container">
		<h2 style="color: #2e6c80;">Emails you time table for your classes everyday</h2>
		<p>Just enter your FAST NUCES ID number below and email and Wala! You are done</p>
		<h2 style="color: #2e6c80;"><br>Register Account:</h2>
		<form action="" method="post">
			<span class"input-group-addon">ID</span><span class="error" style="color:red"><?php echo $idErr;?></span>
			<input type="text" class="form-control" placeholder="FAST ID" name="ID">
			<span class"input-group-addon">Email</span><span class="error" style="color:red"><?php echo $emailErr;?></span>
			<input type="text" class="form-control" placeholder="Email Address" name="email">
			<br><span class="input-group-btn"><button name="button1" class ="btn btn-default"> Register </button></span>
		</form>
		
		<h2 style="color: #2e6c80;"><br>Remove Account:</h2>
		<form action="" method="post">
			<span class"input-group-addon">ID</span><span class="error" style="color:red"><?php echo $idErr2;?></span>
			<input type="text" class="form-control" placeholder="FAST ID" name="ID2">
			<br><span class="input-group-btn"><button name="button2" class ="btn btn-default"> Remove </button></span>
		</form>	
	</div>
	</body>
</html>