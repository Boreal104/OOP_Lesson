<?php

error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json; charset=utf-8');

class TXPO
{
    public $from;
    public $to;
    public $step;
    public $type;
    public $elements;

    private $histogram;

    public function __construct($from, $to, $step, $type)
    {

        if ($to) {
            $this->to = $to;
        } else {
            $this->to = $from;
            $type = 1;
            $step = null;
        }

        $this->from = $from;
        $this->step = $step;
        $this->type = $type;
        $this->histogram = new Histogram_Gen();
        $this->Calculate();
        $this->Print();
    }

    protected function RangeResult()
    {
        for ($i = $this->from; $i <= $this->to; $i++) {
            $this->elements[] = new Element($i, $this->histogram);
        }
    }

    protected function AritResult()
    {
        for ($i = $this->from; $i <= $this->to; $i += $this->step) {
            $this->elements[] = new Element($i, $this->histogram);
        }
    }

    protected function GeoResult()
    {
        for ($i = $this->from; $i <= $this->to; $i *= $this->step) {
            $this->elements[] = new Element($i, $this->histogram);
        }
    }

    public function Calculate()
    {

        $this->elements = null;

        switch ($this->type) {
            case 1:
                $this->RangeResult();
                break;
            case 2:
                $this->AritResult();
                break;
            case 3:
                $this->GeoResult();
                break;
            default:
                break;
        }

    }

    public function Print()
    {
        $this->elements = [
            'Elements' => $this->elements,
            'Histogram' => $this->histogram
        ];
        echo json_encode($this->elements, JSON_PRETTY_PRINT);
    }
}

class Histogram_Gen extends TXPO
{
    public $myarray = array();

    public function __construct() {}

    public function Add_element($x, $y)
    {
        $this->myarray[] = array($x, $y);
    }
}

class Element
{
    public $num;
    public $nums;
    public $max;
    public $count;

    public function __construct($var, $histogram)
    {
        $max = $var; 
        $count = 0;
        $numbers = array();

        $this->num = $var;
        $numbers[] = $var;

        while ($var > 1) {
            if ($var % 2 == 0) {
                $var /= 2;
            } else {
                $var = $var * 3 + 1;
            }

            $count++;
            if ($var > $max) {
                $max = $var;
            }

            $numbers[] = $var;
        }

        $this->nums = $numbers;
        $this->max = $max;
        $this->count = $count;
        $histogram->Add_element($this->num, $count);
    }
}
?>
