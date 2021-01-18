<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Author;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TestController extends AbstractController
{











    /**
     * @Route("/test_1", name="test_1")
     */
    public function test_1(): Response
    {


        return new Response('<br>END TEST');
    }




    /**
     * @Route("/test_2", name="test_2")
     */
    public function test_2(): Response
    {

        return new Response('<br>END TEST<br>');
    }



    /**
     * @Route("/test_assert_year")
     */
    public function test_assert_year(Request $request, ValidatorInterface $validator): Response
    {
        $author = new Author();
        $author->setName('Test2');
        $author->setGender('male');
        $author->setEmail('pub_year_email@mail.com');
        /* validate */
        if ( count($errors = $validator->validate($author)) > 0 ) {
            return new Response((string)$errors);
        }

        $book = new Book();
        $book->setTitle('Test1');
        /* test assert year */
        $book->setPubYear('2017');
        $book->setAuthor($author);
        /* validate */
        if ( count($errors = $validator->validate($book)) > 0 ) {
            return new Response((string)$errors);
        }
        /* save */
        /*$em = $this->getDoctrine()->getManager();
        $em->persist($author);
        $em->persist($book);
        $em->flush();*/

        return new Response('<br>END TEST<br>');
    }


    /**
     * @Route("/test_relation", name="test_relation")
     */
    public function test_relation(): Response
    {

        $author = $this->getDoctrine()->getRepository(Author::class)->find(3);
        $books = $author->getBooks();
        foreach($books as $book) {
            dump($book->getPubYear());
        }
        dd($books);

        return new Response('<br>END TEST<br>');
    }

}
