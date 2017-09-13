<!-- Edit details for a Mutch entry from Mutches table based on id.-->
<!-- WORKING -->

<?php
// common variables & header
include("include/common.inc");
require_once('include/db_connector.php');

//$connector = new DbConnector();

if ($_GET['id']) {
  //################
  // get Mutches details
  $un_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
  $sel = "select * from Mutches where id = '".$un_id."'";
  $result = $conn->query($sel);
  // return with error_msg if no entry found for family line
  if ($result->num_rows == 0) {
    die("ERROR: no entry found in Mutches for id '".$un_id."'");
    //TODO find out how to run another file -  exec("choose.php"); ####
    }
  $row_m = $result->fetch_assoc();

  include("include/header.inc");

  //<!-- link back to Family list -->
  echo ("<div id=\"leftcontentmain\">");
  echo ("<a href=\"fl.php?id=".$row_m["id"]."\">List family</a>");
  echo ("</div>");
//  <!-- main content section -->
  echo ("<div id=\"centercontentmain\">");
  echo ("<br />");
  //<form action=" ">
  //########
  echo ("<form method=\"post\" action=\"".$_SERVER[PHP_SELF]."\">");
  echo ("<input type=\"hidden\" name=\"id\" value=\"".$row_m["id"] ."\">");
  echo ("<b>Enter details for ID=".$row_m["id"]."</b><br />");
  echo ("<input type=\"text\" name=\"f_name\" value=\"".$row_m["first_name"]."\" size=\"15\" maxlength=\"30\"> ");
  echo ("<input type=\"text\" name=\"s_name\" value=\"".$row_m["second_name"]."\" size=\"15\" maxlength=\"30\"> ");
  echo ("<input type=\"text\" name=\"t_name\" value=\"".$row_m["third_name"]."\" size=\"15\" maxlength=\"30\"> ");
  echo ("<br />");
  echo ("m<input type=\"radio\" name=\"sex\" value=\"m\" ");
  if ($row_m["sex"]=="m") {
    echo ("checked=\"checked\"> ");
    }else{
    echo ("> ");
    }
  echo ("f<input type=\"radio\" name=\"sex\" value=\"f\" ");
  if ($row_m["sex"]=="f") {
    echo ("checked=\"checked\"> ");
    }else{
    echo ("> ");
    }
  echo ("Family: <input type=\"text\" name=\"f_line\" value=\"".$row_m["family_line"]."\" size=\"4\" maxlength=\"4\"> ");
  echo ("Child of:<input type=\"text\" name=\"c_of\" value=\"".$row_m["child_of"]."\" size=\"6\" maxlength=\"6\"> ");
  echo ("<br />");
  echo ("<b>Birth details:</b><br />");
  echo ("<input type=\"text\" name=\"dob_d\" value=\"".$row_m["dob_day"]."\" size=\"2\" maxlength=\"2\"> ");
  echo ("<input type=\"text\" name=\"dob_m\" value=\"".$row_m["dob_mon"]."\" size=\"2\" maxlength=\"2\"> ");
  echo ("<input type=\"text\" name=\"dob_y\" value=\"".$row_m["dob_year"]."\" size=\"4\" maxlength=\"4\"> - ");
  echo ("<input type=\"text\" name=\"dob_pl\" value=\"".$row_m["dob_place"]."\" size=\"25\" maxlength=\"50\"> - ");
  echo ("<input type=\"text\" name=\"dob_pa\" value=\"".$row_m["dob_parish"]."\" size=\"25\" maxlength=\"50\">");
  echo ("<br />");
  echo ("<b>Christening details:</b><br />");
  echo ("<input type=\"text\" name=\"doc_d\" value=\"".$row_m["doc_day"]."\" size=\"2\" maxlength=\"2\"> ");
  echo ("<input type=\"text\" name=\"doc_m\" value=\"".$row_m["doc_mon"]."\" size=\"2\" maxlength=\"2\"> ");
  echo ("<input type=\"text\" name=\"doc_y\" value=\"".$row_m["doc_year"]."\" size=\"4\" maxlength=\"4\"> - ");
  echo ("<input type=\"text\" name=\"doc_pl\" value=\"".$row_m["doc_place"]."\" size=\"25\" maxlength=\"50\"> - ");
  echo ("<input type=\"text\" name=\"doc_pa\" value=\"".$row_m["doc_parish"]."\" size=\"25\" maxlength=\"50\">");
  echo ("<br />");
  echo ("<b>Death details:</b><br />");
  echo ("<input type=\"text\" name=\"dod_d\" value=\"".$row_m["dod_day"]."\" size=\"2\" maxlength=\"2\"> ");
  echo ("<input type=\"text\" name=\"dod_m\" value=\"".$row_m["dod_mon"]."\" size=\"2\" maxlength=\"2\"> ");
  echo ("<input type=\"text\" name=\"dod_y\" value=\"".$row_m["dod_year"]."\" size=\"4\" maxlength=\"4\"> - ");
  echo ("<input type=\"text\" name=\"dod_pl\" value=\"".$row_m["dod_place"]."\" size=\"25\" maxlength=\"50\"> - ");
  echo ("<input type=\"text\" name=\"dod_pa\" value=\"".$row_m["dod_parish"]."\" size=\"25\" maxlength=\"50\">");
  echo ("<br />");
  echo ("<br />");
  echo ("<b>No children:</b>");
  echo ("<input type=\"checkbox\" name=\"no_child\" value=\"Yes\"");
  if ($row_m["no_children"]=="Yes") {
    echo ("checked=\"checked\"> ");
    }else{
    echo ("> ");
    }
  echo ("<br />");
  echo ("<b>Never married:</b>");
  echo ("<input type=\"checkbox\" name=\"no_mar\" value=\"Yes\"");
  if ($row_m["never_married"]=="Yes") {
    echo ("checked=\"checked\"> ");
    }else{
    echo ("> ");
    }
  echo ("<input type=\"hidden\" name=\"op\" value=\"add\">");
  echo ("<p><input type=\"submit\" name=\"submit\" value=\"Update\"></p>");
  echo ("</form>");
  include("include/footer.inc");
  }else if ($_POST[op] == "add") {
// *****IF SUBMIT PUSHED*****

// UPDATE record
  $stmt = $conn->prepare("update Mutches set first_name = ?,
    second_name = ?,
    third_name = ?,
    sex = ?,
    family_line = ?,
    child_of = ?,
    dob_day = ?,
    dob_mon = ?,
    dob_year = ?,
    dob_place = ?,
    dob_parish = ?,
    doc_day = ?,
    doc_mon = ?,
    doc_year = ?,
    doc_place = ?,
    doc_parish = ?,
    dod_day = ?,
    dod_mon = ?,
    dod_year = ?,
    dod_place = ?,
    dod_parish = ?,
    no_children = ?,
    never_married = ?
    where id = ?");

// Set blank values to NULL
  if ($_POST['f_name'] == "") {
    $_POST['f_name'] = NULL;
    }
  if ($_POST['s_name'] == "") {
    $_POST['s_name'] = NULL;
    }
  if ($_POST['t_name'] == "") {
    $_POST['t_name'] = NULL;
    }
  if ($_POST['sex'] == "") {
    $_POST['sex'] = NULL;
    }
  if ($_POST['f_line'] == "") {
    $_POST['f_line'] = NULL;
    }
  if ($_POST['c_of'] == 0) {
    $_POST['c_of'] = NULL;
    }
  // birth detail fields
  if ($_POST['dob_d'] == 0) {
    $_POST['dob_d'] = NULL;
    }
  if ($_POST['dob_m'] == 0) {
    $_POST['dob_m'] = NULL;
    }
  if ($_POST['dob_y'] == 0) {
    $_POST['dob_y'] = NULL;
    }
  if ($_POST['dob_pl'] == "") {
    $_POST['dob_pl'] = NULL;
    }
  if ($_POST['dob_pa'] == "") {
    $_POST['dob_pa'] = NULL;
    }
  // christening detail fields
  if ($_POST['doc_d'] == 0) {
    $_POST['doc_d'] = NULL;
    }
  if ($_POST['doc_m'] == 0) {
    $_POST['doc_m'] = NULL;
    }
  if ($_POST['doc_y'] == 0) {
    $_POST['doc_y'] = NULL;
    }
  if ($_POST['doc_pl'] == "") {
    $_POST['doc_pl'] = NULL;
    }
  if ($_POST['doc_pa'] == "") {
    $_POST['doc_pa'] = NULL;
    }
  // death detail fields
  if ($_POST['dod_d'] == 0) {
    $_POST['dod_d'] = NULL;
    }
  if ($_POST['dod_m'] == 0) {
    $_POST['dod_m'] = NULL;
    }
  if ($_POST['dod_y'] == 0) {
    $_POST['dod_y'] = NULL;
    }
  if ($_POST['dod_pl'] == "") {
    $_POST['dod_pl'] = NULL;
    }
  if ($_POST['dod_pa'] == "") {
    $_POST['dod_pa'] = NULL;
    }

  // other fields
  if ($_POST[no_child] == "") {
    $_POST['no_child'] = NULL;
    }
  if ($_POST[no_mar] == "") {
    $_POST['no_mar'] = NULL;
    }

// UPDATE record
  $stmt->bind_param('sssssiiiissiiissiiissssi',
    $_POST['f_name'],
    $_POST['s_name'],
    $_POST['t_name'],
    $_POST['sex'],
    $_POST['f_line'],
    $_POST['c_of'],
    $_POST['dob_d'],
    $_POST['dob_m'],
    $_POST['dob_y'],
    $_POST['dob_pl'],
    $_POST['dob_pa'],
    $_POST['doc_d'],
    $_POST['doc_m'],
    $_POST['doc_y'],
    $_POST['doc_pl'],
    $_POST['doc_pa'],
    $_POST['dod_d'],
    $_POST['dod_m'],
    $_POST['dod_y'],
    $_POST['dod_pl'],
    $_POST['dod_pa'],
    $_POST['no_child'],
    $_POST['no_mar'],
    $_POST['id']);

  $stmt->execute();

  $stmt->close();

  include("include/header.inc");

  $un_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
  //<!-- link back to Family list -->
  echo ("<div id=\"leftcontentmain\">");
  echo ("<a href=\"fl.php?id=".$un_id."\">List family</a>");
  echo ("</div>");
//  <!-- main content section -->
  echo ("<div id=\"centercontentmain\">");
  echo ("<br />");
  echo ("<p><b>UPDATED MUTCHES DATABASE FOR RECORD ID = ".$un_id."</b></p>");
// refresh form
  $sel = "select * from Mutches where id = '".$un_id."'";
  $result = $conn->query($sel);
  // return with error_msg if no entry found for family line
  if ($result->num_rows == 0) {
    die("ERROR: no entry found in Mutches for id '".$un_id."'");
    //TODO find out how to run another file -  exec("choose.php"); ####
    }
  $row_m = $result->fetch_assoc();

  //<form action=" ">
  //########
  echo ("<form method=\"post\" action=\"".$_SERVER[PHP_SELF]."\">");
  echo ("<input type=\"hidden\" name=\"id\" value=\"".$row_m["id"] ."\">");
  echo ("<b>Enter details for ID=".$row_m["id"]."</b><br />");
  echo ("<input type=\"text\" name=\"f_name\" value=\"".$row_m["first_name"]."\" size=\"15\" maxlength=\"30\"> ");
  echo ("<input type=\"text\" name=\"s_name\" value=\"".$row_m["second_name"]."\" size=\"15\" maxlength=\"30\"> ");
  echo ("<input type=\"text\" name=\"t_name\" value=\"".$row_m["third_name"]."\" size=\"15\" maxlength=\"30\"> ");
  echo ("m<input type=\"radio\" name=\"sex\" value=\"m\" ");
  if ($row_m["sex"]=="m") {
    echo ("checked=\"checked\"> ");
    }else{
    echo ("> ");
    }
  echo ("f<input type=\"radio\" name=\"sex\" value=\"f\" ");
  if ($row_m["sex"]=="f") {
    echo ("checked=\"checked\"> ");
    }else{
    echo ("> ");
    }
  echo ("Family: <input type=\"text\" name=\"f_line\" value=\"".$row_m["family_line"]."\" size=\"4\" maxlength=\"4\"> ");
  echo ("Child of:<input type=\"text\" name=\"c_of\" value=\"".$row_m["child_of"]."\" size=\"6\" maxlength=\"6\"> ");
  echo ("<br />");
  echo ("<b>Birth details:</b><br />");
  echo ("<input type=\"text\" name=\"dob_d\" value=\"".$row_m["dob_day"]."\" size=\"2\" maxlength=\"2\"> ");
  echo ("<input type=\"text\" name=\"dob_m\" value=\"".$row_m["dob_mon"]."\" size=\"2\" maxlength=\"2\"> ");
  echo ("<input type=\"text\" name=\"dob_y\" value=\"".$row_m["dob_year"]."\" size=\"4\" maxlength=\"4\"> - ");
  echo ("<input type=\"text\" name=\"dob_pl\" value=\"".$row_m["dob_place"]."\" size=\"25\" maxlength=\"50\"> - ");
  echo ("<input type=\"text\" name=\"dob_pa\" value=\"".$row_m["dob_parish"]."\" size=\"25\" maxlength=\"50\">");
  echo ("<br />");
  echo ("<b>Christening details:</b><br />");
  echo ("<input type=\"text\" name=\"doc_d\" value=\"".$row_m["doc_day"]."\" size=\"2\" maxlength=\"2\"> ");
  echo ("<input type=\"text\" name=\"doc_m\" value=\"".$row_m["doc_mon"]."\" size=\"2\" maxlength=\"2\"> ");
  echo ("<input type=\"text\" name=\"doc_y\" value=\"".$row_m["doc_year"]."\" size=\"4\" maxlength=\"4\"> - ");
  echo ("<input type=\"text\" name=\"doc_pl\" value=\"".$row_m["doc_place"]."\" size=\"25\" maxlength=\"50\"> - ");
  echo ("<input type=\"text\" name=\"doc_pa\" value=\"".$row_m["doc_parish"]."\" size=\"25\" maxlength=\"50\">");
  echo ("<br />");
  echo ("<b>Death details:</b><br />");
  echo ("<input type=\"text\" name=\"dod_d\" value=\"".$row_m["dod_day"]."\" size=\"2\" maxlength=\"2\"> ");
  echo ("<input type=\"text\" name=\"dod_m\" value=\"".$row_m["dod_mon"]."\" size=\"2\" maxlength=\"2\"> ");
  echo ("<input type=\"text\" name=\"dod_y\" value=\"".$row_m["dod_year"]."\" size=\"4\" maxlength=\"4\"> - ");
  echo ("<input type=\"text\" name=\"dod_pl\" value=\"".$row_m["dod_place"]."\" size=\"25\" maxlength=\"50\"> - ");
  echo ("<input type=\"text\" name=\"dod_pa\" value=\"".$row_m["dod_parish"]."\" size=\"25\" maxlength=\"50\">");
  echo ("<br />");
  echo ("<br />");
  echo ("<b>No children:</b>");
  echo ("<input type=\"checkbox\" name=\"no_child\" value=\"Yes\"");
  if ($row_m["no_children"]=="Yes") {
    echo ("checked=\"checked\"> ");
    }else{
    echo ("> ");
    }
  echo ("<br />");
  echo ("<b>Never married:</b>");
  echo ("<input type=\"checkbox\" name=\"no_mar\" value=\"Yes\"");
  if ($row_m["never_married"]=="Yes") {
    echo ("checked=\"checked\"> ");
    }else{
    echo ("> ");
    }
  echo ("<input type=\"hidden\" name=\"op\" value=\"add\">");
  echo ("<p><input type=\"submit\" name=\"submit\" value=\"Update\"></p>");
  echo ("</form>");
  include("include/footer.inc");
  }


?>
