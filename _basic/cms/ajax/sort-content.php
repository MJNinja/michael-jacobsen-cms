<?php
//SET INCLUDES
include_once('../../library/class.systemConfig.php');

//CONNECT TO DATABASE
$connector = new dbConnector();

//GET POSTED SORT DATA
$list       = $_POST['list'];
$table      = $_POST['table'];
$mainID     = $_POST['mainID'];

//LOOP THROUGH ALL RESULTS
foreach($list as $key => $value){
    //SET VARIABLES
    $id                 = $value;
    $sequence           = $key + 1;

    //UPDATE DB INFO
    $update = $connector->query("UPDATE $table
                                SET sequence = ?
                                WHERE $mainID = ?"
                                , array($sequence, $id));
}
?>
