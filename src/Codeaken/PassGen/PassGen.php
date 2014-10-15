<?php
namespace Codeaken\PassGen;

class PassGen
{
    private $avoidSpecial = false;
    private $avoidSimilar = true;
    private $avoidSpace = true;
    private $avoidPunctuation = false;

    private $charsUppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    private $charsLowercase = 'abcdefghijklmnopqrstuvwxyz';
    private $charsNumbers = '0123456789';
    private $charsSpecial = '!"\'$%^&*()-_=+[]{}@#~|<>/\\?';
    private $charsSpace = ' ';
    private $charsPunctuation = ',;.:';
    private $charsSimilar = '1iIlL0oO';

    public function __construct($options = [])
    {
        foreach ($options as $key => $value) {

            switch ($key) {
                case 'avoid_special':
                    $this->setAvoidSpecial($value);
                    break;

                case 'avoid_similar':
                    $this->setAvoidSimilar($value);
                    break;

                case 'avoid_space':
                    $this->setAvoidSpace($value);
                    break;

                case 'avoid_punctuation':
                    $this->setAvoidPunctuation($value);
                    break;

                default:
                    throw new \DomainException("Unknown option: {$key}");
                    break;
            }
        }
    }

    public function getAvoidSpecial()
    {
        return $this->avoidSpecial;
    }

    public function getAvoidPunctuation()
    {
        return $this->avoidPunctuation;
    }

    public function getAvoidSimilar()
    {
        return $this->avoidSimilar;
    }

    public function getAvoidSpace()
    {
        return $this->avoidSpace;
    }

    public function setAvoidSpecial($state)
    {
        if ( ! is_bool($state)) {
            throw new \InvalidArgumentException('$state should be a boolean');
        }

        $this->avoidSpecial = $state;
    }

    public function setAvoidPunctuation($state)
    {
        if ( ! is_bool($state)) {
            throw new \InvalidArgumentException('$state should be a boolean');
        }

        $this->avoidPunctuation = $state;
    }

    public function setAvoidSimilar($state)
    {
        if ( ! is_bool($state)) {
            throw new \InvalidArgumentException('$state should be a boolean');
        }

        $this->avoidSimilar = $state;
    }

    public function setAvoidSpace($state)
    {
        if ( ! is_bool($state)) {
            throw new \InvalidArgumentException('$state should be a boolean');
        }

        $this->avoidSpace = $state;
    }

    public function generate($length)
    {
        $charset = $this->getCharset();
        $charsetLength = strlen($charset) - 1;

        $password = '';
        for ($i=0; $i < $length; $i++) {
            $password .= $charset[mt_rand(0, $charsetLength)];
        }

        return $password;
    }

    private function getCharset()
    {
        // Build the master character set
        $charset = $this->charsUppercase .
                   $this->charsLowercase .
                   $this->charsNumbers .
                   $this->charsSpecial .
                   $this->charsSpace .
                   $this->charsPunctuation;

        // Remove characters the user does not want included
        if ($this->getAvoidSpecial()) {
            $charset = $this->removeChars($charset, $this->charsSpecial);
        }

        if ($this->getAvoidSpace()) {
            $charset = $this->removeChars($charset, $this->charsSpace);
        }

        if ($this->getAvoidPunctuation()) {
            $charset = $this->removeChars($charset, $this->charsPunctuation);
        }

        if ($this->getAvoidSimilar()) {
            $charset = $this->removeChars($charset, $this->charsSimilar);
        }

        return $charset;
    }

    private function removeChars($charset, $removeChars)
    {
        return str_replace(str_split($removeChars), '', $charset);
    }
}
