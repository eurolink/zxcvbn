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
 * Tests for Scorer.
 *
 * @author Eurolink <info@eurolink.co>
 */
class ScorerTest extends \PHPUnit_Framework_TestCase
{
    public function testScore()
    {
        $scorer = new Scorer();
        $this->assertEquals(0, $scorer->score(0), 'Score incorrect');
    }

    public function testCrackTime()
    {
        $scorer = new Scorer();
        $scorer->score(8);
        $metrics = $scorer->getMetrics();
        $this->assertEquals(0.0128, $metrics['crack_time'], 'Crack time incorrect');
    }
}