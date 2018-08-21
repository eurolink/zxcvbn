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
 * Tests for Digit Matches.
 *
 * @author Eurolink <info@eurolink.co>
 */
class DigitMatchTest extends \PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $password = 'password';
        $matches = DigitMatch::match($password);
        $this->assertEmpty($matches);

        $password = '123';
        $matches = DigitMatch::match($password);
        $this->assertCount(1, $matches);
        $this->assertSame($password, $matches[0]->token, 'Token incorrect');
        $this->assertSame($password, $matches[0]->password, 'Password incorrect');

        $password = 'password123';
        $matches = DigitMatch::match($password);
        $this->assertCount(1, $matches);
        $this->assertEquals(strpos($password, '1'), $matches[0]->begin, 'Match begin character incorrect');
        $this->assertEquals(strlen($password) - 1, $matches[0]->end, 'Match end character incorrect');
        $this->assertEquals(3, strlen($matches[0]->token), 'Token length incorrect');

        $password = '123 456546';
        $matches = DigitMatch::match($password);
        $this->assertCount(2, $matches);
  }

    public function testEntropy()
    {
        $password = '123';
        $matches = DigitMatch::match($password);
        $this->assertEquals(log(pow(10, 3), 2), $matches[0]->getEntropy());
    }
}