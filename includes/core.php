<?php
session_start();

$db = new PDO('mysql:host=localhost:8889;dbname=testing', 'root', 'root');

$user = isset($_SESSION['user_id']) ? getUser($_SESSION['user_id']) : false;

if(isset($forAuth) && !$user) {
	redirect('login.php');
}


function dump($parameter)
{
	echo '<pre>';
	var_dump($parameter);
	echo '</pre>';
}

function redirect($url)
{
	header('Location: ' . $url);
	exit;
}

function getUser($id)
{
	global $db;

	$user = $db->query("SELECT * FROM users WHERE id = " . intval($id))->fetch();

	if($user) {
		$user['channel_name'] = htmlspecialchars($user['channel_name']);
	}

	return $user;
}

function preparePost($post)
{
	$post['title'] = htmlspecialchars($post['title']);
	$post['description'] = htmlspecialchars($post['description']);
	$post['content'] = htmlspecialchars($post['content']);
	$post['date'] = date('d.m.Y H:i', strtotime($post['date'])) ? date('d.m.Y H:i', strtotime($post['date'])) : 'date empty';
	$post['author'] = getUser($post['user_id']);

	return $post;
}

function getVideo($id)
{
	global $db;

	$video = $db->query("SELECT * FROM videos WHERE id = " . intval($id))->fetch();

	return $video;
}

function hasAccess($post)
{
	global $user;

	return $user && $post && $user['id'] === $post['user_id'];
}

function uploadImage($image)
{
	$extensionArray = explode('.', $image['name']);
	$extension = $extensionArray[count($extensionArray) - 1];
	$fileName = uniqid() . '.' . $extension;
	$imagePath = 'images/' . $fileName;

	move_uploaded_file($image['tmp_name'], $imagePath);

	return $imagePath;
}
function uploadVideo($video)
{
	$extensionArray = explode('.', $video['name']);
	$extension = $extensionArray[count($extensionArray) - 1];
	$fileName = uniqid() . '.' . $extension;
	$videoPath = 'videos/' . $fileName;

	move_uploaded_file($video['tmp_name'], $videoPath);

	return $videoPath;
}

function validatePost($title, $description, $content, $image, $isEdit = false)
{
	$errors = [];

	if($image['size'] > 0 || $isEdit === false) {
		$types = [
			'image/jpeg',
			'image/png',
		];
		if (!in_array($image['type'], $types)) {
			$errors['thumb'] = 'Incorrect file type';
		}
		if ($image['size'] > 2 * 1024 * 1024) {
			$errors['thumb'] = 'Incorrect image size';
		}
	}

	$titleLength = mb_strlen($title);
	if(!$title || $titleLength > 255) {
		$errors['name'] = 'Incorrect name';
	}

	$descriptionLength = mb_strlen($description);
	if(!$description || $descriptionLength > 500) {
		$errors['description'] = 'Incorrect description';
	}

	if(!$content) {
		$errors['video'] = 'Field is required';
	}
	if($content['size'] > 0) {
		$types = [
			'video/mp4',
		];
		if (!in_array($content['type'], $types)) {
			$errors['video'] = 'Incorrect file type';
		}
		if ($content['size'] > 500 * 1024 * 1024) {
			$errors['video'] = 'Incorrect video size';
		}
	}

	return $errors;
}
