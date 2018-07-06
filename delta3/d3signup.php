<!DOCTYPE html>
<html>
<body>
<h1 align="center">SignUp page</h1>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	Name: <input type="text" name="name"><p id = 'nameErr' style="color:Tomato;"> </p>
	Email Id: <input type="text" name="email"><p id = 'emailErr' style="color:Tomato;"> </p>
	Username: <input type="text" name="username"><p id = 'usernameErr' style="color:Tomato;"> </p>
	Password: <input type="Password" name="password"><p id = 'passwordErr' style="color:Tomato;"> </p>
	Confirm Password: <input type="password" name="confirmpassword"><p id = 'confirmErr' style="color:Tomato;"> </p>
	Gender:<br>
	<input type="radio" name="gender" value="male" checked="true">male<br>
	<input type="radio" name="gender" value="female">female<br>
	<input type="radio" name="gender" value="other">other<br><br>
	<img src="/delta3/Capture.png"><br>
	Enter Captcha: <input type="text" name="captcha"><p id = 'captchaErr' style="color:Tomato;"></p>
	<input type="submit" name="submit" value="Sign Up">
</form>
<?php
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$name=$email=$username=$password=$gender="";
		$namef=$emailf=$usernamef=$passwordf=$confirmpasswordf=$captchaf=0;

		if(Empty($_POST["name"])){
			echo "<script>document.getElementById('nameErr').innerHTML = 'field empty';</script>";
		}
		else if (!preg_match("/^[a-zA-Z ]*$/",$_POST["name"])) {
		  	echo "<script>document.getElementById('nameErr').innerHTML = 'only letters and spaces';</script>";
		}
		else{
			$name = test_input($_POST["name"]);
			$namef=1;
		}

		if(Empty($_POST["email"])){
			echo "<script>document.getElementById('emailErr').innerHTML = 'field empty';</script>";
		}
		else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
		  	echo "<script>document.getElementById('emailErr').innerHTML = 'invalid email id';</script>";
		}
		else{
			$email = test_input($_POST["email"]);
			$emailf=1;
		}

		if(Empty($_POST["username"])){
			echo "<script>document.getElementById('usernameErr').innerHTML = 'field empty';</script>";
		}
		else{
			$username = test_input($_POST["username"]);
			$conn = new PDO("mysql:host=localhost;dbname=delta3", "root", "");
	    	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    	$stmt = $conn->query("SELECT username FROM users where username = '".$username."';"); 
	    	$stmt2 = $stmt->fetch();
	    	$user2 = $stmt2[0];
	    	//echo $user2;
	    	if($username==$user2){
				echo "<script>document.getElementById('usernameErr').innerHTML = 'username already taken';</script>";
	    	}
			else{
				$usernamef=1;
			}
			$conn = null;
		}

		if(Empty($_POST["password"])){
			echo "<script>document.getElementById('passwordErr').innerHTML = 'field empty';</script>";
		}
		else if (strlen($_POST["password"])<8) {
		  	echo "<script>document.getElementById('passwordErr').innerHTML = 'password minimum 8 characters';</script>";
		}
		else{
			$password = test_input($_POST["password"]);
			$passwordf=1;
		}

		if(Empty($_POST["confirmpassword"])){
			echo "<script>document.getElementById('confirmErr').innerHTML = 'field empty';</script>";
		}
		else if ($_POST["confirmpassword"]!=$password) {
		  	echo "<script>document.getElementById('confirmErr').innerHTML = 'passwords do not match';</script>";
		}
		else{
			$confirmpasswordf=1;
		}

		$gender = test_input($_POST["gender"]);

		if(Empty($_POST["captcha"])){
			echo "<script>document.getElementById('captchaErr').innerHTML = 'field empty';</script>";
		}
		else if ($_POST["captcha"]!="qGphJ") {
		  	echo "<script>document.getElementById('captchaErr').innerHTML = 'invalid captcha';</script>";
		}
		else{
			$captchaf=1;
		}

		if($namef==1&&$emailf==1&&$usernamef==1&&$passwordf==1&&$confirmpasswordf==1&&$captchaf==1){
			try {
				$conn = new PDO("mysql:host=localhost;dbname=delta3","root", "");
			    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			    $sql = "insert into users(name,email,gender,username,password) values('".$name."', '".$email."', '".$gender."', '".$username."', '".$password."');";
				$conn->exec($sql);
				$createtable = "create table ".$username."_Appointments(S_no int(10) primary key auto_increment, date date not null, title varchar(20) not null, description varchar(100), start_time time, end_time time);";
				$conn->exec($createtable);
				echo "<script>window.location.href = '/delta3/d3login.php';</script>";
			} 
			catch (Exception $e) {
			    echo "Connection failed: " . $e->getMessage();
			}
		}
	}
	function test_input($data) {
	  	$data = trim($data);
	  	$data = stripslashes($data);
	  	$data = htmlspecialchars($data);
		return $data;
	}
?>
</body>
</html>