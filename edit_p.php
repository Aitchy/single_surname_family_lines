<!-- Edit details for an entry from Parents table based on id.-->
<!-- WORKING -->

<?php
// common variables & header
include("include/common.inc");
require_once("include/db_connector.php");

//$connector = new DbConnector();

if ($_GET['id']) {
  //################
  // get Parents details
  
  $par_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
  $mutch_id = filter_input(INPUT_GET, 'mutch', FILTER_SANITIZE_NUMBER_INT);
  
  $sel = "select * from Parents where id = '".$par_id."'";
  $result = $conn->query($sel);

  // return with error_msg if no entry found
  if ($result->num_rows == 0) {
    die("ERROR: no entry found in Parents for id '".$par_id."'");
    //TODO find out how to run another file -  exec("choose.php"); ####
    }
  $row_p = $result->fetch_assoc();

  include("include/header.inc");

  //<!-- link back to Family list -->
  echo ("<div id=\"leftcontentmain\">");
  echo ("<a href=\"fl.php?id=".$mutch_id."\">List family</a>");
  echo ("</div>");
//  <!-- main content section -->
  echo ("<div id=\"centercontentmain\">");
  echo ("<br />");
  //<form action=" ">
  //########
  echo ("<form method=\"post\" action=\"".$_SERVER[PHP_SELF]."\">");
  echo ("<input type=\"hidden\" name=\"id\" value=\"".$row_p["id"] ."\">");
  echo ("<b>Enter details for ID=".$row_p["id"]."</b><br />");
  echo ("Father's details :");
  echo ("<input type=\"text\" name=\"f_f_name\" value=\"".$row_p["f_first_name"]."\" size=\"15\" maxlength=\"30\"> ");
  echo ("<input type=\"text\" name=\"f_s_name\" value=\"".$row_p["f_second_name"]."\" size=\"15\" maxlength=\"30\"> ");
  echo ("<input type=\"text\" name=\"f_surname\" value=\"".$row_p["f_surname"]."\" size=\"20\" maxlength=\"30\"> ");
  echo ("<br />");
  echo ("Mother's details :");
  echo ("<input type=\"text\" name=\"m_f_name\" value=\"".$row_p["m_first_name"]."\" size=\"15\" maxlength=\"30\"> ");
  echo ("<input type=\"text\" name=\"m_s_name\" value=\"".$row_p["m_second_name"]."\" size=\"15\" maxlength=\"30\"> ");
  echo ("<input type=\"text\" name=\"m_surname\" value=\"".$row_p["m_surname"]."\" size=\"20\" maxlength=\"30\"> ");
  echo ("<br />");
  echo ("<b>Marriage details:</b><br />");
  echo ("<input type=\"text\" name=\"dom_d\" value=\"".$row_p["dom_day"]."\" size=\"2\" maxlength=\"2\"> ");
  echo ("<input type=\"text\" name=\"dom_m\" value=\"".$row_p["dom_mon"]."\" size=\"2\" maxlength=\"2\"> ");
  echo ("<input type=\"text\" name=\"dom_y\" value=\"".$row_p["dom_year"]."\" size=\"4\" maxlength=\"4\"> - ");
  echo ("<input type=\"text\" name=\"dom_pl\" value=\"".$row_p["dom_place"]."\" size=\"25\" maxlength=\"50\"> - ");
  echo ("<input type=\"text\" name=\"dom_pa\" value=\"".$row_p["dom_parish"]."\" size=\"25\" maxlength=\"50\">");
  echo ("<br />");
  echo ("<b>Alternative marriage details:</b><br />");
  echo ("<input type=\"text\" name=\"alt_d\" value=\"".$row_p["alt_day"]."\" size=\"2\" maxlength=\"2\"> ");
  echo ("<input type=\"text\" name=\"alt_m\" value=\"".$row_p["alt_mon"]."\" size=\"2\" maxlength=\"2\"> ");
  echo ("<input type=\"text\" name=\"alt_y\" value=\"".$row_p["alt_year"]."\" size=\"4\" maxlength=\"4\"> - ");
  echo ("<input type=\"text\" name=\"alt_pl\" value=\"".$row_p["alt_place"]."\" size=\"25\" maxlength=\"50\"> - ");
  echo ("<input type=\"text\" name=\"alt_pa\" value=\"".$row_p["alt_parish"]."\" size=\"25\" maxlength=\"50\">");
  echo ("<br />");
  echo ("<br />");
  echo ("<b>Never married:</b>");
  echo ("<input type=\"checkbox\" name=\"no_mar\" value=\"Yes\"");
  if ($row_p["never_married"]=="Yes") {
    echo ("checked=\"checked\"> ");
    }else{
    echo ("> ");
    }
  echo ("<br />");
  echo ("<br />");
  echo ("<b>Note:</b><br />");
  echo ("<textarea name=\"p_note\" cols=60 rows=5 wrap=virtual>".$row_p["note"]."</textarea>");
  echo ("<br />");
  echo ("<br />");
  echo ("<b>Source:</b><br />");
  echo ("<input type=\"text\" name=\"p_source\" value=\"".$row_p["source"]."\" size=\"20\" maxlength=\"30\"> ");
  echo ("<input type=\"hidden\" name=\"mutch\" value=\"".$mutch_id."\">");
  echo ("<input type=\"hidden\" name=\"op\" value=\"add\">");
  echo ("<p><input type=\"submit\" name=\"submit\" value=\"Update\"></p>");
  echo ("</form>");
  include("include/footer.inc");


  }else if ($_POST[op] == "add") {
// *****IF SUBMIT PUSHED*****

  $safe_note = filter_input(INPUT_POST, 'p_note', FILTER_SANITIZE_STRING);
//  $safe_note = $_POST[p_note];
//  $safe_note = addslashes($_POST[p_note]);

// UPDATE record
  $stmt = $conn->prepare("update Parents set f_first_name = ?,
    f_second_name = ?,
    f_surname = ?,
    m_first_name = ?,
    m_second_name = ?,
    m_surname = ?,
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
    never_married = ?,
    note = ?,
    source = ?
    where id = ?");

// Set blank values to NULL
// Spouse Parents names
  if ($_POST['f_f_name'] == "") {
    $_POST['f_f_name'] = NULL;
    }
  if ($_POST['f_s_name'] == "") {
    $_POST['f_s_name'] = NULL;
    }
  if ($_POST['f_surname'] == "") {
    $_POST['f_surname'] = NULL;
    }
  if ($_POST['m_f_name'] == "") {
    $_POST['m_f_name'] = NULL;
    }
  if ($_POST['m_s_name'] == "") {
    $_POST['m_s_name'] = NULL;
    }
  if ($_POST['m_surname'] == "") {
    $_POST['m_surname'] = NULL;
    }

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
  if ($_POST[no_mar] == "") {
    $_POST['no_mar'] = NULL;
    }
  if ($_POST[p_source] == "") {
    $_POST['p_source'] = NULL;
    }

  $stmt->bind_param('ssssssiiissiiisssssi',
    $_POST['f_f_name'],
    $_POST['f_s_name'],
    $_POST['f_surname'],
    $_POST['m_f_name'],
    $_POST['m_s_name'],
    $_POST['m_surname'],
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
    $_POST['no_mar'],
    $safe_note,
    $_POST['p_source'],
    $_POST['id']);

    $stmt->execute();

    $stmt->close();

  //<!-- link back to Family list -->

  include("include/header.inc");

  $mutch_id = filter_input(INPUT_POST, 'mutch', FILTER_SANITIZE_NUMBER_INT);
  $par_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
  echo ("<div id=\"leftcontentmain\">");
  echo ("<a href=\"fl.php?id=".$mutch_id."\">List family</a>");
  echo ("</div>");
//  <!-- main content section -->
  echo ("<div id=\"centercontentmain\">");
  echo ("<br />");
  echo ("<p><b>UPDATED PARENTS DATABASE FOR RECORD ID = ".$par_id."</b></p>");
// refresh form
  $sel = "select * from Parents where id = '".$par_id."'";
  $result = $conn->query($sel);
  // return with error_msg if no entry found for family line
  if ($result->num_rows == 0) {
    die("ERROR: no entry found in Parents for id '".$par_id."'");
    //TODO find out how to run another file -  exec("choose.php"); ####
    }
  $row_p = $result->fetch_assoc();

  //<form action=" ">
  //########
  echo ("<form method=\"post\" action=\"".$_SERVER[PHP_SELF]."\">");
  echo ("<input type=\"hidden\" name=\"id\" value=\"".$row_p["id"] ."\">");
  echo ("Father's details :");
  echo ("<input type=\"text\" name=\"f_f_name\" value=\"".$row_p["f_first_name"]."\" size=\"15\" maxlength=\"30\"> ");
  echo ("<input type=\"text\" name=\"f_s_name\" value=\"".$row_p["f_second_name"]."\" size=\"15\" maxlength=\"30\"> ");
  echo ("<input type=\"text\" name=\"f_surname\" value=\"".$row_p["f_surname"]."\" size=\"20\" maxlength=\"30\"> ");
  echo ("<br />");
  echo ("Mother's details :");
  echo ("<input type=\"text\" name=\"m_f_name\" value=\"".$row_p["m_first_name"]."\" size=\"15\" maxlength=\"30\"> ");
  echo ("<input type=\"text\" name=\"m_s_name\" value=\"".$row_p["m_second_name"]."\" size=\"15\" maxlength=\"30\"> ");
  echo ("<input type=\"text\" name=\"m_surname\" value=\"".$row_p["m_surname"]."\" size=\"20\" maxlength=\"30\"> ");
  echo ("<br />");
  echo ("<b>Marriage details:</b><br />");
  echo ("<input type=\"text\" name=\"dom_d\" value=\"".$row_p["dom_day"]."\" size=\"2\" maxlength=\"2\"> ");
  echo ("<input type=\"text\" name=\"dom_m\" value=\"".$row_p["dom_mon"]."\" size=\"2\" maxlength=\"2\"> ");
  echo ("<input type=\"text\" name=\"dom_y\" value=\"".$row_p["dom_year"]."\" size=\"4\" maxlength=\"4\"> - ");
  echo ("<input type=\"text\" name=\"dom_pl\" value=\"".$row_p["dom_place"]."\" size=\"25\" maxlength=\"50\"> - ");
  echo ("<input type=\"text\" name=\"dom_pa\" value=\"".$row_p["dom_parish"]."\" size=\"25\" maxlength=\"50\">");
  echo ("<br />");
  echo ("<b>Alternative marriage details:</b><br />");
  echo ("<input type=\"text\" name=\"alt_d\" value=\"".$row_p["alt_day"]."\" size=\"2\" maxlength=\"2\"> ");
  echo ("<input type=\"text\" name=\"alt_m\" value=\"".$row_p["alt_mon"]."\" size=\"2\" maxlength=\"2\"> ");
  echo ("<input type=\"text\" name=\"alt_y\" value=\"".$row_p["alt_year"]."\" size=\"4\" maxlength=\"4\"> - ");
  echo ("<input type=\"text\" name=\"alt_pl\" value=\"".$row_p["alt_place"]."\" size=\"25\" maxlength=\"50\"> - ");
  echo ("<input type=\"text\" name=\"alt_pa\" value=\"".$row_p["alt_parish"]."\" size=\"25\" maxlength=\"50\">");
  echo ("<br />");
  echo ("<br />");
  echo ("<b>Never married:</b>");
  echo ("<input type=\"checkbox\" name=\"no_mar\" value=\"Yes\"");
  if ($row_p["never_married"]=="Yes") {
    echo ("checked=\"checked\"> ");
    }else{
    echo ("> ");
    }
  echo ("<br />");
  echo ("<br />");
  echo ("<b>Note:</b><br />");
  echo ("<textarea name=\"p_note\" cols=60 rows=5 wrap=virtual>".$row_p["note"]."</textarea>");
  echo ("<br />");
  echo ("<br />");
  echo ("<b>Source:</b><br />");
  echo ("<input type=\"text\" name=\"p_source\" value=\"".$row_p["source"]."\" size=\"20\" maxlength=\"30\"> ");
  echo ("<input type=\"hidden\" name=\"mutch\" value=\"".$mutch_id."\">");
  echo ("<input type=\"hidden\" name=\"op\" value=\"add\">");
  echo ("<p><input type=\"submit\" name=\"submit\" value=\"Update\"></p>");
  echo ("</form>");
  include("include/footer.inc");

  }

?>
