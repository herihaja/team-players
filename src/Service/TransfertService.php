<?php

namespace App\Service;

use App\Entity\Player;
use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;

class TransfertService
{
    public function __construct(
        private EntityManagerInterface $em
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
}
