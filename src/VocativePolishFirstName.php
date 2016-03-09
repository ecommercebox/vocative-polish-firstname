<?php
/**
 * VocativePolishFirstName
 *
 * Component for remake first name to Polish vocative
 *
 * @author  Mariusz Mielnik <mariusz@ecbox.io>
 * @license  MIT
 *
 */
namespace ecbox;


class VocativePolishFirstName
{
    /**
     * Polish title definitions array
     *
     * U = Unknown (not detected)
     * M = Male
     * W = Woman
     *
     */
    private $_titles = ['U' => '', 'M' => 'Panie', 'W' => 'Pani'];

    /**
     * Vocative array
     *
     * 0 = title
     * 1 = vocative first name
     *
     */
    private $_vocative = [];

    /**
     * @var string
     */
    private $_encoding;

    /**
     * @var array
     *
     * [
     *  'Ola' => ['W', 'Olu'],
     *  'Jan' => ['M', 'Janie']
     * ]
     */
    public $_exceptions = [];

    /**
     * @param string $first_name
     * @param string $encoding
     * @param null /array $titles
     * @throws \Exception
     */
    public function __construct($first_name, $encoding = "UTF-8", $titles = null)
    {
        if (!is_null($titles)) {
            $this->setTitles($titles);
        }
        $this->setEncoding($encoding);
        $this->remakeToVocative($first_name);
    }

    /**
     * Returns titles definition
     *
     * @return array
     */
    public function getTitles()
    {
        return $this->_titles;
    }

    /**
     * Setup own title
     *
     * for english ex. ['U' => '', 'M' => 'Mrs.', 'W' => 'Mr.'];
     * for polish ex. ['U' => '', 'M' => 'Szanowny Panie', 'W' => 'Szanowna Pani'];
     *
     * @param array $titles
     */
    public function setTitles($titles)
    {
        $this->_titles = $titles;
    }

    /**
     * Set string encoding
     *
     * @param $encoding
     */
    public function setEncoding($encoding)
    {
        $this->_encoding = $encoding;
    }

    /**
     * Returns array with vocative firs name and gender.
     *
     * @return array
     */
    public function getVocativeArray()
    {
        return $this->_vocative;
    }

    /**
     * Returns vocative first name
     *
     * @return string
     */
    public function getVocativeFirstName()
    {
        return $this->_vocative[1];
    }

    /**
     * Returns title
     *
     * @return string
     */
    public function getDetectedTitle()
    {
        return $this->_titles[$this->_vocative[0]];
    }

    /**
     * Returns gender M - Male, W - Woman, U - Unknown
     *
     * @return string
     */
    public function getDetectedGender()
    {
        return $this->_vocative[0];
    }


    /**
     * Returns true if first name belongs to male
     *
     * @return bool
     */
    public function isMale()
    {
        if ($this->_vocative[0] == 'M') {
            return true;
        }

        return false;
    }

    /**
     * Returns true if first name belongs to woman
     *
     * @return bool
     */
    public function isWoman()
    {
        if ($this->_vocative[0] == 'W') {
            return true;
        }

        return false;
    }

    /**
     * Return vocative first name with title
     *
     * @param string $delimiter default " " (space)
     * @return string
     */
    public function getVocativeString($delimiter = ' ')
    {
        return $this->getDetectedTitle() . $delimiter . $this->getVocativeFirstName();
    }

    /**
     * Convert name to first letter up
     *
     * @param string $name
     * @return string
     */
    public function nameCaseConvert($name)
    {
        return mb_convert_case(mb_strtolower($name), MB_CASE_TITLE, $this->_encoding);
    }

    /**
     * @param string $first_name
     *
     * @return array|null
     * @throws Exception
     */
    protected function checkExceptions($first_name)
    {
        if (!isset($this->_exceptions[$first_name])) {
            return null;
        }

        if (count($this->_exceptions[$first_name]) != 2) {
            throw new Exception('Invalid format');
        }

        switch ($this->_exceptions[$first_name][0]) {
            case 'M':
            case 'W':
            case 'U':
                return $this->_exceptions[$first_name];
                break;

            default:
                throw new Exception('Undefined gender');
        }
    }

