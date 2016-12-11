<?php
require_once ("./header.php");

$secret = "dDWUc72sCcs20cXskcw";
$reg_register = set_post_bool_var ('reg_register', false);
$reg_username = set_post_string_var ('reg_username');
$reg_email = set_post_string_var ('reg_email');
$confirm = set_get_string_var ('confirm');

if ($reg_register) {
	if ($reg_username != "") {
					if (check_username ($reg_username)) {
						echo '<p class="text-danger">$username is an already registered user. Choose another one.</p>'."\n";
						$username = false;
					}
					else {
						$username = $reg_username;
					}
	}
	else {
		echo '<p class="text-danger">Please enter a Username.</p>'."\n";
		$username = false;
	}

	if (isset ($_POST['reg_password1']) && $_POST['reg_password1'] != "" &&
		  isset ($_POST['reg_password2']) && $_POST['reg_password2'] != "") {
		if (md5 ($_POST['reg_password1']) != md5 ($_POST['reg_password2'])) {
			echo '<p class="text-danger">Passwords do not match.</p>'."\n";
			$password = false;
		}
		else {
			$password = md5 ($_POST['reg_password1']);
		}
	}
	else {
		echo '<p class="text-danger">Please fill out both password fields.</p>'."\n";
		$password = false;
	}

	if ($reg_email != '') {
		if (preg_match ('/^[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i', $reg_email)) {
			$query = "SELECT COUNT(*) AS result FROM user WHERE email='$reg_email'";
			if ($mysql->query ($query)) {
				if (mysql_result ($result, 0) > 0) {
					echo '<p class="text-danger">A User Account with this email address aready exists.</p>'."\n";
					$email = false;
				}
				else {
					$email = $reg_email;
				}
			}
			else {
				$email = false;
				message ($mysql->error);
			}
		}
		else {
			echo '<p class="text-danger">Email address is invalid.</p>'."\n";
			$email = false;
		}
	}
	else {
		echo '<p class="text-danger">Please enter a valid email address.</p>'."\n";
		$email = false;
	}


	if ($username && $password && $email) {
		$query = "      INSERT INTO user
				(username, password, email, active)
				VALUES
				('$username', md5('$password'), '$email', '0')";

		if (mysql_query ("$query")) {
			# dieser key wird als username und secret md5 hash an den
			# user geschickt und f�r die verifikation der registrierung gebraucht.
			$key = md5 ($username . $secret);

			$headers = "From: noreply@yourdomain.com\r\n" .
			$subject = 'Your registration at yourdomain.com';
			$message  = "Hi $username,\r\n\r\n";
			$message .= "This email confirms the creation of your OpenBookmark user account. ";
			$message .= "Your username is '$username'. For security reasons your password is not ";
			$message .= "included in this email. To activate your account, visit the following URL:\r\n\r\n";
			$message .= "http://www.yourdomain.com/register.php?confirm=$key\r\n\r\n";
			$message .= "In case of complications regarding this user account registration, ";
			$message .= "please contact support@yourdomain.com\r\n\r\n";
			$message .= "With kind regards, your yourdomain.com Team";

			mail($email, $subject, $message, $headers);

			echo "  you have been successfully registered.
				Read your email and click the link to activate your account.";
		}
		else {
			echo mysql_error ();
		}
	}
	else {
		display_register_form ();
	}
}
else if ($confirm != '' && strlen ($confirm) === 32) {
	$query = "SELECT username FROM user WHERE MD5(CONCAT(username,'$secret'))='$confirm' AND active='0'";
	$result = mysql_query ("$query");
	if (mysql_num_rows ($result) == 1) {
		# the registration confirmation was successufull,
		# thus we can enable the useraccount in the database.
		$username = mysql_result ($result, 0);
		$query = "UPDATE user SET active='1' WHERE username='$username' AND active='0'";
		if (mysql_query ($query)) {
			echo "You are now registered. Happy bookmarking!";
		}
	}
	else {
		display_register_form ();
	}
}
else {
	display_register_additional_text ();
	display_register_form ();
}

function display_register_form () {
?>

<form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" name="loginform">
<table class="table">
	<tr>
		<td>Username:</td>
		<td><input name="reg_username" type="text" value=""></td>
	</tr>
	<tr>
		<td>Password:</td>
		<td><input name="reg_password1" type="password" value=""></td>
	</tr>
	<tr>
		<td>Password Verification:</td>
		<td><input name="reg_password2" type="password" value=""></td>
	</tr>
	<tr>
		<td>Email Address:</td>
		<td><input name="reg_email" type="text" value=""></td>
	</tr>
	<tr>
		<td></td>
		<td><input type="submit" value="Register" name="reg_register"></td>
	</tr>
</table>
</form>

<?php
}

function display_register_additional_text () {
	?>
	<p>Please provide the information bellow to register.</p>

	<p>If you are already a registered user, <a class="orange" href="./index.php">you can log in here.</a></p>
	<?php
}

require_once ("./footer.php");
?>
