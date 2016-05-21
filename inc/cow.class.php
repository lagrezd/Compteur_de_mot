<?php

/**
 * Created by Compteur_de_mot.
 * User: Damien
 * Date: 17/04/2016
 * Description :
 * @package cow.class.php
 */
class CountOfWords
{

    public static $version = "1.0";
    private $text;
    private $N;
    private $unigrams;
    private $nGrams;
    private $nGramCounts;

    //Options
    public $removeStopWords;
    public $includeLowerNGrams;
    public $convertToLower;
    const stop_words_file="stop_words_french_fr.txt";
    const verbose=FALSE;

    /**
     * CountOfWords constructor.
     */
    public function __construct()
    {
        $this->clean();
    }

    public function clean(){
        $this->text='';
        $this->N=1;
        $this->unigrams = array();
        $this->nGrams = array();
        $this->nGramCounts = array();
        $this->processed=FALSE;
        $this->removeStopWords=FALSE;
        $this->includeLowerNGrams=FALSE;
        $this->convertToLower=TRUE;
    }

    public function process(){
        $this->cleanText();
        $this->identifyNGrams();
        $this->countNGrams();
        $this->processed = TRUE;
    }
    public function process2(){
        $this->cleanText();
        $this->identifyNGrams(2);
        $this->countNGrams();
        $this->processed = TRUE;
    }
    public function process3(){
        $this->cleanText();
        $this->identifyNGrams(3);
        $this->countNGrams();
        $this->processed = TRUE;
    }

    public function addText($text){
        $this->text .= ' || '.$text;
    }

    public function addFile($filename){
        $text = file_get_contents($filename);
        var_dump($text);
        if($text!=FALSE) {
            return $this->addText($text);
        } else {
            return false;
        }
    }

    public function setText($text){
        $this->text = $text;
    }

    public function getText(){
        return $this->text;
    }

    //PRIVATE METHODS
    private function cleanText() {

        $searchReplace = array(
            //REMOVALS
            "'<script[^>]*?>.*?</script>'si" => " " //Strip out Javascript
        , "'<style[^>]*?>.*?</style>'si" => " " //Strip out Styles
        , "'<[/!]*?[^<>]*?>'si" => " " //Strip out HTML tags
            //ACCEPT ONLY
        , "/[^a-zA-Z0-9\-' ]/" => " " //only accept these characters

        );
        foreach($searchReplace as $s=>$r){
            $search[]=$s;
            $replace[]=$r;
        }
        $this->setText(utf8_encode($this->getText()));
        $this->setText(html_entity_decode($this->getText()));
        if($this->convertToLower) $this->setText(strtolower($this->getText()));
        //$this->setText(strip_tags($this->text));
        //if(self::verbose) { echo "<hr>BEFORE<hr><pre>"; echo $this->getText(); echo "</pre>";}
        $this->setText(preg_replace($search, $replace, $this->getText()));
        //if(self::verbose) { echo "<hr>AFTER<hr><pre>"; print_r( preg_split('/\s+/',$this->getText()) ); echo "</pre>";}
    }

    private function addNGrams($nGrams){
        foreach($nGrams as $nGram){
            $this->nGrams[] = $nGram;
        }
    }

    private function identifyNGrams($N=null) {
        if($N==null) $N=$this->N;
        $numUnigrams = count($this->unigrams);
        if($numUnigrams==0) {
            $this->identifyUnigrams();
            $numUnigrams = count($this->unigrams);
        }
        if($N>1){
            $nGrams = array();
            for($i=($N-1); $i<$numUnigrams; $i++){
                $nGram = "";
                for($j=0; $j<$N; $j++){
                    $nGram = $this->unigrams[$i-$j].' '.trim($nGram);
                }
                $nGrams[] = trim($nGram);
            }
        } else {
            $nGrams = $this->unigrams;
        }
        //if($this->removeStopWords) $nGrams = $this->removeStopWords($nGrams);
        $this->addNGrams($nGrams);
        if($this->includeLowerNGrams && $N>1) {
            $this->identifyNGrams($N-1);
        }
    }

    private function identifyUnigrams(){
        $unigrams = preg_split('/\s+/',trim($this->getText()));
        if($this->removeStopWords) {
            $this->unigrams = $this->removeStopWords($unigrams);
        } else {
            $this->unigrams=$unigrams;
        } // printa($this->unigrams);
    }

