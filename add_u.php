<!-- Add details for a new Spouse & Union.-->
<!-- WORKING -->

<?php
// common variables & header
include("include/common.inc");
require_once('include/db_connector.php');

//$connector = new DbConnector();

if ($_GET["mutch"]) {
  //################
  // get Mutch id to add Spouse & Union for 

  include("include/header.inc");

  //<!-- link back to Family list -->
  echo ("<div id=\"leftcontentmain\">");
  echo ("<a href=\"fl.php?id=".$_GET["mutch"]."\">List family</a>");
  echo ("</div>");
//  <!-- main content section -->
  echo ("<div id=\"centercontentmain\">");
  echo ("<br />");
  // first form to enter new spouse details
  echo ("<form method=\"post\" action=\"".$_SERVER[PHP_SELF]."\">");
  echo ("<b>Enter details for new Spouse</b><br />");
  echo ("<input type=\"text\" name=\"f_name\" size=\"15\" maxlength=\"30\"> ");
  echo ("<input type=\"text\" name=\"s_name\" size=\"15\" maxlength=\"30\"> ");
  echo ("<input type=\"text\" name=\"t_name\" size=\"15\" maxlength=\"30\"> ");
  echo ("<input type=\"text\" name=\"sur_name\" size=\"20\" maxlength=\"50\"> ");
  echo ("<br />");
  echo ("m<input type=\"radio\" name=\"sex\" value=\"m\"> ");
  echo ("f<input type=\"radio\" name=\"sex\" value=\"f\"> ");
  echo ("Child of:<input type=\"text\" name=\"c_of\" size=\"6\" maxlength=\"6\"> ");
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
  echo ("<input type=\"hidden\" name=\"mutch\" value=\"".$_GET["mutch"]."\">");
  echo ("<input type=\"hidden\" name=\"op\" value=\"add_s\">");
  echo ("<p><input type=\"submit\" name=\"submit\" value=\"Enter\"></p>");
  echo ("</form>");
  include("include/footer.inc");
  }else if ($_POST[op] == "add_s") {
// *****IF SUBMIT PUSHED and new spouse added*****

 // CREATE Spouse record
  $stmt = $conn->prepare("INSERT INTO Spouses (first_name,
    second_name,
    third_name,
    surname,
    sex,
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
    dod_parish) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
      
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
  if ($_POST['sur_name'] == "") {
    $_POST['sur_name'] = NULL;
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

  $stmt->bind_param('sssssiiiissiiissiiiss',
    $_POST['f_name'],
    $_POST['s_name'],
    $_POST['t_name'],
    $_POST['sur_name'],
    $_POST['sex'],
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
    $_POST['dod_pa']);

  $stmt->execute();

// Get the new records id
  $new_s_id = $stmt->insert_id;

  $stmt->close();

  $sel = "select * from Spouses where id = ".$new_s_id."";
  $result = $conn->query($sel);
  // return with error_msg if no entry found for family line
  if ($result->num_rows == 0) {
    die("ERROR: no entry found in Spouses for id ".$new_s_id."");
    //TODO find out how to run another file -  exec("choose.php"); ####
    }
  $row_s = $result->fetch_assoc();

  include("include/header.inc");

  //<!-- link back to Family list -->
  echo ("<div id=\"leftcontentmain\">");
  echo ("</div>");
//  <!-- main content section -->
  echo ("<div id=\"centercontentmain\">");
  echo ("<br />");
  echo ("<p><b>ADDED ID = ".$new_s_id." TO SPOUSES DATABASE</b></p>");
// show spouse details entered
  echo ("<b>Details entrered for Spouse ID = ".$row_s["id"]."</b><br />");
  echo ("First name = ".$row_s["first_name"]."<br />");
  echo ("Second name = ".$row_s["second_name"]."<br />");
  echo ("Third name = ".$row_s["third_name"]."<br />");
  echo ("Surname = ".$row_s["surname"]."<br />");
  echo ("<br />");
  echo ("Sex = ");
  if ($row_s["sex"]=="m") {
    echo ("male ");
    }elseif ($row_s["sex"]=="f") {
    echo ("female ");
    }else{
    echo ("not known ");
    }
  echo ("Child of: ".$row_s["child_of"]."<br />");
  echo ("<br />");
  echo ("<b>Birth details:</b><br />");
  echo ("Dob day = ".$row_s["dob_day"]."<br />");
  echo ("Dob month = ".$row_s["dob_mon"]."<br />");
  echo ("Dob year = ".$row_s["dob_year"]."<br />");
  echo ("Dob place = ".$row_s["dob_place"]."<br />");
  echo ("Dob parish = ".$row_s["dob_parish"]."<br />");
  echo ("<br />");
  echo ("<b>Christening details:</b><br />");
  echo ("Doc day = ".$row_s["doc_day"]."<br />");
  echo ("Doc month = ".$row_s["doc_mon"]."<br />");
  echo ("Doc year = ".$row_s["doc_year"]."<br />");
  echo ("Doc place = ".$row_s["doc_place"]."<br />");
  echo ("Doc parish = ".$row_s["doc_parish"]."<br />");
  echo ("<br />");
  echo ("<b>Death details:</b><br />");
  echo ("Dod day = ".$row_s["dod_day"]."<br />");
  echo ("Dod month = ".$row_s["dod_mon"]."<br />");
  echo ("Dod year = ".$row_s["dod_year"]."<br />");
  echo ("Dod place = ".$row_s["dod_place"]."<br />");
  echo ("Dod parish = ".$row_s["dod_parish"]."<br />");
  echo ("<br />");
// next form for entering Union details
  echo ("<form method=\"post\" action=\"".$_SERVER[PHP_SELF]."\">");
  echo ("<b>Enter details for new Union between Mutch ID = ".$_POST[mutch]." and Spouse ID = ".$new_s_id."</b><br />");
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
  echo ("<b>No children:</b>");
  echo ("<input type=\"checkbox\" name=\"no_child\" value=\"Yes\"> ");
  echo ("<br />");
  echo ("<b>Never married:</b>");
  echo ("<input type=\"checkbox\" name=\"no_mar\" value=\"Yes\"> ");
  echo ("<br />");
  echo ("<br />");
  echo ("<b>Note:</b><br />");
  echo ("<textarea name=\"u_note\" cols=60 rows=5 wrap=virtual></textarea>");
  echo ("<br />");
  echo ("<input type=\"hidden\" name=\"mutch\" value=\"".$_POST[mutch]."\">");
  echo ("<input type=\"hidden\" name=\"spouse\" value=\"".$new_s_id."\">");
  echo ("<input type=\"hidden\" name=\"op\" value=\"add_u\">");
  echo ("<p><input type=\"submit\" name=\"submit\" value=\"Enter\"></p>");
  echo ("</form>");
  include("include/footer.inc");
  }else if ($_POST[op] == "add_u") {
// *****IF SUBMIT PUSHED and new union added*****

//  $safe_note = filter_input(INPUT_POST, 'u_note', FILTER_SANITIZE_STRING);
  $safe_note = $_POST['u_note'];

// CREATE Union record
  $stmt = $conn->prepare("INSERT INTO Unions (mutch_link,
    spouse_link,
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
    no_children,
    never_married,
    note) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

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

  $stmt->bind_param('iiiiissiiisssss',
    $_POST['mutch'],
    $_POST['spouse'],
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
    $safe_note);

  $stmt->execute();

// Get the new records id
  $new_u_id = $stmt->insert_id;

  $stmt->close();
  
  $sel = "select * from Unions where id = ".$new_u_id."";
  $result = $conn->query($sel);
  // return with error_msg if no entry found for family line
  if ($result->num_rows == 0) {
    die("ERROR: no entry found in Unions for id ".$new_u_id."");
    //TODO find out how to run another file -  exec("choose.php"); ####
    }
  $row_u = $result->fetch_assoc();

  include("include/header.inc");

  //<!-- link back to Family list -->
  echo ("<div id=\"leftcontentmain\">");
  echo ("<a href=\"fl.php?id=".$_POST[mutch]."\">List family</a>");
  echo ("<br />");
  echo ("<br />");
  // Modify union details
  echo ("<a href=\"edit_u.php?id=".$new_u_id."&mutch=".$_POST[mutch]."\">Modify new union entry</a>");
  echo ("<br />");
  echo ("<br />");
  // Modify spouse details
  echo ("<a href=\"edit_s.php?id=".$_POST[spouse]."&mutch=".$_POST[mutch]."\">Modify new spouse entry</a>");
  echo ("</div>");
//  <!-- main content section -->
  echo ("<div id=\"centercontentmain\">");
  echo ("<br />");
  echo ("<p><b>ADDED NEW UNION ID = ".$new_u_id." TO UNIONS DATABASE</b></p>");
// list details for union added
  echo ("<b>Details entrered for ID=".$row_u["id"]."</b><br />");
  echo ("Mutch ID = ".$row_u["mutch_link"]."<br />");
  echo ("Spouse ID = ".$row_u["spouse_link"]."<br />");
  echo ("<br />");
  echo ("<b>Marriage details:</b><br />");
  echo ("Dom day = ".$row_u["dom_day"]."<br />");
  echo ("Dom month = ".$row_u["dom_mon"]."<br />");
  echo ("Dom year = ".$row_u["dom_year"]."<br />");
  echo ("Dom place = ".$row_u["dom_place"]."<br />");
  echo ("Dom parish = ".$row_u["dom_parish"]."<br />");
  echo ("<br />");
  echo ("<b>Alternate marriage details:</b><br />");
  echo ("Alt day = ".$row_u["alt_day"]."<br />");
  echo ("Alt month = ".$row_u["alt_mon"]."<br />");
  echo ("Alt year = ".$row_u["alt_year"]."<br />");
  echo ("Alt place = ".$row_u["alt_place"]."<br />");
  echo ("Alt parish = ".$row_u["alt_parish"]."<br />");
  echo ("<br />");
  echo ("<br />");
  echo ("<br />");
  echo ("<b>No children:</b>");
  echo ("No children = ");
  if ($row_u["no_children"]=="Yes") {
    echo ("Yes");
    }else{
    echo (" ");
    }
  echo ("<br />");
  echo ("<b>Never married:</b>");
  echo ("Never married = ");
  if ($row_u["never_married"]=="Yes") {
    echo ("Yes ");
    }else{
    echo (" ");
    }
  echo ("<br />");
  echo ("<br />");
  echo ("<b>Note:</b><br />");
  echo ($row_u["note"]);

  include("include/footer.inc");
  }

?>
