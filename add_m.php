<!-- Add details for an entry in Mutches table.-->
<!-- WORKING -->

<?php
// common variables & header
include("include/common.inc");
require_once('include/db_connector.php');

//$connector = new DbConnector();

if ($_GET["u_id"]) {
  //################
  // get Union id to add Other to

  $sel = "select * from Mutches where id = ".$_GET["u_id"]."";
  $result = $conn->query($sel);
  // return with error_msg if no entry found for family line
  if ($result->num_rows == 0) {
    die("ERROR: no entry found in Mutches for id ".$_GET["u_id"]."");
    //TODO find out how to run another file -  exec("choose.php"); ####
    }
  $row_fl = $result->fetch_assoc();

  $mutch_fl = $row_fl["family_line"];

  include("include/header.inc");

  //<!-- link back to Family list -->
  echo ("<div id=\"leftcontentmain\">");
  echo ("<a href=\"fl.php?id=".$_GET["mutch"]."\">List family</a>");
  echo ("</div>");
//  <!-- main content section -->
  echo ("<div id=\"centercontentmain\">");
  echo ("<br />");
  //<form action=" ">
  //########
  echo ("<form method=\"post\" action=\"".$_SERVER[PHP_SELF]."\">");
  echo ("<b>Enter details for a new Mutch child of Family Line ".$mutch_fl."</b><br />");
  echo ("<input type=\"text\" name=\"f_name\" size=\"15\" maxlength=\"30\"> ");
  echo ("<input type=\"text\" name=\"s_name\" size=\"15\" maxlength=\"30\"> ");
  echo ("<input type=\"text\" name=\"t_name\" size=\"15\" maxlength=\"30\"> ");
  echo ("<input type=\"hidden\" name=\"f_line\" value=\"".$mutch_fl."\">");
  echo ("<br />");
  echo ("m<input type=\"radio\" name=\"sex\" value=\"m\"> ");
  echo ("f<input type=\"radio\" name=\"sex\" value=\"f\"> ");
  echo ("Child of:<input type=\"text\" name=\"c_of\" value=\"".$_GET["u_id"]."\" size=\"6\" maxlength=\"6\"> ");
  echo ("<br />");
  echo ("<b>Birth details:</b><br />");
  echo ("<input type=\"text\" name=\"dob_d\" size=\"2\" maxlength=\"2\"> ");
  echo ("<input type=\"text\" name=\"dob_m\" size=\"2\" maxlength=\"2\"> ");
  echo ("<input type=\"text\" name=\"dob_y\" size=\"4\" maxlength=\"4\"> - ");
  echo ("<input type=\"text\" name=\"dob_pl\" size=\"25\" maxlength=\"50\"> - ");
  echo ("<input type=\"text\" name=\"dob_pa\" size=\"25\" maxlength=\"50\">");
  echo ("<br />");
  echo ("<b>Christening details:</b><br />");
  echo ("<input type=\"text\" name=\"doc_d\" size=\"2\" maxlength=\"2\"> ");
  echo ("<input type=\"text\" name=\"doc_m\" size=\"2\" maxlength=\"2\"> ");
  echo ("<input type=\"text\" name=\"doc_y\" size=\"4\" maxlength=\"4\"> - ");
  echo ("<input type=\"text\" name=\"doc_pl\" size=\"25\" maxlength=\"50\"> - ");
  echo ("<input type=\"text\" name=\"doc_pa\" size=\"25\" maxlength=\"50\">");
  echo ("<br />");
  echo ("<b>Death details:</b><br />");
  echo ("<input type=\"text\" name=\"dod_d\" size=\"2\" maxlength=\"2\"> ");
  echo ("<input type=\"text\" name=\"dod_m\" size=\"2\" maxlength=\"2\"> ");
  echo ("<input type=\"text\" name=\"dod_y\" size=\"4\" maxlength=\"4\"> - ");
  echo ("<input type=\"text\" name=\"dod_pl\" size=\"25\" maxlength=\"50\"> - ");
  echo ("<input type=\"text\" name=\"dod_pa\" size=\"25\" maxlength=\"50\">");
  echo ("<br />");
  echo ("<br />");
  echo ("<b>No children:</b>");
  echo ("<input type=\"checkbox\" name=\"no_child\" value=\"Yes\"> ");
  echo ("<br />");
  echo ("<b>Never married:</b>");
  echo ("<input type=\"checkbox\" name=\"no_mar\" value=\"Yes\"> ");
  echo ("<br />");
  echo ("<input type=\"hidden\" name=\"mutch\" value=\"".$_GET["mutch"]."\">");
  echo ("<input type=\"hidden\" name=\"op\" value=\"add\">");
  echo ("<p><input type=\"submit\" name=\"submit\" value=\"Enter\"></p>");
  echo ("</form>");
  include("include/footer.inc");
  }else if ($_POST[op] == "add") {
// *****IF SUBMIT PUSHED*****

// CREATE record
  $stmt = $conn->prepare("INSERT INTO Mutches (first_name,
    second_name,
    third_name,
    sex,
    family_line,
    child_of,
    dob_day,
    dob_mon,
    dob_year,
    dob_place,
    dob_parish,
    doc_day,
    doc_mon,
    doc_year,
    doc_place,
    doc_parish,
    dod_day,
    dod_mon,
    dod_year,
    dod_place,
    dod_parish,
    no_children,
    never_married) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

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
  $stmt->bind_param('sssssiiiissiiissiiissss',
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
    $_POST['no_mar']);

  $stmt->execute();

// Get the new records id
  $new_id = $stmt->insert_id;

  $stmt->close();

  $sel = "select * from Mutches where id = ".$new_id."";
  $result = $conn->query($sel);
  // return with error_msg if no entry found for family line
  if ($result->num_rows == 0) {
    die("ERROR: no entry found in Others for id ".$new_id."");
    //TODO find out how to run another file -  exec("choose.php"); ####
    }
  $row_o = $result->fetch_assoc();

  include("include/header.inc");

  //<!-- link back to Family list -->
  echo ("<div id=\"leftcontentmain\">");
  echo ("<a href=\"fl.php?id=".$_POST[mutch]."\">List family</a>");
  echo ("<br />");
  echo ("<br />");
  echo ("<a href=\"edit_m.php?id=".$new_id."&mutch=".$_POST[mutch]."\">Modify entry</a>");
  echo ("</div>");
//  <!-- main content section -->
  echo ("<div id=\"centercontentmain\">");
  echo ("<br />");
  echo ("<p><b>ADDED ID = ".$new_id." TO MUTCHES DATABASE</b></p>");
// refresh form
  //<form action=" ">
  //########
  echo ("<b>Details entrered:</b><br />");
  echo ("First name = ".$row_o["first_name"]."<br />");
  echo ("Second name = ".$row_o["second_name"]."<br />");
  echo ("Third name = ".$row_o["third_name"]."<br />");
  echo ("<br />");
  echo ("Sex = ");
  if ($row_o["sex"]=="m") {
    echo ("male ");
    }elseif ($row_o["sex"]=="f") {
    echo ("female ");
    }else{
    echo ("not known ");
    }
  echo ("Family line: ".$row_o["family_line"]."<br />");
  echo ("Child of: ".$row_o["child_of"]."<br />");
  echo ("<br />");
  echo ("<b>Birth details:</b><br />");
  echo ("Dob day = ".$row_o["dob_day"]."<br />");
  echo ("Dob month = ".$row_o["dob_mon"]."<br />");
  echo ("Dob year = ".$row_o["dob_year"]."<br />");
  echo ("Dob place = ".$row_o["dob_place"]."<br />");
  echo ("Dob parish = ".$row_o["dob_parish"]."<br />");
  echo ("<br />");
  echo ("<b>Christening details:</b><br />");
  echo ("Doc day = ".$row_o["doc_day"]."<br />");
  echo ("Doc month = ".$row_o["doc_mon"]."<br />");
  echo ("Doc year = ".$row_o["doc_year"]."<br />");
  echo ("Doc place = ".$row_o["doc_place"]."<br />");
  echo ("Doc parish = ".$row_o["doc_parish"]."<br />");
  echo ("<br />");
  echo ("<b>Death details:</b><br />");
  echo ("Dod day = ".$row_o["dod_day"]."<br />");
  echo ("Dod month = ".$row_o["dod_mon"]."<br />");
  echo ("Dod year = ".$row_o["dod_year"]."<br />");
  echo ("Dod place = ".$row_o["dod_place"]."<br />");
  echo ("Dod parish = ".$row_o["dod_parish"]."<br />");
  echo ("<br />");
  echo ("<br />");
  echo ("<b>No children:</b>");
  echo ("No children = ");
  if ($row_o["no_children"]=="Yes") {
    echo ("Yes");
    }else{
    echo (" ");
    }
  echo ("<br />");
  echo ("<b>Never married:</b>");
  echo ("Never married = ");
  if ($row_o["never_married"]=="Yes") {
    echo ("Yes ");
    }else{
    echo (" ");
    }
  echo ("<br />");
  include("include/footer.inc");
  }

?>
