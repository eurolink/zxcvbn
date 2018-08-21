<?php
/*
 * This file is part of the zxcvbn package.
 *
 * (c) Eurolink <info@eurolink.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eurolink\zxcvbn\Matchers;

/**
 * Leet Dictionary Match
 *
 * This class extends DictionaryMatch to translate l33t substitutions
 * into dictionary words for matching.
 *
 * @author Eurolink <info@eurolink.co>
 */
class LeetMatch extends DictionaryMatch
{

    /**
     * Detected substitution.
     *
     * @var string
     */
    public $sub;

    /**
     * Detected substitution alternative display.
     *
     * @var string
     */
    public $subDisplay;

    /**
     * Whether or not l33t substitions were detected.
     *
     * @var boolean
     */
    public $l33t;

    /**
     * L33t substitions table.
     *
     * @var array
     */
    public static $table = [
        'a' => ['4', '@'],
        'b' => ['8'],
        'c' => ['(', '{', '[', '<'],
        'e' => ['3'],
        'g' => ['6', '9'],
        'i' => ['1', '!'],
        #'i' => ['1', '!', '|'],
        'l' => ['|', '7'],
        #'l' => ['1', '|', '7'],
        'o' => ['0'],
        's' => ['$', '5'],
        't' => ['+', '7'],
        'x' => ['%'],
        'z' => ['2'],
    ];

    /**
     * Match occurences of l33t words in password to dictionary words.
     *
     * @copydoc Match::match()
     */
    public static function match($password, array $userInputs = array())
    {
        // Translate l33t password and dictionary match the translated password.
        $map = static::getSubstitutions($password);
        $indexSubs = array_filter($map);

        if (empty($indexSubs)) {
            return [];
        }

        $translatedWord = static::translate($password, $map);

        $matches = [];
        $dicts = static::getRankedDictionaries();

        foreach ($dicts as $name => $dict) {
            $results = static::dictionaryMatch($translatedWord, $dict);

            foreach ($results as $result) {
                // Set substituted elements.
                $result['sub'] = [];
                $result['sub_display'] = '';

                foreach ($indexSubs as $i => $t) {
                    $result['sub'][$password[$i]] = $t;
                    $result['sub_display'][] = "$password[$i] -> $t";
                }

                $result['sub_display'] = implode(', ', $result['sub_display']);
                $result['dictionary_name'] = $name;

                // Replace translated token with orignal password token.
                $token = substr($password, $result['begin'], $result['end'] - $result['begin'] + 1);
                $matches[] = new static($password, $result['begin'], $result['end'], $token, $result);
            }
        }

        return $matches;
    }

    /**
     * @param $password
     * @param $begin
     * @param $end
     * @param $token
     * @param array $params
     */
    public function __construct($password, $begin, $end, $token, $params = array())
    {
        parent::__construct($password, $begin, $end, $token, $params);

        $this->l33t = true;

        if (!empty($params)) {
            $this->sub = isset($params['sub']) ? $params['sub'] : null;
            $this->subDisplay = isset($params['sub_display']) ? $params['sub_display'] : null;
        }
    }

    /**
     * @return float
     */
    public function getEntropy()
    {
        return parent::getEntropy() + $this->l33tEntropy();
    }

    /**
     * @return float
     */
    protected function l33tEntropy()
    {
        $possibilities = 0;

        foreach ($this->sub as $subbed => $unsubbed) {
            $sLen = 0;
            $uLen = 0;

            // Count occurences of substituted and unsubstituted
            // characters in the token.
            foreach (str_split($this->token) as $char) {
                if ($char === (string) $subbed) {
                    $sLen++;
                }

                if ($char === (string) $unsubbed) {
                    $uLen++;
                }
            }

            foreach (range(0, min($uLen, $sLen)) as $i) {
                $possibilities += $this->binom($uLen + $sLen,  $i);
            }
        }

        // corner: return 1 bit for single-letter subs,
        // like 4pple -> apple, instead of 0.
        if ($possibilities <= 1) {
            return 1;
        }

        return $this->log($possibilities);
    }

    /**
     * @param string $string
     * @param array $map
     * @return string
     */
    protected static function translate($string, $map)
    {
        $out = '';

        foreach (range(0, strlen($string) - 1) as $i) {
            $out .= !empty($map[$i]) ? $map[$i] : $string[$i];
        }

        return $out;
    }

    /**
     * @param string $password
     * @return array
     */
    protected static function getSubstitutions($password)
    {
        $map = [];

        /*$chars = array_unique(str_split($password));
        foreach ($l33t as $letter => $subs) {
            $relevent_subs = array_intersect($subs, $chars);
            if (!empty($relevent_subs)) {
                $map[] = $relevent_subs;
            }
        }*/

        foreach (range(0, strlen($password) - 1) as $i) {
            $map[$i] = null;

            foreach (static::$table as $char => $subs) {
                if (in_array($password[$i], $subs)) {
                    $map[$i] = $char;
                }
            }
        }

        return $map;
    }
}