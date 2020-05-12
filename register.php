<html>
	<head>
	    <meta charset="utf-8">
	  <meta http-equiv="X-UA-Compatible" content="IE=edge">
	  <meta name="viewport" content="width=device-width, initial-scale=1">
	  
	  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	  
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	  <script src="js/bootstrap.min.js"></script>
        
	  
	</head>
	<body>
		<div class="container">
			<h1 style="color: #5e9ca0; text-align: center;"><img src="https://html-online.com/img/6-table-div-html.png" alt="html table div" width="150" />
			 TimeTable Notifier!</h1>
			</div>
			<div class="container">
				<h2 style="text-align:center;color: #2e6c80;"><br>Select Courses:</h2>
			</div>
			<br><br>
<?php
	$con=mysqli_connect('localhost','id5977010_rashid','admin','id5977010_notifier');
	//DATABASE CONNECTION			
	if($con)
	{
		$sql="select * from course_names order by cate, full_name";
		$courses=$con->query($sql);
		if($courses->num_rows > 0)
		{
			while($row=$courses->fetch_assoc())
			{
					$name1=$row["name"];
					$name=str_replace("1"," ",$name1);
					$name=str_replace("2",".",$name);
					$full_name=$row["full_name"];
					$font='Raleway';
					echo "<div class='container' style='margin:auto;position:relative;border-bottom:10px ridge #1C6EA4;height:6%;width:50vmax;'>
							<div style='white-space: nowrap;overflow: hidden;width:70%;position:absolute;bottom:20%;'><label style='font:$font, sans-serif;color: #1C6EA4;border-style: none
							;text-align:left;text-transform:uppercase;font-size:3vmin;'><b>$name</b>   |    $full_name</label></div>
							<div class='dropdown' style='right:0;position:absolute;bottom:20%;'> 
								<button style='width:100%'' class='btn btn-primary dropdown-toggle' data-toggle='dropdown' class='btn btn-default' name='sub1' type='submit' id='sub1'>
								<l id='l$name1'></l>
								<span class='caret'></span></button>
								<ul class='dropdown-menu' id='$name1' name='$name1'>
									<li><a href='#' value='1'>Integer</a></li>
									<li><a href='#' value='2'>Text</a></li>
								</ul>
						  </div>
						</div>
						<br><br>
						<script>
							if(document.getElementById('l$name1').value == null)
								document.getElementById('l$name1').innerHTML='SEC';
							$('#$name1 li').on('click', function()
							{
								document.getElementById('l$name1').innerHTML=$(this).text();
								return $(this).text();
							});
						</script>";
			}
		}
	}
?>
		
		<div class="container" style="position:relative;width:50vmax;height:6%;margin:auto;">
			<div style="position:absolute;left:40%;">
				<button class="btn btn-default" onClick="RegisterB();">Register</button>&emsp;&emsp;
				<button class="btn btn-default" onClick="CancelB();">Cancel</button>
			</div>
		</div>
	</body>
	<div id="test"></div>
	<script>
		var jArray =<?php
		$sql="select name, full_name as section from course_names";
		$courses=$con->query($sql);
		$rows=array();
		if($courses->num_rows > 0)
		{
			while($row=$courses->fetch_assoc())
			{
				$rows[]=$row;							
			}
		}
		echo json_encode($rows);
		?>;
		Object.keys(jArray).forEach(v => jArray[v].section = "Sec")
		console.log(jArray);
		var test = new Array();
		for(var i=0;i<jArray.length;i++)
		{
			test[i] = jArray[i].name;	
			handleEvent(i);
		}
		function RegisterB()
		{
			var result = false;
			for (var i in jArray) {
				if (jArray[i].section != "Sec") {
					result = true;
					break;
				}
			}
			if(result)
			{
				var r=false;
				var string = '';
				for(var i=0;i<jArray.length;i++)
				{
					if(i==0)
						string = string+jArray[i].name+"="+jArray[i].section;
					else
						string = string+"&"+jArray[i].name+"="+jArray[i].section;
				}
				string = string + "<?php echo "&id=".$_GET['id']."&email=".$_GET['email'] ?>";
				var hr = new XMLHttpRequest();
				var url = "registered.php";
				var fn = "firstname=rashid";
				hr.open("POST",url,true);
				hr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
				hr.onreadystatechange=function(){
					if(hr.readyState == 4 && hr.status == 200){
						var return_data = hr.responseText;
						r=confirm(return_data);
						if(r==true)
							document.location.href = "main.php";
						else
							document.location.href = "main.php";
					}
				}
				hr.send(string);
				
			}
			else
				alert("No courses were selected.");
		}
		
		function CancelB()
		{
			document.location.href = "main.php";
		}
		
		$('a').click(function(e)
		{
			e.preventDefault();
		});
		
		function handleEvent(j)
		{
			$('#'+test[j]+' li').click(function(event)
			{
				jArray[j].section=event.result;
				console.log(jArray);
			});
		}
	</script>

</html>