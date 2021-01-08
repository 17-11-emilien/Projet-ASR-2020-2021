<?php
require_once 'config/config.conf.php';
require_once 'config/bdd.conf.php';

include_once 'includes/header.inc.php';

if(isset($_POST['submit'])){

    $utilisateur= new utilisateur();
    $utilisateur->hydrate($_POST);

    $utilisateurManager = new utilisateurManager($bdd);
    $utilisateurbdd = $utilisateurManager->getByMail($utilisateur->getMail());

    $isConnect = password_verify($utilisateur->getMdp(), $utilisateurbdd->getMdp());

    print_r2($utilisateurbdd);

    var_dump($isConnect);

    if($isConnect == true){
        $sid = md5($utilisateurbdd->getMail() . time() );
        setcookie('sid', $sid , time() + 86400);
        $utilisateur->setSid($sid);
        $utilisateurManager->updateByMail($utilisateur);
    }

    if($isConnect==true){
        $_SESSION['notification']['message']='Connecté';
        $_SESSION['notification']['result']='success';
        header("Location: index.php?p=1");

    }else{
    $_SESSION['notification']['message']='Une erreur est survenue pendant la connexion';    
    $_SESSION['notification']['result']='danger'; 
    header("Location: index.php?p=1");
    }

    exit();
    
}else{
    include_once 'includes/header.inc.php';
?>

<body>
<!-- Page Content -->
  <div class="container">
    <div class="row">
      <div class="col-lg-12 text-center">
        <h1 class="mt-5">Connexion</h1>
        <p class="lead">Veuillez compléter le formulaire ci-dessous pour vous connecter</p>
        <br>
      </div>
    </div>
  </div>
<div class="row">
    <div class="col-lg-6 offset-lg-3">
        <form id=utilisateurform" method="POST" action="connexion.php" enctype="multipart/form-data">
            
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="mail">Mail de l'utilisateur</label>
                    <input type="mail" name="mail" class="form-control" id="mail" value="" placeholder="" required>
                </div>
            </div>
            
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="mdp">Mdp de l'utilisateur</label>
                    <input type="password" name="mdp" class="form-control" id="mdp" value="" placeholder="" required>
                </div>
            </div>
            
            <div class="col-lg-12">
                <button type="submit" name="submit" id="submit" class="btn btn-primary">Se connecter</button>
            </div>
        </form>
    </div>
</div>
</body>
<?php
}
?>
