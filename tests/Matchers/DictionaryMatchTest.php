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
 * Tests for Dictionary Matches.
 *
 * @author Eurolink <info@eurolink.co>
 */
class DictionaryMatchTest extends \PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $password = 'kdncpqw';
        $matches = DictionaryMatch::match($password);
        $this->assertEmpty($matches);

        $password = 'jjj';
        $matches = DictionaryMatch::match($password);
        $this->assertEmpty($matches);

        $password = 'password';
        $matches = DictionaryMatch::match($password);
        // 11 matches for 'password' in english and password dictionaries.
        $this->assertCount(11, $matches);
        $this->assertSame('pass', $matches[0]->token, 'Token incorrect');
        $this->assertSame('passwords', $matches[0]->dictionaryName, 'Dictionary name incorrect');

        $password = '8dll20BEN3lld0';
        $matches = DictionaryMatch::match($password);
        $this->assertCount(2, $matches);

        $password = '39Kx9.1x0!3n6';
        $matches = DictionaryMatch::match($password, array($password));
        $this->assertCount(1, $matches);
        $this->assertSame('user_inputs', $matches[0]->dictionaryName, 'Dictionary name incorrect');
  }

    public function testEntropy()
    {
        $password = 'password';
        $matches = DictionaryMatch::match($password);

        // Match 0 is 'pass' with rank 35.
        $this->assertEquals(log(35, 2), $matches[0]->getEntropy());
    }
}