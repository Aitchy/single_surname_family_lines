<!-- Edit details for a family line note.-->
<!-- WORKING -->

<?php
// common variables & header
include("include/common.inc");
require_once('include/db_connector.php');

//$connector = new DbConnector();

if ($_GET['fln']) {
  //################
  // get family line Note details
  $fl_nn = filter_input(INPUT_GET, 'fln', FILTER_SANITIZE_NUMBER_INT);
  $mutch_fl = filter_input(INPUT_GET, 'fl', FILTER_SANITIZE_STRING);

  $sel = "select * from FamilyNotes where id = '".$fl_nn."'";
  $result = $conn->query($sel);
  // return with error_msg if no entry found for family line
  if ($result->num_rows == 0) {
    die("ERROR: no entry found in FamilyNotes for ID '".$fl_nn."'");
    //TODO find out how to run another file -  exec("choose.php"); ####
    }
  $row_fln = $result->fetch_assoc();

  include("include/header.inc");

  //<!-- link back to Family list -->
  echo ("<div id=\"leftcontentmain\">");
  echo ("<a href=\"fl.php?fl=".$mutch_fl."\">List family</a>");
  echo ("</div>");
//  <!-- main content section -->
  echo ("<div id=\"centercontentmain\">");
  echo ("<br />");
  // MODIFY DETAILS FOR NOTE
  echo ("<form method=\"post\" action=\"".$_SERVER[PHP_SELF]."\">");
  echo ("<input type=\"hidden\" name=\"fln\" value=\"".$row_fln["id"] ."\">");
  echo ("<input type=\"hidden\" name=\"fl\" value=\"".$row_fln["fl_code"] ."\">");
  echo ("<b>Modify details for note ID = ".$row_fln["id"]." for Family Line = ".$row_fln["fl_code"]."</b><br />");
  echo ("<br />");
  echo ("<input type=\"text\" name=\"dis_ord\" value=\"".$row_fln["display_order"]."\" size=\"2\" maxlength=\"2\"> ");
  echo ("<br />");
  echo ("<br />");
  echo ("<b>Note:</b><br />");
  echo ("<textarea name=\"fl_note\" cols=60 rows=5 wrap=virtual>".$row_fln["note"]."</textarea>");
  echo ("<br />");
  echo ("<br />");
  echo ("<b>Source:</b><br />");
  echo ("<input type=\"text\" name=\"fl_source\" value=\"".$row_fln["source"]."\" size=\"20\" maxlength=\"50\"> ");
  echo ("<br />");
  echo ("<br />");
  echo ("<b>Date added:</b><br />");
  echo ("<input type=\"text\" name=\"add\" value=\"".$row_fln["date_entered"]."\" size=\"10\" maxlength=\"10\"> ");
  echo ("<input type=\"hidden\" name=\"op\" value=\"add\">");
  echo ("<p><input type=\"submit\" name=\"submit\" value=\"Update\"></p>");
  echo ("</form>");
  include("include/footer.inc");
  }else if ($_POST[op] == "add") {
// *****IF SUBMIT PUSHED*****

//  $safe_note = filter_input(INPUT_POST, 'fl_note', FILTER_UNSAFE_RAW);
//  $safe_note = addslashes($_POST[fl_note]);
  $safe_note = $_POST['fl_note'];

// UPDATE record
  $stmt = $conn->prepare("update FamilyNotes set display_order = ?,
    note = ?,
    date_entered = ?,
    source = ?
    where id = ?");

// Set blank values to NULL
  if ($_POST['dis_ord'] == 0) {
    $_POST['dis_ord'] = NULL;
    }
  if ($_POST['fl_source'] == "") {
    $_POST['fl_source'] = NULL;
    }
  if ($_POST['add'] == 0000-00-00) {
    $_POST['add'] = NULL;
    }

  $stmt->bind_param('isssi',
    $_POST['dis_ord'],
    stripcslashes($safe_note),
    $_POST['add'],
    $_POST['fl_source'],
    $_POST['fln']);

  $stmt->execute();

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

  include("include/header.inc");

  $fl_nn = filter_input(INPUT_POST, 'fln', FILTER_SANITIZE_NUMBER_INT);
  $mutch_fl = filter_input(INPUT_POST, 'fl', FILTER_SANITIZE_STRING);
  //<!-- link back to Family list -->
  echo ("<div id=\"leftcontentmain\">");
// #### TODO If or else use ??????  echo ("<input type=\"hidden\" name=\"mutch\" value=\"".$_GET["mutch"]."\">");
  echo ("<a href=\"fl.php?fl=".$mutch_fl."\">List family</a>");
  echo ("</div>");
//  <!-- main content section -->
  echo ("<div id=\"centercontentmain\">");
  echo ("<br />");
  echo ("<p><b>UPDATED NOTE ID = ".$fl_nn." FOR FAMILY LINE ".$mutch_fl."</b></p>");
// refresh form
  $sel = "select * from FamilyNotes where id = '".$fl_nn."'";
  $result = $conn->query($sel);
  // return with error_msg if no entry found for family line
  if ($result->num_rows == 0) {
    die("ERROR: no entry found in FamilyNotes for id '".$fl_nn."'");
    //TODO find out how to run another file -  exec("choose.php"); ####
    }
  $row_fln = $result->fetch_assoc();

  //update form if required
  echo ("<form method=\"post\" action=\"".$_SERVER[PHP_SELF]."\">");
  echo ("<input type=\"hidden\" name=\"fln\" value=\"".$row_fln["id"] ."\">");
  echo ("<input type=\"hidden\" name=\"fl\" value=\"".$row_fln["fl_code"] ."\">");
//  echo ("<b>Modify details for note ID = ".$row_fln["id"]." for Family Line = ".$row_fln["fl_code"]."</b><br />");
  echo ("<br />");
  echo ("<input type=\"text\" name=\"dis_ord\" value=\"".$row_fln["display_order"]."\" size=\"2\" maxlength=\"2\"> ");
  echo ("<br />");
  echo ("<br />");
  echo ("<b>Note:</b><br />");
  echo ("<textarea name=\"fl_note\" cols=60 rows=5 wrap=virtual>".$row_fln["note"]."</textarea>");
  echo ("<br />");
  echo ("<br />");
  echo ("<b>Source:</b><br />");
  echo ("<input type=\"text\" name=\"fl_source\" value=\"".$row_fln["source"]."\" size=\"20\" maxlength=\"50\"> ");
  echo ("<br />");
  echo ("<br />");
  echo ("<b>Date added:</b><br />");
  echo ("<input type=\"text\" name=\"add\" value=\"".$row_fln["date_entered"]."\" size=\"10\" maxlength=\"10\"> ");
  echo ("<input type=\"hidden\" name=\"op\" value=\"add\">");
  echo ("<p><input type=\"submit\" name=\"submit\" value=\"Update\"></p>");
  echo ("</form>");
  include("include/footer.inc");
  }

?>
