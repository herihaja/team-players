<?php

namespace App\Service;

use App\Entity\Player;
use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PlayerService
{
    public function __construct(
        private EntityManagerInterface $em,
        private UrlGeneratorInterface $router,
    ) {
    }

    /**
     * Method to transfert player from his team to a new team.
     *
     * @return array<string>
     */
    public function transfert(Player $player, Team $destination, float $price): array
    {
        $source = $player->getTeam();
        $sourceBalance = $source->getBalance() + $price;
        $destinationBalance = $destination->getBalance() - $price;
        if ($destinationBalance < 0) {
            return ['success' => false, 'message' => 'The destination team cannot afford this player.'];
        }
        $source->setBalance($sourceBalance);
        $destination->setBalance($destinationBalance);
        $player->setTeam($destination);
        $this->em->persist($source);
        $this->em->persist($destination);
        $this->em->persist($player);
        $this->em->flush();

        return ['success' => true, 'message' => 'Player transfered successfully.'];
    }

    /**
     * Add links to an item to be returned as api response.
     *
     * @param array<mixed> $item
     *
     * @return array<mixed>
     */
    public function addLinks(array $item): array
    {
        $item['editLink'] = $this->router->generate('app_player_edit', ['id' => $item['id']]);
        $item['transfertLink'] = $this->router->generate('app_player_transfert', ['id' => $item['id']]);

        return $item;
    }
}
