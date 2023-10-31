<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Form\MenuType;
use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{


    /**
     * @Route("/menu",name="menu")
     */
    public function menu(MenuRepository $menuRepository): Response
    {
        return $this->render('menu/afficheMenu.html.twig', [
            'controller_name' => 'MenuController',
            'liste' => $menuRepository->findAll(),
        ]);
    }
    /**
     * @Route("/ajoutPlat", name="ajoutPlat")
     */
    public function ajoutPlat(Request $request, EntityManagerInterface $em): Response
    {
        $plat = new Menu();
        $form = $this->createForm(MenuType::class, $plat);
        $form->add('save', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($plat);
            $em->flush();
            return $this->redirectToRoute('menu');
        }
        return $this->render('menu/ajout.html.twig', ['form' => $form->createView()]);
    }


    /**
     * @Route("/editMenu/{id}",name="editMenu")
     */
    public function editMenu(Request $request, EntityManagerInterface $em, MenuRepository $rep, int $id): Response
    {
        $menu = new Menu();
        $menu = $rep->find($id);
        $form = $this->createForm(MenuType::class, $menu);
        $form->add('save', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($menu);
            $em->flush();
            return $this->redirectToRoute('menu');
        }
        return $this->render('menu/ajout.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/delete/{id}",name="delete")
     */
    public function delete(MenuRepository $rep, $id, EntityManagerInterface $em): Response
    {
        $menu = new Menu();
        $menu = $rep->find($id);
        $em->remove($menu);
        $em->flush();
        return $this->redirectToRoute('menu');
    }



    /**
     * @Route("/show/{id}",name="show")
     */
    public function show(MenuRepository $menuRepository, $id): Response
    {
        return $this->render('menu/showDetails.html.twig', [
            'plat' => $menuRepository->find($id),
        ]);
    }
}
