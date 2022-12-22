<?php
include 'includes/header.php';


if (!$user) {
	redirect('login.php');
}
$errors = [];
$success = 0;
if (isset($_POST['submit'])) {
	$name = $_POST['name'];
	$description = $_POST['description'];
	$thumb = $_FILES['thumb'];
	$video = $_FILES['video'];

	if (!$name) {
		$errors['name'] = 'Поле обязательно для заполнения';
	}
	if (!$description) {
		$errors['description'] = 'Поле обязательно для заполнения';
	}
	if (!$thumb) {
		$errors['thumb'] = 'Поле обязательно для заполнения';
	}
	if (!$video) {
		$errors['video'] = 'Поле обязательно для заполнения';
	}

	if (mb_strlen($name) > 50) {
		$errors['name'] = "Максимальная длина имени составляет 50";
	}
	if (mb_strlen($description) > 500) {
		$errors['description'] = "Максимальная длина описания составляет 500";
	}

	if (count($errors) === 0) {
		$errors = validatePost($name, $description, $video, $thumb);
		if (count($errors) === 0) {
			$query = $db->prepare("INSERT INTO videos (name, description, thumb, file, user_id) VALUES(:name, :description, :thumb, :file, :user_id)");
			$query->execute([
				"name" => $name,
				"description" => $description,
				"thumb" => uploadImage($thumb),
				"file" => uploadVideo($video),
				"user_id" => intval($user['id'])
			]);
			$success = 1;
		}
		else{
			$success = 2;
		}
	}
}
?>

<h2>Загрузить видео</h2>
<?php if ($success === 1) : ?>
	<h3>Видео загружено!</h3>
<?php endif; ?>
<form enctype="multipart/form-data" action="upload.php" method="post">
	<label for="name">Название</label>
	<input type="text" maxlength="50" name="name" id="name"><br><br>
	<?php
	if (isset($errors['name'])) :
	?>
		<strong>
			<?php echo $errors['name']; ?>
		</strong>
		<br><br>
	<?php endif; ?>

	<label for="description">Описание</label>
	<input type="text" maxlength="500" name="description" id="description"><br><br>
	<?php
	if (isset($errors['description'])) :
	?>
		<strong>
			<?php echo $errors['description']; ?>
		</strong>
		<br><br>
	<?php endif; ?>

	<label for="thumb">Изображение</label>
	<input type="file" name="thumb" id="thumb"><br><br>
	<?php
	if (isset($errors['thumb'])) :
	?>
		<strong>
			<?php echo $errors['thumb']; ?>
		</strong>
		<br><br>
	<?php endif; ?>

	<label for="video">Видео</label>
	<input type="file" name="video" id="video"><br><br>
	<?php
	if (isset($errors['video'])) :
	?>
		<strong>
			<?php echo $errors['video']; ?>
		</strong>
		<br><br>
	<?php endif; ?>

	<input type="submit" value="Загрузить" name="submit">
</form>