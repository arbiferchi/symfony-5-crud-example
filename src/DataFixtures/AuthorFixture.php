<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Author;


class AuthorFixture extends BaseFixture
{
    /**
     * @param ObjectManager $manager
     * @return mixed|void
     */
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(
            Author::class,
            50,
            function( Author $author, $i ) {
                $author->setName( $this->faker->name );
                $author->setEmail( $this->faker->email );
                $author->setGender( Author::GENDERS[rand(0,1)] );
            });

        $manager->flush();
    }

}
