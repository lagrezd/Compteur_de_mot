<?php
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
    <title>Compteur de mots et occurences</title>
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
    <script src="https://cdn.datatables.net/buttons/1.1.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.1.2/js/buttons.html5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable( {
                colReorder: true,
                "order": [[ 0, "desc" ]],
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel'
                ]
            } );
            $('#example2').DataTable( {
                colReorder: true,
                "order": [[ 0, "desc" ]],
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel'
                ]
            } );
            $('#example3').DataTable( {
                colReorder: true,
                "order": [[ 0, "desc" ]],
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel'
                ]
            } );
        } );
    </script>


    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
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
            <!--div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#">Dashboard</a></li>
                    <li><a href="#">Settings</a></li>
                    <li><a href="#">Profile</a></li>
                    <li><a href="#">Help</a></li>
                </ul>
                <form class="navbar-form navbar-right">
                    <input type="text" class="form-control" placeholder="Search...">
                </form>
            </div-->
        </div>
    </nav>
    <div class="container-fluid">
        <div-- class="row">
            <div class="col-sm-3 col-md-2 sidebar">
                <ul class="nav nav-sidebar">
                    <li class="active"><a href="#">Dashboard <span class="sr-only">(current)</span></a></li>
                    <!--li><a href="#">Reports</a></li>
                    <li><a href="#">Analytics</a></li>
                    <li><a href="#">Export</a></li-->
                </ul>
            </div>
            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                <div class="col-xs-12">
                    <h1 class="page-header">Dashboard</h1>
                    <form action="index.php" method="POST">
                        <div class="form-group">
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="form-group">
                                        <textarea rows="15" cols="160" name="mots" id="mots" class="form-control" placeholder="Insérer la liste des mots ici..." style="resize: vertical"><?php if (isset($_POST['mots'])) { echo htmlspecialchars($_POST['mots']);} ?></textarea>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-5">
                            <!--div class="form-group">
                                <label for="url">Ou par url</label>
                                <input class="form-control" type="text" name="url" id="url" value="http://" size="47">
                            </div>
                            <div class="form-group">
                                <label for="file">Ou par fichier</label>
                                <input type="file" name="file" id="file">
                            </div>
                            <h3>Options</h3>
                            <div class="col-md-6 checkbox">
                                <label>
                                    <input type="checkbox" id="checkboxSuccess" value="option1" checked="checked">
                                    Texte en minuscule<br>(pour éviter les doublons)
                                </label>
                            </div>
                            <div class="col-md-6 checkbox" style="margin-top: 10px">
                                <label>
                                    <input type="checkbox" id="checkboxSuccess" value="option1" checked="checked">
                                    Enlever les stopwords<br>
                                    <a href="stop-words_french_fr.txt" target="_blank">voir la liste</a>
                                </label><br><br>
                            </div-->
                            <div class="form-group">
                                <input type="submit" value="Envoyer" name="submit_button" id="submit_button" class="btn btn-primary btn-lg">
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-xs-6">
                    <h2>Occurence de mots</h2>
                    <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Nb.</th>
                            <th>Mot clés</th>
                            <th>Densité en %</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if(isset($_POST['mots'])) {
                            $cow->addText($_POST['mots']);
                            $cow->process();
                            //echo $cow->printSummary();
                            $words = $cow->getTopNGrams(100);
                            foreach($words as $key => $value)
                            {
                                ?>
                                <tr>
                                <td><?php echo $value; ?></td>
                                <td><?php echo $key; ?></td>
                                <td>-</td>
                                </tr><?php
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-xs-6">
                    <h2>Occurence de 2 mots</h2>
                    <table id="example2" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Nb.</th>
                            <th>Mots clés</th>
                            <th>Densité en %</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if(isset($_POST['mots'])) {
                            $cow = new CountOfWords();
                            $cow->addText($_POST['mots']);
                            $cow->process2();
                            //echo $cow->printSummary();
                            $words = $cow->getTopNGrams(100);
                            foreach($words as $key => $value)
                            {
                                ?>
                                <tr>
                                <td><?php echo $value; ?></td>
                                <td><?php echo $key; ?></td>
                                <td>-</td>
                                </tr><?php
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-xs-12">
                    <h2>Occurence de 3 mots</h2>
                    <table id="example3" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Nb.</th>
                            <th>Expression de 3 mots</th>
                            <th>Densité en %</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if(isset($_POST['mots'])) {
                            $cow = new CountOfWords();
                            $cow->addText($_POST['mots']);
                            $cow->process3();
                            //echo $cow->printSummary();
                            $words = $cow->getTopNGrams(100);
                            foreach($words as $key => $value)
                            {
                                ?>
                                <tr>
                                <td><?php echo $value; ?></td>
                                <td><?php echo $key; ?></td>
                                <td>-</td>
                                </tr><?php
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                </div>
            <!--h3>Statistiques :</h3>
            <ul>
                <li><strong><?php echo $count_word->getAllWordsCount($requete_all); ?></strong> mots : (mots convertis en minuscule pour enlever les doublons)
                    <ul>
                        <li>
                            <strong><?php echo $nbr_mots_hors_stopwords = str_word_count($count_word->removeStopWords($requete_all), 0, $count_word->getAllWordsCount($requete_all)); ?>
                            </strong> mots hors stop words (<?php echo $pourcentage_stopwords = round(($nbr_mots_hors_stopwords*100/$count_word->getAllWordsCount($requete_all)), 0);?> %)
                        </li>
                        <li><strong><?php echo ($count_word->getAllWordsCount($requete_all)-$nbr_mots_hors_stopwords); ?></strong> stop words (<?php echo (100-$pourcentage_stopwords);?> %)</li>
                    </ul>
                </li>
                <li><strong><?php
                        $mots_stopwords = explode(' ', strtolower($requete_all));
                        echo $mot_unique = count ($nbr_mots_unique = array_unique($mots_stopwords));
                        //var_dump($nbr_mots_unique);
                        ?></strong> mots uniques (mots convertis en minuscule pour les doublons):</strong>
                    <ul>
                        <li><strong>
                                <!-- supprimer les tableaux vides et lignes vides
                                array_filter(array_map('array_filter', $montab));
                                -->
                                <?php
                                $mots_hors_stopwords = explode(' ', $mots_hors_stopwords);
                                $mots_hors_stopwords = array_filter(array_unique($mots_hors_stopwords));
                                echo $mots_unique_hors = count($mots_hors_stopwords); ?></strong> mots hors stop words (<?php echo $pourcentage_stopwords_unique = round(($mots_unique_hors*100/$mot_unique), 0);?>%)</li>
                        <li><strong><?php echo ($mot_unique-$mots_unique_hors); ?></strong> stop words (<?php echo (100-$pourcentage_stopwords_unique);?>%)</li>
                    </ul>
                </li>
                <li><strong><?php echo $count_word->getCaracteres($requete_all);?></strong> caratères</li>
                <li><strong><?php echo $nbr_caracteres_sans_espaces = strlen(utf8_decode(str_replace(' ', '', utf8_decode($requete_all))));?></strong> caractères sans les espaces</li>
            </ul>
            <?php //} ?>
        </div-->
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