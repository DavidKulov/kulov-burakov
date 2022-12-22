<?php
include 'includes/header.php';

$errors = [];
if(isset($_POST['submit'])) {
	$name = trim($_POST['name']);
	$description = trim($_POST['description']);
	$email = trim($_POST['email']);
	$password = $_POST['password'];



	$nameLength = mb_strlen($name);
	if($nameLength < 1 || $nameLength > 20) {
		$errors['name'] = 'Имя неверно';
	}

	$descriptionLength = mb_strlen($name);
	if($descriptionLength < 1 || $descriptionLength > 500) {
		$errors['description'] = 'description is not correct';
	}

	if(filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
		$errors['email'] = 'Адрес электронной почты неверен';
	}

	$existEmailQuery = $db->prepare("SELECT * FROM users WHERE email = :email");
	$existEmailQuery->execute([
		'email' => $email,
	]);
	if($existEmailQuery->fetch() !== false) {
		$errors['email'] = 'Электронная почта существует';
	}

	$existChannelQuery = $db->prepare("SELECT * FROM users WHERE channel_name = :channel_name");
	$existChannelQuery->execute([
		'channel_name' => $name,
	]);
	if($existEmailQuery->fetch() !== false) {
		$errors['email'] = 'Электронная почта существует';
	}

	$passwordLength = mb_strlen($password);
	if($passwordLength < 3) {
		$errors['password'] = 'Пароль неверен';
	}

	if(count($errors) === 0) {
		$insertQuery = $db->prepare("INSERT INTO users (channel_name, channel_description, email, password) VALUES (:channel_name, :channel_description, :email, :password)");
		$insertQuery->execute([
			'channel_name' => $name,
			'channel_description' => $description,
			'email' => $email,
			'password' => md5($password),
		]);

		redirect('login.php');
	}
}
?>

	<h1>Registration</h1>
	<form action="registration.php" novalidate method="post">
		<div>
			<label>
				Имя канала:
				<input type="text" placeholder="Ваше имя канала" name="name"
					value="<?= $name ?? '' ?>"
				>
				<?= $errors['name'] ?? '' ?>
			</label>
		</div>
		<div>
			<label>
				Описание канала:
				<input type="text" placeholder="Ваше описание канала" name="description"
					value="<?= $description ?? '' ?>"
				>
				<?= $errors['description'] ?? '' ?>
			</label>
		</div>
		<div>
			<label>
				E-Mail:
				<input type="email" placeholder="Ваш email" name="email"
					value="<?= $email ?? '' ?>">
				<?= $errors['email'] ?? '' ?>
			</label>
		</div>
		<div>
			<label>
				Пароль:
				<input type="password" placeholder="Ваш пароль" name="password">
				<?= $errors['password'] ?? '' ?>
			</label>
		</div>

		<div>
			<input type="submit" name="submit" value=" Регистрация">
		</div>
	</form>

<?php
include 'includes/footer.php';
