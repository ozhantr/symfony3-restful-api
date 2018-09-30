<?php

namespace AppBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Location;

class LoadLocationData extends Fixture
{
    /**
     * Load data fixtures with the passed EntityManager.
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $locations = [
            ['zip' => '10115', 'city' => 'Berlin'],
            ['zip' => '32457', 'city' => 'Porta Westfalica'],
            ['zip' => '01623', 'city' => 'Lommatzsch'],
            ['zip' => '21521', 'city' => 'Hamburg'],
            ['zip' => '06895', 'city' => 'Bülzig'],
            ['zip' => '01612', 'city' => 'Diesbar-Seußlitz'],
        ];

        foreach ($locations as $locationData) {
            $location = new Location();
            $location->setZip($locationData['zip']);
            $location->setName($locationData['city']);
            $manager->persist($location);
        }

        $manager->flush();
    }
}
