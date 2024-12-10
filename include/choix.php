<?php

class Choix
{
    public $id_choix;
    public $type;
    public $style;


    static function readAll()
    {
        // définition de la requête SQL
        $sql = 'select * from choix';

        // connexion
        $pdo = connexion();

        // préparation de la requête
        $query = $pdo->prepare($sql);

        // exécution de la requête
        $query->execute();

        // récupération de toutes les lignes sous forme d'objets
        $tableau = $query->fetchAll(PDO::FETCH_CLASS, 'Choix');

        // retourne le tableau d'objets
        return $tableau;
    }




    static function readOne($id_choix)
    {
        $sql = 'select * from choix where id_choix = :valeur';

        // connexion à la base de données
        $pdo = connexion();

        // préparation de la requête
        $query = $pdo->prepare($sql);

        // on lie le paramètre :valeur à la variable $id reçue
        $query->bindValue(':valeur', $id_choix, PDO::PARAM_INT);



        // exécution de la requête
        $query->execute();

        // récupération de l'unique ligne
        $objet = $query->fetchObject('Choix');

        // retourne l'objet contenant résultat
        return $objet;
    }

}