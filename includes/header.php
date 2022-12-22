<?php
include 'core.php';
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
		  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Blog</title>
</head>
<body>
<nav>
	<b><a href="index.php">На главную</a></b> |

	<?php if ($user): ?>
		<a href="profile.php?id=<?= $user['id'] ?>">Профиль</a> |
		<a href="upload.php">Заугрузить видео</a> |
		<a href="index.php?user_id=<?= $user['id'] ?>">Мои посты</a> |
		<a href="logout.php">Выход (<?= $user['email'] ?>)</a>
	<?php else: ?>
		<a href="registration.php">Регистрация</a> |
		<a href="login.php">Авторизация</a>
	<?php endif; ?>
</nav>