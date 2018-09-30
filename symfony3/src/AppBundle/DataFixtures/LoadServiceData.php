<?php

namespace AppBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Service;

class LoadServiceData extends Fixture
{
    /**
     * Load data fixtures with the passed EntityManager.
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $services = [
            ['id' => 804040, 'name' => 'Sonstige Umzugsleistungen'],
            ['id' => 802030, 'name' => 'Abtransport, Entsorgung und Entrümpelung'],
            ['id' => 411070, 'name' => 'Fensterreinigung'],
            ['id' => 402020, 'name' => 'Holzdielen schleifen'],
            ['id' => 108140, 'name' => 'Kellersanierung'],
        ];

        foreach ($services as $serviceData) {
            $service = new Service();
            $service->setId($serviceData['id']);
            $service->setName($serviceData['name']);
            $manager->persist($service);
        }

        $manager->flush();
    }
}
