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
     * Returns array with vocative firs name and title.
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
     * Return vocative first name with title
     *
     * @return string
     */
    public function getVocativeString()
    {
        return $this->getDetectedTitle() . ' ' . $this->getVocativeFirstName();
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

            switch ($first_name) {
                case mb_substr($first_name, -2, 2) == "ni":
                    $this->_vocative = ['M', $first_name];
                    break;
                case mb_substr($first_name, -2, 2) == "eł":
                    $this->_vocative = ['M', mb_substr($first_name, 0, -2) . "le"];
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
                case mb_substr($first_name, -3, 3) == "per":
                    $this->_vocative = ['M', mb_substr($first_name, 0, -2) . "rze"];
                    break;
                case mb_substr($first_name, -2, 2) == "ek":
                    $this->_vocative = ['M', mb_substr($first_name, 0, -2) . "ku"];
                    break;
                case mb_substr($first_name, -2, 2) == "st":
                    $this->_vocative = ['M', mb_substr($first_name, 0, -2) . "ście"];
                    break;
                case in_array(mb_substr($first_name, -3, 3),
                        array("cja", "ria", "lia", "dia", "wia", "fia")) || in_array(mb_substr($first_name, -4, 4),
                        array("iela", "bela", "zula", "saba")):
                    $this->_vocative = ['W', mb_substr($first_name, 0, -1) . "o"];
                    break;
                case in_array(mb_substr($first_name, -4, 4), array("iola", "rola")) :
                    $this->_vocative = ['W', mb_substr($first_name, 0, -1) . "u"];
                    break;
                case mb_substr($first_name, -3, 3) == "aja":
                    $this->_vocative = ['W', mb_substr($first_name, 0, -1) . "u"];
                    break;
                case in_array(mb_substr($first_name, -2, 2), array("ja", "ia", "la")) :
                    $this->_vocative = ['W', mb_substr($first_name, 0, -1) . "o"];
                    break;
                case mb_substr($first_name, -2, 2) == "ba":
                    $this->_vocative = ['M', mb_substr($first_name, 0, -1) . "o"];
                    break;
                case in_array(mb_substr($first_name, -1, 1), array("n", "f", "m", "w", "p", "s", "b")):
                    $this->_vocative = ['M', $first_name . "ie"];
                    break;
                case in_array(mb_substr($first_name, -1, 1), array("g", "h", "j", "k", "l", "z")):
                    $this->_vocative = ['M', $first_name . "u"];
                    break;
                case mb_substr($first_name, -3, 3) == "der":
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
                case mb_substr($first_name, -1, 1) == "y":
                    $this->_vocative = ['M', $first_name];
                    break;
                default:
                    $this->_vocative = ['U', $first_name];
            }
        }
    }
}