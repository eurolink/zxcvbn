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
 * Tests for Matcher.
 *
 * @author Eurolink <info@eurolink.co>
 */
class MatcherTest extends \PHPUnit_Framework_TestCase
{
    public function testGetMatches()
    {
        $matcher = new Matcher();
        $matches = $matcher->getMatches('jjj');
        $this->assertSame('repeat', $matches[0]->pattern, 'Pattern incorrect');
        $this->assertCount(1, $matches);

        $matches = $matcher->getMatches('jjjjj');
        $this->assertSame('repeat', $matches[0]->pattern, 'Pattern incorrect');
    }
}