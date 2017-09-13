<!-- Generic family details page called from choose.php with fl_code or id from Mutches table.-->
<!-- WORKING -->

<?php
// common variables & header
include("include/common.inc");
//require_once('include/db_connector.php');

// TODO tidy up this checking twice ####
// if page was called with Mutch id then check to see if it is a family line
if ($_GET['id']) {
  include('include/db_connector.php');
//  $connector = new DbConnector();

  // get Mutches details
  $sel = "select * from Mutches where id = '" . $_GET['id'] . "'";
  $result = $conn->query($sel);
  $row_m = $result->fetch_assoc();
  if ($row_m["family_line"]) {
    $fl = $row_m["family_line"];
    }
  }

// if page was called with Family Line code
if ($_POST['fl']) {
  $fl = $_POST['fl'];
  }
if ($_GET['fl']) {
  $fl = $_GET['fl'];
  }

// TODO make a standard include with connection check ####
  include('include/db_connector.php');
// $connector = new DbConnector();

// get Mutches details
if ($fl) {
  // correct fl code format first
  $fl=add_zeros($fl);
  $sel = "select * from Mutches where family_line = '" . $fl . "'";
  } else {
  $sel = "select * from Mutches where id = '" . $_GET['id'] . "'";
  }
$result = $conn->query($sel);

// return with error_msg if no entry found for family line
if ($result->num_rows == 0) {
    $message = "Family Line ".$fl." not found!";
    echo ("<script type='text/javascript'>alert('$message'); </script>");
    echo ("<script type='text/javascript'>location='edit.php'; </script>");
  } else {
  $row_m = $result->fetch_assoc();
  $m_id = $row_m["id"];

// get parent details if it exists
  $pu_id = $row_m["child_of"];
  if ($pu_id) {
    $sel = "select * from Unions where id = ".$pu_id;
    $result = $conn->query($sel);
    $row_pu = $result->fetch_assoc();
    $p_id = $row_pu["mutch_link"];
    $sel = "select * from Mutches where id = ".$p_id;
    $result = $conn->query($sel);
    $row_p = $result->fetch_assoc();
    }

// paste variable info into header
  $PageName = "(M - ".$row_m["id"].") ".$row_m["first_name"]." ".$row_m["second_name"]." ".$row_m["third_name"]." Mutch";

  if ($fl != NULL) {
    $PageTagline = "This is Family Line Reference [" . strip_zeros($fl) . "], details known for this family are ......";
    $sel = "select * from FamilyLines where fl_code = '".$fl."'";
    $result = $conn->query($sel);
    $row_fl = $result->fetch_assoc();
    $fl_update = $row_fl["updated"];
    } else {
    $PageTagline = "Details known for this family are ......";
    }
  }
include("include/header.inc");
?>

<!-- link back to start -->
<div id="leftcontentmain">
<a href="edit.php">Search</a>
<?php
//include("include/nav_menu.inc");  //###REMOVE FOR EDIT VERSION
?>
</div>

<!-- main content section -->
<div id="centercontentmain">
<br />
<p class="itemmainleft">
<?php
// Mutch details for parent of family
echo ("<a class=\"menu\" href=\"edit_m.php?id=".$row_m["id"]."\">[EDIT]</a> <b>".$PageName."</b>");
if ($row_m["dob_year"] != NULL) {
  echo (" born <b>".$row_m["dob_day"]." ".$month_text[$row_m["dob_mon"]]." ".$row_m["dob_year"]."</b>");
  }
if ($row_m["dob_place"] != NULL) {
  echo (" at <b>".$row_m["dob_place"]."</b>");
  }
if ($row_m["dob_parish"] != NULL) {
  echo (" in the parish of <b>".$row_m["dob_parish"]."</b>");
  }
if ($row_m["doc_year"] != NULL) {
  echo (" christened <b>".$row_m["doc_day"]." ".$month_text[$row_m["doc_mon"]]." ".$row_m["doc_year"]."</b>");
  }
if ($row_m["doc_place"] != NULL) {
  echo (" at <b>".$row_m["doc_place"]."</b>");
  }
