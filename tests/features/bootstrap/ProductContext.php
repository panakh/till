<?php

use App\Domain\InvalidAmountException;
use App\Domain\InvalidProductNameException;
use App\Domain\InvalidProductPriceException;
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
class ProductContext implements Context
{
    private $inputs = [
        'spaces' => '            ',
        'empty_string' => '',
        'null' => null
    ];

    private $names = [];
    private $price;
    private $errors = [];
    private $name;
    private $prices = [];
    private $productData = [];
    private $products = [];

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
     * @Given names
     * @param TableNode $table
     */
    public function names(TableNode $table)
    {
        foreach ($table as $row) {

            if (!array_key_exists('name', $row)) {
                throw new InvalidArgumentException('Table row should contain column \'name\'');
            }

            if (!array_key_exists($row['name'], $this->inputs)) {
                throw new InvalidArgumentException(sprintf('Input %s is not valid', $row['name']));
            }

            $this->names[] = $this->inputs[$row['name']];
        }
    }

    /**
     * @Given price :arg1
     */
    public function price($price)
    {
        $this->price = floatval($price);

    }

    /**
     * @When I create products for names
     */
    public function iCreateProductsForNames()
    {

        foreach ($this->names as $name) {
            try {
                $product = new Product($name, $this->price);
                $this->errors[] = null;
            } catch (InvalidProductNameException $e) {

                $this->errors[] = $e;
            }
        }

    }

    /**
     * @Then I get :arg1 exceptions
     */
    public function iGetExceptions($exception)
    {
        foreach ($this->errors as $caughtException) {

            Assert::assertNotNull($caughtException, 'Exception not thrown');
            Assert::assertInstanceOf($exception,
                $caughtException,
                'Wrong exception thrown');
        }
    }

    /**
     * @Given prices
     */
    public function prices(TableNode $table)
    {
        foreach ($table as $row) {

            if (!array_key_exists('price', $row)) {
                throw new InvalidArgumentException('Table row should contain column \'price\'');
            }

            $price = null;

            if (array_key_exists($row['price'], $this->inputs)) {
                $price = $this->inputs[$row['price']];
            } else {
                $price = $row['price'];
            }

            $this->prices[] = $price;
        }
    }

    /**
     * @Given float prices
     */
    public function floatPrices(TableNode $table)
    {
        foreach ($table as $row) {

            if (!array_key_exists('price', $row)) {
                throw new InvalidArgumentException('Table row should contain column \'price\'');
            }

            $this->prices[] = floatval($row['price']);
            $price = null;
        }
    }

    /**
     * @Given name :name
     */
    public function name($name)
    {
        $this->name = $name;
    }

    /**
     * @When I create products for prices
     */
    public function iCreateProductsForPrices()
    {
        foreach ($this->prices as $price) {
            try {
                $product = new Product($this->name, $price);
                $this->errors[] = null;
            } catch (InvalidProductPriceException $e) {

                $this->errors[] = $e;
            }
        }
    }

    /**
     * @Given valid products
     */
    public function validProducts(TableNode $table)
    {
        foreach ($table as $row) {

            if (!array_key_exists('name', $row)) {
                throw new InvalidArgumentException('Table row should contain column \'name\'');
            }

            if (!array_key_exists('price', $row)) {
                throw new InvalidArgumentException('Table row should contain column \'name\'');
            }

            $this->productData[] = [
                'name' => $row['name'],
                'price' => (float)$row['price']
            ];
        }
    }

    /**
     * @When I create products
     */
    public function iCreateProducts()
    {
        foreach ($this->productData as $data) {
            $this->products[] = new Product($data['name'], $data['price']);
        }
    }

    /**
     * @Then I have valid products created
     */
    public function iHaveValidProductsCreated()
    {
        Assert::assertEquals(
            count($this->products),
            count($this->productData),
            'No of products created does not match the input data');

        foreach ($this->products as $index => $product) {
            $data = $this->productData[$index];
            Assert::assertEquals($data['name'], $product->getName(), 'Product name does not match');
            Assert::assertEquals($data['price'], $product->getPrice(), 'Product price does not match');
        }
    }

}
