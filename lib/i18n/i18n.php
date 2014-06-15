<?php
namespace i18n;

// example constant to use for message files
define('APP_BASE', dirname(__FILE__).DIRECTORY_SEPARATOR);

class i18n {

  protected $path = "";
  protected $lang = "en";
  protected $dir;
  protected $_phrases = array();


  /**
   * Constructor
   * @param String $lang language file to load (en=messages_en.txt, etc)
   * @param String $dir directory to the language files
   */
  public function __construct($lang="en", $dir = APP_BASE)
  {
    $this->setLang($lang);
    $this->setDir($dir.$this->path.DIRECTORY_SEPARATOR);
    $this->loadPhrases();
  }

  protected function setLang($lang)
  {
    $this->lang = $lang;
  }

  protected function setDir($dir)
  {
    $this->dir = $dir;
  }

  protected function loadPhrases()
  {
    $filename = $this->dir."messages_".$this->lang.".ini";
    if(!file_exists($filename)) {
      return false;
    }

    $this->_phrases = $this->flatten(parse_ini_file($filename, true, INI_SCANNER_RAW));

    //error_log("---------------------- PHRASES -------------------" . print_r($this->_phrases, 1));
  }

  public function getLanguage () {
    return $this->lang;
  }

  protected function flatten($array, $prefix = '') {
    $result = array();
    foreach($array as $key=>$value) {
      if(is_array($value)) {
        $result = $result + $this->flatten($value, $prefix . $key . '.');
      }
      else {
        $result[$prefix . $key] = $value;
      }
    }
    return $result;
  }


  /**
   *
   * Call this with the phrase you're looking for,
   * plus any extra parameters
   *
   * the messages files have key/value pairs like this:
   * value.greaterthan=The value {0} has to be greater than {1}
   *
   * $i->getPhrase("value.greaterthan",50,77);
   *
   * would return
   * The value 50 has to be greater than 77
   *
   * @return String (or null if phrase isn't found)
   */
  public function get($phrase)
  {
    $args = func_get_args();
    array_shift($args);
    if(!array_key_exists($phrase, $this->_phrases))
    {
      return null;
    }
    $text = $this->_phrases[$phrase];
    foreach($args as $key=>$arg)
    {
      $text = str_replace("{".$key."}", $arg, $text);
    }
    return $text;
  }

}

?>
