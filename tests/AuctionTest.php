<?php

namespace App\Tests;

use App\Domain\Bid;
use App\Domain\BidAuction;
use App\Domain\Buyer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AuctionTest extends KernelTestCase
{
    public function testAuction()
    {
        $auction = new BidAuction(100);

        $buyerA = new Buyer(1, 'BuyerA');
        $buyerB = new Buyer(2, 'BuyerB');
        $buyerC = new Buyer(3, 'BuyerC');
        $buyerD = new Buyer(4, 'BuyerD');
        $buyerE = new Buyer(5, 'BuyerE');

        $buyerA->addBid(new Bid(110));
        $buyerA->addBid(new Bid(130));
        $buyerB->addBid(new Bid(0));
        $buyerC->addBid(new Bid(125));
        $buyerD->addBid(new Bid(105));
        $buyerD->addBid(new Bid(115));
        $buyerD->addBid(new Bid(90));
        $buyerE->addBid(new Bid(132));
        $buyerE->addBid(new Bid(135));
        $buyerE->addBid(new Bid(140));
        $auction->addBuyer($buyerA);
        $auction->addBuyer($buyerB);
        $auction->addBuyer($buyerC);
        $auction->addBuyer($buyerD);
        $auction->addBuyer($buyerE);

        self::bootKernel();
        $container = static::getContainer();
        $service = $container->get('app.winning_calculation_service');

        [$winner, $winningPrice] = $service->winnerAlgorithm($auction);

        $this->assertEquals('BuyerE', $winner);
        $this->assertEquals(130, $winningPrice);
    }
}
