<?php

class articleManager {

// DECLARATION ET INSTANCIATIONS
    private $bdd; // Instance de PDO
    private $_result;
    private $_message;
    private $_article; // Instance de article
    private $_getLastInsertId;

    public function __construct(PDO $bdd) {
        $this->setBdd($bdd);
    }

    public function getBdd() {
        return $this->bdd;
    }

    public function get_result() {
        return $this->_result;
    }

    public function get_message() {
        return $this->_message;
    }

    public function get_article() {
        return $this->_article;
    }

    function get_getLastInsertId() {
        return $this->_getLastInsertId;
    }

    function set_getLastInsertId($_getLastInsertId): void {
        $this->_getLastInsertId = $_getLastInsertId;
    }

    public function setBdd($bdd): void {
        $this->bdd = $bdd;
    }

    public function set_result($_result): void {
        $this->_result = $_result;
    }

    public function set_message($_message): void {
        $this->_message = $_message;
    }

    public function set_article($_article): void {
        $this->_article = $_article;
    }

    public function get($id) {
        $sql = 'SELECT * FROM articles WHERE id = :id';
        $req = $this->bdd->prepare($sql);

        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->execute();

        $donnees = $req->fetch(PDO::FETCH_ASSOC);

        $article = new article();

        $article->hydrate($donnees);

        return $article;
    }

    public function getList() {
        $listArticle = [];
        $sql = 'SELECT id, '
                . 'titre, '
                . 'texte, '
                . 'publie, '
                . 'DATE_FORMAT(date, "%d/%m/%Y") as date '
                . 'FROM articles';
        $req = $this->bdd->prepare($sql);

        $req->execute();

        while ($donnees = $req->fetch(PDO::FETCH_ASSOC)) {
            $articles = new article();
            $articles->hydrate($donnees);
            $listArticle[] = $articles;
        }

        return $listArticle;
    }

    //Fonction d'affichage des commentaires de l'article
  public function getOneArt($id_article) {
    $listArticle = [];

    // Prépare une requête de type SELECT avec une clause WHERE selon l'id
    $sql = 'SELECT * FROM articles WHERE id = :id_article';
    $req = $this->bdd->prepare($sql);

    // Exécution de la requête avec attribution des valeurs aux marqueurs nominatifs
    $req->bindValue(':id_article', $id_article, PDO::PARAM_INT);
    $req->execute();

    // On stocke les données obtenues dans un tableau.
    while ($donnees = $req->fetch(PDO::FETCH_ASSOC)) {
      $article = new article();
      $article->hydrate($donnees);
      $listArticle[] = $article;
    }
    //print_r2($listArticle);
    return $listArticle;
  }
  
    public function add(article $article) {
        $sql = "INSERT INTO articles "
                . "(titre, texte, publie, date) "
                . "VALUES (:titre, :texte, :publie, :date)";
        $req = $this->bdd->prepare($sql);

        $req->bindValue(':titre', $article->getTitre(), PDO::PARAM_STR);
        $req->bindValue(':texte', $article->getText(), PDO::PARAM_STR);
        $req->bindValue(':publie', $article->getPublie(), PDO::PARAM_INT);
        $req->bindValue(':date', $article->getDate(), PDO::PARAM_STR);

        $req->execute();
        if ($req->errorCode() == 00000) {
            $this->_result = true;
            $this->_getLastInsertId = $this->bdd->lastInsertId();
        } else {
            return FALSE;
        }
    }

    public function countArticlesPublie() {
        $sql = "SELECT count(*) as total FROM articles "
                . " WHERE publie = 1";
        $req = $this->bdd->prepare($sql);
        $req->execute();
        $count = $req->fetch(PDO::FETCH_ASSOC);
        $total = $count['total'];
        return $total;
    }

    public function getListArticlesAAfficher($depart, $limit) {
        $listArticle = [];
        $sql = 'SELECT id, '
                . 'titre, '
                . 'texte, '
                . 'publie, '
                . 'DATE_FORMAT(date, "%d/%m/%Y") as date '
                . 'FROM articles '
                . 'WHERE publie = 1 '
                . 'LIMIT :depart, :limit';

        $req = $this->bdd->prepare($sql);

        $req->bindValue(':depart', $depart, PDO::PARAM_INT);
        $req->bindValue(':limit', $limit, PDO::PARAM_INT);

        $req->execute();

        while ($donnees = $req->fetch(PDO::FETCH_ASSOC)) {
            $articles = new article();
            $articles->hydrate($donnees);
            $listArticle[] = $articles;
        }

        return $listArticle;
    }

    public function update(article $article) {
        $sql = "UPDATE articles SET "
                . "titre = :titre, texte = :texte, publie =:publie WHERE id = :id";


        $req = $this->bdd->prepare($sql);

        $req->bindValue(':titre', $article->getTitre(), PDO::PARAM_STR);
        $req->bindValue(':texte', $article->getText(), PDO::PARAM_STR);
        $req->bindValue(':publie', $article->getPublie(), PDO::PARAM_INT);
        $req->bindValue(':id', $article->getId(), PDO::PARAM_INT);


        $req->execute();
        if ($req->errorCode() == 00000) {
            $this->_result = true;
            $this->_getLastInsertId = $article->getId();
        } else {
            return FALSE;
        }
    }

    public function getListArticlesFromRecherche($recherche) {
        $listArticle = [];

        // Prépare une requête de type SELECT avec une clause WHERE selon l'id.
        $sql = 'SELECT id, '
                . 'titre, '
                . 'texte, '
                . 'publie, '
                . 'DATE_FORMAT(date, "%d/%m/%Y") as date '
                . 'FROM articles '
                . 'WHERE publie = 1 '
                . 'AND (titre LIKE :recherche '
                . 'OR texte LIKE :recherche)';
        $req = $this->bdd->prepare($sql);

        $req->bindValue(':recherche', "%" . $recherche . "%", PDO::PARAM_STR);

        // Exécution de la requête avec attribution des valeurs aux marqueurs nominatifs.
        $req->execute();

        // On stocke les données obtenues dans un tableau.
        while ($donnees = $req->fetch(PDO::FETCH_ASSOC)) {
            //On créé des objets avec les données issues de la table
            $article = new article();
            $article->hydrate($donnees);
            $listArticle[] = $article;
        }

        //print_r2($listArticle);
        return $listArticle;
    }

}
