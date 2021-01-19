<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Author;
use App\Entity\Book;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class BookController extends AbstractController
{

    /**
     * Show all (index)
     *
     * @Route("/book/show-all", name="book-show-all")
     */
    public function actionShowAll(): Response
    {
        $books = $this->getDoctrine()
            ->getRepository(Book::class)
            ->findAllJoinedAuthors();

        return $this->render('book/show-all.html.twig', ['books' => $books]);
    }


    /**
     * Show one
     *
     * @Route("/book/show-one/{book}", name="book-show-one")
     */
    public function actionShowOne(Book $book): Response
    {
        return $this->render('book/show-one.html.twig', [
            'book' => $book,
        ]);
    }


    /**
     * Create
     *
     * @Route("/book/create", name="book-create")
     * Method ({"GET", "POST"})
     */
    public function actionCreate(Request $request, ValidatorInterface $validator): Response
    {
        $em = $this->getDoctrine()->getManager();
        /* show form */
        if ($request->isMethod('GET')) {
            $pub_years = array_reverse( range(1900, strftime("%Y", time())) );
            $authors = $em->getRepository(Author::class)
                ->findBy([], ['name'=>'ASC']);

            return $this->render('book/create.html.twig', [
                'authors' => $authors,
                'pub_years' => $pub_years
            ]);
        }

        /* check @csrf */
        $token = $request->request->get('token');
        if ( !$this->isCsrfTokenValid('action-create', $token) ) {
            return $this->redirectToRoute('book-show-all');
        }

        /* author */
        $author = $em->getRepository(Author::class)->find($request->request->get('author_id'));
        /* validate */
        if ( count($errors = $validator->validate($author)) > 0 ) {
            return new Response((string)$errors);
        }

        /* book */
        $book = new Book();
        $book->setAuthor($author);
        $book->setTitle($request->request->get('title'));
        $book->setPubYear($request->request->get('pub_year'));
        /* validate */
        if ( count($errors = $validator->validate($book)) > 0 ) {
            return new Response((string)$errors);
        }
        /* save */
        $em->persist($book);
        $em->flush();

        return $this->redirectToRoute('book-show-all');
    }


    /**
     * Update
     *
     * @Route("/book/update/{book}", name="book-update")
     * Method ({"GET", "POST"})
     */
    public function actionUpdate(Book $book, Request $request, ValidatorInterface $validator): Response
    {
        $em = $this->getDoctrine()->getManager();

        /* show form */
        if ($request->isMethod('GET')) {
            $authors = $em->getRepository(Author::class)->findBy([], ['name'=>'ASC']);
            $pub_years = array_reverse( range(1900, strftime("%Y", time())) );
            return $this->render('book/update.html.twig', [
                'book' => $book,
                'authors' => $authors,
                'pub_years' => $pub_years
            ]);
        }

        /* check @csrf */
        $token = $request->request->get('token');
        if ( !$this->isCsrfTokenValid('action-update', $token) ) {
            return $this->redirectToRoute('book-show-all');
        }

        /* get author */
        $author = $em->getRepository(Author::class)->find($request->request->get('author_id'));
        /* validate author */
        if ( count($errors = $validator->validate($author)) > 0 ) {
            return new Response((string)$errors);
        }

        /* fill book */
        $book->setAuthor($author);
        $book->setTitle($request->request->get('title'));
        $book->setPubYear($request->request->get('pub_year'));

        /* validate book */
        if ( count($errors = $validator->validate($book)) > 0 ) {
            return new Response((string)$errors);
        }
        /* save */
        $em->flush();

        return $this->redirectToRoute('book-show-all');
    }


    /**
     * Delete
     *
     * @Route("/book/delete/{book}", name="book-delete")
     * Method ({"POST"})
     */
    public function actionDelete(Book $book, Request $request): Response
    {
        /* check @csrf */
        $token = $request->request->get('token');
        if ( !$this->isCsrfTokenValid('action-delete', $token) ) {
            return $this->redirectToRoute('book-show-all');
        }
        /* remove item */
        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($book);
            $em->flush();
        }

        return $this->redirectToRoute('book-show-all');
    }
}