    //STATIC METHODS - can be called without having an instance of TextMiner
    //removeStopWords: removes the stopwords from a referenced array
    public static function removeStopWords (&$words) { //expects an array ([0] = w1, [1] = w2, etc.)
        $numWordsIn = count($words);
        if(self::verbose) { echo "removedStopWords => wordcount (IN: ".$numWordsIn.") "; }
        if(file_exists(self::stop_words_file)) {
            $stopWords = explode("\n",strtolower(file_get_contents(self::stop_words_file)));
        } else {
            $stopWords = array("","the","and","a","of","by","although","i","to","in","on","at","but","or","nor","for","-", 'aucun');
        }
        //printa($stopWords);
        $words = array_diff($words,$stopWords);
        $words = array_values($words);//re-indexes array
        $numWordsOut = count($words);
        if(self::verbose) { echo " (OUT: ".$numWordsOut.") Removed: ".($numWordsIn-$numWordsOut)."<br/>"; }
        return $words;
    }

    public function getTopNGrams($n=10,$as_array=TRUE){
        $results = array_slice($this->nGramCounts,0,$n,TRUE);
        if($as_array) {
            return $results;
        } else {
            return implode(', ',array_keys($results));
        }
    }

    public function setNGramCounts($nGramCounts){
        arsort($nGramCounts);
        $this->nGramCounts = $nGramCounts;
    }

    private function countNGrams() {
        $nGramCounts = array_count_values($this->getNGrams());
        /*if(1||$this->removeRedundantLesserGrams){
            arsort($nGramCounts);
            foreach($nGramCounts as $k=>$v){
                echo "$k:$v\n";
            }
        }*/
        $this->setNGramCounts($nGramCounts);
    }

    public function printSummary(){
        echo "======================<br/>";
        echo "Text: <b>".trim(substr($this->getText(),0,200))."...</b><br/>";
        echo "Total nGrams: <b>".count($this->getNGrams())."</b><br/>";
        echo "======================<br/>";
    }

    public function getNGrams(){
        return $this->nGrams;
    }


    /*
public function getCaracteres($txt){
    return strlen(utf8_decode($txt));
}

public function cleanText()
{
    // Remove this caractere
    return "âàáãäåÀÁÃÂçÇêéèêëÊÉìíïîÌÍÎÏñôðòóõöÒÓÔÕÖûùúüÙÚÛÜÿýÝŸ";

}

public function removeStopWords($txt)
{
    $file = fopen("./stop-words/stop-words_french_fr.txt", "r");
    $stopwords = array();
    while (!feof($file)) {
        $stopwords[] = fgets($file);
    }
    fclose($file);
    foreach ($stopwords as &$word) {
        $word =  '/\b' . preg_quote(rtrim($word), '/' ) . '\b/u';
    }
    return preg_replace($stopwords, '', strtolower($txt));

}

public function getAllWordsCount($txt) {
    return str_word_count($txt, 0, $this->cleanText());
}

/**
 * @param $txt
 * @return array
 *//*
    public function getWordsCount($txt)
    {
        $words = array();
        if(preg_match_all('~\p{L}+~',$txt,$matches) > 0)
        {

            foreach ($matches[0] as $w)
            {
               $words[$w] = isset($words[$w]) === false ? 1 : $words[$w] + 1;
                //var_dump($words);
            }
        }

        return $words;
    }

    /**
     * @param $txt
     * @return array
     *//*
    public function getTwoWordsCount($txt, $words)
    {

        $words2 = array();
        $occurences = array_count_values(str_word_count(strtolower(str_replace("'", ' ', $txt)), 1));
        /*if(preg_match_all('~\p{L}+~',$txt,$matches) > 0)
        {

            foreach ($matches[0] as $w)
            {
                $words2[$w] = isset($words2[$w]) === false ? 1 : $words2[$w] + 1;
                //var_dump($words);
            }
        }*//*
        var_dump($occurences);

        return $occurences;
    }

    /**
     * @param $txt
     * @return array
     *//*
    public function getThreeWordsCount($txt,$words)
    {

        $words3 = array();
        if(preg_match_all('~\p{L}+~',$txt,$matches) > 0)
        {

            foreach ($matches[0] as $w)
            {
                $words3[$w] = isset($words3[$w]) === false ? 1 : $words3[$w] + 1;
                //var_dump($words);
            }
        }

        return $words3;
    }

    function my_meta_description($txt,$n=3)
    {
        $txt=strip_tags($txt);  // not neccssary for none HTML
        // $text=strip_shortcodes($text); // uncomment only inside wordpress system
        $txt = trim(preg_replace("/\s+/"," ",$txt));

        $word_array = explode(" ", $txt);
        var_dump($word_array);
        if (count($word_array) <= $n) {
            return implode(" ",$word_array);
        } else {
            $txt='';
            foreach ($word_array as $length=>$word)
            {

                $txt.=$word ;
                if($length==$n) break;
                else $txt.=" ";
            }
        }
        return $txt;
    }*/

}

if(!function_exists('printa')) {
    function printa($array){
        echo "<pre>";
        print_r($array);
        echo "</pre>";
    }
}