<?php
header('Content-Type: text/html; charset=utf-8');
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
    const stop_words_file="stop-words_french_fr.txt";
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
        $this->removeStopWords=TRUE;
        $this->includeLowerNGrams=FALSE;
        $this->convertToLower=TRUE;
    }

    public function process(){
        $this->wd_remove_accents($this->getText());
        $this->cleanText();
        $this->identifyNGrams();
        $this->countNGrams();
        //$this->processed = TRUE;
    }
    public function process2(){
        $this->wd_remove_accents($this->getText());
        $this->cleanText();
        $this->identifyNGrams(2);
        $this->countNGrams();
        $this->processed = TRUE;
    }
    public function process3(){
        $this->wd_remove_accents($this->getText());
        $this->cleanText();
        $this->identifyNGrams(3);
        $this->countNGrams();
        $this->processed = TRUE;
    }

    public function addText($text){
        $this->text .=$text;
        //$this->text .= ' || '.$text;
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

    function wd_remove_accents($str, $charset='utf-8')
    {
        $str = htmlentities($str, ENT_NOQUOTES, $charset);

        $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
        $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
        $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères

        //var_dump($str);
        return $str;
    }

    //PRIVATE METHODS
    private function cleanText() {

        if($this->convertToLower) $this->setText(strtolower($this->getText()));

        /*$patterns[0] = '/[á|â|à|å|ä]/';
        $patterns[1] = '/[ð|é|ê|è|ë]/';
        $patterns[2] = '/[í|î|ì|ï]/';
        $patterns[3] = '/[ó|ô|ò|ø|õ|ö]/';
        $patterns[4] = '/[ú|û|ù|ü]/';
        $patterns[5] = '/æ/';
        $patterns[6] = '/ç/';
        $patterns[7] = '/ß/';
        $replacements[0] = 'a';
        $replacements[1] = 'e';
        $replacements[2] = 'i';
        $replacements[3] = 'o';
        $replacements[4] = 'u';
        $replacements[5] = 'ae';
        $replacements[6] = 'c';
        $replacements[7] = 'ss';

        $text = preg_replace($patterns, $replacements, $this->getText());*/
        $unwanted_array = array(
            '&amp;' => 'and',   '@' => 'at',    '©' => 'c', '®' => 'r', 'À' => 'a',
            'Á' => 'a', 'Â' => 'a', 'Ä' => 'a', 'Å' => 'a', 'Æ' => 'ae','Ç' => 'c',
            'È' => 'e', 'É' => 'e', 'Ë' => 'e', 'Ì' => 'i', 'Í' => 'i', 'Î' => 'i',
            'Ï' => 'i', 'Ò' => 'o', 'Ó' => 'o', 'Ô' => 'o', 'Õ' => 'o', 'Ö' => 'o',
            'Ø' => 'o', 'Ù' => 'u', 'Ú' => 'u', 'Û' => 'u', 'Ü' => 'u', 'Ý' => 'y',
            'ß' => 'ss','à' => 'a', 'á' => 'a', 'â' => 'a', 'ä' => 'a', 'å' => 'a',
            'æ' => 'ae','ç' => 'c', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e',
            'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ò' => 'o', 'ó' => 'o',
            'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u',
            'û' => 'u', 'ü' => 'u', 'ý' => 'y', 'þ' => 'p', 'ÿ' => 'y', 'Ā' => 'a',
            'ā' => 'a', 'Ă' => 'a', 'ă' => 'a', 'Ą' => 'a', 'ą' => 'a', 'Ć' => 'c',
            'ć' => 'c', 'Ĉ' => 'c', 'ĉ' => 'c', 'Ċ' => 'c', 'ċ' => 'c', 'Č' => 'c',
            'č' => 'c', 'Ď' => 'd', 'ď' => 'd', 'Đ' => 'd', 'đ' => 'd', 'Ē' => 'e',
            'ē' => 'e', 'Ĕ' => 'e', 'ĕ' => 'e', 'Ė' => 'e', 'ė' => 'e', 'Ę' => 'e',
            'ę' => 'e', 'Ě' => 'e', 'ě' => 'e', 'Ĝ' => 'g', 'ĝ' => 'g', 'Ğ' => 'g',
            'ğ' => 'g', 'Ġ' => 'g', 'ġ' => 'g', 'Ģ' => 'g', 'ģ' => 'g', 'Ĥ' => 'h',
            'ĥ' => 'h', 'Ħ' => 'h', 'ħ' => 'h', 'Ĩ' => 'i', 'ĩ' => 'i', 'Ī' => 'i',
            'ī' => 'i', 'Ĭ' => 'i', 'ĭ' => 'i', 'Į' => 'i', 'į' => 'i', 'İ' => 'i',
            'ı' => 'i', 'Ĳ' => 'ij','ĳ' => 'ij','Ĵ' => 'j', 'ĵ' => 'j', 'Ķ' => 'k',
            'ķ' => 'k', 'ĸ' => 'k', 'Ĺ' => 'l', 'ĺ' => 'l', 'Ļ' => 'l', 'ļ' => 'l',
            'Ľ' => 'l', 'ľ' => 'l', 'Ŀ' => 'l', 'ŀ' => 'l', 'Ł' => 'l', 'ł' => 'l',
            'Ń' => 'n', 'ń' => 'n', 'Ņ' => 'n', 'ņ' => 'n', 'Ň' => 'n', 'ň' => 'n',
            'ŉ' => 'n', 'Ŋ' => 'n', 'ŋ' => 'n', 'Ō' => 'o', 'ō' => 'o', 'Ŏ' => 'o',
            'ŏ' => 'o', 'Ő' => 'o', 'ő' => 'o', 'Œ' => 'oe','œ' => 'oe','Ŕ' => 'r',
            'ŕ' => 'r', 'Ŗ' => 'r', 'ŗ' => 'r', 'Ř' => 'r', 'ř' => 'r', 'Ś' => 's',
            'ś' => 's', 'Ŝ' => 's', 'ŝ' => 's', 'Ş' => 's', 'ş' => 's', 'Š' => 's',
            'š' => 's', 'Ţ' => 't', 'ţ' => 't', 'Ť' => 't', 'ť' => 't', 'Ŧ' => 't',
            'ŧ' => 't', 'Ũ' => 'u', 'ũ' => 'u', 'Ū' => 'u', 'ū' => 'u', 'Ŭ' => 'u',
            'ŭ' => 'u', 'Ů' => 'u', 'ů' => 'u', 'Ű' => 'u', 'ű' => 'u', 'Ų' => 'u',
            'ų' => 'u', 'Ŵ' => 'w', 'ŵ' => 'w', 'Ŷ' => 'y', 'ŷ' => 'y', 'Ÿ' => 'y',
            'Ź' => 'z', 'ź' => 'z', 'Ż' => 'z', 'ż' => 'z', 'Ž' => 'z', 'ž' => 'z',
            'ſ' => 'z', 'Ə' => 'e', 'ƒ' => 'f', 'Ơ' => 'o', 'ơ' => 'o', 'Ư' => 'u',
            'ư' => 'u', 'Ǎ' => 'a', 'ǎ' => 'a', 'Ǐ' => 'i', 'ǐ' => 'i', 'Ǒ' => 'o',
            'ǒ' => 'o', 'Ǔ' => 'u', 'ǔ' => 'u', 'Ǖ' => 'u', 'ǖ' => 'u', 'Ǘ' => 'u',
            'ǘ' => 'u', 'Ǚ' => 'u', 'ǚ' => 'u', 'Ǜ' => 'u', 'ǜ' => 'u', 'Ǻ' => 'a',
            'ǻ' => 'a', 'Ǽ' => 'ae','ǽ' => 'ae','Ǿ' => 'o', 'ǿ' => 'o', 'ə' => 'e',
            'Ё' => 'jo','Є' => 'e', 'І' => 'i', 'Ї' => 'i', 'А' => 'a', 'Б' => 'b',
            'В' => 'v', 'Г' => 'g', 'Д' => 'd', 'Е' => 'e', 'Ж' => 'zh','З' => 'z',
            'И' => 'i', 'Й' => 'j', 'К' => 'k', 'Л' => 'l', 'М' => 'm', 'Н' => 'n',
            'О' => 'o', 'П' => 'p', 'Р' => 'r', 'С' => 's', 'Т' => 't', 'У' => 'u',
            'Ф' => 'f', 'Х' => 'h', 'Ц' => 'c', 'Ч' => 'ch','Ш' => 'sh','Щ' => 'sch',
            'Ъ' => '-', 'Ы' => 'y', 'Ь' => '-', 'Э' => 'je','Ю' => 'ju','Я' => 'ja',
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e',
            'ж' => 'zh','з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l',
            'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's',
            'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch',
            'ш' => 'sh','щ' => 'sch','ъ' => '-','ы' => 'y', 'ь' => '-', 'э' => 'je',
            'ю' => 'ju','я' => 'ja','ё' => 'jo','є' => 'e', 'і' => 'i', 'ї' => 'i',
            'Ґ' => 'g', 'ґ' => 'g', 'א' => 'a', 'ב' => 'b', 'ג' => 'g', 'ד' => 'd',
            'ה' => 'h', 'ו' => 'v', 'ז' => 'z', 'ח' => 'h', 'ט' => 't', 'י' => 'i',
            'ך' => 'k', 'כ' => 'k', 'ל' => 'l', 'ם' => 'm', 'מ' => 'm', 'ן' => 'n',
            'נ' => 'n', 'ס' => 's', 'ע' => 'e', 'ף' => 'p', 'פ' => 'p', 'ץ' => 'C',
            'צ' => 'c', 'ק' => 'q', 'ר' => 'r', 'ש' => 'w', 'ת' => 't', '™' => 'tm',
        );
        $text = strtr( $this->getText(), $unwanted_array );
        //$text = preg_replace($_convertTable, $this->getText());
        ?><pre><?php print($text);?></pre><?php
        //die();
        $searchReplace = array(
            //REMOVALS
            "'<script[^>]*?>.*?</script>'si" => " " //Strip out Javascript
            , "'<style[^>]*?>.*?</style>'si" => " " //Strip out Styles
            , "'<[/!]*?[^<>]*?>'si" => " " //Strip out HTML tags
            //ACCEPT ONLY
            //,"/[^a-zA-Z0-9\-' ]/" => " " //only accept these characters

        );
        //foreach($searchReplace as $s=>$r){
        foreach($searchReplace as $s=>$r){
            $search[]=$s;
            $replace[]=$r;
        }
        //var_dump($this->getText());
        //$this->setText(utf8_encode($this->getText()));
        //$this->setText(html_entity_decode($this->getText()));
        /*if($this->convertToLower) $this->setText(strtolower($this->getText()));*/
        //$this->setText(strip_tags($this->text));
        if(self::verbose) { echo "<hr>BEFORE<hr><pre>"; echo $this->getText(); echo "</pre>";}
        $this->setText(preg_replace($search, $replace, $this->getText()));
        if(self::verbose) { echo "<hr>AFTER<hr><pre>"; print_r( preg_split('/\s+/',$this->getText()) ); echo "</pre>";}
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

        // remove stop words
        if($this->removeStopWords) {
            $nGrams = $this->removeStopWords($nGrams);
            //var_dump($nGrams);
        }
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
            $stopWords1 = explode("\n",file_get_contents(self::stop_words_file));
            //var_dump($stopWords1);
        } else {
            //$stopWords = array("","the","and","a","of","by","although","i","to","in","on","at","but","or","nor","for","-", 'afin', 'à', 'ainsi');
        }
        //var_dump($stopWords2);
        $words = array_diff($words, $stopWords1);
        $words = array_values($words);//re-indexes array
        //echo "you are here !";
        //var_dump($words);
        $numWordsOut = count($words);
        //echo $numWordsOut;
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