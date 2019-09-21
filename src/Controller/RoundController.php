<?php

namespace App\Controller;

use App\Entity\Round;
use App\Form\RoundType;
use App\Repository\RoundRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/round")
 */
class RoundController extends AbstractController
{
    /**
     * @Route("/", name="round_index", methods={"GET"})
     */
    public function index(RoundRepository $roundRepository): Response
    {
        return $this->render('round/index.html.twig', [
            'rounds' => $roundRepository->findAll(),
        ]);
    }

    /**
     * @Route("/latest", name="round_latest", methods={"GET"})
     */
    public function latest(RoundRepository $roundRepository): Response
    {
        return $this->render('round/latest.html.twig', [
            'round' => $roundRepository->findOneBy([], ['id' => 'DESC']),
        ]);
    }

    /**
     * @Route("/latest/player1", name="round_latest_player1", methods={"GET"})
     */
    public function latestPlayer1(RoundRepository $roundRepository): Response
    {
        return $this->render('round/latest_single_field.html.twig', [
            'field' => $roundRepository->findOneBy([], ['id' => 'DESC'])->getPlayer1(),
        ]);
    }

    /**
     * @Route("/latest/player2", name="round_latest_player2", methods={"GET"})
     */
    public function latestPlayer2(RoundRepository $roundRepository): Response
    {
        return $this->render('round/latest_single_field.html.twig', [
            'field' => $roundRepository->findOneBy([], ['id' => 'DESC'])->getPlayer2(),
        ]);
    }

    /**
     * @Route("/latest/player1/score", name="round_latest_player1_score", methods={"GET"})
     */
    public function latestPlayer1Score(RoundRepository $roundRepository): Response
    {
        return $this->render('round/latest_single_field.html.twig', [
            'field' => $roundRepository->findOneBy([], ['id' => 'DESC'])->getPlayer1Score(),
        ]);
    }

    /**
     * @Route("/latest/player2/score", name="round_latest_player2_score", methods={"GET"})
     */
    public function latestPlayer2Score(RoundRepository $roundRepository): Response
    {
        return $this->render('round/latest_single_field.html.twig', [
            'field' => $roundRepository->findOneBy([], ['id' => 'DESC'])->getPlayer2Score(),
        ]);
    }



    /**
     * @Route("/new", name="round_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $round = new Round();
        $form = $this->createForm(RoundType::class, $round);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($round);
            $entityManager->flush();

            return $this->redirectToRoute('round_index');
        }

        return $this->render('round/new.html.twig', [
            'round' => $round,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="round_show", methods={"GET"})
     */
    public function show(Round $round): Response
    {
        return $this->render('round/show.html.twig', [
            'round' => $round,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="round_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Round $round): Response
    {
        $form = $this->createForm(RoundType::class, $round);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('round_index');
        }

        return $this->render('round/edit.html.twig', [
            'round' => $round,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="round_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Round $round): Response
    {
        if ($this->isCsrfTokenValid('delete'.$round->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($round);
            $entityManager->flush();
        }

        return $this->redirectToRoute('round_index');
    }
}
