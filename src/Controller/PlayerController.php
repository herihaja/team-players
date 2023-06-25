<?php

namespace App\Controller;

use App\Entity\Player;
use App\Entity\Team;
use App\Form\PlayerType;
use App\Form\TransfertType;
use App\Repository\PlayerRepository;
use App\Repository\TeamRepository;
use App\Service\PlayerService;
use App\Utils\ApiPaginator;
use App\Utils\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\Query;

#[Route('/player')]
class PlayerController extends AbstractController
{
    #[Route('/', name: 'app_player_index', methods: ['GET'])]
    public function index(PlayerRepository $playerRepository, Request $request, Paginator $paginator): Response
    {
        $paginator->paginate($playerRepository->getPaginatorQuery(), $request->query->getInt('page', 1));

        return $this->render('player/index.html.twig', [
            'paginator' => $paginator,
        ]);
    }

    

    #[Route('/axios', name: 'app_player_list', methods: ['GET'])]
    public function axios(PlayerRepository $playerRepository, Request $request, ApiPaginator $paginator, PlayerService $service): JsonResponse
    {
        $paginator->paginate(
            $playerRepository->getPaginatorQuery(), 
            $request->query->getInt('page', 1),
            10,
            function ($item) use ($service) { return $service->addLinks($item); }
        );
        
        return new JsonResponse(['data' => $paginator->getItems()]);
    }

    #[Route('/new', name: 'app_player_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PlayerRepository $playerRepository): Response
    {
        $player = new Player();
        $form = $this->createForm(PlayerType::class, $player);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $playerRepository->save($player, true);

            return $this->redirectToRoute('app_player_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('player/form.html.twig', [
            'player' => $player,
            'form' => $form,
            'isEdit' => false
        ]);
    }

    #[Route('/{id}/edit', name: 'app_player_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Player $player, PlayerRepository $playerRepository): Response
    {
        $form = $this->createForm(PlayerType::class, $player, ['isEdit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $playerRepository->save($player, true);

            return $this->redirectToRoute('app_player_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('player/form.html.twig', [
            'player' => $player,
            'form' => $form,
            'isEdit' => true
        ]);
    }

    #[Route('/{id}', name: 'app_player_delete', methods: ['POST'])]
    public function delete(Request $request, Player $player, PlayerRepository $playerRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$player->getId(), $request->request->get('_token'))) {
            $playerRepository->remove($player, true);
        }

        return $this->redirectToRoute('app_player_index', [], Response::HTTP_SEE_OTHER);
    }

    // Todo: update method to post
    #[Route('/{id}/transfert', name: 'app_player_transfert', methods: ['GET', 'POST'])]
    public function transfert(Request $request, Player $player, PlayerService $service, TeamRepository $teamRepository): Response
    {
        $form = $this->createForm(TransfertType::class, $player, ['allow_extra_fields'=> true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postData = $request->request->all('transfert');
            $team = $teamRepository->find($postData["destination"]);
            $service->transfert($player, $team, $postData['price']);
            $this->addFlash('Info', 'The player is transfered...');
            return $this->redirectToRoute('app_player_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('player/transfert.html.twig', [
            'player' => $player,
            'form' => $form
        ]);
    }

    #[Route('/vuejs', name: 'app_player_dashboard', methods: ['GET'])]
    public function dashboard(Request $request): Response
    {
        return $this->render('team/dashboard.html.twig');
    }
}
