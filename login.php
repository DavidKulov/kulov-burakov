<?php
include 'includes/header.php';

$errors = [];
if (isset($_POST['submit'])) {
	$email = trim($_POST['email']);
	$password = $_POST['password'];

	if (!$email) {
		$errors['email'] = 'Gоле обязательно для заполнения';
	}
	if (!$password) {
		$errors['password'] = 'Gоле обязательно для заполнения';
	}

	if(count($errors) === 0) {
		$query = $db->prepare("SELECT * FROM users WHERE email = :email");
		$query->execute([
			'email' => $email,
		]);
		$user = $query->fetch();

		if($user) {
			if(md5($password) === $user['password']) {
				$_SESSION['user_id'] = $user['id'];
				redirect('index.php');
			}
		}

		$errors['email'] = 'Данные неверны';
	}
}
?>

<h1>Авторизация</h1>
<form action="login.php" novalidate method="post">
	<div>
		<label>
			E-Mail:
			<input type="email" placeholder="Ваш email" name="email"
				value="<?= $email ?? '' ?>"
			>
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
		<input type="submit" value="Login" name="submit">
	</div>
</form>

<?php
include 'includes/footer.php';