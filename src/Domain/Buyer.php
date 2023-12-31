<?php

namespace App\Domain;

class Buyer
{
    private int $id;
    private string $name;
    private array $bids;

    public function __construct(int $id, string $name)
    {
        //The ID serves to enable buyers to be unique, because there can be buyers with same name
        //ID is used in winning calculation algorithm
        //In case we have database, id would have unique property, and validation for that,
        //but for this scenario it is only set randomly in AuctionCommand
        $this->id = $id;
        $this->name = $name;
        $this->bids = [];
    }

    public function addBid(Bid $bid)
    {
        $this->bids[] = $bid;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getBids(): array
    {
        return $this->bids;
    }
}
