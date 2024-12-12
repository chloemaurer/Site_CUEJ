<?php

class Chapitre
{
    public $id_chapitre;
    public $titre;
    public $chapo;
    public $src;
    public $alt;


    function chargePOST()
    {
        if (isset($_POST['id_chapitre'])) {
            $this->id_chapitre = $_POST['id_chapitre'];
        } else {
            $this->id_chapitre = 0;
        }
        if (isset($_POST['titre'])) {
            $this->titre = $_POST['titre'];
        } else {
            $this->titre = '';
        }
        /**/
        if (isset($_POST['chapo'])) {
            $this->chapo = $_POST['chapo'];
        } else {
            $this->chapo = 'pas de chapo';
        }
        if (isset($_POST['src'])) {
            $this->src = $_POST['src'];
        } else {
            $this->src = 'image sans lien';
        }
        if (isset($_POST['alt'])) {
            $this->alt = $_POST['alt'];
        } else {
            $this->alt = 'image sans description';
        }
    }


    static function readAll()
    {
        // définition de la requête SQL
        $sql = 'select * from chapitre';

        // connexion
        $pdo = connexion();

        // préparation de la requête
        $query = $pdo->prepare($sql);

        // exécution de la requête
        $query->execute();

        // récupération de toutes les lignes sous forme d'objets
        $tableau = $query->fetchAll(PDO::FETCH_CLASS, 'Chapitre');

        // retourne le tableau d'objets
        return $tableau;
    }




    static function readOne($id_chapitre)
    {
        $sql = 'select * from chapitre where id_chapitre = :valeur';

        // connexion à la base de données
        $pdo = connexion();

        // préparation de la requête
        $query = $pdo->prepare($sql);

        // on lie le paramètre :valeur à la variable $id reçue
        $query->bindValue(':valeur', $id_chapitre, PDO::PARAM_INT);



        // exécution de la requête
        $query->execute();

        // récupération de l'unique ligne
        $objet = $query->fetchObject('Chapitre');

        // retourne l'objet contenant résultat
        return $objet;
    }


    function create()
    {
        // construction de la requête :type, :texte sont les valeurs à insérées
        $sql = 'INSERT INTO chapitre (titre, chapo, src, alt, id_chapitre) VALUES (:titre, :chapo, :src, :alt, id_chapitre);';

        // connexion à la base de données
        $pdo = connexion();

        // préparation de la requête
        $query = $pdo->prepare($sql);

        // on donne une valeur aux paramètres à partir des attributs de l'objet courant
        $query->bindValue(':titre', $this->titre, PDO::PARAM_STR);
        $query->bindValue(':chapo', $this->chapo, PDO::PARAM_STR);
        $query->bindValue(':src', $this->src, PDO::PARAM_STR);
        $query->bindValue(':alt', $this->alt, PDO::PARAM_STR);
        $query->bindValue(':id_chapitre', $this->id_chapitre, PDO::PARAM_STR);

        // exécution de la requête
        $query->execute();

        // on récupère la clé de l'bloc inséré
        $this->id_chapitre = $pdo->lastInsertId();
    }



    static function delete($id)
    {
        // construction de la requête :type, :texte sont les valeurs à insérées
        $sql = 'DELETE FROM chapitre WHERE id_chapitre = :id;';

        // connexion à la base de données
        $pdo = connexion();

        // préparation de la requête
        $query = $pdo->prepare($sql);

        // on lie le paramètre :id à la variable $id reçue
        $query->bindValue(':id', $id, PDO::PARAM_INT);

        // exécution de la requête
        $query->execute();
    }



    function update()
    {
        // construction de la requête :titre, :chapo sont les valeurs à insérées
        $sql = 'UPDATE chapitre SET titre = :titre , chapo = :chapo, src = :src, alt = :alt WHERE id_chapitre = :id_chapitre;';

        // connexion à la base de données
        $pdo = connexion();

        // préparation de la requête
        $query = $pdo->prepare($sql);

        // on donne une valeur aux paramètres à partir des attributs de l'objet courant
        $query->bindValue(':id_chapitre', $this->id_chapitre, PDO::PARAM_INT);
        $query->bindValue(':titre', $this->titre, PDO::PARAM_STR);
        $query->bindValue(':chapo', $this->chapo, PDO::PARAM_STR);
        $query->bindValue(':src', $this->src, PDO::PARAM_STR);
        $query->bindValue(':alt', $this->alt, PDO::PARAM_STR);


        // exécution de la requête
        $query->execute();
    }


}
