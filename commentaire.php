<?php
require_once 'config/config.conf.php';
require_once 'config/bdd.conf.php';
require_once 'components/smarty/libs/Smarty.class.php';

//Récupérer l'id de l'article
if (isset($_GET['id']))
{
    $id_article = $_GET['id'];
    $articleManager = new articleManager($bdd);
    $article = $articleManager->get($id_article);

    if (isset($_POST['addComment']))
    {
        $commentaire = new commentaire();
        $commentaire->hydrate($_POST);
        $commentaireManager = new commentaireManager($bdd);
        $commentaireManager->add($commentaire);

        if ($commentaireManager->getResult() == true)
        {
            $_SESSION['notification']['message'] = 'Votre commentaire a été publié avec succès';
            $_SESSION['notification']['result'] = 'success'; 
        }
        else
        {
            $_SESSION['notification']['message'] = 'Une erreur est survenue lors de la publication de votre commentaire';
            $_SESSION['notification']['result'] = 'danger';            
        }
        header('Location: commentaire.php?id=' . $id_article . '#comments-container');
    }
    else
    {
        if (isset($_GET['id']))
        {
            $id_article = $_GET['id'];
            $commentaireManager = new commentaireManager($bdd);

            $listArticle = $articleManager->getOneArt($id_article);
            $listCommentaire = $commentaireManager->getListCommentaireAAfficher($id_article);

            $smarty = new Smarty();
            $smarty->setTemplateDir('templates/');
            $smarty->setCompileDir('templates_c/');

            $smarty->assign('id_article', $id_article);
            $smarty->assign('listCommentaire', $listCommentaire);
            $smarty->assign('listArticle', $listArticle);

            include_once 'includes/header.inc.php';

            $smarty->display('commentaire.tpl');

            include_once 'includes/footer.inc.php';
        }
    }
}

unset($_SESSION['notification']);