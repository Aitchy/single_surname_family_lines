<!-- selection / search page-->

<?php
// common variables & header
include("include/common.inc");

$PageName = "Edit Database";
$PageTagline = "ONLY FOR SYSTEM ADMINISTRATORS USE!";
include("include/header.inc");
?>

<div id="leftcontentmain">

</div>

<div id="centercontentmain">

<?php
// display links to Mutches if requested
if ($_POST[mutch]  and (strlen($_POST[mname])  > 1)) {
	// split name string
//$tempname=$_POST[mname];
//$tempname1=$_POST[mname];
	$name = split(" ", $_POST[mname], 3);
	$name1=($name[0]);
	$name2=($name[1]);
	$name3=($name[2]);

    include("include/db_connect.php");
//	$conn = new mysqli($host, $user, $pass, $db);

//____________________ Check this is working

	if (strlen($name2)>0 and strtolower($name2)!="mutch"){
  		$sel = "select * from Mutches where first_name LIKE '" . $name1 . "' and second_name LIKE '".$name2."' order by dob_year";
		}else{
	  	$sel = "select * from Mutches where first_name LIKE '" . $name1 . "' order by dob_year";
		}
	$result = $conn->query($sel);

  	if ($result->num_rows > 0){
    	echo("<br />");
    	echo("<p class=\"itemmainleft\">");
    	echo("<font class=\"title\">Your search for ".ucfirst(strtolower($name1)));
		if (strtolower($name2)!="mutch"){
	    	echo(" ".ucfirst(strtolower($name2)));
			}
		if (strtolower($name3)!="mutch"){
	    	echo(" ".ucfirst(strtolower($name3)));
			}
		echo(" Mutch");
    	echo(" returned ".$result->num_rows." hits in the main Mutches database, select below...</font>");
    	echo("</p>");
    	echo("<ul>");
    	while($row = $result->fetch_assoc()) {
      		if ($row["family_line"]) {
        		echo ("<li><a href=\"fl.php?fl=".$row["family_line"]."\">(M - ".$row["id"].") <b>".$row["first_name"]." ".$row["second_name"]." ".$row["third_name"]." [".strip_zeros($row["family_line"])."] </b>");
        		} else {
        		echo ("<li><a href=\"fl.php?id=".$row["id"]."\">(M - ".$row["id"].") <b>".$row["first_name"]." ".$row["second_name"]." ".$row["third_name"]."</b>");
        		}
      		if ($row["dob_year"] != NULL) {
        		echo (" (b) ".$row["dob_day"]." ".$month_text[$row["dob_mon"]]." ".$row["dob_year"]);
        		}
      		if ($row["dob_place"] != NULL) {
        		echo (" at ".$row["dob_place"]);
        		}
      		if ($row["dob_parish"] != NULL) {
        		echo (" ".$row["dob_parish"]);
        		}
      		if ($row["dod_year"] != NULL) {
        		echo (" (d) ".$row["dod_day"]." ".$month_text[$row["dod_mon"]]." ".$row["dod_year"]);
        		}
      		if ($row["dod_place"] != NULL) {
        		echo (" at ".$row["dod_place"]);
        		}
      		if ($row["dod_parish"] != NULL) {
        		echo (" ".$row["dod_parish"]);
        		}
    		// #### ADD CHILD SPOUSE DETAILS

      		echo("</a></li>");
      		}

    	echo("</ul>");
		echo ("<p class=\"itemmainleft\">");
    	echo("</p>");
    	} else {
		// if name not found
    	$ename=ucfirst(strtolower($name1));
		if (strtolower($name2)!="mutch"){
	    	$ename=$ename." ".ucfirst(strtolower($name2));
			}
		if (strtolower($name3)!="mutch"){
	    	$ename=$ename." ".ucfirst(strtolower($name3));
			}
		echo ("<p class=\"itemmainleft\">");
    	echo ("<font class=\"hilite\">Sorry no entries were found for ".htmlentities($ename)." Mutch in the main Mutches database.  Please try another search.</font>");
    	echo("</p>");
    	}
// Look for Mutches in the Others database
	if (strlen($name2)>0 and strtolower($name2)!="mutch"){
  		$sel = "select * from Others where surname='Mutch' and first_name LIKE '" . $name1 . "' and second_name LIKE '".$name2."' order by dob_year";
		}else{
	  	$sel = "select * from Others where surname='Mutch' and first_name LIKE '" . $name1 . "' order by dob_year";
		}
	$result = $conn->query($sel);

  	if ($result->num_rows > 0){
    	echo("<br />");
    	echo("<p class=\"itemmainleft\">");
    	echo("<font class=\"title\">Your search for ".ucfirst(strtolower($name1)));
		if (strtolower($name2)!="mutch"){
	    	echo(" ".ucfirst(strtolower($name2)));
			}
		if (strtolower($name3)!="mutch"){
	    	echo(" ".ucfirst(strtolower($name3)));
			}
		echo(" Mutch");
    	echo(" returned ".$result->num_rows." hits in the supplementary database, select below...</font>");
    	echo("</p>");
    	echo("<ul>");
    	while($row_sup = $result->fetch_assoc()) {
    	// Get the correct Mutch link for child
			$sel = "select * from Unions where spouse_link = ".$row_sup["child_of"]." order by dom_year";
			$result_p = $conn->query($sel);
	    	$row_p = $result_p->fetch_assoc();
       		echo ("<li><a href=\"fl.php?id=".$row_p["mutch_link"]."\">(O - ".$row_sup["id"].") <b>".$row_sup["first_name"]." ".$row_sup["second_name"]." ".$row_sup["third_name"]."</b>");
      		if ($row_sup["dob_year"] != NULL) {
        		echo (" (b) ".$row_sup["dob_day"]." ".$month_text[$row_sup["dob_mon"]]." ".$row_sup["dob_year"]);
        		}
      		if ($row_sup["dob_place"] != NULL) {
        		echo (" at ".$row_sup["dob_place"]);
        		}
      		if ($row_sup["dob_parish"] != NULL) {
        		echo (" ".$row_sup["dob_parish"]);
        		}
      		if ($row_sup["dod_year"] != NULL) {
        		echo (" (d) ".$row_sup["dod_day"]." ".$month_text[$row_sup["dod_mon"]]." ".$row_sup["dod_year"]);
        		}
      		if ($row_sup["dod_place"] != NULL) {
        		echo (" at ".$row_sup["dod_place"]);
        		}
      		if ($row_sup["dod_parish"] != NULL) {
        		echo (" ".$row_sup["dod_parish"]);
        		}
      		echo("</a></li>");
      		}

    	echo("</ul>");
		echo ("<p class=\"itemmainleft\">");
    	echo("</p>");
    	} else {
		// if name not found
    	$ename=ucfirst(strtolower($name1));
		if (strtolower($name2)!="mutch"){
	    	$ename=$ename." ".ucfirst(strtolower($name2));
			}
		if (strtolower($name3)!="mutch"){
	    	$ename=$ename." ".ucfirst(strtolower($name3));
			}
		echo ("<p class=\"itemmainleft\">");
    	echo ("<font class=\"hilite\">Sorry no entries were found for ".htmlentities($ename)." Mutch in the supplementary database.  Please try another search.</font>");
    	echo("</p>");
    	}
//
  	}

