<?php
include('include/choix.php');
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
                $modele = 'form/' . $id . 'twig.html';
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
        Article::controleur($action, $id, $view, $data);

        // switch ($action) {
        //     case 'read':
        //         if ($id > 0) {
        //             $modele = 'article.twig.html';
        //             $data = [
        //                 'article' => Article::readOne($id),
        //                 'listebloc' => Bloc::readByArticle($id)
        //             ];
        //         } else {
        //             $modele = 'liste_articles.twig.html';
        //             $data = ['listearticle' => Article::readAll()];
        //         }
        //         break;
        //         ////////////////////////////////////
        //     case 'new':
        //         $modele = 'newarticle.twig.html';
        //         $data = ['listechapitre' => Chapitre::readAll()];
        //         break;

        //     case 'create':
        //         $article = new Article();
        //         var_dump($_POST);
        //         $article->chargePOST();
        //         var_dump($_POST);
        //         $article->create();
        //         header('Location: controleur.php?page=article&action=read');
        //         break;
        //         ////////////////////////////////////
        //     case 'delete':
        //         Article::delete($id);
        //         header('Location: controleur.php?page=article&action=read');
        //         break;
        //         ////////////////////////////////////
        //     case 'modifier':
        //         $article = Article::readOne($id);
        //         $modele = 'updatearticle.twig.html';
        //         $data = ['article' => $article, 'listechapitre' => Chapitre::readAll()];
        //         break;

        //     case 'update':
        //         $article = new Article();
        //         $article->chargePOST();
        //         var_dump($article);
        //         $article->update();
        //         header('Location: controleur.php?page=article&action=read');
        //         break;

        //     default:
        //         echo 'Action non reconnue';
        // }
        break;

    case 'chapitre':
        Chapitre::controleur($action, $id, $view, $data);
        break;

    case 'choix':
        switch ($action) {
            case 'read':
                if ($id > 0) {
                    $modele = 'choix.twig.html';
                    $data = [
                        'choix' => Choix::readOne($id),
                    ];
                } else {
                    $modele = 'liste_choix_bloc.twig.html';
                    $data = ['listechoix' => Choix::readAll()];
                }
                break;

            default:
                echo 'Action non reconnue';
        }
        break;
    case 'admin':
        $modele = 'admin.twig.html';
        $data = [];
        break;
    default:
        // Récupérer tous les chapitres
        $listechapitre = Chapitre::readAll();
        $listearticle = Article::readAll();

        // Ajouter les articles associés à chaque chapitre
        foreach ($listechapitre as $chapitre) {
            $chapitre->articles = Article::readAllByChapitre($chapitre->id_chapitre);
        }

        // Passer les données au template
        $modele = 'accueil.twig.html';
        $data = ['listechapitre' => $listechapitre];
        break;
}

echo $twig->render($modele, $data);
