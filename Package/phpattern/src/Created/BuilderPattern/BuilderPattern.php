<?php

namespace TerryLucas2017\Pattern\Created\BuilderPattern;


class BuilderPattern
{
    public function exc()
    {
        $builder = new Director();

        $builder->build(CarBuilder::class);
    }

}