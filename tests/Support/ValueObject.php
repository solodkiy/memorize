<?php


namespace Solodkiy\Memorize\Support;

class ValueObject
{
    /**
     * @var
     */
    private $val;

    public function __construct($val)
    {
        $this->val = $val;
    }

    /**
     * @return mixed
     */
    public function getVal()
    {
        return $this->val;
    }

    public function hash()
    {
        return md5($this->val);
    }
}