if ($row_m["doc_parish"] != NULL) {
  echo (" in the parish of <b>".$row_m["doc_parish"]."</b>");
  }
if ($row_m["dod_year"] != NULL) {
  echo (" - died <b>".$row_m["dod_day"]." ".$month_text[$row_m["dod_mon"]]." ".$row_m["dod_year"]."</b>");
  }
if ($row_m["dod_place"] != NULL) {
  echo (" at <b>".$row_m["dod_place"]."</b>");
  }
if ($row_m["dod_parish"] != NULL) {
  echo (" in the parish of <b>".$row_m["dod_parish"]."</b>");
  }
echo ("<br />");
echo ("<br />");

// link to parent of the parent of family
if ($row_m["sex"] == "m") {
  echo ("~ His");
  } elseif ($row_m["sex"] == "f") {
  echo ("~ Her");
  } else {
  echo ("~Their");
  }
if ($p_id) {
  if ($row_p["sex"] == "m") {
    echo (" father was");
    } elseif ($row_p["sex"] == "f") {
    echo (" mother was");
    } else {
    echo (" parent was");
    }
  if ($row_p["family_line"]) {
    echo ("  <b><a href=\"fl.php?fl=".$row_p["family_line"]."\">(M - ".$row_p["id"].") ".$row_p["first_name"]." ".$row_p["second_name"]." ".$row_p["third_name"]." Mutch [".strip_zeros($row_p["family_line"])."] </a></b>");
    } else {
    echo (" <b><a href=\"fl.php?id=".$row_p["id"]."\">(M - ".$row_p["id"].") ".$row_p["first_name"]." ".$row_p["second_name"]." ".$row_p["third_name"]." Mutch</a></b>");
    }
  } else {
  echo(" parent is not known.");
  }
echo ("<br />");
echo ("</p>");
echo ("<br />");

// Spouse details for parent of family

