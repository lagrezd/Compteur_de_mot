<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);
include('/inc/cow.class.php');
$cow = new CountOfWords();

//var_dump($cow);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Compteur de mots et occurences - Synthèse</title>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/dashboard.css" rel="stylesheet">

    <!--link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
    <!--link rel="stylesheet" href="https://cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css"-->
    <link rel="stylesheet" href="https://cdn.datatables.net/colreorder/1.3.2/css/colReorder.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.1.2/css/buttons.dataTables.min.css">

    <script src="//code.jquery.com/jquery-1.12.3.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/colreorder/1.3.2/js/dataTables.colReorder.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.0/js/dataTables.buttons.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.2.0/js/buttons.flash.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.1.2/js/buttons.html5.min.js"></script>

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Compteur de mots</a>
        </div>
    </div>
</nav>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            <ul class="nav nav-sidebar">
                <li><a href="http://localhost/Compteur_de_mot/">Dashboard <span class="sr-only">(current)</span></a></li>
                <li class="active"><a href="#">Synthèse</a></li>
                <!--li><a href="#">Graphique</a></li-->
                <li><a href="#">Stop words</a></li>
            </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main" style="padding-top: 15px">
            <div class="col-xs-12">
                <h1 class="page-header">Synthèse</h1>

                <?php
                //$_SESSION['mots'] = $_GET['mots'];

                if(isset($_SESSION['mots'])) {
                    //echo 'post mot ok';
                    $cow->addText($_SESSION['mots']);
                    $cow->process();
                    //$words = $cow->getTopNGrams(100);
                } ?>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                   <div class="dashboard-stat blue">
                        <div class="visual">
                            <i class="fa fa-comments"></i>
                        </div>
                        <div class="details">
                            <div class="number"> <?php echo $cow->getAllWordsCount($_SESSION['mots']); ?> </div>
                            <div class="desc">  mots </div>
                        </div>
                        <a class="more" href="#"> Voir le détail
                            <i class="m-icon-swapright m-icon-white"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="dashboard-stat red">
                        <div class="visual">
                            <i class="fa fa-bar-chart-o"></i>
                        </div>
                        <div class="details">
                            <div class="number"> -<?php //echo $nbr_mots_hors_stopwords = str_word_count($cow->removeStopWords($_SESSION['mots']), 0, $cow->getAllWordsCount($_SESSION['mots'])); ?> </div>
                            <div class="desc"> mots hors stop words (-<?php //echo $pourcentage_stopwords = round(($nbr_mots_hors_stopwords*100/$count_word->getAllWordsCount($requete_all)), 0);?> %) </div>
                        </div>
                        <a class="more" href="#"> Voir le détails
                            <i class="m-icon-swapright m-icon-white"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="dashboard-stat green">
                        <div class="visual">
                            <i class="fa fa-shopping-cart"></i>
                        </div>
                        <div class="details">
                            <div class="number"> -<?php //echo ($count_word->getAllWordsCount($requete_all)-$nbr_mots_hors_stopwords); ?></div>
                            <div class="desc">  stop words (-<?php //echo (100-$pourcentage_stopwords);?> %) </div>
                        </div>
                        <a class="more" href="#"> Voir le détails
                            <i class="m-icon-swapright m-icon-white"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="dashboard-stat purple">
                        <div class="visual">
                            <i class="fa fa-globe"></i>
                        </div>
                        <div class="details">
                            <!-- supprimer les tableaux vides et lignes vides
                            array_filter(array_map('array_filter', $montab));
                            -->
                            <div class="number"> -<?php
                                /*$mots_stopwords = explode(' ', strtolower($requete_all));
                                echo $mot_unique = count ($nbr_mots_unique = array_unique($mots_stopwords));
                                //var_dump($nbr_mots_unique);

                               */ ?> </div>
                            <div class="desc"> mots uniques : </div>
                        </div>
                        <a class="more" href="#"> Voir le détails
                            <i class="m-icon-swapright m-icon-white"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="dashboard-stat yellow">
                        <div class="visual">
                            <i class="fa fa-globe"></i>
                        </div>
                        <div class="details">
                            <div class="number"> -<?php
                                /*$mots_hors_stopwords = explode(' ', $mots_hors_stopwords);
                                $mots_hors_stopwords = array_filter(array_unique($mots_hors_stopwords));
                                echo $mots_unique_hors = count($mots_hors_stopwords);*/ ?> </div>
                            <div class="desc">  mots uniques hors stop words (-<?php //echo $pourcentage_stopwords_unique = round(($mots_unique_hors*100/$mot_unique), 0);?> %) </div>
                        </div>
                        <a class="more" href="#"> Voir le détails
                            <i class="m-icon-swapright m-icon-white"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="dashboard-stat green-dark">
                        <div class="visual">
                            <i class="fa fa-shopping-cart"></i>
                        </div>
                        <div class="details">
                            <div class="number"> -<?php //echo ($count_word->getAllWordsCount($requete_all)-$nbr_mots_hors_stopwords); ?></div>
                            <div class="desc"> mots uniques stop words (-<?php //echo (100-$pourcentage_stopwords);?> %) </div>
                        </div>
                        <a class="more" href="#"> Voir le détails
                            <i class="m-icon-swapright m-icon-white"></i>
                        </a>
                    </div>
                </div>
                <div class="col-sm-6 col-xs-12">
                    <div class="dashboard-stat white">
                        <div class="visual">
                            <i class="fa fa-globe"></i>
                        </div>
                        <div class="details">
                            <div class="number"> <?php echo $cow->getCaracteres($_SESSION['mots']);?> </div>
                            <div class="desc"> nombre de caractères </div>
                        </div>
                        <a class="more" href="#"> Voir le détails
                            <i class="m-icon-swapright m-icon-white"></i>
                        </a>
                    </div>
                </div>
                <div class="col-sm-6 col-xs-12">
                    <div class="dashboard-stat white">
                        <div class="visual">
                            <i class="fa fa-globe"></i>
                        </div>
                        <div class="details">
                            <div class="number"> <?php echo strlen(utf8_decode(str_replace(' ', '', utf8_decode($_SESSION['mots']))));?> </div>
                            <div class="desc"> nombre de caractères sans espaces</div>
                        </div>
                        <a class="more" href="#"> Voir le détails
                            <i class="m-icon-swapright m-icon-white"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <!-- Bootstrap core JavaScript
        ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/vendor/jquery.min.js"><\/script>')</script>
    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->
    <script src="js/vendor/holder.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>