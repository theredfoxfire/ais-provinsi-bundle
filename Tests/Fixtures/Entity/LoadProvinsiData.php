<?php

namespace Ais\ProvinsiBundle\Tests\Fixtures\Entity;

use Ais\ProvinsiBundle\Entity\Provinsi;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class LoadProvinsiData implements FixtureInterface
{
    static public $provinsis = array();

    public function load(ObjectManager $manager)
    {
        $provinsi = new Provinsi();
        $provinsi->setTitle('title');
        $provinsi->setBody('body');

        $manager->persist($provinsi);
        $manager->flush();

        self::$provinsis[] = $provinsi;
    }
}
