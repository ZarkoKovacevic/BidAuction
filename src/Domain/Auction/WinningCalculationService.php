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
                    //In case we get the new highest bid, we need to check if old one is highest non-winning bid
                    if ($winningPrice >= $highestNonWinningBid) {
                        //if second part of first clause is for checking if same buyer has the highest and the highest non-winning bid
                        //highest non-winning bid should be from different buyer
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
                    //If bid is not the highest one, need to check if is higher that highest non-winning bid
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