// display links to Spouse families if requested
if ($_POST[spouse]  and (strlen($_POST[sname])  > 1)) {
	// split name string
	$sname = split(" ", $_POST[sname], 3);
	$sname1=($sname[0]);
	$sname2=($sname[1]);
	$sname3=($sname[2]);

    include("include/db_connect.php");
//	$conn = new mysqli($host, $user, $pass, $db);

	if (strlen($sname3)>0){
		$sel = "select * from Spouses where surname LIKE '" . $sname3  . "' and second_name LIKE '" . $sname2. "' and first_name LIKE '" . $sname1 . "' order by first_name, second_name";
		}elseif (strlen($sname2)>0){
		$sel = "select * from Spouses where surname LIKE '" . $sname2 . "' and first_name LIKE '" . $sname1 . "' order by first_name";
		}else{
		$sel = "select * from Spouses where surname LIKE '" . $sname1 . "' order by first_name";
		}

	$result = $conn->query($sel);

  	if ($result->num_rows > 0){
    	echo("<br />");
    	echo("<p class=\"itemmainleft\">");
    	echo("<font class=\"title\">Your search for ".ucfirst(strtolower($sname1)));
		if (strlen($sname2)>0){
	    	echo(" ".ucfirst(strtolower($sname2)));
			}
		if (strlen($sname3)>0){
	    	echo(" ".ucfirst(strtolower($sname3)));
			}
    	echo(" returned ".$result->num_rows." hits in the Spouses database, select below...</font>");
    	echo("</p>");
    	echo("<ul>");
    	while ($row = $result->fetch_assoc()) {
			$sel = "select * from Unions where spouse_link = '" . $row[id] . "'";
			$result_u = $conn->query($sel);
			$row_u = $result_u->fetch_assoc();
			$sel = "select * from Mutches where id = '" . $row_u[mutch_link] . "'";
			$result_m = $conn->query($sel);
			$row_m = $result_m->fetch_assoc();
      		if ($row_m["family_line"]) {
        		echo ("<li><a href=\"fl.php?fl=".$row_m["family_line"]."\">(S - ".$row["id"].") <b>".$row["first_name"]." ".$row["second_name"]." ".$row["third_name"]."  ".$row["surname"]." [".strip_zeros($row_m["family_line"])."]</b>");
        		echo("");
        		} else {
        		echo ("<li><a href=\"fl.php?id=".$row_m["id"]."\">(S - ".$row["id"].") <b>".$row["first_name"]." ".$row["second_name"]." ".$row["third_name"]."  ".$row["surname"]."</b>");
        		}
      		if ($row["dob_year"] != NULL) {
        		echo (" (b) ".$row["dob_day"]." ".$month_text[$row["dob_mon"]]." ".$row["dob_year"]);
        		}
      		if ($row["dob_place"] != NULL) {
        		echo (" at ".$row["dob_place"]);
        		}
      		if ($row["dob_parish"] != NULL) {
        		echo (" ".$row["dob_parish"]);
        		}
      		if ($row["dod_year"] != NULL) {
        		echo (" (d) ".$row["dod_day"]." ".$month_text[$row["dod_mon"]]." ".$row["dod_year"]);
        		}
      		if ($row["dob_place"] != NULL) {
        		echo (" at ".$row["dob_place"]);
        		}
      		if ($row["dod_parish"] != NULL) {
        		echo (" ".$row["dod_parish"]);
        		}
      		echo("</a></li>");
      		}

    	echo("</ul>");
		echo ("<p class=\"itemmainleft\">");
    	echo("</p>");
    	} else {
		// if name not found
    	$ename=ucfirst(strtolower($sname1));
		if (strtolower($sname2)){
	    	$ename=$ename." ".ucfirst(strtolower($sname2));
			}
		if (strtolower($name3)){
	    	$ename=$ename." ".ucfirst(strtolower($sname3));
			}
		echo ("<p class=\"itemmainleft\">");
    	echo ("<font class=\"hilite\">Sorry no entries were found for ".$ename." in the Spouses database.  Please try another search.</font>");
    	echo ("</p>");
    	}

  	}

?>
<font class="hide">
<br />
Enter the name of the Mutch you are interested in...
<br />
<br />
<form method="post" action="<?php echo($PHP_SELF)?>">
<!--- <form method="post" action="edit.php"> --->
<font class="title">Mutch Family Member:</font>
<br />
<input type="text" name="mname" size="30" />
<input type="submit" name="mutch" value="Search" class="buttons" />
</form>
<br />
<br />

Enter search details of spouses...
<br />
<br />
<form method="post" action="<?php echo($_SESSION[PHP_SELF])?>">
<!--- <form method="post" action="edit.php"> --->
<font class="title">Spouse's Name:</font>
<br />
<input type="text" name="sname" size="30" />
<input type="submit" name="spouse" value="Search" class="buttons" />
</form>
<br />
<br />

Enter Family Line...
<br />
<br />
<form method="post" action="fl.php">
<font class="title">Family Line:</font>
<br />
<input type="text" name="fl" size="4" />
<input type="submit" value="Show Family" class="buttons" />
</form>

</font>

<?php
include("include/footer.inc");
?>
