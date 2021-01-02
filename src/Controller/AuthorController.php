<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Author;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class AuthorController extends AbstractController
{

    /**
     * Show all (index)
     *
     * @Route("/author/show-all", name="author-show-all")
     */
    public function actionShowAll(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Author::class);

        return $this->render('author/show-all.html.twig', [
            'authors' => $repository->findAll(),
        ]);
    }


    /**
     * Show one
     *
     * @Route("/author/show-one/{author}", name="author-show-one")
     */
    public function actionShowOne(Author $author): Response
    {
        return $this->render('author/show-one.html.twig', [
            'author' => $author,
        ]);
    }


    /**
     * Create
     *
     * @Route("/author/create", name="author-create")
     * Method ({"GET", "POST"})
     */
    public function actionCreate(Request $request, ValidatorInterface $validator): Response
    {
        /* show form */
        if ($request->isMethod('GET')) {
            return $this->render('author/create.html.twig');
        }
        $author = new Author();
        $author->setName($request->request->get('name'));
        $author->setEmail($request->request->get('email'));
        $author->setGender($request->request->get('gender'));

        /* validate, save */
        if ( count($errors = $validator->validate($author)) > 0 ) {
            return new Response((string)$errors);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($author);
        $em->flush();

        return $this->redirectToRoute('author-show-all');
    }


    /**
     * Update
     *
     * @Route("/author/update/{author}", name="author-update")
     * Method ({"GET", "POST"})
     */
    public function actionUpdate(Author $author, Request $request, ValidatorInterface $validator): Response
    {
        /* show form */
        if ($request->isMethod('GET')) {
            return $this->render('author/update.html.twig', [
                'author' => $author
            ]);
        }
        $author->setName($request->request->get('name'));
        $author->setEmail($request->request->get('email'));
        $author->setGender($request->request->get('gender'));

        /* validate */
        if ( count($errors = $validator->validate($author)) > 0 ) {
            return new Response((string)$errors);
        }
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->redirectToRoute('author-show-all');
    }


    /**
     * Delete
     *
     * @Route("/author/delete/{author}", name="author-delete")
     * Method ({"POST"})
     */
    public function actionDelete(Author $author, Request $request): Response
    {
        /* show form */
        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($author);
            $em->flush();
        }

        return $this->redirectToRoute('author-show-all');
    }
}
