<?php

namespace App\Domain\Auction;

use App\Domain\BidAuction;
use App\Interface\WinningCalculationInterface;

class WinningCalculationService implements WinningCalculationInterface
{
    /**
     * @param BidAuction $bidAuction
     * @return array<mixed>
     */
    public function winnerAlgorithm(BidAuction $bidAuction): array
    {
        $highestNonWinningBid = 0;
        $highestNonWinningBuyer = null;
        $winningPrice = $bidAuction->getReservePrice();
        $winningBuyer = null;

        foreach ($bidAuction->getBuyers() as $buyer) {
            foreach ($buyer->getBids() as $bid) {
                if ($bid->getAmount() >= $winningPrice) {
                    if ($winningPrice >= $highestNonWinningBid) {
                        if (($winningBuyer && $winningBuyer->getId() != $buyer->getId()) ||
                            !$highestNonWinningBuyer
                        ) {
                            $highestNonWinningBid = $winningPrice;
                            $highestNonWinningBuyer = $buyer;
                        }
                    }
                    $winningPrice = $bid->getAmount();
                    $winningBuyer = $buyer;
                } elseif ($bid->getAmount() >= $highestNonWinningBid) {
                    $highestNonWinningBid = $bid->getAmount();
                    $highestNonWinningBuyer = $buyer;
                }
            }
        }

        if ($highestNonWinningBid > $bidAuction->getReservePrice()) {
            $winningPrice = $highestNonWinningBid;
        }

        return [$winningBuyer->getName(), $winningPrice];
    }
}