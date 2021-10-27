<?php
include("config.php");

//$sql = sprintf("SELECT * FROM `myti`.`uv_ticket` WHERE `agent_id` = '90' || `agent_id` = '91' || `agent_id` = '92' || `agent_id` = '93' || `agent_id` = '94' || `agent_id` = '95' || `agent_id` = '96' || `agent_id` = '284' || `agent_id` = '421' || `agent_id` = '425' and status_id != '5'");
$sql = sprintf("SELECT * FROM `uv_ticket` WHERE `agent_id` = '2863'");
$finddata = $conn->query($sql);

while ($run = $finddata->fetch_array()) {

echo "beforeifcon:-".$run['id'].$run['subject'];
echo "<br>";

if($run['status_id'] <> '5'){
$runquery = sprintf("UPDATE uv_ticket SET `agent_id` = NULL WHERE `status_id` <> '5' and `id` = ".$run['id']);
$data = $conn->query($runquery);
echo "id:-".$run['id'].$run['subject'];
echo "<br>";
}

}

?>
