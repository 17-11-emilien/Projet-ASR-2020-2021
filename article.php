<?php
require_once 'config/config.conf.php';
require_once 'config/bdd.conf.php';

include_once 'includes/header.inc.php';

if($utilisateurConnecte->isConnect == false){
    $_SESSION['notification']['message']='Vous devez être connecté pour accéder à la page';
    $_SESSION['notification']['result']='danger';
    header("Location: connexion.php");
}

if(isset($_POST['submit'])){
    echo "le formulaire est posté";
    
    $article = new article();
    $article->hydrate($_POST);
    
    $article->setDate(date('Y-m-d'));
    
    $publie = $article->getPublie() === 'on' ? 1 : 0;
    
    $article->setPublie($publie);
    print_r2($article);
    
    //insertion article
    $articleManager = new articleManager($bdd);
    if($_POST['submit'] == 'modifier')
    {
        $articleManager->update($article);
    }
    else
    {
        $articleManager->add($article);
    }

    if($_FILES['image']['error'] == 0){
        $fileInfos = pathinfo($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], 'img/' . $articleManager->get_getLastInsertId() . '.' . $fileInfos['extension']);
    }
    if ($articleManager->get_result() == true) {
        $_SESSION['notification']['message'] = 'l\'article a bien été mis à jour';
        $_SESSION['notification']['result'] = 'success';
    } else {
        $_SESSION['notification']['message'] = 'Une erreur est survenue pendant la création de votre article';        
        $_SESSION['notification']['result'] = 'danger';

    }
    
    header("Location: index.php?p=1");
    exit();
    
}else{
    $check =0;
    if(!empty($_GET['id']))
    {
        $articleManager = new articleManager($bdd);
        $article = $articleManager->get($_GET['id']);
        $check = 1;
    }    
    else
    {
      $article = new article();  
      $article->hydrate(array());
    }
    include_once 'includes/header.inc.php';

?>

<body>
<!-- Page Content -->
  <div class="container">
    <div class="row">
      <div class="col-lg-12 text-center">
        <h1 class="mt-5">Ajout d'un article</h1>
        <p class="lead">Veuillez compléter le formulaire ci-dessous pour ajouter un article</p>
      </div>
    </div>
  </div>
<div class="row">
    <div class="col-lg-6 offset-lg-3">
        <form id=articleform" method="POST" action="article.php" enctype="multipart/form-data">
            <input type="hidden" id="id" name="id" value="<?php echo $article->getId()?>">
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="titre">Titre</label>
                    <input type="text" name="titre" class="form-control" id="titre" value="<?php echo $article->getTitre(); ?>" placeholder="" required>
                </div>
            </div>
            
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="texte">Texte de l'article</label>
                    <textarea name="texte" class="form-control" id="texte" rows="3" required><?php echo $article->getText() ?></textarea>
                </div>
            </div>
            
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="image">L'image de l'article</label>
                    <input type="file" name="image" class="form-control-file" id="image">
                </div>
            </div>
                 
            <div class="col-lg-12">
                <div class="form-group form-check">
                    <input type="checkbox" name="publie" class="form-check-input" id="exampleCheck1" checked="<?php if($check == 1){ echo'checked';} ?>">
                    <label class="form-check-label" for="publie" id="publie" >Article publié ?</label>
                </div>
            </div>
            <div class="col-lg-12">
                <button type="submit" name="submit" value='<?php if($check == 1){ echo 'modifier';} else {echo 'ajouter';}      ?>' id="submit" class="btn btn-primary">  <?php if($check == 1){ echo'Mettre à jour mon article';}  else{echo'Publier mon article';} ?>  </button>
                
            </div>
        </form>
    </div>
</div>
</body>
<?php

}
?>