<!DOCTYPE html>
<html>
<body id="bodytag" name='bodytag'>
	<span>
	<?php
		$user="";
		$url;

		$user = $_GET["username"];
		echo "<h3>welcome ".$user."</h3>";

		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$user = $_GET['username'];
			$url = "d3schedule.php?user=kaarthiksram55";
			echo $url;
    		header('Location: '.$url);
    		//exit();
		}

		// if(!isset($_GET['username'])){
		// 	header("Location: d3login.php");
		// 	exit();
		// }
	?>
	</span>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<input type="submit" name='appointment' value="schedule appointment">
	<p>Select year:</p>
	<select id = 'yearselector' onchange="onYearChange(this.value)" name='yearselector'>
		<option value = 2018>2018</option>
		<option value = 2019>2019</option>
		<option value = 2020>2020</option>
	</select>
	<input type="text" name="hidden" id="hidden">
    </form>
	<br>
	<br>           	
	<?php
   		class TableRows extends RecursiveIteratorIterator { 
		    function __construct($it) { 
		        parent::__construct($it, self::LEAVES_ONLY); 
		    }

		    function current() {
		        return "<td style='width:150px;border:1px solid black;'>" . parent::current(). "</td>";
		    }

		    function beginChildren() { 
		        echo "<tr>"; 
		    } 

		    function endChildren() { 
		        echo "</tr>" . "\n";
		    } 
		} 
		function loadAppointments(){
	       	echo "<table style='border: solid 1px black;'></table>";
			echo "<tr><th>title</th><th>description</th><th>Start Time</th><th>End Time</th></tr>";
			$date = $_POST['hidden'];
			//echo $_POST['hidden'];
			try {
			    $conn = new PDO("mysql:host=localhost;dbname=delta3", "root", "");
			    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			    $stmt = $conn->prepare("SELECT title,description,start_time,end_time FROM kaarthiksram55_appointments where date='".$_POST['hidden']."';");
			    $stmt->execute();
			    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
			    foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) { 
			        echo $v;
			    }
			}
			catch(PDOException $e) {
			    echo "Error: " . $e->getMessage();
			}
			$conn = null;
			echo "</table>";
		}
	?>
	<script type="text/javascript" name='scripttag'>
		var yeartemp=-1;
		var flag = 0;
		var dayofyear = 1;
		var bodytag = document.getElementById('bodytag');

		function onYearChange(year){
			var bodychilds = bodytag.childNodes,loopvar=0,tagname;
			for(loopvar=bodychilds.length-1;loopvar>=0;loopvar--){
				tagname = bodytag.childNodes[loopvar].tagName;
				if(tagname!='SELECT'&&tagname!='P'&&tagname!='BR'&&tagname!='SCRIPT'&&tagname!='FORM'&&tagname!='SPAN')
					bodytag.removeChild(bodytag.childNodes[loopvar]);
			}
			loadAppointmentsByYear(year);
			yeartemp = this.value;
		}

		function loadAppointmentByDate(date,month,year){
			var div = document.createElement('div');
			var divid = div.id = year+"/"+month+"/"+date;
			document.getElementById('hidden').value=divid;
			bodytag.appendChild(div);
           	div.appendChild(document.createTextNode(date+"/"+month+"/"+year));
           	div.appendChild(document.createElement('br'));
           	div.appendChild(document.createElement('hr'));
           	<?php echo loadAppointments(); ?>
		}

		function loadMonthHeader(month){
			var htag = document.createElement('h2');
			if(month==1)htag.innerHTML = 'January';
			else if(month==2) htag.innerHTML = 'February';
			else if(month==3) htag.innerHTML = 'March';
			else if(month==4) htag.innerHTML = 'April';
			else if(month==5) htag.innerHTML = 'May';
			else if(month==6) htag.innerHTML = 'June';
			else if(month==7) htag.innerHTML = 'July';
			else if(month==8) htag.innerHTML = 'August';
			else if(month==9) htag.innerHTML = 'Septebmer';
			else if(month==10) htag.innerHTML = 'October';
			else if(month==11) htag.innerHTML = 'November';
			else htag.innerHTML = 'December';
			bodytag.appendChild(htag);
		}

		function loadAppointmentsByYear(year){
			var loopdate=1,loopmonth=1,noofdays=31,noofdaysflag=1;
			for(loopmonth=1;loopmonth<=12;loopmonth++){
				loadMonthHeader(loopmonth);
				for(loopdate=1;loopdate<=noofdays;loopdate++){
					if(noofdaysflag==1){
						if(loopmonth==4||loopmonth==6||loopmonth==9||loopmonth==11){
							noofdays=30;
						}
						else if(loopmonth==2){
							if(year%100!=0&&year%4==0)
								noofdays=29;
							else if(year%400==0)
								noofdays = 29;
							else
								noofdays=28;
						}
						else{
							noofdays=31;
						}
						noofdaysflag=0
					}
					loadAppointmentByDate(loopdate,loopmonth,year);
				}
				noofdaysflag=1;
			}
		}

	</script>
</body>
</html>