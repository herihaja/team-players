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

    public function transfert(Player $player, Team $destination, float $price): void
    {
        $source = $player->getTeam();
        $sourceBalance = $source->getBalance() + $price;
        $destinationBalance = $destination->getBalance() - $price;
        if ($destinationBalance < 0) {
            throw new \Exception('This team can not afford this player!!');
        }
        $source->setBalance($sourceBalance);
        $destination->setBalance($destinationBalance);
        $player->setTeam($destination);
        $this->em->persist($source);
        $this->em->persist($destination);
        $this->em->persist($player);
        $this->em->flush();
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
