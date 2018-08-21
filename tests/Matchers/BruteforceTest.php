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
 * Tests for Bruteforce.
 *
 * @author Eurolink <info@eurolink.co>
 */
class BruteforceTest extends \PHPUnit_Framework_TestCase
{
    public function testCardinality()
    {
        $match = new Bruteforce('99', 0, 1, '99');
        $this->assertSame(10, $match->getCardinality());

        $match = new Bruteforce('aa', 0, 1, 'aa');
        $this->assertSame(26, $match->getCardinality());

        $match = new Bruteforce('!', 0, 0, '!');
        $this->assertSame(33, $match->getCardinality());

        $match = new Bruteforce('Ab', 0, 1, 'Ab');
        $this->assertSame(52, $match->getCardinality());
    }

    public function testEntropy()
    {
        $match = new Bruteforce('99', 0, 1, '99');
        $this->assertSame(log(pow(10, 2), 2), $match->getEntropy());

        $password = 'aB1*';
        $match = new Bruteforce($password, 0, 3, $password);
        $this->assertSame(95, $match->getCardinality());
        $this->assertSame(log(pow(95, 4), 2), $match->getEntropy());
    }
}