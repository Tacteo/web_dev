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

		//$myusername = mysqli_real_escape_string($db,$_POST['username']);
		//$mypassword = mysqli_real_escape_string($db,$_POST['password']); 

		$sql = "SELECT id, password FROM users WHERE username = '$input_username'";

		$result = mysqli_query($conn, $sql);
		
		if (mysqli_connect_errno()) {
			$login_errors = "Failed to connect to MySQL: " . mysqli_connect_error();
		} else {

			$n_rows = mysqli_num_rows($result);

			if ($n_rows == 0) {
				$login_errors = 'Username not found.';
			} else if ($n_rows == 1) {
				$row = mysqli_fetch_assoc($result);
				if (password_verify($input_password, $row['password'])) {
					$_SESSION['logged_in_user'] = $input_username;
					header("location: index.php");
					mysqli_close($conn);
					exit();
				} else {
					$login_errors = 'Incorrect password.';
				}
			} else {
				$login_errors = 'System error. Multiple entries for entered username.';
			}
		}

		mysqli_close($conn);

		//$login_errors = "User: $input_username , Rows: $n_rows";


		// $sql = "SELECT id, password FROM users WHERE username = $input_username";

		// $result = mysqli_query($db,$sql);
		// $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		// $active = $row['active'];

		// $count = mysqli_num_rows($result);

		// // If result matched $myusername and $mypassword, table row must be 1 row

		// if($count == 1) {
		// 	session_register("myusername");
		// 	$_SESSION['login_user'] = $myusername;

		// 	header("location: index.php");
		// }else {
		// 	$error = "Your Login Name or Password is invalid";
		// }
	}
?>
<html>
<head>
	<meta charset="UTF-8">
  	<title>Login</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body bgcolor = "#FFFFFF">
    <div align = "center">
        <div style = "width:300px; border: solid 1px #333333; " align = "left">
            <div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>Login</b></div>
				
            <div style = "margin:30px">
               
               <form action="login.php" method = "post">
                  <label>Username: </label><input type="text" name="username" class="box" /><br /><br />
                  <label>Password: </label><input type="password" name ="password" class="box" /><br/><br />
                  <input type="submit" value="Submit" /><br />
               </form>
               
               <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $login_errors; ?></div>

               <div style = "font-size:11px; color:#555555; margin-top:10px"><a href="register.php">Register</a></div>
					
            </div>	
        </div>	
    </div>
</body>
</html>