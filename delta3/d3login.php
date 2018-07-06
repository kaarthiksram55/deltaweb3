<!DOCTYPE html>
<html>
<body>
	<form method="post" id='main' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		username: <input type="text" name="username" id="username"><br><br>
		password: <input type="password" name="password"><br>
		<input type="submit" name="login">
	</form>
	<a href="/delta3/d3signup.php">new user? Sign Up!</a>
	<?php
		$user = $pass = "";
		$nousererror="";
		$startflag = 0;
		$userflag = 2;
		$url;

		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$user = test_input($_POST["username"]);
			$pass = test_input($_POST["password"]);
			check();
		}

		function check(){
			try {
				static $username="root";
				static $password="";
				static $dbname="delta3";
				global $user,$pass,$nousererror,$startflag,$userflag,$url;

		    	$conn = new PDO("mysql:host=localhost;dbname=$dbname", $username, $password);
		    	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    	$stmt = $conn->query("SELECT username FROM users where username = '".$user."' and password = '".$pass."';"); 
		    	$stmt2 = $stmt->fetch();
		    	$user2 = $stmt2[0];
		    	if($user==$user2&&$user!="")
					$url = "d3main.php?username=".$user;
		    		header('Location: '.$url);
		    		exit();
			}
			catch(PDOException $e){
			    echo "Connection failed: " . $e->getMessage();
		    }
		    $conn=null;
		}

		class TableRows extends RecursiveIteratorIterator { 
		    function __construct($it) { 
		        parent::__construct($it, self::LEAVES_ONLY); 
		    }

		    function current() {
		        return parent::current();
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