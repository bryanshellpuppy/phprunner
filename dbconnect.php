//If you want to create code outside of the phprunner interface but want to use the connection string barried in the code of your project, try this:

<?php
include("include/dbcommon.php");
$cman = new ConnectionManager();
$connection = $cman->getDefault();  // or $cman->byTable("assessment_controls")
$mysqli = $connection->conn;
if (!$mysqli) {
    die("<b>Database connection not initialized. Check PHPRunner database config.</b>");
}?>
//Replace your old style php mysql connection with this and it will use the existing connections. Thus you can create utility scripts without hard coding username and passwords.
