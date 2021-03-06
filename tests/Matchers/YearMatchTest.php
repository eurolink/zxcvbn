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
 * Tests for Year Matches.
 *
 * @author Eurolink <info@eurolink.co>
 */
class YearMatchTest extends \PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $password = 'password';
        $matches = YearMatch::match($password);
        $this->assertEmpty($matches);

        $password = '1900';
        $matches = YearMatch::match($password);
        $this->assertCount(1, $matches);
        $this->assertSame($password, $matches[0]->token, 'Token incorrect');
        $this->assertSame($password, $matches[0]->password, 'Password incorrect');

        $password = 'password1900';
        $matches = YearMatch::match($password);
        $this->assertCount(1, $matches);
        $this->assertSame('1900', $matches[0]->token, 'Token incorrect');
    }

    public function testEntropy()
    {
        $password = '1900';
        $matches = YearMatch::match($password);
        $this->assertEquals(log(119, 2), $matches[0]->getEntropy());
    }
}