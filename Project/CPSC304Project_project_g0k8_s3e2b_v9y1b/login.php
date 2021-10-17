<?php
/*
* ╔═════════════════════╗
* ║ Procedural PhP Code ║
* ╚═════════════════════╝
* We need to include config.php to have access to the dbConnect and dbDisconnect functions
*/
require('config.php'); // We are not using session.php otherwise we will end in an infinite redirect loop
session_start(); // Create a new session

// Form handler: script is given something through POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Output buffer to be able to clear and redirect page once done

	// If creation uname, psw, and email are set, then this is the account creation form
	if (isset($_POST['create_uname']) && isset($_POST['create_psw']) && isset($_POST['create_email'])) {
		// Connect to DB first - ideally this would be using an account dedicated to public use and username/password should not be hardcoded
		$cid = dbConnect(DB_USERNAME, DB_PASSWORD, DB_ADDRESS);
		if ($cid !== false) {
			// Database connection established successfully
			// Since POST is a super global variable, we don't need to give POST parameters here? Unsure, check on this
			registerAccount($cid);
		}
		// Once registration is complete (regardless of success), we should disconnect from the database
		dbDisconnect($cid);
	}

	// If login uname and password are set, then that means this is the login form
	if (isset($_POST['login_uname']) && isset($_POST['login_psw'])) {
		// Connect to DB first
		$cid = dbConnect(DB_USERNAME, DB_PASSWORD, DB_ADDRESS);
		if ($cid !== false) {
			// Database connection established successfully
			$caught = false;
			// requestLogin() will throw an exception if the login fails so we need a try-catch statement
			try {
				$user_id = requestLogin($cid);
			} catch (Exception $loginE) {
				$caught = true;
				$login_err = "<br>Error: " . $loginE->getMessage() . "<br>";
			}
			if ($caught === false) {
				// Only disconnect if login was successful, otherwise it is not needed
				dbDisconnect($cid);
				// Store the steamID of a successful login in the session
				$_SESSION['login_user'] = $user_id;
				// We should now redirect to main.php
				header("Location: main.php");
				exit();
			}
		}
	}
}


/*
* ╔═════════════════╗
* ║ Functions Begin ║
* ╚═════════════════╝
* We don't need database connection functions due to them existing in config.php
*/

/*
* registerAccount :
*
* Inserts new account data into database. Requires active database connection
*
* @param resource $cid Connection Identifier
* @return boolean $ret_val True on success, False on failure
*
*/
function registerAccount($cid)
{
	// SteamID is like the accountID and should be auto-generated to an available unique number
	// Balance should be set to 0
	// All other user information should be entered by the user on the registration page
	// echo "<br> Debug: Entered registerAccount function.<br>";
	// Let's set up some of these parameters now:
	$steamID = generateAccountID();       // TODO: generator function. Temp function RNG used
	$balance = 0.00;
	$username = $_POST['create_uname'];          // Should probably add a verifier for acceptable input here
	$password = $_POST['create_psw'];            // ^^ Maybe also salt+hashing?
	$email = $_POST['create_email'];             // ^^
	$registrationDate = date("Y-m-d");    // Current date at the time the button was clicked. Should we include time?
	$countryCodeArray = array('CA', 'US', 'ID', 'JP', 'SE', 'PL'); // Value array
	$country = $_POST['create_country']; // Picks the value from the value array that matches the index used for the country selection dropdown

	// echo "<br> Debug: User data acquired:<br> Steam ID: " . $steamID . "<br> Balance: " . $balance . "<br> Password: " . $password . "<br> Email: " . $email . "<br> Username: " . $username . "<br> Registration Date: " . $registrationDate . "<br> Country: " . $country . "<br>";

	// Prepare the query with parameters
	$createAccount = oci_parse($cid, "INSERT INTO Account (steamID, balance, password, email, username, registrationDate, country) VALUES (:steamID_bv, :balance_bv, :password_bv, :email_bv, :username_bv, TO_DATE(:registrationDate_bv, 'YYYY-MM-DD'), :country_bv)");

	if ($createAccount === false) {
		$e = OCI_Error($cid);
		// echo "<br> Debug: Parse error: " . htmlentities($e['message']) . "<br>";
	} else {
		// echo "<br> Debug: Parsed statement.<br>";
	}

	// One func to rule them all, one func to find them, one func to bring them all, and in the code bind them
	oci_bind_by_name($createAccount, ":steamID_bv", $steamID);
	oci_bind_by_name($createAccount, ":balance_bv", $balance);
	oci_bind_by_name($createAccount, ":password_bv", $password);
	oci_bind_by_name($createAccount, ":email_bv", $email);
	oci_bind_by_name($createAccount, ":username_bv", $username);
	oci_bind_by_name($createAccount, ":registrationDate_bv", $registrationDate);
	oci_bind_by_name($createAccount, ":country_bv", $country);

	// echo "<br> Debug: Executing statement...<br>";
	// Query ready to be executed
	$ret_val = oci_execute($createAccount);
	// echo "<br> Debug: ret_val = <br>";
	// echo var_export($ret_val);
	if ($ret_val === false) {
		// Something went wrong and the query execution failed
		// echo "<br> Account creation failed.<br>";
		$e = oci_error($createAccount); // For OCIExecute errors pass the statementhandle
		// echo "<br> Debug: Statement execution error: " . htmlentities($e['message']) . "<br>";
	}
	oci_free_statement($createAccount);
	// Once completed, maybe go to login page now so they can log in with the new account?
	return $ret_val;
}

