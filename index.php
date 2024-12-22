<?php
session_start();

include('include/chapitre.php');
include('include/article.php');
include('include/bloc.php');
include('include/connexion.php');
include('include/twig.php');
$twig = init_twig();

// récupération de la variable page sur l'URL
if (isset($_GET['page'])) $page = $_GET['page'];
else $page = '';

// récupération de la variable action sur l'URL
if (isset($_GET['action'])) $action = $_GET['action'];
else $action = 'read';

// récupération de l'id s'il existe (par convention la clé 0 correspond à un id inexistant)
if (isset($_GET['id'])) $id = intval($_GET['id']);
else $id = 0;

// test des différents choix
switch ($page) {
    case 'bloc':
        Article::controleur($action, $id, $view, $data);
        break;


    case 'article':
        Article::controleur($action, $id, $view, $data);
        break;


    case 'chapitre':
        Chapitre::controleur($action, $id, $view, $data);
        break;

    case 'login':
        $view = 'login.twig.html';
        $data=[];
        break;

    case 'valid_login':
        // Teste si le formulaire login a été rempli
        if (isset($_POST['login'])) {
            $login = postString('login');
            // Si le mot de passe est correct
            if ($login == "ruraux2024!") {
                // Enregistre le login dans la session et charge le controleur admin
                $_SESSION['login'] = $login;
                header('Location: admin.php?page=chapitre&action=read');
            } else {
                // Mot de passe incorrect : retour à la page login
                unset($_SESSION['login']);
                header('Location: index.php?login');
            }
            //header('Location: index.php?login');
        } else {
            // Accès hors formulaire : retour à la page d'accueil
            header('Location: index.php');
        }
        break;

    default:
        Chapitre::controleur($action, $id, $view, $data);
}

echo $twig->render($view, $data);
