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


    default:
        // Récupérer tous les chapitres
        $listechapitre = Chapitre::readAll();

        // Ajouter les articles associés à chaque chapitre
        foreach ($listechapitre as $chapitre) {
            $chapitre->articles = Article::readAllBychapitre($chapitre->id_chapitre);
        }

        // Passer les données au template
        $modele = 'accueil.twig.html';
        $data = ['listechapitre' => $listechapitre];
        break;
}

echo $twig->render($modele, $data);
