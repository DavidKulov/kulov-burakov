<?php


$forAuth = true;
include 'includes/header.php';

$video = getVideo($_GET['id']);
$thumb = $video['thumb'];
$errors = [];
if(isset($_POST['submit'])) {
	$thumb = $_FILES['thumb'];
	if(count($errors) === 0) {


	$query = $db->prepare("UPDATE posts WHERE id = :id SET thumb = :thumb ");
		$query->execute([
			'id' => $_POST['id'],
			'thumb' => uploadImage($thumb),
		]);
		redirect('index.php');
	}
}

?>

<h1>Edit post</h1>
<!-- ------------------------------------------------------------------------- -->
<form enctype="multipart/form-data" action="/edit.php" method="post">
	<label for="thumb">Изображение</label>
	<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
	<input type="file" name="thumb" id="thumb"><br><br>
	<?php
	if (isset($errors['thumb'])) :
	?>
	<strong>
		<?php echo $errors['thumb']; ?>
	</strong>
	<br><br>
  <?php endif; ?>

  <input type="submit" value="Редактировать!" name="submit">
</form>

