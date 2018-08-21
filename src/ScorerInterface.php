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
interface ScorerInterface
{
    /**
     * Score for a password's bits of entropy.
     *
     * @param float $entropy Entropy to score.
     *
     * @return float Score.
     */
    public function score($entropy);

    /**
     * Get metrics used to determine score.
     *
     * @return array Key value array of metrics.
     */
    public function getMetrics();
}
