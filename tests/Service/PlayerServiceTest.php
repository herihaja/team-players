<?php

use App\Entity\Player;
use App\Entity\Team;
use App\Service\PlayerService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PlayerServiceTest extends TestCase
{
    private EntityManagerInterface $entityManager;
    private PlayerService $playerService;
    private UrlGeneratorInterface $router;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->router = $this->createMock(UrlGeneratorInterface::class);

        $this->playerService = new PlayerService($this->entityManager, $this->router);
    }

    public function testSuccessTransfert(): void
    {
        $player = new Player();
        $source = new Team();
        $destination = new Team();
        $price = 100.0;
        $player->setTeam($source);

        $source->setBalance(200.0);
        $destination->setBalance(300.0);

        $this->playerService->transfert($player, $destination, $price);

        $this->assertEquals(300.0, $source->getBalance());
        $this->assertEquals(200.0, $destination->getBalance());

        $this->assertEquals($destination, $player->getTeam());
    }

    public function testFailedTransfert(): void
    {
        $player = new Player();
        $source = new Team();
        $destination = new Team();
        $price = 100.0;
        $player->setTeam($source);

        $source->setBalance(200.0);
        $destination->setBalance(80.0);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('This team can not afford this player!!');
        $this->playerService->transfert($player, $destination, $price);
    }

    public function testAddLinks()
    {
        $this->router->expects($this->exactly(2))
            ->method('generate')
            ->willReturnOnConsecutiveCalls(
                '/player/1/edit',
                '/player/1/transfert'
            );

        $item = [
            'id' => 1,
            'name' => 'John Doe',
            'age' => 25,
        ];

        $result = $this->playerService->addLinks($item);

        $this->assertEquals('/player/1/edit', $result['editLink']);
        $this->assertEquals('/player/1/transfert', $result['transfertLink']);

        $this->assertEquals(1, $result['id']);
        $this->assertEquals('John Doe', $result['name']);
        $this->assertEquals(25, $result['age']);
    }
}
