<!-- Add details for a spouses parent entry in Parents table.-->
<!-- WORKING -->

<?php
// common variables & header
include("include/common.inc");
require_once('include/db_connector.php');

//$connector = new DbConnector();

if ($_GET["s_id"]) {
  // get Spouse id to add Parent to 

  $mutch_id = filter_input(INPUT_GET, 'mutch', FILTER_SANITIZE_NUMBER_INT);
    
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
  echo ("<b>Enter details for spouse's Father</b><br />");
  echo ("<input type=\"text\" name=\"f_f_name\" size=\"15\" maxlength=\"30\"> ");
  echo ("<input type=\"text\" name=\"f_s_name\" size=\"15\" maxlength=\"30\"> ");
  echo ("<input type=\"text\" name=\"f_surname\" size=\"20\" maxlength=\"30\"> ");
  echo ("<br />");
  echo ("<b>Enter details for spouse's Mother</b><br />");
  echo ("<input type=\"text\" name=\"m_f_name\" size=\"15\" maxlength=\"30\"> ");
  echo ("<input type=\"text\" name=\"m_s_name\" size=\"15\" maxlength=\"30\"> ");
  echo ("<input type=\"text\" name=\"m_surname\" size=\"20\" maxlength=\"30\"> ");
  echo ("<br />");
  echo ("<br />");
  echo ("<b>Marriage details:</b><br />");
  echo ("<input type=\"text\" name=\"dom_d\" size=\"2\" maxlength=\"2\"> ");
  echo ("<input type=\"text\" name=\"dom_m\" size=\"2\" maxlength=\"2\"> ");
  echo ("<input type=\"text\" name=\"dom_y\" size=\"4\" maxlength=\"4\"> - ");
  echo ("<input type=\"text\" name=\"dom_pl\" size=\"25\" maxlength=\"50\"> - ");
  echo ("<input type=\"text\" name=\"dom_pa\" size=\"25\" maxlength=\"50\">");
  echo ("<br />");
  echo ("<b>Alternative marriage details:</b><br />");
  echo ("<input type=\"text\" name=\"alt_d\" size=\"2\" maxlength=\"2\"> ");
  echo ("<input type=\"text\" name=\"alt_m\" size=\"2\" maxlength=\"2\"> ");
  echo ("<input type=\"text\" name=\"alt_y\" size=\"4\" maxlength=\"4\"> - ");
  echo ("<input type=\"text\" name=\"alt_pl\" size=\"25\" maxlength=\"50\"> - ");
  echo ("<input type=\"text\" name=\"alt_pa\" size=\"25\" maxlength=\"50\">");
  echo ("<br />");
  echo ("<br />");
  echo ("<b>Never married:</b>");
  echo ("<input type=\"checkbox\" name=\"no_mar\" value=\"Yes\"> ");
  echo ("<br />");
  echo ("<br />");
  echo ("<b>Note:</b><br />");
  echo ("<textarea name=\"p_note\" cols=60 rows=5 wrap=virtual></textarea>");
  echo ("<br />");
  echo ("<b>Source:</b><br />");
  echo ("<input type=\"text\" name=\"p_source\" size=\"20\" maxlength=\"30\"> ");
  echo ("<input type=\"hidden\" name=\"mutch\" value=\"".$mutch_id."\">");
  echo ("<input type=\"hidden\" name=\"spouse\" value=\"".$_GET["s_id"]."\">");
  echo ("<input type=\"hidden\" name=\"op\" value=\"add\">");
  echo ("<p><input type=\"submit\" name=\"submit\" value=\"Enter\"></p>");
  echo ("</form>");
  include("include/footer.inc");
  }else if ($_POST[op] == "add") {
// *****IF SUBMIT PUSHED*****

  $safe_note = filter_input(INPUT_POST, 'p_note', FILTER_SANITIZE_STRING);
  $mutch_id = filter_input(INPUT_POST, 'mutch', FILTER_SANITIZE_NUMBER_INT);
    
//  $safe_note = $_POST[p_note];
//  $safe_note = addslashes($_POST[p_note]);

// CREATE record
  $stmt = $conn->prepare("INSERT INTO Parents (f_first_name, 
    f_second_name,
    f_surname,
    m_first_name,
    m_second_name,
    m_surname,
    dom_day,
    dom_mon,
    dom_year,
    dom_place,
    dom_parish,
    alt_day,
    alt_mon,
    alt_year,
    alt_place,
    alt_parish,
    never_married,
    note,
    source) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

// Set blank values to NULL
  // partent name fields
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

  // other fields
  if ($_POST[no_mar] == "") {
    $_POST['no_mar'] = NULL;
    }
  if ($_POST[p_source] == "") {
    $_POST['p_source'] = NULL;
    }

  $stmt->bind_param('ssssssiiissiiisssss',
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
    $_POST['p_source']);

  $stmt->execute();

// Get the new records id
  $new_id = $stmt->insert_id;

  $stmt->close();

// Set child_of in Spouses record
  $co_stmt = $conn->prepare("update Spouses set child_of = ?
    where id = ?");

  $co_stmt->bind_param('si',
    $new_id,
    $_POST['spouse']);

  $co_stmt->execute();

  $co_stmt->close();

  $sel = "select * from Parents where id = ".$new_id."";
  $result = $conn->query($sel);
  // return with error_msg if no entry found for family line
  if ($result->num_rows == 0) {
    die("ERROR: no entry found in Parents for id ".$new_id."");
    //TODO find out how to run another file -  exec("choose.php"); ####
    }
  $row_p = $result->fetch_assoc();

  include("include/header.inc");

  //<!-- link back to Family list -->
  echo ("<div id=\"leftcontentmain\">");
  echo ("<a href=\"fl.php?id=".$mutch_id."\">List family</a>");
  echo ("<br />");
  echo ("<br />");
  echo ("<a href=\"edit_p.php?id=".$new_id."&mutch=".$mutch_id."\">Modify entry</a>");
  echo ("</div>");
//  <!-- main content section -->
  echo ("<div id=\"centercontentmain\">");
  echo ("<br />");
  echo ("<p><b>ADDED ID = ".$new_id." TO PARENTS DATABASE and ADDED LINK TO SPOUSE ID = ".$_POST[spouse]."</b></p>");
// refresh form
  //<form action=" ">

  echo ("<b>Details entrered for ID=".$row_p["id"]."</b><br />");
  echo ("<b>Father's details:</b><br />");
  echo ("First name = ".$row_p["f_first_name"]."<br />");
  echo ("Second name = ".$row_p["f_second_name"]."<br />");
  echo ("Surname = ".$row_p["f_surname"]."<br />");
  echo ("<b>Mother's details:</b><br />");
  echo ("First name = ".$row_p["m_first_name"]."<br />");
  echo ("Second name = ".$row_p["m_second_name"]."<br />");
  echo ("Surname = ".$row_p["m_surname"]."<br />");
  echo ("<b>Marriage details:</b><br />");
  echo ("Dom day = ".$row_p["dom_day"]."<br />");
  echo ("Dom month = ".$row_p["dom_mon"]."<br />");
  echo ("Dom year = ".$row_p["dom_year"]."<br />");
  echo ("Dom place = ".$row_p["dom_place"]."<br />");
  echo ("Dom parish = ".$row_p["dom_parish"]."<br />");
  echo ("<br />");
  echo ("<b>Alternative marriage details:</b><br />");
  echo ("Alt day = ".$row_p["alt_day"]."<br />");
  echo ("Alt month = ".$row_p["alt_mon"]."<br />");
  echo ("Alt year = ".$row_p["alt_year"]."<br />");
  echo ("Alt place = ".$row_p["alt_place"]."<br />");
  echo ("Alt parish = ".$row_p["alt_parish"]."<br />");
  echo ("<br />");
  echo ("<br />");
  echo ("<b>Never married:</b>");
  echo ("Never married = ");
  if ($row_p["never_married"]=="Yes") {
    echo ("Yes ");
    }else{
    echo (" ");
    }
  echo ("<br />");
  echo ("<br />");
  echo ("<b>Note:</b><br />");
  echo ($row_p["note"]);
  echo ("<br />");
  echo ("<br />");
  echo ("<b>Source:</b><br />");
  echo ($row_p["source"]);
  include("include/footer.inc");
  }

?>
