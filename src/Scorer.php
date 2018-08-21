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
class Scorer extends Base implements ScorerInterface
{
    /**
     * Lower bound assumption of time to hash based on bcrypt/scrypt/PBKDF2.
     */
    const SINGLE_GUESS = 0.010;

    /**
     * Assumed number of cores guessing in parallel.
     */
    const NUM_ATTACKERS = 100;

    protected $crackTime;

    /**
     *
     */
    public function score($entropy)
    {
        $seconds = $this->calcCrackTime($entropy);

        if ($seconds < pow(10, 2)) {
            return 0;
        }

        if ($seconds < pow(10, 4)) {
            return 1;
        }

        if ($seconds < pow(10, 6)) {
            return 2;
        }

        if ($seconds < pow(10, 8)) {
            return 3;
        }

        return 4;
    }

    /**
     *
     */
    public function getMetrics()
    {
        return [
            'crack_time' => $this->crackTime
        ];
    }

    /**
     * Get average time to crack based on entropy.
     *
     * @param $entropy
     * @return float
     */
    protected function calcCrackTime($entropy)
    {
        $this->crackTime = (0.5 * pow(2, $entropy)) * (Scorer::SINGLE_GUESS / Scorer::NUM_ATTACKERS);
        return $this->crackTime;
    }
}