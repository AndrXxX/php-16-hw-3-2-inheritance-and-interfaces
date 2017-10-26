<?php

interface PrintDescription
{
    public function getName();

    public function getCost();

    public function getWeight();

    public function getProductDiscount();

    public function getAdvancedDescription();

    public function getDeliveryCost();
}

abstract class Product implements PrintDescription
{
    protected $name;
    protected $cost;
    protected $productDiscount = 10;
    protected $weight;

    public function __construct($name, $cost)
    {
        $this->name = $name;
        $this->cost = $cost;
    }

    public function getName()
    {
        return isset($this->name) ? $this->name : '';
    }

    public function getCost()
    {
        return $this->cost * (1 + ($this->getProductDiscount() / 100));
    }

    public function setCost($cost)
    {
        $this->cost = $cost;
    }

    public function getProductDiscount()
    {
        return isset($this->productDiscount) ? $this->productDiscount : 0;
    }

    public function setProductDiscount($productDiscount)
    {
        $this->productDiscount = $productDiscount;
    }

    public function getWeight()
    {
        return isset($this->weight) ? $this->weight : 0;
    }

    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * Доставка на все продукты = 250 рублей, но если на продукт была скидка, то 300 рублей.
     * @return int
     */
    public function getDeliveryCost()
    {
        return $this->getProductDiscount() > 0 ? 300 : 250;
    }

    abstract public function getAdvancedDescription();
}

final class Phone extends Product
{
    private $screen;

    public function getAdvancedDescription()
    {
        return 'Размер экрана: ' . $this->getScreen() . ' дюймов.';
    }

    public function getScreen()
    {
        return $this->screen;
    }

    public function setScreen($screen)
    {
        $this->screen = $screen;
    }
}

final class Router extends Product
{
    protected $productDiscount = 0;
    private $speed;

    public function getAdvancedDescription()
    {
        return 'Скорость: ' . $this->getSpeed() . ' Мбит/с.';
    }

    public function getSpeed()
    {
        return $this->speed;
    }

    public function setSpeed($speed)
    {
        $this->speed = $speed;
    }
}

final class Telescope extends Product
{
    private $zoom;

    public function getAdvancedDescription()
    {
        return 'Кратность увеличения: ' . $this->getZoom() . '.';
    }

    public function getZoom()
    {
        return $this->zoom;
    }

    public function setZoom($zoom)
    {
        $this->zoom = $zoom;
    }

    /**
     * Переопределяем вывод скидки - продукт имеет скидку только в том случае, если его вес больше 10 килограмм.
     * @return int
     */
    public function getProductDiscount()
    {
        return (isset($this->productDiscount) && ($this->getWeight() > 10)) ? $this->productDiscount : 0;
    }
}

function getObjectDescription(PrintDescription $obj)
{
    $basicDescr = "Товар %s, его цена со скидкой %u руб., скидка %u процентов, вес %s кг. ";
    $advancedDescr = "Дополнительная информация: %s ";
    $deliveryDescr = "Стоимость доставки: %u руб.";

    return sprintf($basicDescr, $obj->getName(), $obj->getCost(), $obj->getProductDiscount(), $obj->getWeight()) .
        sprintf($advancedDescr, $obj->getAdvancedDescription()) .
        sprintf($deliveryDescr, $obj->getDeliveryCost());
}

$phone1 = new Phone('Samsung J1', 15000);
$phone1->setWeight(0.5);
$phone1->setScreen(5);
echo getObjectDescription($phone1) . '<br>';

$phone2 = new Phone('Apple iPhone 5s', 25000);
$phone2->setWeight(0.6);
$phone2->setScreen(4.5);
echo getObjectDescription($phone2) . '<br><br>';

$router1 = new Router('Zyxel', 1500);
$router1->setWeight(0.2);
$router1->setSpeed(1000);
echo getObjectDescription($router1) . '<br>';

$router2 = new Router('TP-Link', 1200);
$router2->setWeight(0.2);
$router2->setSpeed(100);
echo getObjectDescription($router2) . '<br><br>';

$telescope1 = new Telescope('telescope1', 20000);
$telescope1->setWeight(9);
$telescope1->setZoom(2);
echo getObjectDescription($telescope1) . '<br>';

$telescope2 = new Telescope('telescope1', 20000);
$telescope2->setWeight(11);
$telescope2->setZoom(5);
echo getObjectDescription($telescope2) . '<br><br>';

