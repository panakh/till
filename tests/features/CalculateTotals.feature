Feature: Calculate Totals
  In order to buy products
  As a customer
  I need to be able to see the totals of the items that I am buying

  Scenario: Adding items with
    Given products
      | name              | price |
      | Baked Beans       | .5    |
      | Washing up liquid | .72   |
      | Rubber gloves     | 1.50  |
      | Bread             | .72   |
      | Butter            | .83   |
    And a discount of ".5"
    When I calculate the totals
    Then the sub total is "4.27"
    And the discount is ".5"
    And the grand total is "3.77"