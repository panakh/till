Feature: Product behaviour
  In order to add a product to a receipt
  As a customer
  I need to be able to create product information

  Scenario: invalid name
    Given names
      | name         |
      | spaces       |
      | empty_string |
      | null         |
    And price 1.0
    When I create products for names
    Then I get "App\Domain\InvalidProductNameException" exceptions

  Scenario: invalid price
    Given prices
      | price         |
      | spaces        |
      | empty_string  |
      | just a string |
    And name "Baked beans"
    When I create products for prices
    Then I get "App\Domain\InvalidProductPriceException" exceptions

  Scenario: negative prices
    Given float prices
      | price |
      | -1.0  |
      | -1    |
    And name "Baked beans"
    When I create products for prices
    Then I get "App\Domain\InvalidProductPriceException" exceptions

  Scenario: valid product creation
    Given valid products
      | name        | price |
      | Baked beans | 1.0   |
      | Bread       | .50   |
    When I create products
    Then I have valid products created