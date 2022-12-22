<?php
	include './includes/header.php';

	$query = $db->prepare("SELECT * FROM videos limit 10");
		$query->execute();
		$videos = $query->fetchAll();
?>

<?php foreach ($videos as $video):?>
	<article>
		<img src="<?= $video['thumb'] ?>" alt="" style="width: 300px">
		<h2><a href="video.php?id=<?= $video['id'] ?>"><?= $video['name'] ?></a></h2>
		<p>
			Имя канала:
			<a href="profile.php?id=<?= $video['user_id'] ?>"><?= getUser($video['user_id'])['channel_name'] ?>
		</p>
	</article>
<?php endforeach;
