<?php

namespace App\Controller;


use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    /**
     * @Route("/addBook", name="addBook")
     */
    public function addBook(Request $request, EntityManagerInterface $em): Response
    {
        $book = new Book();
        $book->setPublished(true);
        $form = $this->createForm(BookType::class, $book);
        $form->add('save', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $author = $book->getRel();
            $author->setNbBooks($author->getNbBooks() + 1);
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('listesBooks');
        }
        return $this->render('book/addBook.html.twig', ['form' => $form->createView()]);
    }


    /**
     * @Route("/listesBooks",name="listesBooks")
     */
    public function listBooks(BookRepository $bookRepository): Response
    {
        return $this->render('book/listBooks.html.twig', [
            'controller_name' => 'BookController',
            'liste' => $bookRepository->findAll(),
        ]);
    }

    /**
     * @Route("/editBook/{id}",name="editBook")
     */

    public function editBook(Request $request, EntityManagerInterface $em, BookRepository $rep, int $id): Response
    {
        $book = new Book();
        $book = $rep->find($id);
        $form = $this->createForm(BookType::class, $book);
        $form->add('save', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('listesBooks');
        }
        return $this->render('book/addBook.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/deleteBook/{id}",name="deleteBook")
     */

    public function deleteBook(BookRepository $rep, $id, EntityManagerInterface $em): Response
    {
        $book = new Book();
        $book = $rep->find($id);
        $em->remove($book);
        $em->flush();
        return $this->redirectToRoute('listesBooks');
    }

    /**
     * @Route("/show/{id}",name="show")
     */
    public function show(BookRepository $bookRepository, $id): Response
    {

        return $this->render('book/show.html.twig', [
            'controller_name' => 'BookController',
            'book' => $bookRepository->find($id),
        ]);
    }
}
