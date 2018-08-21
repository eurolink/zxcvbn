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
 * Estimator class for calculating results for a password.
 *
 * @author Eurolink <info@eurolink.co>
 */
class Estimator extends Base
{
    /**
     * @var Scorer
     */
    protected $scorer;

    /**
     * @var Searcher
     */
    protected $searcher;

    /**
     * @var Matcher
     */
    protected $matcher;

    public function __construct()
    {
        $this->scorer   = new Scorer();
        $this->searcher = new Searcher();
        $this->matcher  = new Matcher();
    }

    /**
     * Calculate Strength
     *
     * Estimator calculates the password strength via non-overlapping
     * minimum entropy patterns.
     *
     * @param string $password   Password to measure.
     * @param array  $userInputs Optional user inputs.
     *
     * @return array Strength result array with keys:
     *               - password
     *               - entropy
     *               - match_sequence
     *               - score
     */
    public function passwordStrength($password, array $userInputs = [])
    {
        $timeStart = microtime(true);

        if (strlen($password) === 0) {
            $timeStop = microtime(true) - $timeStart;
            return $this->result($password, 0, [], 0, ['calc_time' => $timeStop]);
        }

        // Get matches for $password.
        $matches = $this->matcher->getMatches($password, $userInputs);

        // Calcuate minimum entropy and get best match sequence.
        $entropy = $this->searcher->getMinimumEntropy($password, $matches);
        $bestMatches = $this->searcher->matchSequence;

        // Calculate score and get crack time.
        $score = $this->scorer->score($entropy);
        $metrics = $this->scorer->getMetrics();

        $timeStop = microtime(true) - $timeStart;
        // Include metrics and calculation time.
        $params = array_merge($metrics, ['calc_time' => $timeStop]);

        return $this->result($password, $entropy, $bestMatches, $score, $params);
    }

    /**
     * Format result array.
     *
     * @param string $password
     * @param float $entropy
     * @param array $matches
     * @param int $score
     * @param array $params
     *
     * @return array
     */
    protected function result($password, $entropy, $matches, $score, array $params = [])
    {
        $r = [
            'password'       => $password,
            'entropy'        => $entropy,
            'match_sequence' => $matches,
            'score'          => $score
        ];

        return array_merge($params, $r);
    }
}