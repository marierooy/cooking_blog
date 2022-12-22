<?php

namespace App\Controller;

use App\Entity\Note;
use App\Form\NoteType;
use App\Entity\Comment;
use App\Entity\Recette;
use App\Form\CommentType;
use App\Form\RecetteType;
use App\Repository\NoteRepository;
use App\Repository\CommentRepository;
use App\Repository\RecetteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecetteController extends AbstractController
{
    #[Route('/', name: 'home', methods: ['GET'])]
    public function index(RecetteRepository $recetteRepository): Response
    {
        return $this->render('recette/index.html.twig', [
            'recettes' => $recetteRepository->findAll(),
        ]);
    }

    #[Route('/recette/new', name: 'app_recette_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RecetteRepository $recetteRepository): Response
    {
        $recette = new Recette();
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recette->setUser($this->getUser());
            $recetteRepository->save($recette, true);

            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('recette/new.html.twig', [
            'recette' => $recette,
            'form' => $form,
        ]);
    }

    #[Route('/recette/{id}', name: 'app_recette_show', methods: ['GET', "POST"])]
    public function show(Request $request, CommentRepository $commentRepository, Recette $recette, NoteRepository $noteRepository): Response
    {
        $comment = new Comment();
        $formComment = $this->createForm(CommentType::class, $comment);
        $formComment->handleRequest($request);

        if ($formComment->isSubmitted() && $formComment->isValid()) {
            $comment->setUser($this->getUser());
            $comment->setRecette($recette);
            $commentRepository->save($comment, true);

            return $this->redirectToRoute('app_recette_show', ['id' => $recette->getId()], Response::HTTP_SEE_OTHER);
        }

        $note = new Note();
        $formNote = $this->createForm(NoteType::class, $note);
        $formNote->handleRequest($request);

        if ($formNote->isSubmitted() && $formNote->isValid()) {
            $alreadyNoted = count($noteRepository->findBy([
                'recette' => $recette,
                'user' => $this->getUser()
            ]));

            if ($alreadyNoted > 0) {
                return $this->redirectToRoute('app_recette_show', ['id' => $recette->getId()], Response::HTTP_SEE_OTHER);
            }

            $note->setUser($this->getUser());
            $note->setRecette($recette);

            $noteRepository->save($note, true);

            return $this->redirectToRoute('app_recette_show', ['id' => $recette->getId()], Response::HTTP_SEE_OTHER);
        }

        $count = 0;
        $total = 0;
        $notes = $recette->getNotes();
        foreach($notes as $note) {
            $count++;
            $total += $note->getValue();
        }

        if ($count > 0) {
            $total = $total / $count;
        }

        return $this->render('recette/show.html.twig', [
            'recette' => $recette,
            'formComment' => $formComment,
            'comments' => $recette->getComments(),
            'formNote' => $formNote,
            'total' => $total,
            'count' => $count,
            'notes' => $notes
        ]);
    }

    #[Route('/recette/{id}/edit', name: 'app_recette_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Recette $recette, RecetteRepository $recetteRepository): Response
    {
        if($recette->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recetteRepository->save($recette, true);

            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('recette/edit.html.twig', [
            'recette' => $recette,
            'form' => $form,
        ]);
    }

    #[Route('/recette/{id}/delete', name: 'app_recette_delete', methods: ['POST'])]
    public function delete(Request $request, Recette $recette, RecetteRepository $recetteRepository): Response
    {
        if($recette->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        if ($this->isCsrfTokenValid('delete'.$recette->getId(), $request->request->get('_token'))) {
            $recetteRepository->remove($recette, true);
        }

        return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
    }
}
