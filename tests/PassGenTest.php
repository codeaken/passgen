<?php

use Codeaken\PassGen\PassGen;

class PassGenTest extends PHPUnit_Framework_TestCase
{
    public function testRemoveChars()
    {
        $method = $this->getMethod('removeChars');

        $passGen = new PassGen();
        $this->assertEquals('A', $method->invoke($passGen, 'ABC', 'BC'));
        $this->assertEquals('', $method->invoke($passGen, 'ABC', 'ABC'));
        $this->assertEquals('', $method->invoke($passGen, 'ABC', 'CBA'));
        $this->assertEquals('ABC', $method->invoke($passGen, 'ABC', 'bc'));
        $this->assertEquals('ABC', $method->invoke($passGen, 'ABC', 'DEF'));
    }

    public function testConstructorOptions()
    {
        $passGen = new PassGen(['avoid_special' => true]);
        $this->assertTrue($passGen->getAvoidSpecial());

        $passGen = new PassGen(['avoid_special' => false]);
        $this->assertFalse($passGen->getAvoidSpecial());

        $passGen = new PassGen(['avoid_similar' => true]);
        $this->assertTrue($passGen->getAvoidSimilar());

        $passGen = new PassGen(['avoid_similar' => false]);
        $this->assertFalse($passGen->getAvoidSimilar());

        $passGen = new PassGen(['avoid_space' => true]);
        $this->assertTrue($passGen->getAvoidSpace());

        $passGen = new PassGen(['avoid_space' => false]);
        $this->assertFalse($passGen->getAvoidSpace());

        $passGen = new PassGen(['avoid_punctuation' => true]);
        $this->assertTrue($passGen->getAvoidPunctuation());

        $passGen = new PassGen(['avoid_punctuation' => false]);
        $this->assertFalse($passGen->getAvoidPunctuation());
    }

    public function testConstructorUnknownOption()
    {
        $this->setExpectedException('DomainException');

        $passGen = new PassGen(['unknown' => 'option']);
    }

    public function testSetGetAvoidSpecial()
    {
        $passGen = new PassGen();

        $passGen->setAvoidSpecial(true);
        $this->assertTrue($passGen->getAvoidSpecial());

        $passGen->setAvoidSpecial(false);
        $this->assertFalse($passGen->getAvoidSpecial());
    }

    public function testSetGetAvoidSimilar()
    {
        $passGen = new PassGen();

        $passGen->setAvoidSimilar(true);
        $this->assertTrue($passGen->getAvoidSimilar());

        $passGen->setAvoidSimilar(false);
        $this->assertFalse($passGen->getAvoidSimilar());
    }

    public function testSetGetAvoidSpace()
    {
        $passGen = new PassGen();

        $passGen->setAvoidSpace(true);
        $this->assertTrue($passGen->getAvoidSpace());

        $passGen->setAvoidSpace(false);
        $this->assertFalse($passGen->getAvoidSpace());
    }

    public function testSetGetAvoidPunctuation()
    {
        $passGen = new PassGen();

        $passGen->setAvoidPunctuation(true);
        $this->assertTrue($passGen->getAvoidPunctuation());

        $passGen->setAvoidPunctuation(false);
        $this->assertFalse($passGen->getAvoidPunctuation());
    }

    public function testSetAvoidSpecialNonBoolean()
    {
        $this->setExpectedException('InvalidArgumentException');

        $passGen = new PassGen();
        $passGen->setAvoidSpecial('1');
    }

    public function testSetAvoidSimilarNonBoolean()
    {
        $this->setExpectedException('InvalidArgumentException');

        $passGen = new PassGen();
        $passGen->setAvoidSimilar('1');
    }

    public function testSetAvoidSpaceNonBoolean()
    {
        $this->setExpectedException('InvalidArgumentException');

        $passGen = new PassGen();
        $passGen->setAvoidSpace('1');
    }

    public function testSetAvoidPunctuationNonBoolean()
    {
        $this->setExpectedException('InvalidArgumentException');

        $passGen = new PassGen();
        $passGen->setAvoidPunctuation('1');
    }

    public function testCharsetSpecial()
    {
        $method = $this->getMethod('getCharset');
        $charsSpecial = $this->getProperty('charsSpecial');

        $passGen = new PassGen();

        $passGen->setAvoidSpecial(false);
        $this->assertTrue($this->isCharsInString($method->invoke($passGen), $charsSpecial));

        $passGen->setAvoidSpecial(true);
        $this->assertFalse($this->isCharsInString($method->invoke($passGen), $charsSpecial));
    }

    public function testCharsetSimilar()
    {
        $method = $this->getMethod('getCharset');
        $charsSimilar = $this->getProperty('charsSimilar');

        $passGen = new PassGen();

        $passGen->setAvoidSimilar(false);
        $this->assertTrue($this->isCharsInString($method->invoke($passGen), $charsSimilar));

        $passGen->setAvoidSimilar(true);
        $this->assertFalse($this->isCharsInString($method->invoke($passGen), $charsSimilar));
    }

    public function testCharsetPunctuation()
    {
        $method = $this->getMethod('getCharset');
        $charsPunctuation = $this->getProperty('charsPunctuation');

        $passGen = new PassGen();

        $passGen->setAvoidPunctuation(false);
        $this->assertTrue($this->isCharsInString($method->invoke($passGen), $charsPunctuation));

        $passGen->setAvoidPunctuation(true);
        $this->assertFalse($this->isCharsInString($method->invoke($passGen), $charsPunctuation));
    }

    public function testCharsetSpace()
    {
        $method = $this->getMethod('getCharset');
        $charsSpace = $this->getProperty('charsSpace');

        $passGen = new PassGen();

        $passGen->setAvoidSpace(false);
        $this->assertTrue($this->isCharsInString($method->invoke($passGen), $charsSpace));

        $passGen->setAvoidSpace(true);
        $this->assertFalse($this->isCharsInString($method->invoke($passGen), $charsSpace));
    }

    public function testGenerateLength()
    {
        $passGen = new PassGen();

        $this->assertEquals(1, strlen($passGen->generate(1)));
        $this->assertEquals(10, strlen($passGen->generate(10)));
    }

    private function isCharsInString($string, $chars)
    {
        $chars = str_split($chars);
        foreach ($chars as $char) {
            if (false !== strpos($string, $char)) {
                return true;
            }
        }

        return false;
    }

    private function getMethod($methodName)
    {
        $class = new ReflectionClass('Codeaken\PassGen\PassGen');

        $method = $class->getMethod($methodName);
        $method->setAccessible(true);

        return $method;
    }

    private function getProperty($propertyName)
    {
        $class = new ReflectionClass('Codeaken\PassGen\PassGen');

        $prop = $class->getProperty($propertyName);
        $prop->setAccessible(true);

        return $prop->getValue(new PassGen());
    }
}
