<?php

// Récupère une chaine de caractères dans $_POST en la filtrant
function postString($name)
{
	$text = '';
	if (isset($_POST[$name])) {
		$text = htmlspecialchars($_POST[$name], ENT_QUOTES, 'UTF-8');
	}
	return $text;
}

// Récupère un entier dans $_POST en assurant sa conversion
function postInt($name)
{
	$int = 0;
	if (isset($_POST[$name])) {
		$int = intval($_POST[$name]);
	}
	return $int;
}

function filterText($text)
{
	// Remplace les ^ par des espaces insécables
	$text = str_replace('^', '&nbsp;', $text);

	// Remplace //texte// par <em>texte</em>
	$text = preg_replace('/\/\/(.*)\/\//', '<em>\1</em>', $text);

	// Remplace ((url|serveur.com)) par <a href="url">serveur.com</a>
	$text = preg_replace('/\(\(([^\s\|]*)\|(.*)\)\)/', '<a href="\1">\2</a>', $text);
	return $text;
}

function chargeFILE($type)
{
	if (isset($_FILES[$type]) && !empty($_FILES[$type]['name'])) {
		$fileName = $_FILES[$type]['name'];
		$fileTmpName = $_FILES[$type]['tmp_name'];
		$fileSize = $_FILES[$type]['size'];
		$fileError = $_FILES[$type]['error'];
		$fileType = $_FILES[$type]['type'];

		$fileExt = explode('.', $fileName);
		$fileActualExt = strtolower(end($fileExt));

		$allowed = ['jpg', 'jpeg', 'jfif', 'png', 'mp3', 'wav', 'flac'];

		if (in_array($fileActualExt, $allowed)) {
			if ($fileError === 0) {
				if ($fileSize < 80000000) {
					$fileNameNew = uniqid('', true) . "." . $fileActualExt;
					$fileDestination = 'upload/' . $fileNameNew;
					move_uploaded_file($fileTmpName, $fileDestination);
					return $fileNameNew;
				} else {
					echo 'Votre fichier est trop volumineux';
				}
			} else {
				echo 'Une erreur est survenue lors du téléchargement du fichier';
			}
		} else {
			echo 'Ce type de fichier (' . $fileType . ') ou d\'extension (' . $fileActualExt . ') n\'est pas supporté';
		}
		return '';
	}
}
