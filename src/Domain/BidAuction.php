<?php

namespace App\Domain;

class BidAuction
{
    private int $reservePrice;
    private array $buyers;

    public function __construct(int $reservePrice)
    {
        $this->reservePrice = $reservePrice;
        $this->buyers = [];
    }

    public function addBuyer($buyer)
    {
        $this->buyers[] = $buyer;
    }

    /**
     * @return int
     */
    public function getReservePrice(): int
    {
        return $this->reservePrice;
    }

    /**
     * @param int $reservePrice
     */
    public function setReservePrice(int $reservePrice): void
    {
        $this->reservePrice = $reservePrice;
    }

    /**
     * @return array
     */
    public function getBuyers(): array
    {
        return $this->buyers;
    }
}
