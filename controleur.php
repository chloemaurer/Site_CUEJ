<?php
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
        switch ($action) {
            case 'read':
                if ($id > 0) {
                    $modele = 'bloc.twig.html';
                    $data = ['bloc' => Bloc::readOne($id)];
                } else {
                    $modele = 'liste_blocs.twig.html';
                    $data = ['listebloc' => Bloc::readAll()];
                }
                break;

            case 'new':
                $modele = 'newbloc.twig.html';
                $data = [];
                break;

            case 'create':
                $bloc = new Bloc();
                $bloc->chargePOST();
                $bloc->create(); // utilise maintenant la vraie variable $_POST
                header('Location: controleur.php?page=bloc&action=read');
                break;

            case 'delete':
                echo 'Suppression du bloc';
                Bloc::delete($id);
                header('Location: controleur.php?page=bloc&action=read');
                break;

            case 'modifier':
                $bloc = Bloc::readOne($id);
                $modele = 'updatebloc.twig.html';
                $data = [$bloc->afficheForm()];
                break;

            case 'update':
                $bloc = new Bloc();
                $bloc->chargePOST();    // utilise maintenant la vraie variable $_POST;
                $bloc->update();
                header('Location: controleur.php?page=bloc&action=read');
                break;

            default:
                echo 'Action non reconnue';
        }
        break;

    case 'article':
        switch ($action) {
            case 'read':
                if ($id > 0) {
                    $modele = 'article.twig.html';
                    $data = [
                        'article' => Article::readOne($id),
                        'listebloc' => Bloc::readByArticle($id)
                    ];
                } else {
                    $modele = 'liste_articles.twig.html';
                    $data = ['listearticle' => Article::readAll()];
                }
                break;
                ////////////////////////////////////
            case 'new':
                $modele = 'newarticle.twig.html';
                $data = [];
                break;

            case 'create':
                $article = new Article();
                $article->chargePOST();
                $article->create();
                header('Location: controleur.php?page=article&action=read');
                break;
                ////////////////////////////////////
            case 'delete':
                Article::delete($id);
                header('Location: controleur.php?page=article&action=read');
                break;
                ////////////////////////////////////
            case 'modifier':
                $article = Article::readOne($id);
                $modele = 'updatearticle.twig.html';
                $data = [$article->afficheForm()];
                break;

            case 'update':
                $article = new Article();
                $article->chargePOST();
                $article->update();
                header('Location: controleur.php?page=article&action=read');
                break;

            default:
                echo 'Action non reconnue';
        }
        break;

    case 'chapitre':
        switch ($action) {
            case 'read':
                if ($id > 0) {
                    $modele = 'chapitre.twig.html';
                    $data = [
                        'chapitre' => Chapitre::readOne($id),
                        'listebloc' => Bloc::readByArticle($id),
                    ];
                } else {
                    $modele = 'liste_chapitres.twig.html';
                    $data = ['listechapitre' => Chapitre::readAll()];
                }
                break;


                ////////////////////////////////////
            case 'delete':
                Chapitre::delete($id);
                header('Location: controleur.php?page=chapitre&action=read');
                break;
                ////////////////////////////////////
            case 'modifier':
                $chapitre = Chapitre::readOne($id);
                $modele = 'updatechapitre.twig.html';
                $data = [$chapitre->afficheForm()];
                break;

            case 'update':
                $chapitre = new Chapitre();
                $chapitre->chargePOST();
                $chapitre->update();
                header('Location: controleur.php?page=chapitre&action=read');
                break;
            default:
                echo 'Action non reconnue';
        }
        break;

    case 'choix':
        switch ($action) {
            case 'read':
                if ($id > 0) {
                    $modele = 'choix.twig.html';
                    $data = [
                        'chapitre' => Chapitre::readOne($id),
                        'listebloc' => Bloc::readByArticle($id),
                    ];
                } else {
                    $modele = 'liste_chapitres.twig.html';
                    $data = ['listechapitre' => Chapitre::readAll()];
                }
                break;

            default:
                echo 'Action non reconnue';
        }
        break;
    default:
        $modele = 'accueil.twig.html';
        $data = [];
}

echo $twig->render($modele, $data);
