<!-- Edit details for an entry in the Unions table based on id.-->
<!-- WORKING -->

<?php
// common variables & header
include("include/common.inc");
require_once('include/db_connector.php');

//$connector = new DbConnector();

if ($_GET['id']) {
  //################
  // get Unions details

  $un_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
//  $mutch_id = filter_input(INPUT_GET, 'mutch',FILTER_SANITIZE_NUMBER_INT);
    
  $sel = "select * from Unions where id = '".$un_id."'";
  $result = $conn->query($sel);
  // return with error_msg if no entry found for family line
  if ($result->num_rows == 0) {
    die("ERROR: no entry found in Unions for id '".$un_id."' - SOMETHING HAS GONE SERIOUSLY WRONG!");
    //TODO find out how to run another file -  exec("choose.php"); ####
    }
  $row_u = $result->fetch_assoc();

  include("include/header.inc");

  //<!-- link back to Family list -->
  echo ("<div id=\"leftcontentmain\">");
  echo ("<a href=\"fl.php?id=".$row_u["mutch_link"]."\">List family</a>");
  echo ("</div>");
//  <!-- main content section -->
  echo ("<div id=\"centercontentmain\">");
  echo ("<br />");
  //<form action=" ">
  //########
  echo ("<form method=\"post\" action=\"".$_SERVER[PHP_SELF]."\">");
  echo ("<input type=\"hidden\" name=\"id\" value=\"".$row_u["id"] ."\">");
  echo ("<b>Enter details for Union ID=".$row_u["id"]."</b><br />");
  echo ("<input type=\"text\" name=\"m_link\" value=\"".$row_u["mutch_link"]."\" size=\"6\" maxlength=\"6\"> ");
  echo ("<input type=\"text\" name=\"s_link\" value=\"".$row_u["spouse_link"]."\" size=\"6\" maxlength=\"6\"> ");
  echo ("<br />");
  echo ("<b>Marriage details:</b><br />");
  echo ("<input type=\"text\" name=\"dom_d\" value=\"".$row_u["dom_day"]."\" size=\"2\" maxlength=\"2\"> ");
  echo ("<input type=\"text\" name=\"dom_m\" value=\"".$row_u["dom_mon"]."\" size=\"2\" maxlength=\"2\"> ");
  echo ("<input type=\"text\" name=\"dom_y\" value=\"".$row_u["dom_year"]."\" size=\"4\" maxlength=\"4\"> - ");
  echo ("<input type=\"text\" name=\"dom_pl\" value=\"".$row_u["dom_place"]."\" size=\"25\" maxlength=\"50\"> - ");
  echo ("<input type=\"text\" name=\"dom_pa\" value=\"".$row_u["dom_parish"]."\" size=\"25\" maxlength=\"50\">");
  echo ("<br />");
  echo ("<b>Alternative details:</b><br />");
  echo ("<input type=\"text\" name=\"alt_d\" value=\"".$row_u["alt_day"]."\" size=\"2\" maxlength=\"2\"> ");
  echo ("<input type=\"text\" name=\"alt_m\" value=\"".$row_u["alt_mon"]."\" size=\"2\" maxlength=\"2\"> ");
  echo ("<input type=\"text\" name=\"alt_y\" value=\"".$row_u["alt_year"]."\" size=\"4\" maxlength=\"4\"> - ");
  echo ("<input type=\"text\" name=\"alt_pl\" value=\"".$row_u["alt_place"]."\" size=\"25\" maxlength=\"50\"> - ");
  echo ("<input type=\"text\" name=\"alt_pa\" value=\"".$row_u["alt_parish"]."\" size=\"25\" maxlength=\"50\">");
  echo ("<br />");
  echo ("<br />");
  echo ("<b>No children:</b>");
  echo ("<input type=\"checkbox\" name=\"no_child\" value=\"Yes\"");
  if ($row_u["no_children"]=="Yes") {
    echo ("checked=\"checked\"> ");
    }else{
    echo ("> ");
    }
  echo ("<br />");
  echo ("<b>Never married:</b>");
  echo ("<input type=\"checkbox\" name=\"no_mar\" value=\"Yes\"");
  if ($row_u["never_married"]=="Yes") {
    echo ("checked=\"checked\"> ");
    }else{
    echo ("> ");
    }
  echo ("<br />");
  echo ("<br />");
  echo ("<b>Note:</b><br />");
  echo ("<textarea name=\"u_note\" cols=60 rows=5 wrap=virtual>".$row_u["note"]."</textarea>");
  echo ("<input type=\"hidden\" name=\"mutch\" value=\"".$row_u["mutch_link"]."\">");
  echo ("<input type=\"hidden\" name=\"op\" value=\"add\">");
  echo ("<p><input type=\"submit\" name=\"submit\" value=\"Update\"></p>");
  echo ("</form>");
  include("include/footer.inc");
  
  }else if ($_POST[op] == "add") {
// *****IF SUBMIT PUSHED*****

//  $safe_note = filter_input(INPUT_POST, 'u_note', FILTER_SANITIZE_STRING);
//  $safe_note = $_POST[u_note];
  $safe_note = $_POST['u_note'];

// UPDATE record
  $stmt = $conn->prepare("update Unions set mutch_link = ?,
    spouse_link = ?,
    dom_day = ?,
    dom_mon = ?,
    dom_year = ?,
    dom_place = ?,
    dom_parish = ?,
    alt_day = ?,
    alt_mon = ?,
    alt_year = ?,
    alt_place = ?,
    alt_parish = ?,
    no_children = ?,
    never_married = ?,
    note = ?
    where id = ?");

// Set blank values to NULL

  // marriage detail fields
  if ($_POST['dom_d'] == 0) {
    $_POST['dom_d'] = NULL;
    }
  if ($_POST[dom_m] == 0) {
    $_POST['dom_m'] = NULL;
    }
  if ($_POST[dom_y] == 0) {
    $_POST['dom_y'] = NULL;
    }
  if ($_POST[dom_pl] == "") {
    $_POST['dom_pl'] = NULL;
    }
  if ($_POST[dom_pa] == "") {
    $_POST['dom_pa'] = NULL;
    }

  // alternate marriage detail fields
  if ($_POST[alt_d] == 0) {
    $_POST['alt_d'] = NULL;
    }
  if ($_POST[alt_m] == 0) {
    $_POST['alt_m'] = NULL;
    }
  if ($_POST[alt_y] == 0) {
    $_POST['alt_y'] = NULL;
    }
  if ($_POST[alt_pl] == "") {
    $_POST['alt_pl'] = NULL;
    }
  if ($_POST[alt_pa] == "") {
    $_POST['alt_pa'] = NULL;
    }

  // other fields
  if ($_POST[no_child] == "") {
    $_POST['no_child'] = NULL;
    }
  if ($_POST[no_mar] == "") {
    $_POST['no_mar'] = NULL;
    }

  $stmt->bind_param('iiiiissiiisssssi',
    $_POST['m_link'],
    $_POST['s_link'],
    $_POST['dom_d'],
    $_POST['dom_m'],
    $_POST['dom_y'],
    $_POST['dom_pl'],
    $_POST['dom_pa'],
    $_POST['alt_d'],
    $_POST['alt_m'],
    $_POST['alt_y'],
    $_POST['alt_pl'],
    $_POST['alt_pa'],
    $_POST['no_child'],
    $_POST['no_mar'],
    $safe_note,
    $_POST['id']);

  $stmt->execute();

  $stmt->close();

  include("include/header.inc");

  //<!-- link back to Family list -->
  
 // $sp_id = filter_input(INPUT_POST, 's_link', FILTER_SANITIZE_NUMBER_INT);
  $mutch_id = filter_input(INPUT_POST, 'mutch', FILTER_SANITIZE_NUMBER_INT);
  $un_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
  echo ("<div id=\"leftcontentmain\">");
// #### TODO If or else use ??????  echo ("<input type=\"hidden\" name=\"mutch\" value=\"".$_GET["mutch"]."\">");
  echo ("<a href=\"fl.php?id=".$mutch_id."\">List family</a>");
  echo ("</div>");
//  <!-- main content section -->
  echo ("<div id=\"centercontentmain\">");
  echo ("<br />");
  echo ("<p><b>UPDATED UNIONS DATABASE FOR RECORD ID = ".$un_id."</b></p>");
// refresh form
  $sel = "select * from Unions where id = '".$un_id."'";
  $result = $conn->query($sel);
  // return with error_msg if no entry found for family line
  if ($result->num_rows == 0) {
    die("ERROR: no entry found in Unions for id '".$un_id."'");
    //TODO find out how to run another file -  exec("choose.php"); ####
    }
  $row_u = $result->fetch_assoc();

  //<form action=" ">
  //########
  echo ("<form method=\"post\" action=\"".$_SERVER[PHP_SELF]."\">");
  echo ("<b>Enter details for Union ID=".$row_u["id"]."</b><br />");
  echo ("<input type=\"hidden\" name=\"id\" value=\"".$row_u["id"]."\">");
  echo ("<input type=\"text\" name=\"m_link\" value=\"".$row_u["mutch_link"]."\" size=\"6\" maxlength=\"6\"> ");
  echo ("<input type=\"text\" name=\"s_link\" value=\"".$row_u["spouse_link"]."\" size=\"6\" maxlength=\"6\"> ");
  echo ("<br />");
  echo ("<b>Marriage details:</b><br />");
  echo ("<input type=\"text\" name=\"dom_d\" value=\"".$row_u["dom_day"]."\" size=\"2\" maxlength=\"2\"> ");
  echo ("<input type=\"text\" name=\"dom_m\" value=\"".$row_u["dom_mon"]."\" size=\"2\" maxlength=\"2\"> ");
  echo ("<input type=\"text\" name=\"dom_y\" value=\"".$row_u["dom_year"]."\" size=\"4\" maxlength=\"4\"> - ");
  echo ("<input type=\"text\" name=\"dom_pl\" value=\"".$row_u["dom_place"]."\" size=\"25\" maxlength=\"50\"> - ");
  echo ("<input type=\"text\" name=\"dom_pa\" value=\"".$row_u["dom_parish"]."\" size=\"25\" maxlength=\"50\">");
  echo ("<br />");
  echo ("<b>Alternative marriage details:</b><br />");
  echo ("<input type=\"text\" name=\"alt_d\" value=\"".$row_u["alt_day"]."\" size=\"2\" maxlength=\"2\"> ");
  echo ("<input type=\"text\" name=\"alt_m\" value=\"".$row_u["alt_mon"]."\" size=\"2\" maxlength=\"2\"> ");
  echo ("<input type=\"text\" name=\"alt_y\" value=\"".$row_u["alt_year"]."\" size=\"4\" maxlength=\"4\"> - ");
  echo ("<input type=\"text\" name=\"alt_pl\" value=\"".$row_u["alt_place"]."\" size=\"25\" maxlength=\"50\"> - ");
  echo ("<input type=\"text\" name=\"alt_pa\" value=\"".$row_u["alt_parish"]."\" size=\"25\" maxlength=\"50\">");
  echo ("<br />");
  echo ("<br />");
  echo ("<b>No children:</b>");
  echo ("<input type=\"checkbox\" name=\"no_child\" value=\"Yes\"");
  if ($row_u["no_children"]=="Yes") {
    echo ("checked=\"checked\"> ");
    }else{
    echo ("> ");
    }
  echo ("<br />");
  echo ("<b>Never married:</b>");
  echo ("<input type=\"checkbox\" name=\"no_mar\" value=\"Yes\"");
  if ($row_u["never_married"]=="Yes") {
    echo ("checked=\"checked\"> ");
    }else{
    echo ("> ");
    }
  echo ("<br />");
  echo ("<br />");
  echo ("<b>Note:</b><br />");
  echo ("<textarea name=\"u_note\" cols=60 rows=5 wrap=virtual>".$row_u["note"]."</textarea>");
  echo ("<input type=\"hidden\" name=\"mutch\" value=\"".$mutch_id."\">");
  echo ("<input type=\"hidden\" name=\"op\" value=\"add\">");
  echo ("<p><input type=\"submit\" name=\"submit\" value=\"Update\"></p>");
  echo ("</form>");
  include("include/footer.inc");
  }

?>
