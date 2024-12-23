<?php

use phpDocumentor\Reflection\DocBlock\Tags\Var_;

require_once('fonctions.php');

class Bloc
{
    public $id;
    public $ordre;
    public $type;
    public $texte;
    public $texte_1;
    public $texte_2;
    public $texte_3;
    public $texte_4;
    public $texte_titre;
    public $texte_citation;
    public $texte_legende;
    public $texte_credit;
    public $style;
    public $image;
    public $image_1;
    public $image_2;
    public $image_3;
    public $image_4;
    public $alt;
    public $audio;
    public $video;
    public $infographie;
    public $id_article;

    // Le constructeur corrige les données récupérées de la BDD
    // Ici convertie les clés et l'ordre (pour le tri) en entier
    function __construct()
    {
        $this->id = intval($this->id);
        $this->ordre = intval($this->ordre);
        $this->id_article = intval($this->id_article);
    }

    function insecables()
    {
        if ($this->texte) $this->texte = insecables($this->texte);
        if ($this->texte_1) $this->texte_1 = insecables($this->texte_1);
        if ($this->texte_2) $this->texte_2 = insecables($this->texte_2);
        if ($this->texte_3) $this->texte_3 = insecables($this->texte_3);
        if ($this->texte_4) $this->texte_4 = insecables($this->texte_4);
        if ($this->texte_titre) $this->texte_titre = insecables($this->texte_titre);
        if ($this->texte_citation) $this->texte_citation = insecables($this->texte_citation);
        if ($this->texte_legende) $this->texte_legende = insecables($this->texte_legende);
        if ($this->texte_credit) $this->texte_credit = insecables($this->texte_credit);
    }

    static function readOne($id)
    {
        $sql = 'SELECT * FROM bloc WHERE id = :id';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchObject('Bloc');
    }

