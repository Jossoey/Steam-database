<?php
/*
* ╔═════════════════════╗
* ║ Procedural PhP Code ║
* ╚═════════════════════╝
* PhP script starts here.
* We need to define all buttons and their behaviour.
*/
// Check and include session information
// Session.php includes config.php inside so we do not need to call config.php
require('session.php');

// Script is given something through POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// If deleteAccount is set, we are to delete the account currently logged in and return to login page
	if (isset($_POST['deleteAccount'])) {
		// Connect to DB first - ideally this would be using an account dedicated to public use and username/password should not be hardcoded
		$cid = dbConnect(DB_USERNAME, DB_PASSWORD, DB_ADDRESS);
		if ($cid !== false) {
			// Database connection established successfully
			// Since POST is a super global variable, we don't need to give POST parameters here? Unsure, check on this
			deleteAccount($cid);
		}
		// Once deletion is complete, we should disconnect from the database
		dbDisconnect($cid);
		header("Location: login.php");
		exit();
	}
	
	// If updateReview is set, we update the review if one exists, does not add a new review if one does not exist
	if (isset($_POST['updateReview'])) {
		// Connect to DB first - ideally this would be using an account dedicated to public use and username/password should not be hardcoded
		$cid = dbConnect(DB_USERNAME, DB_PASSWORD, DB_ADDRESS);
		if ($cid !== false) {
			// Database connection established successfully
			// Since POST is a super global variable, we don't need to give POST parameters here? Unsure, check on this
			updateReview($cid);
		}
		// Once update is complete, we should disconnect from the database
		dbDisconnect($cid);
	}
}
?>

<!-------------------------------------------------------------------- HTML ------------------------------------------------------->


<!DOCTYPE html>
<html>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>CPSC 304 Project</title>
	<link rel="stylesheet" href="main.css">
	<link rel="stylesheet" href="select.css">
</head>

