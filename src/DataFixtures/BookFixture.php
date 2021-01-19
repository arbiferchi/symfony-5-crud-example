<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Author;
use App\Entity\Book;


class BookFixture extends BaseFixture implements DependentFixtureInterface
{

    private $authors;
    private $countAuthors;
    private $year = 2021;

    public function load(ObjectManager $manager)
    {
        $this->authors = $manager->getRepository(Author::class)->findAll();
        $this->countAuthors = count($this->authors);

        parent::load($manager);
    }

    /**
     * @param ObjectManager $manager
     * @return mixed|void
     */
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(
            Book::class,
            100,
            function( Book $book, $i ) {
                $book->setAuthor( $this->authors[rand(0, ($this->countAuthors-1))] );
                $book->setTitle( $this->faker->text(40) );
                $book->setPubYear($this->year - rand(0, 50));
            });

        $manager->flush();
    }


    public function getDependencies()
    {
        return [AuthorFixture::class];
    }

}
