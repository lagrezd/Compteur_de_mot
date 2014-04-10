<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Compteur de mots et occurences</title>
</head>
<body>
<h1>Compteur de mots </h1>
    <form action="http://localhost/Lab/compteur-file/index.php" method="POST">
        <textarea rows="15" cols="175" name="mots" id="mots"><?php if (isset($_POST['mots'])) { echo htmlspecialchars($_POST['mots']);} ?></textarea><br>
        <input type="submit" value="OK" name="submit_button" id="submit_button">
    </form>
    <h2>Statistiques :</h2>
     <?php
        $requete_all = isset($_POST['mots']) ? $_POST['mots'] : '';
        $nbr_expressions = strlen(utf8_decode($requete_all));
        $file = fopen("stop-words/stop-words_french_fr.txt", "r");
        $stopwords = array();
        while (!feof($file)) {
           $stopwords[] = fgets($file);
        }
        fclose($file); ?>
        <ul>
          <li><strong><?php echo $nbr_expressions;?></strong> caratères</li>
          <li><strong><?php $sansespaces = str_replace(' ', '', $requete_all);echo strlen(utf8_decode($sansespaces)); ?></strong> caractères sans les espaces</li>
          <li><strong><?php $nbr_mots = str_word_count($requete_all, 0); echo $nbr_mots;?></strong> mots :</strong>
            <ul>
              <li><?php
                    foreach ($stopwords as &$word) {
                        $word = '/\b' . preg_quote($word, '/') . '\b/';
                    }
                    $test2 = preg_replace($word, '', $requete_all);
                    //print_r($test2);
                    echo count($test2); ?> mots hors stop words (%)</li>
              <li> stop words (%)</li>
            </ul>
          </li>
          <li><strong><?php $array2 = array_unique($requete_all);echo count($array2);?></strong> mots uniques :</strong>
            <ul>
              <li><?php
                echo preg_replace("\b$stopwords\b", ",", $array2);?> mots hors stop words (%)</li>
              <li> stop words (%)</li>
            </ul>
          </li>
        </ul>
        <h3>Liste des mots uniques : <pre><?php print_r($array2);?></pre></h3>
        <h3>Liste des occurences :</h3>
            <?php

                $test_trim  = array_map('trim',$array);

                    foreach ($array2 as &$value) {
                        //$nbr_occurence = substr_count(implode($requete_all), trim($requete_all)). ' => '.$requete_all;
                        //echo $nbr_occurence;

                        $test_count = substr_count( implode($array), $value );
                        if ($test_count >= 2 and strlen($value) >= 2){
                          echo "$value => $test_count<br />\n";
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