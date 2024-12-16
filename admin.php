<?php
session_start();

require_once('include/twig.php');
require_once('include/fonctions.php');
require_once('include/connexion.php');
require_once('include/chapitre.php');
require_once('include/article.php');
require_once('include/bloc.php');

// Initialisation de Twig
$twig = init_twig();

// Route (page/action/id) pour le contrôleur
if (isset($_GET['page'])) $page = $_GET['page'];
else $page = '';
if (isset($_GET['action'])) $action = $_GET['action'];
else $action = 'read';
if (isset($_GET['id'])) $id = intval($_GET['id']);
else $id = 0;

// Vérification des droits administrateur
// Charge le login stocké dans la session
// $login = '';
// if (isset($_SESSION['login'])) $login = $_SESSION['login'];

// Si le login est incorrect : retour à la page d'accueil
// if ($login != 'mmi2024!') {
//     header('Location: index.php');
// }

// Le tableau de données par défaut
$modele = '';
$data = [];

switch ($page) {
    case 'chapitre':
        Chapitre::controleurAdmin($action, $id, $modele, $data);
        break;
    case 'article':
        Article::controleurAdmin($action, $id, $modele, $data);
        break;
    case 'bloc':
        Bloc::controleurAdmin($action, $id, $modele, $data);
        break;
        // case 'logout':
        //     unset($_SESSION['login']);
        //     header('Location: index.php');
        //     break;
    case 'choix':
        $modele = 'liste_choix_bloc.twig.html';
        $data = [];
        break;
    default:
        $modele = 'admin.twig.html';
        $data = [];
}

// Ajoute les informations de login
echo $twig->render($modele, $data);
