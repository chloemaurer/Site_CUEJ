<?php

require_once('fonctions.php');

class Chapitre
{
    public $id_chapitre;
    public $titre;
    public $chapo;
    public $image;
    public $alt;
    public $articles = [];
    public $ordre;

    // Le constructeur corrige les données récupérées de la BDD
    // Ici convertie l'identifiant en entier
    function __construct()
    {
        $this->id_chapitre = intval($this->id_chapitre);
    }

    static function readAll()
    {
        $sql = 'SELECT * FROM chapitre';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_CLASS, 'Chapitre');
    }

    static function readOne($id_chapitre)
    {
        $sql = 'SELECT * FROM chapitre WHERE id_chapitre = :id_chapitre';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id_chapitre', $id_chapitre, PDO::PARAM_INT);
        $query->execute();
        $chapitre = $query->fetchObject('Chapitre');

        // Appliquer html_entity_decode() pour éviter l'affichage des entités HTML comme &nbsp;
        if ($chapitre) {
            $chapitre->chapo = html_entity_decode($chapitre->chapo, ENT_QUOTES, 'UTF-8');
        }

        return $chapitre;
    }


    function update()
    {
        $sql = "UPDATE chapitre SET titre=:titre, chapo=:chapo, image=:image, alt=:alt WHERE id_chapitre=:id_chapitre";
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id_chapitre', $this->id_chapitre, PDO::PARAM_INT);
        $query->bindValue(':titre', $this->titre, PDO::PARAM_STR);
        $query->bindValue(':chapo', $this->chapo, PDO::PARAM_STR);
        $query->bindValue(':image', $this->image, PDO::PARAM_STR);
        $query->bindValue(':alt', $this->alt, PDO::PARAM_STR);
        $query->execute();
    }

    function delete()
    {
        // Suppression du fichier lié
        if (!empty($this->image)) unlink('upload/' . $this->image);

        // Suppression du thème
        $sql = "DELETE FROM chapitre WHERE id_chapitre=:id_chapitre";
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id_chapitre', $this->id_chapitre, PDO::PARAM_INT);
        $query->execute();
    }

    function chargePOST()
    {
        $this->id_chapitre = postInt('id_chapitre');
        $this->titre = postString('titre');
        $this->chapo = insecables(postString('chapo'));
        $this->image = postString('old-image');
        $this->alt = postString('alt');

        // Récupère les informations sur le fichier uploadés si il existe
        $image = chargeFILE('image');

        if (!empty($image)) {
            // Supprime l'ancienne image si update
            unlink('upload/' . $this->image);
            $this->image = $image;
        }
    }




    // Récupère l'ordre maximal des articles d'un thème
    static function readOrderMax($id_chapitre)
    {
        $sql = 'SELECT max(ordre) AS maximum FROM chapitre WHERE id_chapitre = :id_chapitre';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id_article', $id_chapitre, PDO::PARAM_INT);
        $query->execute();
        $objet = $query->fetchObject();
        return intval($objet->maximum);
    }


    function exchangeOrder()
    {
        // Recherche le chapitre précédent (dans la même catégorie)
        $sql = 'SELECT * FROM chapitre
            WHERE id_categorie = :id_categorie AND ordre < :ordre ORDER BY ordre DESC';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id_categorie', $this->id_chapitre, PDO::PARAM_INT);
        $query->bindValue(':ordre', $this->ordre, PDO::PARAM_INT);
        $query->execute();

        // Le chapitre précédent est le plus grand des ordres inférieurs
        $before = $query->fetchObject('Chapitre');

        // Échange des ordres si un chapitre précédent existe
        if ($before) {
            $tmp = $this->ordre;
            $this->ordre = $before->ordre;
            $this->update(); // Met à jour le chapitre courant
            $before->ordre = $tmp;
            $before->update(); // Met à jour le chapitre précédent
        }
    }



    static function controleur($action, $id_chapitre, &$modele, &$data)
    {
        switch ($action) {
            default:
                $modele = 'accueil.twig.html';

                // Récupérer tous les chapitres
                $listechapitre = Chapitre::readAll();

                // Ajouter les articles associés à chaque chapitre
                foreach ($listechapitre as $chapitre) {
                    $chapitre->articles = Article::readAllBychapitre($chapitre->id_chapitre);
                }

                // Passer les données au template
                $data = ['listechapitre' => $listechapitre];
                break;
        }
    }

    static function controleurAdmin($action, $id_chapitre, &$modele, &$data)
    {
        switch ($action) {
            case 'read':
                if ($id_chapitre > 0) {
                    $modele = 'chapitre/chapitre.twig.html';
                    $data = [
                        'chapitre' => Chapitre::readOne($id_chapitre),
                        'listearticle' => Article::readAllByChapitre($id_chapitre)
                    ];
                } else {
                    $modele = 'chapitre/liste_chapitres.twig.html';
                    $data = ['listechapitre' => Chapitre::readAll()];
                }
                break;

            case 'modifier':
                $modele = "chapitre/updatechapitre.twig.html";
                $data = ['chapitre' => Chapitre::readOne($id_chapitre)];
                break;
            case 'update':
                $chapitre = new Chapitre();
                $chapitre->chargePOST();
                $chapitre->update();


                header('Location: admin.php?page=chapitre');
                break;
            case 'delete':
                $chapitre = Chapitre::readOne($id_chapitre);
                $chapitre->delete();
                header('Location: admin.php?page=chapitre');
                break;
            default:
                $modele = 'chapitre/liste_chapitres.twig.html';
                $data = ['listechapitre' => Chapitre::readAll()];
                break;
        }
    }

    // Création de la table themes
    static function init()
    {
        // connexion
        $pdo = connexion();

        // suppression des données existantes le cas échéant
        $sql = 'drop table if exists chapitre';
        $query = $pdo->prepare($sql);
        $query->execute();

        // création de la table 'chapitre'
        $sql = 'create table chapitre (
				id_chapitre serial primary key,
				titre varchar(128),
				chapo varchar(512),
				image varchar(512))';
        $query = $pdo->prepare($sql);
        $query->execute();
    }
}
