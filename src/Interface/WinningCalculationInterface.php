<?php

namespace App\Interface;

use App\Domain\BidAuction;

interface WinningCalculationInterface {
    public function winnerAlgorithm(BidAuction $bidAuction): array;
}