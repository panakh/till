<?php

namespace App\Command;

use App\Domain\Receipt;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Helper\TableStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ReceiptDisplay
{
    private $receipt;
    private $table;

    public function __construct(Receipt $receipt, Table $table)
    {
        $this->receipt = $receipt;
        $this->table = $table;
    }

    public function write()
    {
        $this->buildTable();
        $this->table->render();
    }

    private function buildTable()
    {
        $this->addHeader();
        $this->rightAlignPriceColumn();
        $this->addSeparator();
        $this->addProducts();
        $this->addSeparator();
        $this->addEmptyRow();
        $this->addSeparator();
        $this->addSubTotal();
        $this->addDiscounts();
        $this->addSeparator();
        $this->addEmptyRow();
        $this->addSeparator();
        $this->addGrandTotal();
    }

    private function rightAlignPriceColumn()
    {
        $style = new TableStyle();
        $style->setPadType(STR_PAD_LEFT);
        $this->table->setColumnStyle(1, $style);
    }

    private function addHeader()
    {
        $this->table->addRow(['Item', 'Price']);
    }

    private function addSeparator()
    {
        $this->table->addRow(new TableSeparator());
    }

    private function addProducts()
    {
        $products = $this->receipt->getProducts();

        $rows = [];

        foreach ($products as $product) {
            //this can be improved using money format
            $formattedPrice = $this->formatPrice($product->getPrice());
            $this->table->addRow([$product->getName(), $formattedPrice]);
        }
    }

    private function formatPrice($price)
    {
        $formattedPrice = money_format('%n', $price);
        return $formattedPrice;
    }

    private function addEmptyRow()
    {
        $this->table->addRow(['', '']);
    }

    private function addSubTotal()
    {
        $this->table->addRow(['Sub-Total', $this->formatPrice($this->receipt->getSubTotal())]);
    }

    private function addDiscounts()
    {
        $this->table->addRow(['Discounts', $this->formatPrice($this->receipt->getDiscount())]);
    }

    private function addGrandTotal()
    {
        $this->table->addRow(['Grand Total', $this->formatPrice($this->receipt->getGrandTotal())]);
    }
}