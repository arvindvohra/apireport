<?php
include("config.php");

$sql = sprintf("UPDATE myti.uv_ticket SET status_id = '5' WHERE status_id = '4' AND updated_at < DATE_SUB(NOW(),INTERVAL 2 day)");
$conn->query($sql);

?>
