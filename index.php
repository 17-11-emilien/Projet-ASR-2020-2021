<?php
require_once 'config/config.conf.php';
require_once 'config/bdd.conf.php';
require_once('components/smarty/libs/Smarty.class.php');

$page = !empty($_GET['p']) ? $_GET['p'] : 1;

// Ici il y a la gestion du nombre de page et d'article par page

$articleManager = new articleManager($bdd);

$nbArticlesTotalAPublie = $articleManager->countArticlesPublie();

$indexDepart = ($page - 1) * nb_articles_par_page;

$nbPages = ceil($nbArticlesTotalAPublie / nb_articles_par_page);

$listeArticles = $articleManager->getListArticlesAAfficher($indexDepart, nb_articles_par_page);

$smarty = new Smarty();

$smarty->setTemplateDir('templates/');
$smarty->setCompileDir('templates_c/');

$smarty->assign('listeArticles', $listeArticles);
$smarty->assign('utilisateurConnecte', $utilisateurConnecte);
$smarty->assign('nbPages', $nbPages);
$smarty->assign('page', $page);


include_once 'includes/header.inc.php';

$smarty->display('index.tpl');

include_once 'includes/footer.inc.php';

unset($_SESSION['notification']);