/*
* requestLogin :
*
* Authenticates username and password by checking the database
* Requires active database connection
* Throws exception on failed login
*
* @param resource $cid Connection Identifier
* @return int $user_data User's steamID
*
*/
function requestLogin($cid)
{
	// Grab username and password from user
	$username = $_POST['login_uname'];
	$password = $_POST['login_psw'];

	// We should check if the username and password are empty
	// Evaluates to True if not empty
	if (isset($username) && isset($password)) {
		// Prepare query with parameters
		$query = 'SELECT steamID FROM Account WHERE (username = :username_bv AND password = :password_bv)';
		$login = ociparse($cid, $query);
		// Bind variables
		oci_bind_by_name($login, ':username_bv', $username);
		oci_bind_by_name($login, ':password_bv', $password);
		// Query ready to be executed
		if (oci_execute($login)) {
			// Query successfully executed, now we grab the data we got from our query
			$row = oci_fetch_array($login);
			// If username and password match an account, there will be 1 row only
			// If they do not match, the row will be null
			if (!(empty($row['STEAMID']))) {
				// Successful login
				// echo "Login successful by " . $row['STEAMID'] . "<br>";
				$user_data = $row['STEAMID'];
				return $user_data;
			}
			// Will only get to this line if login did not succeed
			throw new Exception('Authentication failed.');
		}
	}
}

// Temp generateAccountID function
// @return random value integer
function generateAccountID()
{
	return mt_rand(10000000000000000, 99999999999999999);
}

?>

<!DOCTYPE html>
<html>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="login.css">
	<link rel="stylesheet" href="select.css">
</head>

