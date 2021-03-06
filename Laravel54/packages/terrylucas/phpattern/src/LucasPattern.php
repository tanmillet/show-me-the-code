<?php

namespace TerryLucas2017\Pattern;

use TerryLucas2017\Interview\Demo01;
use TerryLucas2017\Pattern\Created\AbstractFactoryPattern\AbstractFactoryPattern;
use TerryLucas2017\Pattern\Created\BuilderPattern\BuilderPattern;
use TerryLucas2017\Pattern\Created\FactoryMethodPattern\FactoryMethodPattern;
use TerryLucas2017\Pattern\Created\SimpleFactoryPattern\FactoryPattern;
use TerryLucas2017\Pattern\Created\SingletonPattern\Singleton;
use TerryLucas2017\Pattern\Structural\Composite\FormElement;
use TerryLucas2017\Pattern\Structural\Composite\InputElement;
use TerryLucas2017\Pattern\Structural\Composite\TextElement;
use TerryLucas2017\Pattern\Structural\Facade\Alarm;
use TerryLucas2017\Pattern\Structural\Facade\Camera;
use TerryLucas2017\Pattern\Structural\Facade\Client;
use TerryLucas2017\Pattern\Structural\Facade\Light;
use TerryLucas2017\Pattern\Structural\Facade\SecurityFacade;
use TerryLucas2017\Pattern\Structural\Facade\Sensor;
use TerryLucas2017\Pattern\Structural\Decorator\Bird;
use TerryLucas2017\Pattern\Structural\Decorator\Fish;
use TerryLucas2017\Pattern\Structural\Decorator\WuKong;
use TerryLucas2017\Pattern\Structural\Proxy\ProxySubject;
use TerryLucas2017\Pattern\Structural\Proxy\RealSubject;

class LucasPattern
{
    public function exc()
    {
        // //简单工厂模式测试
        // $op = new FactoryPattern();
        // $op->exc();
        //
        // //抽象工厂模式测试
        // $op = new AbstractFactoryPattern();
        // $op->exc();
        //
        // //工厂方法测试
        // $op = new FactoryMethodPattern();
        // $op->exc();
        //
        // //单例模式测试
        // $op = Singleton::getInstance();
        // print_r($op);
        //
        // //建造者模式测试
        // $op = new BuilderPattern();
        // $op->exc();

        // $wukong = new WuKong();
        // $fish = new Fish($wukong);
        // $bird = new Bird($fish);
        //
        // dump($fish->say());
        // dump($fish->swim());
        // dump($bird->swim());

        // $proxy = new ProxySubject(new RealSubject());
        // dump($proxy->lucasRequest());

        // $client = new Client(new SecurityFacade(new Light(), new Alarm(), new Sensor(), new Camera()));
        // $client->activate();
        // $client->disactivate();

        // $composite = new FormElement();
        // $composite->addElements(new TextElement('姓名：'));
        // $composite->addElements(new InputElement());
        // $composite->addElements(new TextElement('密码：'));
        // $composite->addElements(new InputElement());
        // dump($composite->reader());

        $demo01 = new  Demo01();
        dump($demo01->index());
    }
}
