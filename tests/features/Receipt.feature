Feature: Receipt behaviour
  In order to pay for my purchase
  As a customer
  I need to be able to see the receipt

  Scenario: invalid discount
    Given I have a receipt
    When I add discount string "string"
    Then I get "App\Domain\InvalidAmountException" exception

  Scenario: valid discount
    Given I have a receipt
    When I add a discount of 0.5
    Then discount is 0.5

  Scenario Outline: invalid currency
    Given currency <currency>
    When I create a receipt
    Then I should get an "App\Domain\UnsupportedCurrencyException"
    Examples:
      | currency |
      | r        |
      | "%"      |
      | 0        |
      | 7        |
      | "   "    |

  Scenario Outline: valid currency
    Given currency <currency>
    When I create a receipt
    Then receipt is created successfully
    Examples:
      | currency |
      | "$"      |
      | "£"      |