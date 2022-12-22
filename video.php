<?php
include "includes/header.php";

?>

<?php
$query = $db->prepare("SELECT * FROM videos WHERE id = :id");
$query->execute([
  'id' => $_GET['id'],
]);

$video = $query->fetch();

?>
<video style='background-color: black' width="400px" height="400px" src="<?= $video['file'] ?>" autoplay></video>