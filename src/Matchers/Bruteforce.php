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
 * Class Bruteforce
 *
 * Intentionally not named with Match suffix to prevent autoloading from Matcher.
 *
 * @author Eurolink <info@eurolink.co>
 */
class Bruteforce extends Match
{
    /**
     * @copydoc Match::match()
     */
    public static function match($password, array $userInputs = array())
    {
        // Matches entire string.
        $match = new static($password, 0, strlen($password) - 1, $password);

        return [$match];
    }

    /**
     *
     *
     * @param $password
     * @param $begin
     * @param $end
     * @param $token
     * @param $cardinality
     */
    public function __construct($password, $begin, $end, $token, $cardinality = null)
    {
        parent::__construct($password, $begin, $end, $token);

        $this->pattern = 'bruteforce';

        // Cardinality can be injected to support full password cardinality instead of token.
        $this->cardinality = $cardinality;
    }

    /**
     *
     */
    public function getEntropy()
    {
        if (is_null($this->entropy)) {
            $this->entropy = $this->log(pow($this->getCardinality(), strlen($this->token)));
        }

        return $this->entropy;
    }
}
