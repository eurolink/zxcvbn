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
 * Tests for L33t Matches.
 *
 * @author Eurolink <info@eurolink.co>
 */
class LeetMatchTest extends \PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        // Test non-translated dictionary word.
        $password = 'pass';
        $matches = LeetMatch::match($password);
        $this->assertEmpty($matches);

        $password = 'p4ss';
        $matches = LeetMatch::match($password);
        $this->assertCount(5, $matches);

        $password = 'p4ssw0rd';
        $matches = LeetMatch::match($password);
        $this->assertCount(11, $matches);

        // Test translated characters that are not a dictionary word.
        $password = '76+(';
        $matches = LeetMatch::match($password);
        $this->assertEmpty($matches);
    }

    public function testEntropy()
    {
        $password = 'p4ss';
        $matches = LeetMatch::match($password);
        // 'pass' has a rank of 35 and l33t entropy of 1.
        $this->assertEquals(log(35, 2) + 1, $matches[0]->getEntropy());

        $password = 'p45s';
        $matches = LeetMatch::match($password);
        $this->assertEquals(log(35, 2) + 2, $matches[0]->getEntropy());
    }
}