<?php
$forAuth = true;
include 'includes/core.php';


$db->query("DELETE FROM videos WHERE id = " . intval($_GET['id']));

redirect('index.php');
