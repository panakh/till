<?php

namespace App\Command;

use App\Domain\Product;
use App\Domain\Receipt;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class CalculateTotalsCommand extends Command
{
    private $input;
    private $output;

    protected function configure()
    {
        $this->setName('till:calculate-totals')
            ->setDescription('Calculates totals for items entered')
            ->setHelp($this->getCommandHelp());
    }

    protected function getCommandHelp()
    {
        return <<<HELP
The <info>%command.name%</info> command receives items and calculates the totals
  <info>php %command.full_name%</info> <comment>item-names...</comment>
HELP;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        $currency = $this->receiveCurrency();
        $receipt = new Receipt($currency);
        $products = [];
        while (true) {

            $item = $this->receiveItem();
            if (null == $item) {
                break;
            }

            $price = $this->receivePrice();

            $product = new Product($item, $price);
            $receipt->addProduct($product);
        }

        $discount = $this->receiveDiscount();
        $receipt->addDiscount((float)$discount);
        $receipt->calculateTotals();

        $display = new ReceiptDisplay($receipt, new Table($output));
        $display->write();
    }

    private function receiveCurrency()
    {
        $helper = $this->getHelper('question');
        $question = new Question('Please enter your till\'s currency (£,$): ');
        $currency = $helper->ask($this->input, $this->output, $question);
        return $currency;
    }

    private function receiveItem()
    {
        $helper = $this->getHelper('question');
        $question = new Question('Please enter the item, <info>Hit enter to finish</info> : ');
        $name = $helper->ask($this->input, $this->output, $question);
        return $name;
    }

    private function receivePrice()
    {
        $helper = $this->getHelper('question');
        $question = new Question('Please enter the price : ', 0);
        $price = $helper->ask($this->input, $this->output, $question);
        return $price;
    }

    private function receiveDiscount()
    {
        $helper = $this->getHelper('question');
        $question = new Question('Please enter discounts : ', 0);
        $discount = $helper->ask($this->input, $this->output, $question);
        return $discount;
    }
}