<body>

	<div style="width: 100%; display: table;">
		<div style="display: table-row">
			<div style="width: 50%; display: table-cell">
				<img style="max-width: 300px; max-height: auto; margin-bottom: 10px;" src="steam_logo.png"
					alt="Steam Database">
			</div>
			<div style="display: table-cell">
				<a href="logout.php"><button class="logout">Log Out</button></a>
			</div>
		</div>
	</div>

	<div>
		<button class="resdelButton" onclick="document.getElementById('login').style.display='block'"
			style="width:auto;">Delete Account</button>
	</div>

	<div id="login" class="modal">
		<form class="modal-content animate" action="" method="post">
			<div class="imgcontainer">
				<span onclick="document.getElementById('login').style.display='none'" class="close"
					title="Close Modal">&times;</span>
			</div>

			<div class="container_1">
				<label for="uname"><b>Username</b></label>
				<input type="text" placeholder="Enter Username" name="delete_uname" required>

				<label for="psw"><b>Password</b></label>
				<input type="password" placeholder="Enter Password" name="delete_psw" required>

				<button href="login.php" class="delete" type="submit" value="Delete"
					name="deleteAccount">Delete</button>
			</div>
		</form>
	</div>

	<button type="button" class="collapsible">Update Review</button>
	<div class="content">
		<!-- <div>
      <h2 class="title">Insert</h2>
      <h3 class="subtitle">Product</h3>
      <div class="form">
        <form method="POST" action="main.php">
          <input type="hidden" id="insertProductRequest" name="insertProductRequest">
          Application ID: <input type="text" name="insAppID" required>
          Developer name: <select name="insDevName" required>
            <option value="Re-Logic">Re-Logic</option>
            <option value="Edmund McMillen">Edmund McMillen</option>
            <option value="CD Projekt Red">CD Projekt Red</option>
            <option value="AMPLITUDE Studios">AMPLITUDE Studios</option>
            <option value="Paradox Development Studio">Paradox Development Studio</option>
            <option value="Ghost Ship Games">Ghost Ship Games</option>
            <option value="Forgotten Empires">Forgotten Empires</option>
          </select>
          Publisher ID: <input type="text" name="insPubID" required>
          Price: <input type="text" name="insPrice">
          Title: <input type="text" name="insTitle">
          Release date: <input type="date" name="insDate"> </br> </br>
          <input type="submit" value="Insert" name="insertProductSubmit"></p>
        </form>
      </div>
      <h3 class="subtitle">Soundtrack</h3>
      <div class="form">
        <form method="POST" action="main.php">
          <input type="hidden" id="insertSoundtrackRequest" name="insertSoundtrackRequest">
          Application ID: <input type="text" name="insAppID" required>
          Composer: <input type="text" name="insComposer">
          <input type="submit" value="Insert" name="insertSoundtrackSubmit"></p>
        </form>
      </div>
      <h3 class="subtitle">Game Tag</h3>
      <div class="form">
        <form method="POST" action="main.php">
          <input type="hidden" id="insertTagRequest" name="insertTagRequest">
          Tag name: <input type="text" name="insTag" required>
          <input type="submit" value="Insert" name="insertTagSubmit"></p>
        </form>
      </div>
    </div>

    <hr> -->
		<div>
			<form method="POST" action="main.php">
				</br>
				<label for="uname">Username: </label>
				<input class="uname" type="text" placeholder="Enter Username" name="review_uname" style="margin: 20px;"
					required>
				</br></br>
				<div style="width: 50%; display: table;">
					<div style="display: table-row">
						<div style="width: 18%; display: table-cell">
							Select a product:
						</div>
						<div style="display: table-cell" class="custom-select" style="width:350px;">
							<select name="review_product" required>
								<option value="Terraria">Terraria</option>
								<option value="Terraria_ST">Terraria: Official Soundtrack</option>
								<option value="TBI">The Binding of Isaac</option>
								<option value="TBIWL">The Binding of Isaac: Wrath of the Lamb</option>
								<option value="Cyberpunk">Cyberpunk 2077</option>
								<option value="DRG">Deep Rock Galactic</option>
								<option value="DRG_ST">Deep Rock Galactic - Original Soundtrack Volume I + II</option>
								<option value="AE2_ST">Age of Empires II: Definitive Edition Soundtrack</option>
								<option value="ES2_ST">Endless Space 2 - Harmonic Memories Soundtrack</option>
								<option value="Stellaris">Stellaris</option>
								<option value="Stellaris_ST">Stellaris: Complete Soundtrack</option>
							</select>
						</div>
					</div>
				</div>
				</br>


				</br>
				Do you recommend this game?
				<input type="radio" name="review_rec" value="yes" required>
				<label>Yes</label>
				<input type="radio" name="review_rec" value="no">
				<label>No</label>

				</br></br>
				New description:
				</br></br>
				<textarea name="review_desc" placeholder="Comment,..." class="textbox"></textarea>

				</br>
				</br>
				<input class="submitButton" type="submit" value="Submit" name="updateReview">
			</form>
		</div>
	</div>

	<button type="button" class="collapsible">Search</button>
	<div class="content">
		<form method="GET" action="main.php">
			<div class="radioButton">
				<input type="radio" id="select" name="search" value="select">
				<label for="nagd"><b>Selection</b>: Find which player has the most hours in the chosen game
				</label>
				</br>
				</br>
				<div class="other_options">
					<div style="width: 50%; display: table;">
						<div style="display: table-row">
							<div style="width: 15%; display: table-cell">
								Select a game:
							</div>
							<div style="display: table-cell" class="custom-select" style="width:100px;">
								<select name="gameSelection" required>
									<option value="Terraria">Terraria</option>
									<option value="TBI">The Binding of Isaac</option>
									<option value="TBIWL">The Binding of Isaac: Wrath of the Lamb</option>
									<option value="Cyberpunk">Cyberpunk 2077</option>
									<option value="DRG">Deep Rock Galactic</option>
									<option value="Stellaris">Stellaris</option>
								</select>
							</div>
						</div>
					</div>
				</div>

				<hr>
				<input type="radio" id="project" name="search" value="project">
				<label for="nagd"><b>Projection</b>: Show all product details</label>

				<hr>
				<input type="radio" id="join" name="search" value="join">
				<label for="nagd"><b>Join</b>: Find all players who own a particular product</label>
				</br>
				</br>
				<div class="other_options">
					<div style="width: 50%; display: table;">
						<div style="display: table-row">
							<div style="width: 17%; display: table-cell">
								Select product:
							</div>
							<div style="display: table-cell" class="custom-select" style="width:350px;">
								<select name="productJoin" required>
									<option value="Terraria">Terraria</option>
									<option value="Terraria_ST">Terraria: Official Soundtrack</option>
									<option value="TBI">The Binding of Isaac</option>
									<option value="TBIWL">The Binding of Isaac: Wrath of the Lamb</option>
									<option value="Cyberpunk">Cyberpunk 2077</option>
									<option value="DRG">Deep Rock Galactic</option>
									<option value="DRG_ST">Deep Rock Galactic - Original Soundtrack Volume I + II
									</option>
									<option value="AE2_ST">Age of Empires II: Definitive Edition Soundtrack</option>
									<option value="ES2_ST">Endless Space 2 - Harmonic Memories Soundtrack</option>
									<option value="Stellaris">Stellaris</option>
									<option value="Stellaris_ST">Stellaris: Complete Soundtrack</option>
								</select>
							</div>
						</div>
					</div>
				</div>

				<hr>
				<input type="radio" id="agb" name="search" value="agb">
				<label for="nagd"><b>Aggregation with Group By</b>: For each product that has made at least 1 sale, show
					the total number of times it
					has been purchased</label>

				<hr>
				<input type="radio" id="agh" name="search" value="agh">
				<label for="nagd"><b>Aggregation with Having</b>: Show all games that have at least 1 review and have
					more than 50% of its reviews recommend it</label>

				<hr>
				<input type="radio" id="nagd" name="search" value="nagd">
				<label for="nagd"><b>Nested Aggregation with Group By</b>: For each tag that describes at least 2 games,
					find the price of the cheapest game it describes</label>

				<hr>
				<input type="radio" id="div" name="search" value="div">
				<label for="nagd"><b>Division</b>: Find players who own all Steam products made by the chosen
					developer</label>
				</br>
				</br>
				<div class="other_options">
					<div style="width: 50%; display: table; overflow: visible;">
						<div style="display: table-row">
							<div style="width: 19%; display: table-cell; overflow: visible;">
								Select a developer:
							</div>
							<div style="display: table-cell" class="custom-select" style="width:350px;">
								<select name="developerDivison" required>
									<option value="RL">Re-Logic</option>
									<option value="EMAFH">Edmund McMillen and Florian Himsl</option>
									<option value="GSG">Ghost Ship Games</option>
									<option value="CDPR">CD Projekt Red</option>
									<option value="FETMWW">Forgotten Empires, Tantalus Media, Wicked Witch</option>
									<option value="AS">AMPLITUDE Studios</option>
									<option value="PDS">Paradox Development Studio</option>
								</select>
							</div>
						</div>
					</div>
				</div>
				
				<div>
				</br>
			</br>
		</br>
	</br>
