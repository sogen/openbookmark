<?php

class auth {

	var $username = '';
	var $password = '';

	function auth () {
		if (!session_id ()){
			session_start();
		}

		if ($this->check_auth ()) {
			$_SESSION['logged_in'] = true;
		}
		else {
			$_SESSION['logged_in'] = false;
		}
	}

	function check_auth () {
		if (session_id ()
			&& isset ($_SESSION['challengekey'])
			&& strlen ($_SESSION['challengekey']) === 32
			&& isset ($_SESSION['username'])
			&& $_SESSION['username'] != ''
			&& isset ($_SESSION['logged_in'])
			&& $_SESSION['logged_in']) {
			return true;
		}
		else if ($this->check_cookie ()) {
			return true;
		}
		return false;
	}

	function assign_data () {
		if (   isset($_POST['username'])
			&& isset($_POST['password'])
			&& $_POST['username'] != ''
			&& $_POST['password'] != '') {
				$this->username = $_POST['username'];
				$this->password = $_POST['password'];
				return true;
		}
		return false;
	}

	function login () {
		$_SESSION['logged_in'] = false;
		if ($this->assign_data ()) {
			global $mysql;
			$query = sprintf("SELECT COUNT(*) FROM user WHERE md5(username)=md5('%s') AND password=md5('%s')",
				$mysql->escape ($this->username),
				$mysql->escape ($this->password));
			if ($mysql->query ($query) && mysql_result ($mysql->result, 0) === "1") {
				if (isset ($_POST['remember'])) {
					global $cookie;
					$cookie['data'] = serialize (array ($this->username, md5 ($cookie['seed'] . md5 ($this->password))));
					@setcookie ($cookie['name'],
								$cookie['data'],
								$cookie['expire'],
								$cookie['path'],
								$cookie['domain']);
				}
				$this->set_login_data ($this->username);
			}
			else {
				$this->logout ();
			}
		}
		unset ($_POST['password']);
		unset ($this->password);
	}

	function logout () {
		global $cookie;
		unset ($_SESSION['challengekey']);
		unset ($_SESSION['username']);
		@setcookie ($cookie['name'], "", time() - 1, $cookie['path'], $cookie['domain']);
		$_SESSION['logged_in'] = false;
	}

	function set_login_data ($username) {
		$_SESSION['challengekey'] = md5 ($username . microtime ());
		$_SESSION['username'] = $username;
		$_SESSION['logged_in'] = true;
	}

	function check_cookie () {
		global $cookie, $mysql;
		if (   isset ($cookie['name'])
			&& $cookie['name'] != ''
			&& isset ($_COOKIE[$cookie['name']])) {
			list ($cookie['username'], $cookie['password_hash']) = @unserialize ($_COOKIE[$cookie['name']]);
			$query = sprintf("SELECT COUNT(*) FROM user WHERE username='%s' AND MD5(CONCAT('%s', password))='%s'",
				$mysql->escape ($cookie['username']),
				$mysql->escape ($cookie['seed']),
				$mysql->escape ($cookie['password_hash']));
			if ($mysql->query ($query) && mysql_result ($mysql->result, 0) === "1") {
				$this->set_login_data ($cookie['username']);
				return true;
			}
			else {
				$this->logout ();
				return false;
			}
		}
		return false;
	}

	function display_login_form () {
		?>

<?php
require_once ("./css/signin.php");
?>


<div class="container">

			<form class="form-signin" name="loginform" method="POST" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">


<h2 class="form-signin-heading">Please sign in</h2>


				<div class="form-group">
							<label for="InputEmail1" class="sr-only">Email address</label>
								<input class="form-control" name="username" type="text" value="" tabindex="1" placeholder="Email" autofocus>
				</div>

				<div class="form-group">
				    <label for="InputPassword1" class="sr-only">Password</label>

									<input class="form-control" name="password" type="password" value="" tabindex="2" placeholder="Password">
				</div>


				<div class="checkbox">
			    <label>
			      <input type="checkbox" name="remember" tabindex="3">  Remember me
			    </label>
			  </div>



				<input class="btn btn-lg btn-primary btn-block" type="submit" value="Login" tabindex="4">



				<?php
				if (strtolower (basename ($_SERVER['SCRIPT_NAME'])) == "index.php") {
					echo '<br><div><a href="./shared.php">Users Sharing Bookmarks</a></div>';
				}
				?>


			</form>

</div>



		<?php
	}
}

?>
