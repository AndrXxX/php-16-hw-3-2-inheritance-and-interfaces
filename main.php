<?php

interface BasicInfo
{
    public function getFullDescription($itemType = true);
}

class Item implements BasicInfo
{
    protected $name;
    protected $cost;
    protected $color;

    public function __construct($name, $cost, $color)
    {
        $this->name = $name;
        $this->cost = $cost;
        $this->color = $color;
    }

    public function getFullDescription($itemType = true)
    {
        $format = "%s цвета %s, со стоимостью %u руб.";
        $format = ($itemType) ? 'Предмет ' . $format : $format;
        return sprintf($format, $this->getName(), $this->getColor(), $this->getCost());
    }

    public function getName()
    {
        return $this->name;
    }

    public function getColor()
    {
        return $this->color;
    }

    public function setColor($color)
    {
        $this->color = $color;
    }

    public function getCost()
    {
        return $this->cost;
    }

    public function setCost($cost)
    {
        $this->cost = $cost;
    }
}

class Car extends Item
{
    protected $color = 'white';

    public function getFullDescription($itemType = true)
    {
        return ' Машина ' . parent::getFullDescription(false) . '<br>';
    }
}

class TelevisionSet extends Item
{
    const STATE_OFF = 'выключен';
    const STATE_ON = 'включен';

    private $state = self::STATE_OFF;
    private $channel = 1;
    private $volume = 50;

    public function volumeUp()
    {
        $this->volume = (($this->state === self::STATE_ON) && ($this->volume < 100)) ? ++$this->volume : $this->volume;
    }

    public function volumeDown()
    {
        $this->volume = (($this->state === self::STATE_ON) && ($this->volume > 0)) ? --$this->volume : $this->volume;
    }

    public function turnOn()
    {
        $this->state = self::STATE_ON;
    }

    public function turnOff()
    {
        $this->state = self::STATE_OFF;
    }

    public function getFullDescription($itemType = true)
    {
        $format = " Телевизор сейчас %s, его громкость %s, канал №%u.<br>";
        return ' Телевизор ' . parent::getFullDescription(false) .
            sprintf($format, $this->getState(), $this->getVolume(), $this->getChannel());
    }

    public function getState()
    {
        return $this->state;
    }

    public function getVolume()
    {
        return $this->volume;
    }

    public function getChannel()
    {
        return $this->channel;
    }

    public function setChannel($channel)
    {
        $this->channel = ($this->state === self::STATE_ON) ? $channel : $this->channel;
    }
}

class Pen extends Item
{
    private $thickness;
    private $inkLevel;

    public function usePen()
    {
        $this->inkLevel = $this->inkLevel > 0 ? --$this->inkLevel : 0;
    }

    public function getFullDescription($itemType = true)
    {
        $format = " У этой ручки толщина стержня %01.1f, уровень чернил %u.<br>";
        return ' Ручка ' . parent::getFullDescription(false) .
            sprintf($format, $this->getThickness(), $this->getInkLevel());
    }

    public function getThickness()
    {
        return $this->thickness;
    }

    public function setThickness($thickness)
    {
        $this->thickness = $thickness;
    }

    public function getInkLevel()
    {
        return $this->inkLevel;
    }

    public function setInkLevel($inkLevel = 100)
    {
        $this->inkLevel = $inkLevel;
    }
}

class Duck extends Item
{
    private $age;
    private $weight;

    public function changeWeight($changeWeight)
    {
        $this->weight = ($this->weight + $changeWeight) >= 1 ? ($this->weight + $changeWeight) : 1;
    }

    public function upAge()
    {
        $this->age = ++$this->age;
    }

    public function getFullDescription($itemType = true)
    {
        $format = " Возраст этой утки %u лет, ее вес %u кг.<br>";
        return ' Утка ' . parent::getFullDescription(false) .
            sprintf($format, $this->getAge(), $this->getWeight());
    }

    public function getAge()
    {
        return $this->age;
    }

    public function setAge($age)
    {
        $this->age = $age;
    }

    public function getWeight()
    {
        return $this->weight;
    }

    public function setWeight($weight)
    {
        $this->weight = $weight;
    }
}

class Product extends Item
{
    private static $groupDiscount = [];
    private $price;
    private $productDiscount;
    private $productGroup;

    public function getFullDescription($itemType = true)
    {
        $format = "Товар %1\$s %2\$s цвета %3\s цена %4\$u руб., скидка на всю группу %1\$s - %5\$u процентов, 
          скидка именно на %1\$s %2\$s - %6\$u процентов. <br>";
        return vsprintf($format, $this->getProductInfo());
    }