// get Unions details if it exists
$sel = "select * from Unions where mutch_link = ".$m_id." order by dom_year";
$result = $conn->query($sel);

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {

// get Spouses details
    $sel = "select * from Spouses where id = ".$row["spouse_link"];
    $result_s = $conn->query($sel);
    $row_s = $result_s->fetch_assoc();
    echo ("<p class=\"itemmainleft\">");

    if ($row["dom_year"] == NULL) {
      echo ("~ <a class=\"menu\" href=\"edit_u.php?id=".$row["id"]."&mutch=".$row_m["id"]."\">[EDIT Union]</a> (U - ".$row["id"].") Had children with <a class=\"menu\" href=\"edit_s.php?id=".$row_s["id"]."&mutch=".$row_m["id"]."\">[EDIT]</a> (S - ".$row_s["id"].") <b>".$row_s["first_name"]." ".$row_s["second_name"]." ".$row_s["third_name"]." ".$row_s["surname"]."</b>");
      } else {
      echo ("~ <a class=\"menu\" href=\"edit_u.php?id=".$row["id"]."&mutch=".$row_m["id"]."\">[EDIT Union]</a> (U - ".$row["id"].") Spouse <a class=\"menu\" href=\"edit_s.php?id=".$row_s["id"]."&mutch=".$row_m["id"]."\">[EDIT]</a> (S - ".$row_s["id"].") <b>".$row_s["first_name"]." ".$row_s["second_name"]." ".$row_s["third_name"]." ".$row_s["surname"]."</b>");
      } 

    if ($row_s["dob_year"] != NULL) {
      echo (" born <b>".$row_s["dob_day"]." ".$month_text[$row_s["dob_mon"]]." ".$row_s["dob_year"]."</b>");
      }
    if ($row_s["dob_place"] != NULL) {
      echo (" at <b>".$row_s["dob_place"]."</b>");
      }
    if ($row_s["dob_parish"] != NULL) {
      echo (" in the parish of <b>".$row_s["dob_parish"]."</b>");
      }
    if ($row_s["doc_year"] != NULL) {
      echo (" christened <b>".$row_s["doc_day"]." ".$month_text[$row_s["doc_mon"]]." ".$row_s["doc_year"]."</b>");
      }
    if ($row_s["doc_place"] != NULL) {
      echo (" at <b>".$row_s["doc_place"]."</b>");
      }
    if ($row_s["doc_parish"] != NULL) {
      echo (" in the parish of <b>".$row_s["doc_parish"]."</b>");
      }
    if ($row_s["dod_year"] != NULL) {
      echo (" - died <b>".$row_s["dod_day"]." ".$month_text[$row_s["dod_mon"]]." ".$row_s["dod_year"]."</b>");
      }
    if ($row_s["dod_place"] != NULL) {
      echo (" at <b>".$row_s["dod_place"]."</b>");
      }
    if ($row_s["dod_parish"] != NULL) {
      echo (" in the parish of <b>".$row_s["dod_parish"]."</b>");
      }
// marriage details
    if ($row["dom_year"] != NULL) {
      echo ("<br />");
      echo ("<br />");
      echo ("They married <b>".$row["dom_day"]." ".$month_text[$row["dom_mon"]]." ".$row["dom_year"]."</b>");
      }
    if ($row["dom_place"] != NULL) {
      echo (" at <b>".$row["dom_place"]."</b>");
      }
    if ($row["dom_parish"] != NULL) {
      echo (" in the parish of <b>".$row["dom_parish"]."</b>");
      }
    if ($row["note"] != NULL) {
      echo (". ".$row["note"]);
      }

// get Spouse parents details
    if ($row_s["child_of"] != NULL) {
      $sel = "select * from Parents where id = ".$row_s["child_of"];
      $result_sp = $conn->query($sel);
      $row_sp = $result_sp->fetch_assoc();
      if ($row_sp["f_first_name"]  != NULL or $row_sp["f_surname"] != NULL) {
		echo ("<br />");
		echo ("<br /> <a class=\"menu\" href=\"edit_p.php?id=".$row_sp["id"]."&mutch=".$row_m["id"]."\">[EDIT Parents]</a> (SP - ".$row_sp["id"].") ");
		if ($row_s["sex"] == "m") {
	  		echo ("His");
	  	} elseif ($row_s["sex"] == "f") {
	  		echo ("Her");
	  	} else {
	  		echo ("Their");
	  	}
	    echo (" father was <font class=\"title\">".$row_sp["f_first_name"]." ".$row_sp["f_second_name"]." ".$row_sp["f_surname"]."</font>");
	  }
      if ($row_sp["m_first_name"]  != NULL or $row_sp["m_surname"] != NULL) {
		if ($row_sp["f_first_name"]  != NULL or $row_sp["f_surname"] != NULL) {
	  		if ($row_s["sex"] == "m") {
	    		echo (" and his");
	    	} elseif ($row_s["sex"] == "f") {
	    		echo (" and her");
	    	} else {
	    		echo (" and their");
	    	}
	    } else {
	  		echo ("<br />");
	  		echo ("<br />");
	  		if ($row_s["sex"] == "m") {
	    		echo ("His");
	    	} elseif ($row_s["sex"] == "f") {
	    		echo ("Her");
	    	} else {
	    		echo ("Their");
	    	}
	  	}
		echo (" mother was <font class=\"title\">".$row_sp["m_first_name"]." ".$row_sp["m_second_name"]." ".$row_sp["m_surname"]."</font>");
	  }
// spouse parents marriage details
      if ($row_sp["dom_year"] != NULL) {
		echo (" who were married <font class=\"title\">".$row_sp["dom_day"]." ".$month_text[$row_sp["dom_mon"]]." ".$row_sp["dom_year"]."</font>");
	  }
      if ($row_sp["dom_place"] != NULL) {
		echo (" at <b>".$row_sp["dom_place"]."</b>");
	  }
      if ($row_sp["dom_parish"] != NULL) {
			echo (" in the parish of <b>".$row_sp["dom_parish"]."</b>");
	  }
      if ($row_sp["note"] != NULL) {
		echo (". ".$row_sp["note"]);
	  }
// TODO need to add "never married" and "source" fields ###########
    }else{
    	echo ("<br />");
    	echo ("<br /> <a class=\"menu\" href=\"add_p.php?s_id=".$row_s["id"]."&mutch=".$row_m["id"]."\">[ADD Spouse Parents]</a>");
    }

/*    if($row["no_children"] =="Yes") {// (HOW CAN THIS EVER BE TRUE???) TODO check this works ####
      echo (" ~ They never had children.");
      }
*/
    echo ("<br />");
// Mutch Children details for family
    $sel = "select * from Mutches where child_of = ".$row["id"]." order by dob_year";
    $result_c = $conn->query($sel);
// check for details of other surname children too 
    $sel = "select * from Others where child_of = ".$row["id"]." order by dob_year";
    $result_o = $conn->query($sel);

    if ($result_c->num_rows > 0) {
      echo("<br />");
//--------------------
	echo ("<b><a class=\"menu\" href=\"add_m.php?u_id=".$row["id"]."&mutch=".$row_m["id"]."\">[ADD NEW (mutches) CHILD]</a></b>");
	echo ("<br />");
	echo ("<br />");
//--------------------
      echo("~ Children were:");
      echo("</p>");
      echo("<ul class=\"children\">");

      while($row_c = $result_c->fetch_assoc()) {
// Link to child family details
	if ($row_c["family_line"]) {
//	  echo ("<li><b><a class=\"menu\" href=\"edit_m.php?id=".$row_c["id"]."\">[XEDIT]</a> <a href=\"fl.php?fl=".$row_c["family_line"]."\">(M - ".$row_c["id"].") ".$row_c["first_name"]." ".$row_c["second_name"]." ".$row_c["third_name"]." [".strip_zeros($row_c["family_line"])."]</a></b>");
	  echo ("<li><b> <a href=\"fl.php?fl=".$row_c["family_line"]."\">(M - ".$row_c["id"].") ".$row_c["first_name"]." ".$row_c["second_name"]." ".$row_c["third_name"]." [".strip_zeros($row_c["family_line"])."]</a></b>");
	  } else {
//	  echo ("<li><b><a class=\"menu\" href=\"edit_m.php?id=".$row_c["id"]."\">[XEDIT]</a> <a href=\"fl.php?id=".$row_c["id"]."\">(M - ".$row_c["id"].") ".$row_c["first_name"]." ".$row_c["second_name"]." ".$row_c["third_name"]."</a></b>");
	  echo ("<li><b> <a href=\"fl.php?id=".$row_c["id"]."\">(M - ".$row_c["id"].") ".$row_c["first_name"]." ".$row_c["second_name"]." ".$row_c["third_name"]."</a></b>");
	  }
        if ($row_c["dob_year"] != NULL) {
          echo (" born <b>".$row_c["dob_day"]." ".$month_text[$row_c["dob_mon"]]." ".$row_c["dob_year"]."</b>");
          }
        if ($row_c["dob_place"] != NULL) {
          echo (" at <b>".$row_c["dob_place"]."</b>");
          }
        if ($row_c["dob_parish"] != NULL) {
          echo (" in the parish of <b>".$row_c["dob_parish"]."</b>");
          }
        if ($row_c["doc_year"] != NULL) {
          echo (" christened <b>".$row_c["doc_day"]." ".$month_text[$row_c["doc_mon"]]." ".$row_c["doc_year"]."</b>");
          }
        if ($row_c["doc_place"] != NULL) {
          echo (" at <b>".$row_c["doc_place"]."</b>");
          }
        if ($row_c["doc_parish"] != NULL) {
          echo (" in the parish of <b>".$row_c["doc_parish"]."</b>");
          }
        if ($row_c["dod_year"] != NULL) {
          echo (" - died <b>".$row_c["dod_day"]." ".$month_text[$row_c["dod_mon"]]." ".$row_c["dod_year"]."</b>");
          }
        if ($row_c["dod_place"] != NULL) {
          echo (" at <b>".$row_c["dod_place"]."</b>");
          }
        if ($row_c["dod_parish"] != NULL) {
          echo (" in the parish of <b>".$row_c["dod_parish"]."</b>");
          }

// Spouse details for child

// get child's Unions details if it exists
	$sel = "select * from Unions where mutch_link = ".$row_c["id"]." order by dom_year";
	$result_cu = $conn->query($sel);

	if ($result_cu->num_rows > 0) {
	  while ($row_cu= $result_cu->fetch_assoc()) {
// get Spouses details
	    $sel = "select * from Spouses where id = ".$row_cu["spouse_link"];
	    $result_cs = $conn->query($sel);
	    $row_cs = $result_cs->fetch_assoc();
	    if ($row_cu["dom_year"] == NULL) {
//	      echo (". <a class=\"menu\" href=\"edit_u.php?id=".$row_cs["id"]."&mutch=".$row_c["id"]."\">[XEDIT Union]</a> (U - ".$row_cu["id"].")  Had a relationship with <b> <a class=\"menu\" href=\"edit_s.php?id=".$row_cs["id"]."&mutch=".$row_c["id"]."\">[XEDIT]</a> (S - ".$row_cs["id"].") ".$row_cs["first_name"]." ".$row_cs["second_name"]." ".$row_cs["third_name"]." ".$row_cs["surname"]."</b>");
	      echo (".  (U - ".$row_cu["id"].")  Had a relationship with <b>  (S - ".$row_cs["id"].") ".$row_cs["first_name"]." ".$row_cs["second_name"]." ".$row_cs["third_name"]." ".$row_cs["surname"]."</b>");
	      }else{
//	      echo (". <a class=\"menu\" href=\"edit_u.php?id=".$row_cs["id"]."&mutch=".$row_c["id"]."\">[XEDIT Union]</a> (U - ".$row_cu["id"].")  Married <b> <a class=\"menu\" href=\"edit_s.php?id=".$row_cs["id"]."&mutch=".$row_c["id"]."\">[XEDIT]</a> (S - ".$row_cs["id"].") ".$row_cs["first_name"]." ".$row_cs["second_name"]." ".$row_cs["third_name"]." ".$row_cs["surname"]."</b>");
	      echo (".  (U - ".$row_cu["id"].")  Married <b>  (S - ".$row_cs["id"].") ".$row_cs["first_name"]." ".$row_cs["second_name"]." ".$row_cs["third_name"]." ".$row_cs["surname"]."</b>");
	      }
	    }  // end of child's spouse while
	   } // end of child's spouse info

	  echo(".</li>");
	  }  // end of child while
      	echo("</ul>");  // end of Mutch child info
	echo ("<p class=\"itemmainleft\">");
// if Other surname children details
      	} elseif ($result_o->num_rows > 0) {
	echo("<br />");
//--------------------
	echo ("<b><a class=\"menu\" href=\"add_o.php?u_id=".$row["id"]."&mutch=".$row_m["id"]."\">[ADD NEW (other) CHILD]</a></b>");
	echo ("<br />");
	echo ("<br />");
//--------------------
	echo("~ Children were:");
	echo("</p>");
	echo("<ul class=\"children\">");

	while($row_o = $result_o->fetch_assoc()) {
	  echo ("<li><b><a class=\"menu\" href=\"edit_o.php?id=".$row_o["id"]."&mutch=".$row_m["id"]."\">[EDIT]</a> (O - ".$row_o["id"].") ".$row_o["first_name"]." ".$row_o["second_name"]." ".$row_o["third_name"]." ".$row_o["surname"]."</b>");
	  if ($row_o["dob_year"] != NULL) {
	    echo (" born <b>".$row_o["dob_day"]." ".$month_text[$row_o["dob_mon"]]." ".$row_o["dob_year"]."</b>");
	    }
	  if ($row_o["dob_place"] != NULL) {
	    echo (" at <b>".$row_o["dob_place"]."</b>");
	    }
	  if ($row_o["dob_parish"] != NULL) {
	    echo (" in the parish of <b>".$row_o["dob_parish"]."</b>");
	    }
	  if ($row_o["doc_year"] != NULL) {
	    echo (" christened <b>".$row_o["doc_day"]." ".$month_text[$row_o["doc_mon"]]." ".$row_o["doc_year"]."</b>");
	    }
	  if ($row_o["doc_place"] != NULL) {
	    echo (" at <b>".$row_o["doc_place"]."</b>");
	    }
	  if ($row_o["doc_parish"] != NULL) {
	    echo (" in the parish of <b>".$row_o["doc_parish"]."</b>");
	    }
	  if ($row_o["dod_year"] != NULL) {
	    echo (" - died <b>".$row_o["dod_day"]." ".$month_text[$row_o["dod_mon"]]." ".$row_o["dod_year"]."</b>");
	    }
	  if ($row_o["dod_place"] != NULL) {
	    echo (" at <b>".$row_o["dod_place"]."</b>");
	    }
	  if ($row_o["dod_parish"] != NULL) {
	    echo (" in the parish of <b>".$row_o["dod_parish"]."</b>");
	    }
	  if ($row_o["other_note"] != NULL) {
	    echo (". ".$row_o["other_note"]);
	    }
	  echo(".</li>");
	  }  // end of Other surname child while
	echo("</ul>");  // end of Other child info
	echo ("<p class=\"itemmainleft\">");
	} else {
	echo ("<br />");
//--------------------
	echo ("<b><a class=\"menu\" href=\"add_o.php?u_id=".$row["id"]."&mutch=".$row_m["id"]."\">[ADD NEW (other) CHILD]</a></b>");
	echo ("<br />");
	echo ("<br />");
//--------------------
	echo("~ Details of children are not known.");
	echo ("<br />");
	}  // end of child info
      }  // end of spouse while
    } else if($row_m["never_married"]){
  echo ("<p class=\"itemmainleft\">");
  echo(" ~ Never married.");
  } else {
  echo ("<p class=\"itemmainleft\">");
//--------------------
	echo ("<b><a class=\"menu\" href=\"add_m.php?u_id=".$row["id"]."&mutch=".$row_m["id"]."\">[ADD NEW (mutches) CHILD]</a></b>");
	echo ("<br />");
	echo ("<br />");
//--------------------
  echo(" ~ Details of a spouse are not know.");
  } // end of spouse info