    /**
     * Remake first name to Polish vocative
     *
     * @param string $first_name
     * @return array
     * @throws \Exception
     */
    protected function remakeToVocative($first_name)
    {
        if (empty($first_name)) {
            throw new  \Exception('First name cannot be empty');
        }

        if (empty($this->_vocative)) {
            $first_name = trim($this->nameCaseConvert($first_name));
            mb_internal_encoding($this->_encoding);

            if (($vocative = $this->checkExceptions($first_name)) !== null) {
                $this->_vocative = $vocative;

                return;
            }

            switch ($first_name) {
                case in_array(mb_substr($first_name, -2, 2), array("ni", "li", "zi")):
                    $this->_vocative = ['M', $first_name];
                    break;
                case mb_substr($first_name, -2, 2) == "eł":
                    $this->_vocative = ['M', mb_substr($first_name, 0, -2) . "le"];
                    break;
                case mb_substr($first_name, -2, 2) == "ił":
                    $this->_vocative = ['M', mb_substr($first_name, 0, -1) . "le"];
                    break;
                case mb_substr($first_name, -2, 2) == "et":
                    $this->_vocative = ['M', mb_substr($first_name, 0, -1) . "cie"];
                    break;
                case mb_substr($first_name, -2, 2) == "tr":
                    $this->_vocative = ['M', $first_name . "ze"];
                    break;
                case mb_substr($first_name, -2, 2) == "ał":
                    $this->_vocative = ['M', mb_substr($first_name, 0, -1) . "le"];
                    break;
                case in_array(mb_substr($first_name, -2, 2), array("it", "rt")):
                    $this->_vocative = ['M', mb_substr($first_name, 0, -1) . "cie"];
                    break;
                case mb_substr($first_name, -4, 4) == "ciek":
                    $this->_vocative = ['M', mb_substr($first_name, 0, -4) . "ćku"];
                    break;
                case mb_substr($first_name, -4, 4) == "siek":
                    $this->_vocative = ['M', mb_substr($first_name, 0, -4) . "śku"];
                    break;
                case mb_substr($first_name, -4, 4) == "niec":
                    $this->_vocative = ['M', mb_substr($first_name, 0, -4) . "ńcu"];
                    break;
                case mb_substr($first_name, -3, 3) == "per":
                    $this->_vocative = ['M', mb_substr($first_name, 0, -2) . "rze"];
                    break;
                case in_array(mb_substr($first_name, -2, 2), array("ek", "ko")):
                    $this->_vocative = ['M', mb_substr($first_name, 0, -2) . "ku"];
                    break;
                case mb_substr($first_name, -2, 2) == "st":
                    $this->_vocative = ['M', mb_substr($first_name, 0, -2) . "ście"];
                    break;
                case mb_substr($first_name, -2, 2) == "sł":
                    $this->_vocative = ['M', mb_substr($first_name, 0, -2) . "śle"];
                    break;
                case in_array(mb_substr($first_name, -3, 3),
                        array("cja", "ria", "lia", "dia", "wia", "fia")) || in_array(mb_substr($first_name, -4, 4),
                        array("iela", "bela", "zula", "saba")):
                    $this->_vocative = ['W', mb_substr($first_name, 0, -1) . "o"];
                    break;
                case in_array(mb_substr($first_name, -4, 4), array("iola", "rola")) :
                    $this->_vocative = ['W', mb_substr($first_name, 0, -1) . "u"];
                    break;
                case in_array(mb_substr($first_name, -3, 3), array("aja", "sia")) :
                    $this->_vocative = ['W', mb_substr($first_name, 0, -1) . "u"];
                    break;
                case in_array(mb_substr($first_name, -2, 2), array("ja", "ia", "la")) :
                    $this->_vocative = ['W', mb_substr($first_name, 0, -1) . "o"];
                    break;
                case mb_substr($first_name, -2, 2) == "ba":
                    $this->_vocative = ['M', mb_substr($first_name, 0, -1) . "o"];
                    break;
                case in_array(mb_substr($first_name, -2, 2), array("oe", "ue")) :
                    $this->_vocative = ['M', $first_name];
                    break;
                case mb_substr($first_name, -2, 2) == "oń":
                    $this->_vocative = ['M', mb_substr($first_name, 0, -1) . "niu"];
                    break;
                case in_array(mb_substr($first_name, -1, 1), array("n", "f", "m", "w", "p", "s", "b")):
                    $this->_vocative = ['M', $first_name . "ie"];
                    break;
                case mb_substr($first_name, -3, 3) == "bel":
                    $this->_vocative = ['M', mb_substr($first_name, 0, -3) . "ble"];
                    break;
                case mb_substr($first_name, -2, 2) == "ez":
                    $this->_vocative = ['W', $first_name];
                    break;
                case in_array(mb_substr($first_name, -1, 1), array("g", "h", "j", "k", "l", "z")):
                    $this->_vocative = ['M', $first_name . "u"];
                    break;
                case mb_substr($first_name, -3, 3) == "der":
                    $this->_vocative = ['M', mb_substr($first_name, 0, -2) . "rze"];
                    break;
                case mb_substr($first_name, -4, 4) == "ster":
                    $this->_vocative = ['M', mb_substr($first_name, 0, -2) . "rze"];
                    break;
                case mb_substr($first_name, -1, 1) == "r":
                    $this->_vocative = ['M', $first_name . "ze"];
                    break;
                case mb_substr($first_name, -1, 1) == "d":
                    $this->_vocative = ['M', $first_name . "zie"];
                    break;
                case mb_substr($first_name, -1, 1) == "a":
                    $this->_vocative = ['W', mb_substr($first_name, 0, -1) . "o"];
                    break;
                case mb_substr($first_name, -1, 1) == "e":
                    $this->_vocative = ['W', $first_name];
                    break;
                case mb_substr($first_name, -1, 1) == "y":
                    $this->_vocative = ['M', $first_name];
                    break;
                case mb_substr($first_name, -1, 1) == "o":
                    $this->_vocative = ['M', $first_name];
                    break;
                case mb_substr($first_name, -1, 1) == "t":
                    $this->_vocative = ['M', mb_substr($first_name, 0, -1) . "cie"];
                    break;
                default:
                    $this->_vocative = ['U', $first_name];
            }
        }
    }
}