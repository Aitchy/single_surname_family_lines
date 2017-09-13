<!-- Edit details for an entry in the Family Line table based on FL Code.-->
<!-- WORKING -->

<?php
// common variables & header
include("include/common.inc");
require_once('include/db_connector.php');

// $connector = new DbConnector();

if ($_GET['fl']) {
  //################
  // get Family Line details

  $mutch_fl = filter_input(INPUT_GET, 'fl', FILTER_SANITIZE_STRING);

  $sel = "select * from FamilyLines where fl_code = '".$mutch_fl."'";
  $result = $conn->query($sel);
  // return with error_msg if no entry found for family line
  if ($result->num_rows == 0) {
    die("ERROR: no entry found in FamilyLines for Family Line '".$mutch_fl."'");
    //TODO find out how to run another file -  exec("choose.php"); ####
    }
  $row_fl = $result->fetch_assoc();

  include("include/header.inc");

  //<!-- link back to Family list -->
  echo ("<div id=\"leftcontentmain\">");
  echo ("<a href=\"fl.php?fl=".$mutch_fl."\">List family</a>");
  echo ("</div>");
//  <!-- main content section -->
  echo ("<div id=\"centercontentmain\">");
  echo ("<br />");
  // MODIFY DETAILS FOR FAMILY LINE
  echo ("<form method=\"post\" action=\"".$_SERVER[PHP_SELF]."\">");
  echo ("<input type=\"hidden\" name=\"fl\" value=\"".$row_fl["fl_code"] ."\">");
  echo ("<b>Enter details for Family Line = ".$row_fl["fl_code"]."</b><br />");
  echo ("<br />");
  echo ("<b>Confirmed?:</b>");
  echo ("<input type=\"checkbox\" name=\"conf\" value=\"Yes\"");
  if ($row_fl["confirmed"]=="Yes") {
    echo ("checked=\"checked\"> ");
    }else{
    echo ("> ");
    }
  echo ("<br />");
  echo ("<br />");
  echo ("<b>Date added:</b><br />");
  echo ("<input type=\"text\" name=\"add\" value=\"".$row_fl["added"]."\" size=\"10\" maxlength=\"10\"> ");
  echo ("<br />");
  echo ("<br />");
  echo ("<b>This Family Line was last modified: ".date("l, jS F Y", strtotime($row_fl["updated"]))."</b><br />");
//  echo ("<input type=\"hidden\" name=\"mod\" value=\"".$row_fl["updated"] ."\">");
  echo ("<input type=\"hidden\" name=\"op\" value=\"add\">");
  echo ("<p><input type=\"submit\" name=\"submit\" value=\"Update\"></p>");
  echo ("</form>");
  include("include/footer.inc");
  }else if ($_POST[op] == "add") {
// *****IF SUBMIT PUSHED*****

  $stmt = $conn->prepare("update FamilyLines set confirmed = ?,
    added = ?,
    updated = ?
    where fl_code = ?");

// Set blank values to NULL
  if ($_POST['add'] == 0000-00-00) {
    $_POST['add'] = NULL;
    }
  if ($_POST['confirmed'] == "") {
    $_POST['confirmed'] = NULL;
    }
  
  $to_day = date("Y-m-d");
  
// ###CHECK THIS CORRECT
  $stmt->bind_param('ssss',
    $_POST['conf'], 
    $_POST['add'],
    $to_day,
    $_POST['fl']);

  $stmt->execute();

  $stmt->close();

  include("include/header.inc");

  $mutch_fl = filter_input(INPUT_POST, 'fl', FILTER_SANITIZE_STRING);

  //<!-- link back to Family list -->
  echo ("<div id=\"leftcontentmain\">");
// #### TODO If or else use ??????  echo ("<input type=\"hidden\" name=\"mutch\" value=\"".$_GET["mutch"]."\">");
  echo ("<a href=\"fl.php?fl=".$mutch_fl."\">List family</a>");
  echo ("</div>");
//  <!-- main content section -->
  echo ("<div id=\"centercontentmain\">");
  echo ("<br />");
  echo ("<p><b>UPDATED FAMILY LINES DATABASE FOR ".$mutch_fl."</b></p>");
// refresh form
  $sel = "select * from FamilyLines where fl_code = '".$mutch_fl."'";
  $result = $conn->query($sel);
  // return with error_msg if no entry found for family line
  if ($result->num_rows == 0) {
    die("ERROR: no entry found in FamilyLines for Family Line '".$mutch_fl."'");
    //TODO find out how to run another file -  exec("choose.php"); ####
    }
  $row_fl = $result->fetch_assoc();

  //update form if required
  echo ("<form method=\"post\" action=\"".$_SERVER[PHP_SELF]."\">");
  echo ("<input type=\"hidden\" name=\"fl\" value=\"".$row_fl["fl_code"] ."\">");
  echo ("<b>Enter details for Family Line = ".$row_fl["fl_code"]."</b><br />");
  echo ("<br />");
  echo ("<b>Confirmed?:</b>");
  echo ("<input type=\"checkbox\" name=\"conf\" value=\"Yes\"");
  if ($row_fl["confirmed"]=="Yes") {
    echo ("checked=\"checked\"> ");
    }else{
    echo ("> ");
    }
  echo ("<br />");
  echo ("<br />");
  echo ("<b>Date added:</b><br />");
  echo ("<input type=\"text\" name=\"add\" value=\"".$row_fl["added"]."\" size=\"10\" maxlength=\"10\"> ");
  echo ("<br />");
  echo ("<br />");
  echo ("<b>This Family Line was last modified: ".date("l, jS F Y", strtotime($row_fl["updated"]))."</b><br />");
//  echo ("<input type=\"hidden\" name=\"mod\" value=\"".$row_fl["updated"] ."\">");
  echo ("<input type=\"hidden\" name=\"op\" value=\"add\">");
  echo ("<p><input type=\"submit\" name=\"submit\" value=\"Update\"></p>");
  echo ("</form>");
  include("include/footer.inc");
  }

?>
