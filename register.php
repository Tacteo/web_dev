<?php
	include("database.php");
	session_start();

	if (isset($_SESSION['logged_in_user'])) {
		header("location: index.php");
	}

	$login_errors = '';

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		// username and password sent from form 

		$input_username = mysqli_real_escape_string($conn, $_POST['username']);
		$input_password = $_POST['password'];

		if ($input_password != $_POST['password_verify']) {
			$login_errors = 'Passwords do not match';
		} else {

			// Check to see if username is taken
			$sql = "SELECT id FROM users WHERE username='$input_username'";
			$result = mysqli_query($conn, $sql);
			
			$n_rows = mysqli_num_rows($result);

			if ($n_rows != 0) {
				$login_errors = 'Username already taken.';
			} else {
				// Add user to db
				$hashed_password = password_hash($input_password, PASSWORD_BCRYPT, ['cost' => 10]);
				$sql = "INSERT INTO users(username, password) VALUES ('$input_username', '$hashed_password');";
				$result = mysqli_query($conn, $sql);
				
				mysqli_query($conn, "INSERT INTO fish (username) VALUES ('$input_username');");
				mysqli_query($conn, "INSERT INTO bugs (username) VALUES ('$input_username');");
				mysqli_query($conn, "INSERT INTO deep_sea_creatures (username) VALUES ('$input_username');");


				if (mysqli_connect_errno()) {
					$login_errors = "Failed to connect to MySQL: " . mysqli_connect_error();
				} else {
					$_SESSION['logged_in_user'] = $input_username;
					header("location: index.php");
					mysqli_close($conn);
					exit();
				}
			}
		}

		mysqli_close($conn);
	}
?>
<html>
<head>
	<meta charset="UTF-8">
  	<title>Register</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body bgcolor = "#FFFFFF">
    <div align = "center">
        <div style = "width:300px; border: solid 1px #333333; " align = "left">
            <div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>Register</b></div>
				
            <div style = "margin:30px">
               
               <form action="register.php" method = "post">
                  <label>Username: </label><input type="text" name="username" class="box" /><br /><br />
                  <label>Password: </label><input type="password" name="password" class="box" /><br/><br />
                  <label>Password: </label><input type="password" name="password_verify" class="box" /><br/><br />
                  <input type="submit" value="Submit" /><br />
               </form>
               
               <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $login_errors; ?></div>
					
            </div>	
        </div>	
    </div>
</body>
</html>