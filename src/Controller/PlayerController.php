<?php

namespace App\Controller;

use App\Entity\Player;
use App\Form\PlayerType;
use App\Form\TransfertType;
use App\Repository\PlayerRepository;
use App\Repository\TeamRepository;
use App\Service\PlayerService;
use App\Utils\ApiPaginator;
use App\Utils\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    #[Route('/json', name: 'app_player_list', methods: ['GET'])]
    public function axios(
        PlayerRepository $playerRepository,
        Request $request,
        ApiPaginator $paginator,
        PlayerService $service
    ): JsonResponse {
        $currentPage = $request->query->getInt('page', 1);
        $search = $request->query->get('search', null);
        $team = $request->query->getInt('team', 0);
        $paginator->paginate(
            $playerRepository->getPaginatorQuery($search, $team),
            $currentPage,
            function ($item) use ($service) { return $service->addLinks($item); }
        );

        return new JsonResponse([
            'data' => $paginator->getItems(),
            'count' => $paginator->getTotal(),
            'lastPage' => $paginator->getLastPage(),
            'currentPage' => $currentPage,
        ]);
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
            'isEdit' => false,
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
            'isEdit' => true,
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

    #[Route('/{id}/transfert', name: 'app_player_transfert', methods: ['GET', 'POST'])]
    public function transfert(
        Request $request,
        Player $player,
        PlayerService $service,
        TeamRepository $teamRepository
    ): Response {
        $form = $this->createForm(TransfertType::class, $player, ['allow_extra_fields' => true]);
        $form->handleRequest($request);
        $message = '';

        if ($form->isSubmitted() && $form->isValid()) {
            $postData = $request->request->all('transfert');
            $team = $teamRepository->find($postData['destination']);
            $result = $service->transfert($player, $team, $postData['price']);
            if ($result['success']) {
                $this->addFlash('Info', 'The player is transfered...');

                return $this->redirectToRoute('app_player_index', [], Response::HTTP_SEE_OTHER);
            }
            $message = $result['message'];
        }

        return $this->render('player/transfert.html.twig', [
            'player' => $player,
            'form' => $form,
            'message' => $message,
        ]);
    }

    #[Route('/vuejs', name: 'app_player_vuejs', methods: ['GET'])]
    public function dashboard(Request $request): Response
    {
        return $this->render('team/dashboard.html.twig');
    }
}
