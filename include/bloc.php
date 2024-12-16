<?php

class Bloc
{
    public $id;
    public $ordre;
    public $type;
    public $texte;
    public $style;
    public $image;
    public $audio;
    public $video;
    public $infographie; // Nouveau champ ajouté
    public $id_article;

    function __construct()
    {
        $this->id = intval($this->id);
        $this->ordre = intval($this->ordre);
        $this->id_article = intval($this->id_article);
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

    static function readAllByArticle($id)
    {
        $sql = 'SELECT * FROM bloc WHERE id_article = :id ORDER BY ordre';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_CLASS, 'Bloc');
    }

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

    function exchangeOrder()
    {
        $sql = 'SELECT * FROM bloc
                WHERE id_article = :id_article AND ordre < :ordre ORDER BY ordre DESC';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id_article', $this->id_article, PDO::PARAM_INT);
        $query->bindValue(':ordre', $this->ordre, PDO::PARAM_INT);
        $query->execute();

        $before = $query->fetchObject('Bloc');

        if ($before) {
            $tmp = $this->ordre;
            $this->ordre = $before->ordre;
            $this->update();
            $before->ordre = $tmp;
            $before->update();
        }
    }

    function create()
    {
        $maximum = self::readOrderMax($this->id_article);
        $this->ordre = $maximum + 1;

        $sql = "INSERT INTO bloc (ordre, type, texte, style, image, audio, video, infographie, id_article)
                VALUES (:ordre, :type, :texte, :style, :image, :audio, :video, :infographie, :id_article)";
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':ordre', $this->ordre, PDO::PARAM_INT);
        $query->bindValue(':type', $this->type, PDO::PARAM_STR);
        $query->bindValue(':texte', $this->texte, PDO::PARAM_STR);
        $query->bindValue(':style', $this->style, PDO::PARAM_STR);
        $query->bindValue(':image', $this->image, PDO::PARAM_STR);
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
                SET ordre=:ordre, type=:type, texte=:texte, style=:style, image=:image, audio=:audio, video=:video, infographie=:infographie
                WHERE id=:id";
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id', $this->id, PDO::PARAM_INT);
        $query->bindValue(':ordre', $this->ordre, PDO::PARAM_INT);
        $query->bindValue(':type', $this->type, PDO::PARAM_STR);
        $query->bindValue(':texte', $this->texte, PDO::PARAM_STR);
        $query->bindValue(':style', $this->style, PDO::PARAM_STR);
        $query->bindValue(':image', $this->image, PDO::PARAM_STR);
        $query->bindValue(':audio', $this->audio, PDO::PARAM_STR);
        $query->bindValue(':video', $this->video, PDO::PARAM_STR);
        $query->bindValue(':infographie', $this->infographie, PDO::PARAM_STR);
        $query->execute();
    }

    function delete()
    {
        if (!empty($this->image)) unlink('upload/' . $this->image);
        if (!empty($this->audio)) unlink('upload/' . $this->audio);
        if (!empty($this->video)) unlink('upload/' . $this->video);
        if (!empty($this->infographie)) unlink('upload/' . $this->infographie);

        $sql = "DELETE FROM bloc WHERE id=:id";
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id', $this->id, PDO::PARAM_INT);
        $query->execute();
    }

    function chargePOST()
    {
        $this->id = postInt('id');
        $this->ordre = postInt('ordre');
        $this->type = postString('type');
        $this->texte = postString('texte');
        $this->style = postString('style');
        $this->image = postString('image');
        $this->audio = postString('audio');
        $this->video = postString('video');
        $this->infographie = postString('infographie'); // Nouveau champ
        $this->id_article = postInt('id_article');

        $image = chargeFILE('image');
        if (!empty($image)) {
            unlink('upload/' . $this->image);
            $this->image = $image;
        }

        $audio = chargeFILE('audio');
        if (!empty($audio)) {
            unlink('upload/' . $this->audio);
            $this->audio = $audio;
        }

        $video = chargeFILE('video');
        if (!empty($video)) {
            unlink('upload/' . $this->video);
            $this->video = $video;
        }

        $infographie = chargeFILE('infographie');
        if (!empty($infographie)) {
            unlink('upload/' . $this->infographie);
            $this->infographie = $infographie;
        }
    }
}
