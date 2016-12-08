<?php

use App\Domain\InvalidAmountException;
use App\Domain\Product;
use App\Domain\Receipt;
use App\Domain\UnsupportedCurrencyException;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use PHPUnit_Framework_Assert as Assert;

/**
 * Defines application features from the specific context.
 */
class ReceiptContext implements Context
{
    private $receipt;
    private $exception;
    private $currency;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {

    }

    /**
     * @Given I have a receipt
     */
    public function iHaveAReceipt()
    {
        $this->receipt = new Receipt('£');
    }

    /**
     * @When I add discount string :arg1
     */
    public function iAddDiscountString($string)
    {
        try {
            $this->receipt->addDiscount($string);
        } catch (InvalidAmountException $e) {
            $this->exception = $e;
        }
    }


    /**
     * @Then I get :arg1 exception
     */
    public function iGetException($exception)
    {
        Assert::assertInstanceOf($exception, $this->exception, 'No exception or wrong exception thrown');
    }

    /**
     * @When I add a discount of :arg1
     */
    public function iAddADiscountOf($discount)
    {
        $discount = floatval($discount);
        $this->receipt->addDiscount($discount);
    }

    /**
     * @Then discount is :arg1
     */
    public function discountIs($discount)
    {
        $discount = floatval($discount);
        Assert::assertEquals($discount, $this->receipt->getDiscount(), 'Wrong discount');
    }


    /**
     * @Given currency :currency
     */
    public function currency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @When I create a receipt
     */
    public function iCreateAReceipt()
    {
        try {
            $this->receipt = new Receipt($this->currency);
        } catch (UnsupportedCurrencyException $e) {
            $this->exception = $e;
        }
    }

    /**
     * @Then I should get an :arg1
     */
    public function iShouldGetAn($expectedException)
    {
        Assert::assertInstanceOf($expectedException, $this->exception);
    }


    /**
     * @Then receipt is created successfully
     */
    public function receiptIsCreatedSuccessfully()
    {
        Assert::assertInstanceOf(Receipt::class, $this->receipt, 'Receipt is not created');
    }
}
