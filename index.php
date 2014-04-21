<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
 ?>
 <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Compteur de mots et occurences</title>
</head>
<body>
    <h1>Compteur de mots </h1>
    <form action="index.php" method="POST">
        <textarea rows="15" cols="175" name="mots" id="mots"><?php if (isset($_POST['mots'])) { echo htmlspecialchars($_POST['mots']);} ?></textarea><br>
        <input type="submit" value="OK" name="submit_button" id="submit_button">
    </form>
    <h2>Statistiques :</h2>
     <?php
        echo $requete_all = isset($_POST['mots']) ? $_POST['mots'] : '';
        $nbr_caracteres = strlen(utf8_decode($requete_all));
        $carateres_accentues = "âàáãäåÀÁÃÂçÇêéèêëÊÉìíïîÌÍÎÏñôðòóõöÒÓÔÕÖûùúüÙÚÛÜÿýÝŸ";
        $file = fopen("stop-words/stop-words_french_fr.txt", "r");
        $stopwords = array();
        while (!feof($file)) {
           $stopwords[] = fgets($file);
        }
        fclose($file);
        echo '<br><br>Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression.';
    ?>
    <ul>
        <!--
            Compte le nombre de mot avec la liste des accents considérés comme des accentes
            print_r (str_word_count($requete_all, 1,"àáãâçêéíîóõôúÀÁÃÂÇÊÉÍÎÓÕÔÚ")); => affiche le tableau des mots
        -->
        <li><strong><?php echo $nbr_mots = str_word_count($requete_all, 0, $carateres_accentues);?></strong> mots : (mots convertis en minuscule pour les doublons)
            <ul>
              <li><?php
                    foreach ($stopwords as &$word) {
                            $word =  '/\b' . preg_quote(rtrim($word), '/' ) . '\b/u';
                    }
                    $mots_hors_stopwords = preg_replace($stopwords, '', strtolower($requete_all));
                    //var_dump($stopwords);
                    //var_dump($mots_hors_stopwords);?>
                    <strong><?php echo $nbr_mots_hors_stopwords = str_word_count($mots_hors_stopwords, 0, $carateres_accentues); ?></strong> mots hors stop words (<?php echo $pourcentage_stopwords = round(($nbr_mots_hors_stopwords*100/$nbr_mots), 0);?> %)</li>
              <li><strong><?php echo ($nbr_mots-$nbr_mots_hors_stopwords); ?></strong> stop words (<?php echo (100-$pourcentage_stopwords);?> %)</li>
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
      <li><strong><?php echo $nbr_caracteres;?></strong> caratères</li>
      <li><strong><?php
      echo $nbr_caracteres_sans_espaces = strlen(utf8_decode(str_replace(' ', '', utf8_decode($requete_all)))); ?></strong> caractères sans les espaces</li>
    </ul>
        <h3>Liste des occurences :</h3>
            <?php

                /*$test_trim  = array_map('trim',$array2);

                    foreach ($array2 as &$value) {
                        //$nbr_occurence = substr_count(implode($requete_all), trim($requete_all)). ' => '.$requete_all;
                        //echo $nbr_occurence;

                        $test_count = substr_count( implode($array), $value );
                        if ($test_count >= 2 and strlen($value) >= 2){
                          //echo "$value => $test_count<br />\n";
                        }
                        //$array_count = array(1=>$value,2=> $test_count);
                        //$array_count = asort($array_count);
                        //echo " $array_count<br />\n";
                        //echo substr_count( implode($array2), trim($array2));

                       ?><?php
                    }

                unset($value); // Détruit la référence sur le dernier élément

                  /*for($i=0; $i <  count($array2); $i++){
                    ?>
                   <li><?php
                   echo $nbr_occurence = substr_count(implode($array), trim($array[$i])). ' => '.$array[$i];

                   //print_r($array[$i]);
                   foreach ($array as $v) {
                        //echo "Valeur courante de \$array[$i]: $v.\n";
                        if ( $nbr_occurence > 1 ){
                            //echo $array[$i].' => '.substr_count(implode($array), trim($array[$i]));
                        }
                    }

                   ?></li><?php
                   // if I echo $needle[$i] it outputs oranges peas apples turnips so I know the $needle array variable is being filled properly.
                }*/?>

</body>
</html>