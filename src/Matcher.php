<?php
/*
 * This file is part of the zxcvbn package.
 *
 * (c) Eurolink <info@eurolink.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eurolink\zxcvbn;

/**
 *
 *
 * @author Eurolink <info@eurolink.co>
 */
class Matcher
{
    public function __construct() {}

    /**
     * Get matches for a password.
     *
     * @param string $password   Password string to match.
     * @param array  $userInputs Values related to the user (optional).
     *
     * @code
     *   array('Alice Smith')
     * @endcode
     *
     * @return array Array of Match objects.
     */
    public function getMatches($password, array $userInputs = array())
    {
        $matches = [];

        foreach ($this->getMatchers() as $matcher) {
            $matched = $matcher::match($password, $userInputs);
            if (is_array($matched) && !empty($matched)) {
                $matches = array_merge($matches, $matched);
            }
        }

        return $matches;
    }

    /**
     * Load available Match objects to match against a password.
     *
     * @return array Classes implementing MatchInterface
     */
    protected function getMatchers()
    {
        // @todo change to dynamic
        return array(
            'Eurolink\zxcvbn\Matchers\DateMatch',
            'Eurolink\zxcvbn\Matchers\DigitMatch',
            'Eurolink\zxcvbn\Matchers\LeetMatch',
            'Eurolink\zxcvbn\Matchers\RepeatMatch',
            'Eurolink\zxcvbn\Matchers\SequenceMatch',
            'Eurolink\zxcvbn\Matchers\SpatialMatch',
            'Eurolink\zxcvbn\Matchers\YearMatch',
            'Eurolink\zxcvbn\Matchers\DictionaryMatch',
        );
    }
}