<body>

	<img class="img" src="steam_logo.png" alt="Steam Database">
	</br></br>

	<button onclick="document.getElementById('login').style.display='block'" style="width:auto;">Login</button>
	<button onclick="document.getElementById('newacc').style.display='block'" style="width:auto;">Create new
		account</button>

	<div id="login" class="modal">
		<form class="modal-content animate" action="" method="post">
			<div class="imgcontainer">
				<span onclick="document.getElementById('login').style.display='none'" class="close" title="Close Modal">&times;</span>
			</div>

			<div class="container">
				<label for="uname"><b>Username</b></label>
				<input type="text" placeholder="Enter Username" name="login_uname" required>

				<label for="psw"><b>Password</b></label>
				<input type="password" placeholder="Enter Password" name="login_psw" required>

				<button type="submit">Login</button>
			</div>
		</form>
	</div>

	<div id="newacc" class="modal">
		<form class="modal-content animate" action="" method="post">
			<div class="imgcontainer">
				<span onclick="document.getElementById('newacc').style.display='none'" class="close" title="Close Modal">&times;</span>
			</div>

			<div class="container">
				<label for="uname"><b>Username</b></label>
				<input type="text" placeholder="Enter Username" name="create_uname" required>

				<label for="psw"><b>Password</b></label>
				<input type="password" placeholder="Enter Password" name="create_psw" required>

				<label for="email"><b>Email</b></label>
				<input type="text" placeholder="Enter Email" name="create_email" required>

				<label for="country"><b style="margin-bottom: 10px;">Country</b></label>
				<div class="custom-select" style="width:200px; margin: 10px 0px;">
					<select name="create_country" required>
						<option value="CA">Canada</option>
						<option value="US">United States</option>
						<option value="ID">Indonesia</option>
						<option value="JP">Japan</option>
						<option value="SE">Sweden</option>
						<option value="PL">Poland</option>
					</select>
				</div>


				<button type="submit">Create account</button>
			</div>
		</form>
	</div>
	<li style="color:rgb(109, 23, 23);">
		<?php
			echo $login_err;
		?>
	</li>
	
</body>

<script>
	var x, i, j, l, ll, selElmnt, a, b, c;
	/* Look for any elements with the class "custom-select": */
	x = document.getElementsByClassName("custom-select");
	l = x.length;
	for (i = 0; i < l; i++) {
		selElmnt = x[i].getElementsByTagName("select")[0];
		ll = selElmnt.length;
		/* For each element, create a new DIV that will act as the selected item: */
		a = document.createElement("DIV");
		a.setAttribute("class", "select-selected");
		a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
		x[i].appendChild(a);
		/* For each element, create a new DIV that will contain the option list: */
		b = document.createElement("DIV");
		b.setAttribute("class", "select-items select-hide");
		for (j = 1; j < ll; j++) {
			/* For each option in the original select element,
			create a new DIV that will act as an option item: */
			c = document.createElement("DIV");
			c.innerHTML = selElmnt.options[j].innerHTML;
			c.addEventListener("click", function(e) {
				/* When an item is clicked, update the original select box,
				and the selected item: */
				var y, i, k, s, h, sl, yl;
				s = this.parentNode.parentNode.getElementsByTagName("select")[0];
				sl = s.length;
				h = this.parentNode.previousSibling;
				for (i = 0; i < sl; i++) {
					if (s.options[i].innerHTML == this.innerHTML) {
						s.selectedIndex = i;
						h.innerHTML = this.innerHTML;
						y = this.parentNode.getElementsByClassName("same-as-selected");
						yl = y.length;
						for (k = 0; k < yl; k++) {
							y[k].removeAttribute("class");
						}
						this.setAttribute("class", "same-as-selected");
						break;
					}
				}
				h.click();
			});
			b.appendChild(c);
		}
		x[i].appendChild(b);
		a.addEventListener("click", function(e) {
			/* When the select box is clicked, close any other select boxes,
			and open/close the current select box: */
			e.stopPropagation();
			closeAllSelect(this);
			this.nextSibling.classList.toggle("select-hide");
			this.classList.toggle("select-arrow-active");
		});
	}

	function closeAllSelect(elmnt) {
		/* A function that will close all select boxes in the document,
		except the current select box: */
		var x, y, i, xl, yl, arrNo = [];
		x = document.getElementsByClassName("select-items");
		y = document.getElementsByClassName("select-selected");
		xl = x.length;
		yl = y.length;
		for (i = 0; i < yl; i++) {
			if (elmnt == y[i]) {
				arrNo.push(i)
			} else {
				y[i].classList.remove("select-arrow-active");
			}
		}
		for (i = 0; i < xl; i++) {
			if (arrNo.indexOf(i)) {
				x[i].classList.add("select-hide");
			}
		}
	}

	/* If the user clicks anywhere outside the select box,
	then close all select boxes: */
	document.addEventListener("click", closeAllSelect);
</script>

</html>