<?php

require_once("config.php");


$table = "(SELECT id, quest_abandon_time, quest_complete_time FROM " . $mysql_characters . ".quest_tracker) AS t1 ";
$table .= "JOIN (SELECT ID, LogTitle FROM " . $mysql_world . ".quest_template) AS t2 ";
$table .= "ON t1.id = t2.ID ";
$group_by = " GROUP BY t1.id ";

$primaryKey = 't1.id';

$columns = array(
    array( 'db' => 't1.id as id', 'name' => 'id', 'where' => 't1.id', 'dt' => 0 ),
    array( 'db' => 't2.LogTitle as title', 'name' => 'title', 'where' => 't2.LogTitle',  'dt' => 1 ),
    array( 'db' => 'COUNT(t1.quest_abandon_time) AS abandoned_times', 'name' => 'abandoned_times', 'where' => '',   'dt' => 2 ),
    array( 'db' => 'COUNT(t1.quest_complete_time) AS completed_times', 'name' => 'completed_times', 'where' => '',     'dt' => 3 ),
    array( 'db' => 'MAX(t1.quest_abandon_time) AS last_abandoned', 'name' => 'last_abandoned', 'where' => 't1.quest_abandon_time',     'dt' => 4 ),
    array( 'db' => 'MAX(t1.quest_complete_time) AS last_completed', 'name' => 'last_completed', 'where' => 't1.quest_complete_time',     'dt' => 5)
    
);

$sql_details = array(
    'user' => $mysql_username,
    'pass' => $mysql_password,
    'db'   => $mysql_world,
    'host' => $mysql_host
);
 
 
require( 'ssp.class.php' );
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $group_by)
);

