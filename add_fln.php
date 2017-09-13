<!-- Add a note to a Family Line entry.-->
<!-- WORKING -->

<?php
// common variables & header
include("include/common.inc");
require_once('include/db_connector.php');

//$connector = new DbConnector();

if ($_GET["fl"]) {
  //################
  // get Family Line code to add Note to 
  $mutch_fl = filter_input(INPUT_GET, 'fl', FILTER_SANITIZE_STRING);

  include("include/header.inc");

  //<!-- link back to Family list -->
  echo ("<div id=\"leftcontentmain\">");
  echo ("<a href=\"fl.php?fl=".$mutch_fl."\">List family</a>");
  echo ("</div>");
//  <!-- main content section -->
  echo ("<div id=\"centercontentmain\">");
  echo ("<br />");
// count existing notes
  $sel = "select * from FamilyNotes where fl_code = '".$mutch_fl."' order by display_order";
  $result = $conn->query($sel);
  // return with error_msg if no entry found for family line
  if ($result->num_rows == 0) {
    die("ERROR: no entry found in FamilyNotes for Family Line '".$mutch_fl."'");
    //TODO find out how to run another file -  exec("choose.php"); ####
    }
//  $result_fln = mysql_fetch_array($result);

// Calculate the new note display order #
  while ($row= $result->fetch_assoc()) {
  	$new_do = 1 + $new_do;
  }
  $new_do = 1 + $new_do;

  //<form action=" ">
  //########
  echo ("<form method=\"post\" action=\"".$_SERVER[PHP_SELF]."\">");
  echo ("<b>Enter details for note #".$new_do." for Family Line ".$mutch_fl."</b><br />");
  echo ("<input type=\"hidden\" name=\"dis_ord\" value=\"".$new_do."\">");
  echo ("<input type=\"hidden\" name=\"fl\" value=\"".$mutch_fl."\">");
  echo ("<br />");
  echo ("<br />");
  echo ("<b>Note:</b><br />");
  echo ("<textarea name=\"fl_note\" cols=60 rows=5 wrap=virtual></textarea>");
  echo ("<br />");
  echo ("<br />");
  echo ("<b>Source:</b><br />");
  echo ("<input type=\"text\" name=\"fl_source\" size=\"20\" maxlength=\"50\"> ");
  echo ("<br />");
  echo ("<br />");
  echo ("<input type=\"hidden\" name=\"op\" value=\"add\">");
  echo ("<p><input type=\"submit\" name=\"submit\" value=\"Update\"></p>");
  echo ("</form>");
  include("include/footer.inc");
  }else if ($_POST[op] == "add") {
// *****IF SUBMIT PUSHED*****

//  $safe_note = filter_input(INPUT_POST, 'fl_note', FILTER_UNSAFE_RAW);
//  $safe_note = addslashes($_POST[fl_note]);
  $safe_note = $_POST['fl_note'];

// CREATE record
  $stmt = $conn->prepare("INSERT INTO FamilyNotes (fl_code, 
    display_order,
    note,
    date_entered,
    source) VALUES (?,?,?,?,?)");

  $to_day = date("Y-m-d");

  $stmt->bind_param('sisss',
    $_POST['fl'],
    $_POST['dis_ord'],
    stripcslashes($safe_note),
    $to_day,
    $_POST['fl_source']);

  $stmt->execute();

 // Get the new records id
  $new_id = $stmt->insert_id;

  $stmt->close();

// UPDATE Family Line record
  $up_stmt = $conn->prepare("update FamilyLines set updated = ?
    where fl_code = ?");
  
  $to_day = date("Y-m-d");

  $up_stmt->bind_param('ss',
    $to_day,
    $_POST['fl']);

  $up_stmt->execute();

  $up_stmt->close();

  $sel = "select * from FamilyNotes where id = ".$new_id."";
  $result = $conn->query($sel);
  // return with error_msg if no entry found for family line
  if ($result->num_rows == 0) {
    die("ERROR: no entry found in FamilyNotes for ID ".$new_id."");
    //TODO find out how to run another file -  exec("choose.php"); ####
    }
  $row_fln = $result->fetch_assoc();

  include("include/header.inc");

  //<!-- link back to Family list -->
  echo ("<div id=\"leftcontentmain\">");
  echo ("<a href=\"fl.php?fl=".$row_fln["fl_code"]."\">List family</a>");
  echo ("<br />");
  echo ("<br />");
  echo ("<a href=\"edit_fln.php?fln=".$row_fln["id"]."&fl=".$row_fln["fl_code"]."\">Modify entry</a>");
  echo ("</div>");
//  <!-- main content section -->
  echo ("<div id=\"centercontentmain\">");
  echo ("<br />");
  echo ("<p><b>ADDED NOTE ID = ".$new_id." TO FAMILYNOTES TABLE</b></p>");
// refresh form
  //<form action=" ">
  //########
  echo ("<b>Details entrered:</b><br />");
  echo ("Family Line = ".$row_fln["fl_code"]."<br />");
  echo ("Display ordered = ".$row_fln["display_order"]."<br />");
  echo ("<br />");
  echo ("<br />");
  echo ("<b>Note:</b><br />");
  echo ($row_fln["note"]);
  echo ("<br />");
  echo ("<b>Entered: ".date("l, jS F Y", strtotime($row_fln["date_entered"]))."</b><br />");
  echo ("<br />");
  echo ("Source = ".$row_fln["source"]."<br />");
  include("include/footer.inc");
  }

?>
