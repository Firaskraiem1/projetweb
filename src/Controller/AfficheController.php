<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AfficheController extends AbstractController
{
    /**
     * @Route("/listesAuteurs",name="listesAuteurs")
     */
    public function listAuteurs(AuthorRepository $authorRepository): Response
    {
        return $this->render('affiche/affiche.html.twig', [
            'controller_name' => 'AfficheController',
            'liste' => $authorRepository->findAll(),
        ]);
    }

    /**
     * @Route("/static", name="name_static")
     */
    public function addStatic(ManagerRegistry $manager): Response
    {
        $auth = new Author();
        $auth->setUsername("firas");
        $auth->setEmail("firas.benkraiem@esprit.tn");
        $em = $manager->getManager();
        $em->persist($auth);
        $em->flush();
        return $this->redirectToRoute('listesAuteurs');
    }

    //ajouter un auteur dynamiquement 

    /**
     * @Route("/add", name="name_add")
     */
    public function addAuthor(Request $request, EntityManagerInterface $em): Response
    {
        $auth = new Author();
        $form = $this->createForm(AuthorType::class, $auth);
        $form->add('save', SubmitType::class); //on peut l'ajouter dans la classe Form AuthorTYpe
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($auth);
            $em->flush();
            return $this->redirectToRoute('listesAuteurs');
        }
        return $this->render('affiche/edit.html.twig', ['form' => $form->createView()]);
    }
    /**
     * @Route("/edit/{id}", name="name_edit")
     */
    public function editAuthor(Request $request, EntityManagerInterface $em, AuthorRepository $rep, int $id): Response
    {
        $auth = new Author();
        $auth = $rep->find($id);
        $form = $this->createForm(AuthorType::class, $auth);
        $form->add('save', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($auth);
            $em->flush();
            return $this->redirectToRoute('listesAuteurs');
        }
        return $this->render('affiche/edit.html.twig', ['form' => $form->createView()]);
    }
    /**
     * @Route("/delete/{id}", name="name_delete")
     */
    public function deleteAuthor(AuthorRepository $rep, $id, EntityManagerInterface $em): Response
    {
        $auth = new Author();
        $auth = $rep->find($id);
        $em->remove($auth);
        $em->flush();
        return $this->redirectToRoute('listesAuteurs');
    }
}
