<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="./CSS/style.css">
</head>

<body>
    <header class="d-flex align-items-center bg-secondary ">
        <nav class="navbar navbar-expand-lg ">
            <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                <li class="nav-item"><a class="nav-link active text-light" href="controleur.php?">Accueil</a></li>
                <li class="nav-item"><a class="nav-link active text-light" href="./templates/liste_choix_bloc.twig.html">Choix des Blocs</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-light" href="controleur.php?page=bloc&action=read" role="button" data-bs-toggle="dropdown">
                        Les Blocs
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="controleur.php?page=bloc&action=read">liste des blocs</a></li>
                        <li><a class="dropdown-item" href="controleur.php?page=bloc&action=new">Création d'un bloc</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-light" href="controleur.php?page=article&action=read" role="button" data-bs-toggle="dropdown">
                        Les Articles
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="controleur.php?page=article&action=read">liste des articles</a></li>
                        <li><a class="dropdown-item" href="controleur.php?page=article&action=new">Création d'un article</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link active text-light" href="controleur.php?page=chapitre&action=read">Les Chapitres</a></li>
            </ul>
        </nav>
    </header>
    <main class="ps-2">

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
            default:
                $modele = 'accueil.twig.html';
                $data = [];
        }

        echo $twig->render($modele, $data);

        ?>
    </main>
    <!-- modification du fichier controleur.php -->
</body>

</html>