    static function readAll()
    {
        $sql = 'SELECT * FROM bloc ORDER BY ordre';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_CLASS, 'Bloc');
    }

    // Récupère les elements d'un thème
    static function readAllByArticle($id)
    {
        $sql = 'SELECT * FROM bloc WHERE id_article = :id ORDER BY ordre';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_CLASS, 'Bloc');
    }

    // Récupère l'ordre maximal des elements d'un thème
    static function readOrderMax($id)
    {
        $sql = 'SELECT max(ordre) AS maximum FROM bloc WHERE id_article = :id';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $objet = $query->fetchObject();
        return intval($objet->maximum);
    }

    // Echange l'ordre de deux elements
    function exchangeOrder()
    {
        // Recherche l'bloc précédent (dans le même thème)
        // C'est l'bloc le plus grand, parmi les elements d'ordre inférieur

        // étape 1 : cherche les elements du même thème ayant un ordre inférieur
        $sql = 'SELECT * FROM bloc
				WHERE id_article = :id_article AND ordre < :ordre ORDER BY ordre DESC';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id_article', $this->id_article, PDO::PARAM_INT);
        $query->bindValue(':ordre', $this->ordre, PDO::PARAM_INT);
        $query->execute();

        // étape 2 : les elements sont triés par ordre décroissant
        // donc le premier bloc est le plus grand des plus petits, donc le précédent
        $before = $query->fetchObject('Bloc');

        // Si le précédent existe (l'bloc courant n'est pas le premier)
        if ($before) {
            // Échange les valeurs d'ordre et enregistre dans la BDD
            $tmp = $this->ordre;
            $this->ordre = $before->ordre;
            $this->update();
            $before->ordre = $tmp;
            $before->update();
        }
    }

    function create()
    {
        // Récupère l'ordre maximum pour créer l'bloc en dernière position
        $maximum = self::readOrderMax($this->id_article);
        $this->ordre = $maximum + 1;

        $sql = "INSERT INTO bloc (ordre, type, texte, texte_1, texte_2, texte_3, texte_4,
        texte_titre, texte_citation, texte_legende, texte_credit, style, image, image_1, image_2, image_3, image_4, alt, audio, video, infographie, id_article)
				VALUES (:ordre, :type, :texte, :texte_1, :texte_2, :texte_3, :texte_4, :texte_titre, :texte_citation, :texte_legende, :texte_credit, :style, :image, :image_1, :image_2, :image_3, :image_4, :alt, :audio, :video, :infographie, :id_article)";
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':ordre', $this->ordre, PDO::PARAM_INT);
        $query->bindValue(':type', $this->type, PDO::PARAM_STR);
        $query->bindValue(':texte', $this->texte, PDO::PARAM_STR);
        $query->bindValue(':texte_1', $this->texte_1, PDO::PARAM_STR);
        $query->bindValue(':texte_2', $this->texte_2, PDO::PARAM_STR);
        $query->bindValue(':texte_3', $this->texte_3, PDO::PARAM_STR);
        $query->bindValue(':texte_4', $this->texte_4, PDO::PARAM_STR);
        $query->bindValue(':texte_titre', $this->texte_titre, PDO::PARAM_STR);
        $query->bindValue(':texte_citation', $this->texte_citation, PDO::PARAM_STR);
        $query->bindValue(':texte_legende', $this->texte_legende, PDO::PARAM_STR);
        $query->bindValue(':texte_credit', $this->texte_credit, PDO::PARAM_STR);
        $query->bindValue(':style', $this->style, PDO::PARAM_STR);
        $query->bindValue(':image', $this->image, PDO::PARAM_STR);
        $query->bindValue(':image_1', $this->image_1, PDO::PARAM_STR);
        $query->bindValue(':image_2', $this->image_2, PDO::PARAM_STR);
        $query->bindValue(':image_3', $this->image_3, PDO::PARAM_STR);
        $query->bindValue(':image_4', $this->image_4, PDO::PARAM_STR);
        $query->bindValue(':alt', $this->alt, PDO::PARAM_STR);
        $query->bindValue(':audio', $this->audio, PDO::PARAM_STR);
        $query->bindValue(':video', $this->video, PDO::PARAM_STR);
        $query->bindValue(':infographie', $this->infographie, PDO::PARAM_STR);
        $query->bindValue(':id_article', $this->id_article, PDO::PARAM_INT);
        $query->execute();
        $this->id = $pdo->lastInsertId();
    }

    function update()
    {
        $sql = "UPDATE bloc
            SET ordre=:ordre,
                type=:type,
                texte=:texte,
                texte_1=:texte_1,
                texte_2=:texte_2,
                texte_3=:texte_3,
                texte_4=:texte_4,
                texte_titre=:texte_titre,
                texte_citation=:texte_citation,
                texte_legende=:texte_legende,
                texte_credit=:texte_credit,
                style=:style,
                image=:image,
                image_1=:image_1,
                image_2=:image_2,
                image_3=:image_3,
                image_4=:image_4,
                alt=:alt,
                audio=:audio,
                video=:video,
                infographie=:infographie,
                id_article=:id_article
            WHERE id=:id";

        $pdo = connexion();
        $query = $pdo->prepare($sql);

        // Bind des valeurs
        $query->bindValue(':id', $this->id, PDO::PARAM_INT);
        $query->bindValue(':ordre', $this->ordre, PDO::PARAM_INT);
        $query->bindValue(':type', $this->type, PDO::PARAM_STR);
        $query->bindValue(':texte', $this->texte, PDO::PARAM_STR);
        $query->bindValue(':texte_1', $this->texte_1, PDO::PARAM_STR);
        $query->bindValue(':texte_2', $this->texte_2, PDO::PARAM_STR);
        $query->bindValue(':texte_3', $this->texte_3, PDO::PARAM_STR);
        $query->bindValue(':texte_4', $this->texte_4, PDO::PARAM_STR);
        $query->bindValue(':texte_titre', $this->texte_titre, PDO::PARAM_STR);
        $query->bindValue(':texte_citation', $this->texte_citation, PDO::PARAM_STR);
        $query->bindValue(':texte_legende', $this->texte_legende, PDO::PARAM_STR);
        $query->bindValue(':texte_credit', $this->texte_credit, PDO::PARAM_STR);
        $query->bindValue(':style', $this->style, PDO::PARAM_STR);
        $query->bindValue(':image', $this->image, PDO::PARAM_STR);
        $query->bindValue(':image_1', $this->image_1, PDO::PARAM_STR);
        $query->bindValue(':image_2', $this->image_2, PDO::PARAM_STR);
        $query->bindValue(':image_3', $this->image_3, PDO::PARAM_STR);
        $query->bindValue(':image_4', $this->image_4, PDO::PARAM_STR);
        $query->bindValue(':alt', $this->alt, PDO::PARAM_STR);
        $query->bindValue(':audio', $this->audio, PDO::PARAM_STR);
        $query->bindValue(':video', $this->video, PDO::PARAM_STR);
        $query->bindValue(':infographie', $this->infographie, PDO::PARAM_STR);
        $query->bindValue(':id_article', $this->id_article, PDO::PARAM_INT);

        // Exécution de la requête
        $query->execute();
    }


    function delete()
    {
        // Suppression du fichier lié
        if (!empty($this->image)) unlink('upload/' . $this->image);
        if (!empty($this->image_1)) unlink('upload/' . $this->image_1);
        if (!empty($this->image_2)) unlink('upload/' . $this->image_2);
        if (!empty($this->image_3)) unlink('upload/' . $this->image_3);
        if (!empty($this->image_4)) unlink('upload/' . $this->image_4);

        $sql = "DELETE FROM bloc WHERE id=:id";
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id', $this->id, PDO::PARAM_INT);
        $query->execute();
    }



    static function deleteByArticle($id_article)
    {
        $sql = 'DELETE FROM bloc WHERE id_article = :id_article';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id_article', $id_article, PDO::PARAM_INT);
        $query->execute();
    }


    function chargePOST()
    {
        $this->id = postInt('id');
        $this->ordre = postInt('ordre');
        $this->type = postString('type');
        $this->texte = postString('texte');
        $this->texte_1 = postString('texte_1');
        $this->texte_2 = postString('texte_2');
        $this->texte_3 = postString('texte_3');
        $this->texte_4 = postString('texte_4');
        $this->texte_titre = postString('texte_titre');
        $this->texte_citation = postString('texte_citation');
        $this->texte_legende = postString('texte_legende');
        $this->texte_credit = postString('texte_credit');
        $this->style = postString('style');
        $this->image = postString('old-image');
        $this->image_1 = postString('old-image_1');
        $this->image_2 = postString('old-image_2');
        $this->image_3 = postString('old-image_3');
        $this->image_4 = postString('old-image_4');
        $this->alt = postString('alt');
        $this->audio = postString('old-audio');
        $this->video = postString('old-video');
        $this->id_article = postInt('id_article');

        // Récupère les informations sur le fichier uploadés si il existe
        $image = chargeFILE('image');
        if (!empty($image)) {
            if (is_file('upload/' . $this->image)) {
                unlink('upload/' . $this->image);
            }
            $this->image = $image;
        }
        $image_1 = chargeFILE('image_1');
        if (!empty($image_1)) {
            if (is_file('upload/' . $this->image_1)) {
                unlink('upload/' . $this->image_1);
            }
            $this->image_1 = $image_1;
        }
        $image_2 = chargeFILE('image_2');
        if (!empty($image_2)) {
            if (is_file('upload/' . $this->image_2)) {
                unlink('upload/' . $this->image_2);
            }
            $this->image_2 = $image_2;
        }
        $image_3 = chargeFILE('image_3');
        if (!empty($image_3)) {
            if (is_file('upload/' . $this->image_3)) {
                unlink('upload/' . $this->image_3);
            }
            $this->image_3 = $image_3;
        }
        $image_4 = chargeFILE('image_4');
        if (!empty($image_4)) {
            if (is_file('upload/' . $this->image_4)) {
                unlink('upload/' . $this->image_4);
            }
            $this->image_4 = $image_4;
        }
        $audio = chargeFILE('audio');
        if (!empty($audio)) {
            if (is_file('upload/' . $this->audio)) {
                unlink('upload/' . $this->audio);
            }
            $this->audio = $audio;
        }

        $video = chargeFILE('video');
        if (!empty($video)) {
            if (is_file('upload/' . $this->video)) {
                unlink('upload/' . $this->video);
            }
            $this->video = $video;
        }
        $infographie = chargeFILE('infographie');
        if (!empty($infographie)) {
            if (is_file('upload/' . $this->infographie)) {
                unlink('upload/' . $this->infographie);
            }
            $this->infographie = $infographie;
        }
    }



    static function controleur($action, $id, &$modele, &$data)
    {
        switch ($action) {
            default:

                $modele = 'bloc/liste_blocs.twig.html';
                $data = [
                    'bloc' => Bloc::readOne($id),
                ];
                break;
        }
    }

    static function controleurAdmin($action, $id, &$modele, &$data)
    {
        switch ($action) {
            case 'read':
                if ($id > 0) {
                    $modele = 'bloc/bloc.twig.html';
                    $data = ['bloc' => Bloc::readOne($id)];
                } else {
                    $modele = 'bloc/liste_blocs.twig.html';
                    $data = ['listebloc' => Bloc::readAll()];
                }
                break;

            case 'new':
                $id = isset($_GET['id']) ? $_GET['id'] : null;
                $modele = 'form/' . $id . '.twig.html';
                $id_article_selectionne =
                    isset($_GET['id_article']) ? intval($_GET['id_article']) : null;
                $data = [
                    'bloc' => Bloc::readOne($id), // Si vous avez des données associées à $id
                    'id' => $id,                  // Passez l'id à Twig
                    'listearticle' => Article::readAll(),
                    'id_article_selectionne' => $id_article_selectionne
                ];
                break;

            case 'create':
                $bloc = new Bloc();
                $bloc->chargePOST();
                $bloc->create();
                header('Location: admin.php?page=article&action=read&id=' . $bloc->id_article);
                exit;
                break;

            case 'delete':
                $bloc = Bloc::readOne($id); // Méthode pour récupérer le bloc
                if ($bloc) {
                    $bloc->delete();
                }
                header('Location: admin.php?page=article&action=read&id=' . $bloc->id_article);
                exit;
                break;


            case 'modifier':
                $id = isset($_GET['id']) ? $_GET['id'] : null;
                $id_bloc = isset($_GET['id_bloc']) ? $_GET['id_bloc'] : null;

                $modele = 'form-update/' . $id . '.twig.html';
                $bloc = Bloc::readOne($id_bloc);
                $data = [
                    'bloc' => $bloc, // Si vous avez des données associées à $id
                    'id' => $id,                  // Passez l'id à Twig
                    'id_bloc' => $id_bloc,
                    'listearticle' => Article::readAll()
                ];
                break;

            case 'update':
                $bloc = new Bloc();
                $bloc->chargePOST();
                $bloc->update();
                header('Location: admin.php?page=article&action=read&id=' . $bloc->id_article);
                exit();
                break;

            case 'exchange':
                $bloc = Bloc::readOne($id);
                $bloc->exchangeOrder();
                header('Location: admin.php?page=article&action=read&id=' . $bloc->id_article);
                break;

            default:
                echo 'Action non reconnue';
                break;
        }
    }
}