if($row_m["no_children"]){
	echo ("<p class=\"itemmainleft\">");
	echo("~ Died childless.");
	}

if ($fl_update != NULL) {
	echo("<font class=\"footnote\">");
	echo("Last updated: ".date("l, jS F Y", strtotime($fl_update)));
	echo("</font>");
	echo ("<br />");
	echo ("<br />");
	}
// Enter new spouse and union for Mutch
echo ("<b><a class=\"menu\" href=\"add_u.php?mutch=".$row_m["id"]."\">[ADD NEW SPOUSE & UNION]</a></b>");
echo ("<br />");
echo ("<br />");
// Get notes for Family Line
if ($row_m["family_line"] != NULL) {
// Update Family Line
	echo ("<b><a class=\"menu\" href=\"edit_fl.php?fl=".$row_m["family_line"]."\">[UPDATE FAMILY LINE]</a></b>");
	echo ("<br />");
	echo ("<br />");
//#	echo ("<p class=\"itemmainleft\">");
	echo ("<font class=\"notebold\">Webmaster's notes:");
	echo ("<br />");
	echo ("<br />");
// Enter new note for Family Line
echo ("<b><a class=\"menu\" href=\"add_fln.php?fl=".$row_m["family_line"]."\">[ADD NEW NOTE]</a></b>");
echo ("<br />");
echo ("<br />");
 	$sel = "select * from FamilyNotes where fl_code = '".$row_m["family_line"]."' order by display_order";
	$result_n = $conn->query($sel);

	if ($result_n->num_rows > 0) {
	  	while ($row_n= $result_n->fetch_assoc()) {
   			echo ("</font> (FLN - ".$row_n["id"].") [Note: ".$row_n["display_order"]."] <b><a class=\"menu\" href=\"edit_fln.php?fl=".$row_m["family_line"]."&fln=".$row_n["id"]."\">[EDIT]</a></b> <font class=\"notebold\"> ".$row_n["note"]);
			echo ("<br />");
			echo ("<br />");
			}
		} else {
		echo ("The details above have been taken from IGI or some other source without any verification by a researcher. If you can verify or correct any of these details please send them to me so that they can be shared. ");
		echo ("<br />");
		echo ("<br />");
		}
	}
echo ("</font>");
echo ("</p>");

?>


<?php
include("include/footer.inc");
?>
