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
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.11/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/colreorder/1.3.1/css/colReorder.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.1.2/css/buttons.dataTables.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/colreorder/1.3.1/js/dataTables.colReorder.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.1.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.1.2/js/buttons.html5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable( {
                colReorder: false,
                "order": [[ 0, "desc" ]],
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel'
                ]
            } );
            $('#example2').DataTable( {
                colReorder: false,
                "order": [[ 0, "desc" ]],
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel'
                ]
            } );
            $('#example3').DataTable( {
                colReorder: false,
                "order": [[ 0, "desc" ]],
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel'
                ]
            } );
        } );
    </script>
</head>
<body style="padding-top: 65px;">
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
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
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <form action="index.php" method="POST">
                        <div class="form-group">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <textarea rows="15" cols="160" name="mots" id="mots" class="form-control" placeholder="Insérer la liste des mots ici..." style="resize: vertical"><?php if (isset($_POST['mots'])) { echo htmlspecialchars($_POST['mots']);} ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
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
                                        <a href="../stop-words/stop-words_french_1_fr.txt" target="_blank">voir la liste</a>
                                    </label><br><br>
                                </div>
                                <div class="form-group">
                                    <input type="submit" value="Envoyer" name="submit_button" id="submit_button" class="btn btn-primary btn-lg">
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <h2>Occurence de mots</h2>
                    <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Nb occurences</th>
                            <th>Mot clés</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if(isset($_POST['mots'])) {
                            $cow->addText($_POST['mots']);
                            $cow->process();
                            //echo $cow->printSummary();
                            $words = $cow->getTopNGrams(50);
                            foreach($words as $key => $value)
                            {
                                ?>
                                <tr>
                                <td><?php echo $value; ?></td>
                                <td><?php echo $key; ?></td>
                                </tr><?php
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            <div class="col-xs-4">
                <h2>Occurence de 2 mots</h2>
                <table id="example2" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Nb occurences</th>
                        <th>Mots clés</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        if(isset($_POST['mots'])) {
                            $cow = new CountOfWords();
                            $cow->addText($_POST['mots']);
                            $cow->process2();
                            //echo $cow->printSummary();
                            $words = $cow->getTopNGrams(50);
                            foreach($words as $key => $value)
                            {
                                ?>
                                <tr>
                                <td><?php echo $value; ?></td>
                                <td><?php echo $key; ?></td>
                                </tr><?php
                            }
                        }
                    ?>
                    </tbody>
                </table>
            </div>
            <div class="col-xs-4">
                <h2>Occurence de 3 mots</h2>
                <table id="example3" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Nb occurences</th>
                        <th>Mots clés</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if(isset($_POST['mots'])) {
                        $cow = new CountOfWords();
                        $cow->addText($_POST['mots']);
                        $cow->process3();
                        //echo $cow->printSummary();
                        $words = $cow->getTopNGrams(50);
                        foreach($words as $key => $value)
                        {
                            ?>
                            <tr>
                            <td><?php echo $value; ?></td>
                            <td><?php echo $key; ?></td>
                            </tr><?php
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            </div>
        <div class="row">
            <h3>Statistiques :</h3>
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
        </div>
    </div>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</body>
</html>