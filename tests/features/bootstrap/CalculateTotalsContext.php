<?php

use App\Domain\Product;
use App\Domain\Receipt;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use PHPUnit_Framework_Assert as Assert;

/**
 * Defines application features from the specific context.
 */
class CalculateTotalsContext implements Context
{
    private $receipt;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->receipt = new Receipt('£');
    }

    /**
     * @Given products
     * @param TableNode $table
     */
    public function products(TableNode $table)
    {
        foreach ($table as $row) {
            $name = trim($row['name']);
            $price = floatval(trim($row['price']));
            $product = new Product($name, $price);
            $this->receipt->addProduct($product);
        }
    }

    /**
     * @Given a discount of :discount
     */
    public function aDiscountOf($discount)
    {
        $discount = floatval($discount);
        $this->receipt->addDiscount($discount);
    }

    /**
     * @When I calculate the totals
     */
    public function iCalculateTheTotals()
    {
        $this->receipt->calculateTotals();
    }

    /**
     * @Then the sub total is :arg1
     */
    public function theSubTotalIs($subtotal)
    {
        $subtotal = floatval($subtotal);
        Assert::assertEquals($subtotal, $this->receipt->getSubtotal(), 'Wrong Subtotal');
    }

    /**
     * @Then the discount is :discount
     */
    public function theDiscountIs($discount)
    {
        $discount = floatval($discount);
        Assert::assertEquals($discount, $this->receipt->getDiscount(), 'Wrong discount');
    }

    /**
     * @Then the grand total is :grandTotal
     */
    public function theGrandTotalIs($grandTotal)
    {
        $grandTotal = floatval($grandTotal);
        Assert::assertEquals($grandTotal, $this->receipt->getGrandTotal(), 'Wrong grand total');
    }
}
