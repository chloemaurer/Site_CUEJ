<?php

// Récupère une chaine de caractères dans $_POST en la filtrant
function postString($name)
{
	$text = '';
	if (isset($_POST[$name])) {
		$text = htmlspecialchars($_POST[$name], ENT_COMPAT, 'UTF-8');
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

		// Vérification des erreurs de téléchargement
		if ($fileError === 0) {
			$allowed = ['jpg', 'jpeg', 'png', 'mp3', 'mp4', 'svg'];
			if (in_array($fileActualExt, $allowed)) {
				if ($fileSize < 80000000) {
					$fileNameNew = uniqid('', true) . "." . $fileActualExt;
					$fileDestination = 'upload/' . $fileNameNew;
					move_uploaded_file($fileTmpName, $fileDestination);
					return $fileNameNew;
				} else {
					echo 'Votre fichier est trop volumineux';
				}
			} else {
				echo 'Ce type de fichier (' . $fileType . ') ou d\'extension (' . $fileActualExt . ') n\'est pas supporté';
			}
			return '';
		}
		else {
			switch ($fileError) {
				case UPLOAD_ERR_INI_SIZE:
				case UPLOAD_ERR_FORM_SIZE:
					echo "Le fichier est trop volumineux (dépasse la taille autorisée).";
					break;
				case UPLOAD_ERR_PARTIAL:
					echo "Le fichier n'a été que partiellement téléchargé.";
					break;
				case UPLOAD_ERR_NO_FILE:
					echo "Aucun fichier n'a été téléchargé.";
					break;
				case UPLOAD_ERR_NO_TMP_DIR:
					echo "Le dossier temporaire est manquant.";
					break;
				case UPLOAD_ERR_CANT_WRITE:
					echo "Échec de l'écriture du fichier sur le disque.";
					break;
				case UPLOAD_ERR_EXTENSION:
					echo "Une extension PHP a arrêté l'upload du fichier.";
					break;
				default:
					echo "Une erreur inconnue est survenue lors du téléchargement.";
			}
			return ''; 
		} 
	}
}



function insecables($texte)
{
	// Espaces insécables fines (&#8239;) avant les signes de ponctuation doubles
	$texte = preg_replace('/\s([;:?!%])/u', '&nbsp;$1', $texte); // ; : ? ! %

	// Espaces insécables (&#160; ou &nbsp;) après les chiffres pour unités et nombres
	$texte = preg_replace('/(\d)\s+(h|ans|fois|€|%)/u', '$1&nbsp;$2', $texte); // 2 h 30 → 2&nbsp;h&nbsp;30
	$texte = preg_replace('/(\d)\s+(ans|fois)/u', '$1&nbsp;$2', $texte); // 17 ans → 17&nbsp;ans
	$texte = preg_replace('/(\d)\s+(€|%)/u', '$1&nbsp;$2', $texte); // 100 € → 100&nbsp;€

	// Espaces insécables pour les grands nombres (ex. 200 000 → 200&nbsp;000)
	$texte = preg_replace('/(\d)(?=(\d{3})+(?!\d))/u', '$1&nbsp;', $texte);

	// Espaces insécables entre guillemets français « ... » et les mots
	$texte = preg_replace('/«\s/u', '«&nbsp;', $texte);
	$texte = preg_replace('/\s»/u', '&nbsp;»', $texte);

	$texte = preg_replace('/\/\/(.*?)\/\//', '<em>\1</em>', $texte);
	$texte = preg_replace('/\=\=(.*)\=\=/', '<strong>\1</strong>', $texte);

	// Remplace ((url|serveur.com)) par <a href="url">serveur.com</a>
	$texte = preg_replace('/\(\(([^\s\|]*)\|(.*)\)\)/', '<a href="\1">\2</a>', $texte);
	return $texte;
}