    public function getProductInfo()
    {
        $product = [
            $this->getProductGroup(),
            $this->getName(),
            $this->getColor(),
            $this->getPrice(),
            self::getGroupDiscount($this->productGroup),
            $this->getProductDiscount()
        ];
        return $product;
    }

    public function getProductGroup()
    {
        return isset($this->productGroup) ? $this->productGroup : '';
    }

    public function setProductGroup($productGroup)
    {
        $this->productGroup = $productGroup;
    }

    /**
     * выводит цену с учетом скидки (выбирается максимальная скидка)
     * @return mixed
     */
    public function getPrice()
    {
        $discount = max($this->productDiscount, self::getGroupDiscount($this->productGroup));
        return $this->price * (1 + $discount / 100);
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public static function getGroupDiscount($group)
    {
        return isset(self::$groupDiscount[$group]) ? self::$groupDiscount[$group] : 0;
    }

    public static function setGroupDiscount($group, $discount)
    {
        self::$groupDiscount[$group] = $discount;
    }

    public function getProductDiscount()
    {
        return isset($this->productDiscount) ? $this->productDiscount : 0;
    }

    public function setProductDiscount($productDiscount)
    {
        $this->productDiscount = $productDiscount;
    }
}

/* ----------- */

$carAudi = new Car('Audi', 1000000, 'white');
$carKia = new Car('Kia', 800000, 'black');


echo $carAudi->getFullDescription();
echo $carKia->getFullDescription();
echo '<br>';

/* ----------- */

$tvPhilips = new TelevisionSet('Philips', 10000, 'black');
$tvSony = new TelevisionSet('Sony', 15000, 'black');

$tvPhilips->turnOn();
$tvPhilips->setChannel(10); // канал изменится, т.к. телевизор включен
$tvSony->setChannel(10); // канал не изменится, т.к. телевизор выключен

for ($i = 1; $i < 10; $i++) {
    $tvPhilips->volumeUp(); // громкость меняться будет, т.к. телевизор включен
    $tvSony->volumeDown(); // громкость меняться не будет, т.к. телевизор выключен

}
$tvPhilips->turnOff();
$tvSony->turnOn();

echo $tvPhilips->getFullDescription();
echo $tvSony->getFullDescription();
echo '<br>';

/* ----------- */

$whitePen = new Pen('Pen1', 10, 'white');
$whitePen->setInkLevel(80);
$whitePen->setThickness('0.5');
$blackPen = new Pen('Pen2', 20, 'black');
$blackPen->setInkLevel();
$blackPen->setThickness('0.7');

for ($i = 0; $i < 150; $i++) {
    if (rand(1, 2) === 1) {
        $whitePen->usePen();
    } else {
        $blackPen->usePen();
    }
}

echo $whitePen->getFullDescription();
echo $blackPen->getFullDescription();
echo '<br>';

/* ----------- */

$brownDuck = new Duck('FirstDuck', 500, 'brown');
$brownDuck->setWeight(2);
$brownDuck->setAge(2);
$blackDuck = new Duck('SecondDuck', 600, 'black');

for ($i = 0; $i < 10; $i++) {
    $brownDuck->changeWeight(rand(-1, 1));
    $blackDuck->changeWeight(rand(-1, 1));
    $brownDuck->upAge();
    $blackDuck->upAge();
}

echo $brownDuck->getFullDescription();
echo $blackDuck->getFullDescription();
echo '<br>';

/* ----------- */

$phoneSamsung = new Product('Samsung', 20000, 'Черный');
$phoneSamsung->setProductGroup('Смартфон');

$phoneApple = new Product('Apple', 30000, 'Белый');
$phoneApple->setProductDiscount(10);
$phoneApple->setProductGroup('Смартфон');

$caseSamsung = new Product('Samsung', 600, 'Черный');
$caseSamsung->setProductDiscount(15);
$caseSamsung->setProductGroup('Чехол');

$caseApple = new Product('Apple', 700, 'Белый');
$caseApple->setProductDiscount(25);
$caseApple->setProductGroup('Чехол');

Product::setGroupDiscount('Смартфон', 5);
Product::setGroupDiscount('Чехол', 20);

echo $phoneSamsung->getFullDescription();
echo $phoneApple->getFullDescription();
echo $caseSamsung->getFullDescription();
echo $caseApple->getFullDescription();
echo '<br>';

