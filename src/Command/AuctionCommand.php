<?php

namespace App\Command;

use App\Domain\Auction\WinningCalculationService;
use App\Domain\Bid;
use App\Domain\BidAuction;
use App\Domain\Buyer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

#[AsCommand(name: 'app:run-auction')]
class AuctionCommand extends Command
{
    private QuestionHelper $helper;

    private WinningCalculationService $winningCalculationService;
    public function __construct(WinningCalculationService $winningCalculationService)
    {
        parent::__construct();
        $this->winningCalculationService = $winningCalculationService;
        $this->helper = new QuestionHelper();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Runs the second-price, sealed-bid auction.')
            ->setHelp('This command runs the second-price, sealed-bid auction using predefined buyers and bids.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Please provide the following information to create the auction:');

        $reservePrice = (float) $this->askQuestion('Enter the reserve price: ', $input, $output);

        // Ask for the number of buyers
        $buyerCount = (int) $this->askQuestion('How many buyers do you want? ', $input, $output);

        $auction = new BidAuction($reservePrice);

        // Ask for buyer names and bids
        for ($i = 1; $i <= $buyerCount; $i++) {
            $buyerName = $this->askQuestion("Name for buyer $i? > ", $input, $output);
            $bidAnswer = $this->askQuestion("Enter bid(s) for $buyerName (separated by commas) > ", $input, $output);
            $bids = explode(',', $bidAnswer);

            $buyer = new Buyer(rand(), $buyerName);

            foreach ($bids as $bidValue) {
                $buyer->addBid(new Bid((float) $bidValue));
            }

            $auction->addBuyer($buyer);
        }

        [$winner, $winningPrice] = $this->winningCalculationService->winnerAlgorithm($auction);

        $output->writeln("Winner: $winner");
        $output->writeln("Winning Price: $winningPrice euros");

        return Command::SUCCESS;
    }

    /**
     * @param string $question
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return string
     */
    private function askQuestion(string $question, InputInterface $input, OutputInterface $output): string
    {
        $question = new Question($question);
        return $this->helper->ask($input, $output, $question);
    }
}