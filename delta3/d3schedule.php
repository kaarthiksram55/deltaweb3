<!DOCTYPE html>
<html>
<body>
	<form method="POST" id = 'main' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<h5 align="center"> Schedule an appointment </h5>
		<br>
		Title: <input type="text" name="title">*<p style="color:Tomato;" id="titleErr"></p><br><br>
		Description: <textarea name='description' rows='6' cols="40"></textarea><br><br>
		Date: <select name="date" id='date' ><option value=''>Select</option></select> 
			  <select name="month"id='month' ><option value=''>Select</option></select> 
			  <select name="year" id='year' ><option value=''>Select</option></select>* <p id='DateErr' style="color:Tomato;"></p><br><br>
		StartTime: <select name="starthours" id="starthours"><option value=''>Select</option></select> <select name="startminutes" id="startminutes"><option value=''>Select</option></select> <p id='starttimeErr' style="color:Tomato;"></p><br><br>
		EndTime: <select name="endhours" id="endhours"><option value=''>Select</option></select> <select name="endminutes" id="endminutes"><option value=''>Select</option></select> <p id='endtimeErr' style="color:Tomato;"><br><br>
		<input type="submit" name="submit" value="Schedule Appointment">
	</form>
	<script type="text/javascript">
		function loadDates(){
			var loopvar = 1;
			for(;loopvar<=31;loopvar++){
				var date = document.createElement('option');
				date.value = loopvar;
				date.text = loopvar;
				document.getElementById('date').add(date);
			}
		}
		function loadMonths(){
			var loopvar = 1;
			for(;loopvar<=12;loopvar++){
				var month = document.createElement('option');
				month.value = loopvar;
			if(loopvar==1)month.text = 'January';
			else if(loopvar==2) month.text = 'February';
			else if(loopvar==3) month.text = 'March';
			else if(loopvar==4) month.text = 'April';
			else if(loopvar==5) month.text = 'May';
			else if(loopvar==6) month.text = 'June';
			else if(loopvar==7) month.text = 'July';
			else if(loopvar==8) month.text = 'August';
			else if(loopvar==9) month.text = 'Septebmer';
			else if(loopvar==10) month.text = 'October';
			else if(loopvar==11) month.text = 'November';
			else month.text = 'December';
				document.getElementById('month').add(month);
			}
		}
		function loadYears(){
			var loopvar = 2018;
			for(;loopvar<=2020;loopvar++){
				var year = document.createElement('option');
				year.value = loopvar;
				year.text = loopvar;
				document.getElementById('year').add(year);
			}
		}
		function loadHours(p){
			var loopvar = 0;
			for(;loopvar<=23;loopvar++){
				var hour = document.createElement('option');
				hour.value = loopvar;
				hour.text = loopvar;
				if(p==1)
					document.getElementById('starthours').add(hour);
				else
					document.getElementById('endhours').add(hour);
			}
		}
		function loadMinutes(p){
			var loopvar = 0;
			for(;loopvar<=60;loopvar++){
				var minute = document.createElement('option');
				minute.value = loopvar;
				minute.text = loopvar;
				if(p==1)
					document.getElementById('endminutes').add(minute);
				else
					document.getElementById('startminutes').add(minute);
			}
		}
		loadDates();
		loadMonths();
		loadYears();
		loadHours(1);
		loadMinutes(1);
		loadHours(2);
		loadMinutes(2);
	</script>
	<?php 
		$user = $_GET['user'];
		echo $user;
		$flag=0;

		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$title=$description=$date=$starttime=$endtime="";
			$titlef=$datef=$timef=$starttimef=$endtimef=0;
			$tablename=$user."_appointments";
			echo $tablename;

			if(Empty($_POST['title'])){
				echo "<script>document.getElementById('titleErr').innerHTML='field Empty';</script>";
			}
			else{
				$title = test_input($_POST['title']);
				$titlef = 1;
			}

			$description = $_POST['description'];

			if($_POST['date']==''||$_POST['month']==''||$_POST['year']==''){
				echo "<script>document.getElementById('DateErr').innerHTML = 'Set a proper date';</script>";
			}
			else{
				$date = test_input($_POST['year']."-".$_POST['month']."-".$_POST['date']);
				$datef=1;
			}

			if($_POST['starthours']==""||$_POST['startminutes']==""){
				echo "<script>document.getElementById('starttimeErr').innerHTML = 'Set a proper time';</script>";
			}
			else{
				$starttime = test_input($_POST['starthours'].":".$_POST['startminutes'].":00");
				$starttimef = 1;
			}

			if($_POST['endhours']==""||$_POST['endminutes']==""){
				echo "<script>document.getElementById('endtimeErr').innerHTML = 'Set a proper time';</script>";
			}
			else{
				$endtime = test_input($_POST['endhours'].":".$_POST['endminutes'].":00");
				$endtimef = 1;
			}

			if($titlef==1&&$datef==1&&$starttimef==1&&$endtimef==1){
				try{
					$conn = new PDO("mysql:host=localhost;dbname=delta3", "root", "");
		   			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$insertQuery="insert into $tablename(date,title,description,start_time,end_time) values('$date','$title','$description','$starttime','$endtime');";
					$conn->exec($insertQuery);
				}
				catch(PDOException $e){
	    			echo "Connection failed: " . $e->getMessage();
	    		}
			}

			$flag=1;
		}

		// if(!isset($_GET['username'])&&$flag==0){
		// 	header("Location: d3login.php");
		// 	exit();
		// }

		function test_input($data) {
		  	$data = trim($data);
		  	$data = stripslashes($data);
		  	$data = htmlspecialchars($data);
			return $data;
		}
	?>
</body>
</html>