</br>
</br>
</br></br>
</br>
</br>
</br>

				</div>

				</br>
				</br>
				<input class="submitButton" type="submit" value="Submit" name="otherSearchSubmit">
			</div>
		</form>
	</div>

	<br><br>

</body>

<script>
	var coll = document.getElementsByClassName("collapsible");
	var i;

	for (i = 0; i < coll.length; i++) {
		coll[i].addEventListener("click", function () {
			this.classList.toggle("active");
			var content = this.nextElementSibling;
			if (content.style.maxHeight) {
				content.style.maxHeight = null;
			} else {
				content.style.maxHeight = content.scrollHeight + "px";
			}
		});
	}
</script>

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
			c.addEventListener("click", function (e) {
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
		a.addEventListener("click", function (e) {
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

<!-------------------------------------------------------------------- PHP ------------------------------------------------------->


<?php
/*
* ╔═════════════════╗
* ║ Functions Begin ║
* ╚═════════════════╝
*/

// dbConnect and dbDisconnect functions are now present in the config.php file which is included inside session.php

/*
* deleteAccount :
*
* Deletes an account that matches the username and password
* Requires active database connection
*
* @param resource $cid Connection Identifier
* @return boolean True on success, False on error
*
*/
	function deleteAccount($cid)
	{
		// Grab username and password from user
		$username = $_POST['delete_uname'];
		$password = $_POST['delete_psw'];

		// We should check if the username and password are empty
		// Evaluates to True if not empty
		if (isset($username) && isset($password)) {
			// Prepare query with parameters
			$query = 'DELETE FROM Account WHERE (username = :username_bv AND password = :password_bv)';
			$delete = ociparse($cid, $query);
			// Bind variables
			oci_bind_by_name($delete, ':username_bv', $username);
			oci_bind_by_name($delete, ':password_bv', $password);
			// Query ready to be executed
			if (oci_execute($delete)) {
				// No execution errors
				return true;
			}
		}
		return false;
	}


/*
* updateReview :
*
* Updates values in existing review that matches the username and game title
* Requires active database connection
*
* @param resource $cid Connection Identifier
* @return boolean True on success, False on error
*
*/
	function updateReview($cid)
	{
		// Grab review data from user
		$username = $_POST['review_uname'];
		$date = date("Y-m-d");
		$product_val = $_POST['review_product'];
		switch ($product_val) {
			case "Terraria":
				$title = "Terraria";
				break;
			case "Terraria_ST":
				$title = "Terraria: Official Soundtrack";
				break;
			case "TBI":
				$title = "The Binding of Isaac";
				break;
			case "TBIWL":
				$title = "The Binding of Isaac: Wrath of the Lamb";
				break;
			case "Cyberpunk":
				$title = "Cyberpunk 2077";
				break;
			case "DRG":
				$title = "Deep Rock Galactic";
				break;
			case "DRG_ST":
				$title = "Deep Rock Galactic - Original Soundtrack Volume I + II";
				break;
			case "AE2_ST":
				$title = "Age of Empires II: Definitive Edition Soundtrack";
				break;
			case "ES2_ST":
				$title = "Endless Space 2 - Harmonic Memories Soundtrack";
				break;
			case "Stellaris":
				$title = "Stellaris";
				break;
			case "Stellaris_ST":
				$title = "Stellaris: Complete Soundtrack";
				break;
		}
		$reviewDesc = $_POST['review_desc'];
		if ($_POST['review_rec'] == "yes") {
			$rec = 1;
		} else {
			$rec = 0;
		}
		// We should check if the username and title are empty
		// Evaluates to True if not empty
		if (isset($username) && isset($title)) {
			// Prepare query with parameters
			$query = "UPDATE Review SET recommended = :rec_bv, reviewDate = TO_DATE(:date_bv, 'YYYY-MM-DD'), reviewDescription = :desc_bv WHERE (appID = (SELECT appID FROM Product WHERE title = :title_bv) AND steamID = (SELECT steamID FROM Account WHERE username = :username_bv))";
			$update = ociparse($cid, $query);
			// Bind variables
			oci_bind_by_name($update, ':username_bv', $username);
			oci_bind_by_name($update, ':date_bv', $date);
			oci_bind_by_name($update, ':title_bv', $title);
			oci_bind_by_name($update, ':desc_bv', $reviewDesc);
			oci_bind_by_name($update, ':rec_bv', $rec);
			// Query ready to be executed
			if (oci_execute($update)) {
				// No execution errors
				echo "<br>Review update complete.<br>";
				return true;
			}
		}
		echo "<br>Review update failed.<br>";
		return false;
	}

$success = True; // keep track of errors so it redirects the page only if there are no errors
$db_conn = NULL; // edit the login credentials in dbConnect()

// this function is directly from oracle-test.php from tutorial 7
function executePlainSQL($cmdstr)
{ //takes a plain (no bound variables) SQL command and executes it
	global $db_conn, $success;

	$statement = oci_parse($db_conn, $cmdstr);

	if (!$statement) {
		echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
		$e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
		echo htmlentities($e['message']);
		$success = False;
	}

	$r = OCIExecute($statement, OCI_DEFAULT);
	if (!$r) {
		echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
		$e = oci_error($statement); // For OCIExecute errors pass the statementhandle
		echo htmlentities($e['message']);
		$success = False;
	}

	return $statement;
}

// handle the Selection query
function handleSelectionRequest()
{
	$value = $_GET["gameSelection"];

	switch ($value) {
		case "Terraria":
			$game = "Terraria";
			break;
		case "TBI":
			$game = "The Binding of Isaac";
			break;
		case "TBIWL":
			$game = "The Binding of Isaac: Wrath of the Lamb";
			break;
		case "Cyberpunk":
			$game = "Cyberpunk 2077";
			break;
		case "DRG":
			$game = "Deep Rock Galactic";
			break;
		case "Stellaris":
			$game = "Stellaris";
			break;
	}

	$result = executePlainSQL("SELECT PR.alias, PS1.hours
									FROM Player PR, Plays PS1, Product PT1 
									WHERE PS1.appID = PT1.appID 
										AND PT1.title = '" . $game . "' 
										AND PR.steamID = PS1.steamID 
										AND PS1.hours = (SELECT DISTINCT MAX(PS2.hours) 
															FROM Plays PS2 
															WHERE PS2.appID = PS1.appID)");

	echo "<table class='tableStyle'>";
	echo "<tr><th colspan='2'>Player with the most hours in <strong>" . $game . "</strong></th></tr>";
	echo "<tr><th>Alias</th><th>Hours</th></tr>";

	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		echo "<tr><td>" . $row["ALIAS"] . "</td><td>" . $row["HOURS"] . "</td></tr>";
	}

	echo "</table>";
}

// handle the Projection query
function handleProjectionRequest()
{
	$result = executePlainSQL("SELECT title, price, releaseDate, developerName FROM Product ORDER BY title");

	echo "<table class='tableStyle'>";
	echo "<tr><th colspan='4'>Product Details</th></tr>";
	echo "<tr><th>Title</th><th>Price</th><th>ReleaseDate</th><th>Developer</th></tr>";

	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		echo "<tr><td>" . $row["TITLE"] . "</td><td>$" . number_format($row["PRICE"],2) . "</td><td>" . $row["RELEASEDATE"] . "</td><td>" . $row["DEVELOPERNAME"] . "</td></tr>";
	}

	echo "</table>";
}

// handle the Nested Aggregation with Group By query
function handleNAWGBRequest()
{
	$result = executePlainSQL("SELECT D1.tagName AS Tag, MIN(P.price) AS Price 
								FROM Describes D1, Product P 
								GROUP BY D1.tagName 
								HAVING 1 < (SELECT COUNT(*) 
											FROM Describes D2 
											WHERE D1.tagName = D2.tagName)
								ORDER BY Tag");

	echo "<table class='tableStyle'>";
	echo "<tr><th colspan='2'>Tags and the price of the cheapest game they describe</th></tr>";
	echo "<tr><th>TagName</th><th>Price</th></tr>";

	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		echo "<tr><td>" . $row["TAG"] . "</td><td>" . $row["PRICE"] . "</td></tr>";
	}

	echo "</table>";
}

// handle the Aggregation with Group By query
function handleAWGBRequest()
{
	$result = executePlainSQL("SELECT A1.appID, P2.title, A1.PurchaseTotal 
								FROM (	SELECT P1.appID AS appID, COUNT(*) AS PurchaseTotal 
										FROM Purchases P1 
										GROUP BY P1.appID) A1, Product P2 
								WHERE A1.appID = P2.appID
								ORDER BY A1.PurchaseTotal DESC, P2.title ASC");

	echo "<table class='tableStyle'>";
	echo "<tr><th colspan='3'>Number of copies of products sold on Steam</th></tr>";
	echo "<tr><th>AppID</th><th>Title</th><th>Total Purchased</th></tr>";

	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		echo "<tr><td>" . $row["APPID"] . "</td><td>" . $row["TITLE"] . "</td><td>" . $row["PURCHASETOTAL"] . "</td></tr>";
	}

	echo "</table>";
}

// handle the Aggregation with Having query
function handleAWHRequest()
{
	$result = executePlainSQL("SELECT A1.appID, R2.title, A1.Review_Count, A1.Recommend_Average
									FROM (SELECT R1.appID AS appID, ROUND((AVG(recommended) * 100), 0) AS Recommend_Average, COUNT(*) AS Review_Count 
											FROM Review R1 
											GROUP BY R1.appID 
											HAVING 0.50 < AVG(R1.recommended)) A1, Product R2 
									WHERE A1.appID = R2.appID
									ORDER BY A1.Recommend_Average DESC");

	echo "<table class='tableStyle'>";
	echo "<tr><th colspan='4'>Products with recommended reviews as the majority</th></tr>";
	echo "<tr><th>AppID</th><th>Title</th><th>Total Number of Reviews</th><th>Percentage Recommended</th></tr>";

	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		echo "<tr><td>" . $row["APPID"] . "</td><td>" . $row["TITLE"] . "</td><td>" . $row["REVIEW_COUNT"] . "</td><td>" . $row["RECOMMEND_AVERAGE"] . "</td></tr>";
	}

	echo "</table>";
}

// handle the Division query
function handleDivisionRequest()
{

	// check which developer was chosen by the user from the drop-down in main.php
	$value = $_GET["developerDivison"];
	switch ($value) {
		case "RL":
			$dev = "Re-Logic";
			break;
		case "EMAFH":
			$dev = "Edmund McMillen and Florian Himsl";
			break;
		case "GSG":
			$dev = "Ghost Ship Games";
			break;
		case "CDPR":
			$dev = "CD Projekt Red";
			break;
		case "FETMWW":
			$dev = "Forgotten Empires, Tantalus Media, Wicked Witch";
			break;
		case "AS":
			$dev = "AMPLITUDE Studios";
			break;
		case "PDS":
			$dev = "Paradox Development Studio";
			break;
	}

	$result = executePlainSQL("SELECT PL.alias 
								FROM Player PL 
								WHERE NOT EXISTS (	SELECT PR.appID 
													FROM Product PR 
													WHERE PR.developerName = '" . $dev . "' MINUS SELECT PU.appID 
																									FROM Purchases PU 
																									WHERE PL.steamID = PU.steamID)
								ORDER BY PL.alias");

	echo "<table class='tableStyle'>";
	echo "<tr><th colspan='1'>Players that own every Steam product made by <strong>" . $dev . "</strong></th></tr>";
	echo "<tr><th>Alias</th></tr>";

	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		echo "<tr><td>" . $row["ALIAS"] . "</td></tr>";
	}

	echo "</table>";
}


// handle the Join query
function handleJoinRequest()
{
	// check which product was chosen by the user from the drop-down in main.php
	$value = $_GET["productJoin"];
	switch ($value) {
		case "Terraria":
			$product = "Terraria";
			break;
		case "Terraria_ST":
			$product = "Terraria: Official Soundtrack";
			break;
		case "TBI":
			$product = "The Binding of Isaac";
			break;
		case "TBIWL":
			$product = "The Binding of Isaac: Wrath of the Lamb";
			break;
		case "Cyberpunk":
			$product = "Cyberpunk 2077";
			break;
		case "DRG":
			$product = "Deep Rock Galactic";
			break;
		case "DRG_ST":
			$product = "Deep Rock Galactic - Original Soundtrack Volume I + II";
			break;
		case "AE2_ST":
			$product = "Age of Empires II: Definitive Edition Soundtrack";
			break;
		case "ES2_ST":
			$product = "Endless Space 2 - Harmonic Memories Soundtrack";
			break;
		case "Stellaris":
			$product = "Stellaris";
			break;
		case "Stellaris_ST":
			$product = "Stellaris: Complete Soundtrack";
			break;
	}

	$result = executePlainSQL("	SELECT PR.steamID, PR.alias 
								FROM Player PR 
								WHERE PR.steamID IN 
									(	SELECT PS.steamID 
										FROM Purchases PS, Product PT 
										WHERE PS.appID = PT.appID AND PT.title = '" . $product . "')");

	echo "<table class='tableStyle'>";
	echo "<tr><th colspan='2'>Players that own the Steam product <strong>" . $product . "</strong></th></tr>";
	echo "<tr><th>SteamID</th><th>Alias</th></tr>";

	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		echo "<tr><td>" . $row["STEAMID"] . "</td><td>" . $row["ALIAS"] . "</td></tr>";
	}

	echo "</table>";
}


function handleGETRequest()
{
	// connect to the database and save the resource variable
	global $db_conn;
	$db_conn = dbConnect("ora_katkp", "a61163531", "dbhost.students.cs.ubc.ca:1522/stu");

	// if the database connection was successful, check which radio button was selected in main.php
	if ($db_conn) {
		if ($_GET["search"] == "project") {
			handleProjectionRequest();
		} else if ($_GET["search"] == "nagd") {
			handleNAWGBRequest();
		} else if ($_GET["search"] == "agb") {
			handleAWGBRequest();
		} else if ($_GET["search"] == "agh") {
			handleAWHRequest();
		} else if ($_GET["search"] == "div") {
			handleDivisionRequest();
		} else if ($_GET["search"] == "join") {
			handleJoinRequest();
		} else if ($_GET["search"] == "select") {
			handleSelectionRequest();
		}

		//disconnect from the database
		dbDisconnect($db_conn);
	}
}

// Check if the submit button was under Others was pressed in main.php
if (isset($_GET["otherSearchSubmit"])) {
	handleGETRequest();
}

?>