<?php
$forAuth = true;
include 'includes/header.php';

if(!$user) {
	redirect('login.php');
}

$errors = [];

if (isset($_POST['submit'])) {
	$db->query("DELETE FROM videos WHERE id = " . $video['id']);
}

$query = $db->prepare("SELECT * FROM videos WHERE user_id = :user_id");
$query->execute([
  'user_id' => $_GET['id'],
]);

$videos = $query->fetchAll();

?>
<br><br>
Название канала: <br>
<strong><?=  getUser( $_GET['id'])['channel_name'] ?></strong><br>
Описание канала: <br>
 <strong><?=  getUser( $_GET['id'])['channel_description'] ?></strong> <br>
Количество видео:
<?= count($videos)?> <br><br><br><br><br>

Видосики: <br><br>

<?php foreach ($videos as $video):
	?>
	<article>
		<img src="<?= $video['thumb'] ?>" alt="" style="width: 300px">
		<h2><a href="video.php?id=<?= $video['id'] ?>"><?= $video['name'] ?></a></h2>
		<?php if ($_GET['id'] === $user['id']) : ?>
			<a href="delete.php?id=<?= $video['id'] ?>">Delete</a>
			<a href="edit.php?id=<?= $video['id'] ?>">Edit</a>

		<?php endif; ?>
	</article>
	<hr>
<?php endforeach; ?>
