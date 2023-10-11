<?php

namespace App\FrontendWizard;

class MonthlyPageWizard {
    // ===> SORTING BUTTONS
    #TODO: Umschreiben, sodass sich die Box dynamisch in einen Doppelpfeil verwandelt
    // CATEGORY
    public function sortButtonCategoryNoSort(): string {
        return "<form action='./?route=monthly-page' method='POST'><input type='hidden' name='sort' value='sortCategoryAsc'><input type='hidden' name='sortingProperty' value='category'><input type='image' src='./img/box.png' alt='Box symbol to sort the list' height='13px' width='13px' class='sortbutton'></form>";
    }

    public function sortButtonCategoryAsc(): string {
        return "<form action='./?route=monthly-page' method='POST'><input type='hidden' name='sort' value='sortCategoryDesc'><input type='hidden' name='sortingProperty' value='category'><input type='image' src='./img/arrow_down.png' alt='Small arrow to sort the list' height='13px' width='13px' class='sortbutton'></form>";
    }
    
    public function sortButtonCategoryDesc(): string {
        return "<form action='./?route=monthly-page' method='POST'><input type='hidden' name='sort' value='sortCategoryAsc'><input type='hidden' name='sortingProperty' value='category'><input type='image' src='./img/arrow_up.png' alt='Small arrow to sort the list' height='13px' width='13px' class='sortbutton'></form>";
        
    }

    // TITLE
    public function sortButtonTitleNoSort(): string {
        return "<form action='./?route=monthly-page' method='POST'><input type='hidden' name='sort' value='sortTitleAsc'><input type='hidden' name='sortingProperty' value='title'><input type='image' src='./img/box.png' alt='Box symbol to sort the list' height='13px' width='13px' class='sortbutton'></form>";
    }

    public function sortButtonTitleAsc(): string {
        return "<form action='./?route=monthly-page' method='POST'><input type='hidden' name='sort' value='sortTitleDesc'><input type='hidden' name='sortingProperty' value='title'><input type='image' src='./img/arrow_down.png' alt='Small arrow to sort the list' height='13px' width='13px' class='sortbutton'></form>";
    }
    
    public function sortButtonTitleDesc(): string {
        return "<form action='./?route=monthly-page' method='POST'><input type='hidden' name='sort' value='sortTitleAsc'><input type='hidden' name='sortingProperty' value='title'><input type='image' src='./img/arrow_up.png' alt='Small arrow to sort the list' height='13px' width='13px' class='sortbutton'></form>";
    }

    // AMOUNT
    public function sortButtonAmountNoSort(): string {
        return "<form action='./?route=monthly-page' method='POST'><input type='hidden' name='sort' value='sortAmountAsc'><input type='hidden' name='sortingProperty' value='amount'><input type='image' src='./img/box.png' alt='Box symbol to sort the list' height='13px' width='13px' class='sortbutton'></form>";
    }

    public function sortButtonAmountAsc(): string {
        return "<form action='./?route=monthly-page' method='POST'><input type='hidden' name='sort' value='sortAmountDesc'><input type='hidden' name='sortingProperty' value='amount'><input type='image' src='./img/arrow_down.png' alt='Small arrow to sort the list' height='13px' width='13px' class='sortbutton'></form>";
    }
    
    public function sortButtonAmountDesc(): string {
        return "<form action='./?route=monthly-page' method='POST'><input type='hidden' name='sort' value='sortAmountAsc'><input type='hidden' name='sortingProperty' value='amount'><input type='image' src='./img/arrow_up.png' alt='Small arrow to sort the list' height='13px' width='13px' class='sortbutton'></form>";
    }

    // DATE
    public function sortButtonDateNoSort(): string {
        return "<form action='./?route=monthly-page' method='POST'><input type='hidden' name='sort' value='sortDateAsc'><input type='hidden' name='sortingProperty' value='date'><input type='image' src='./img/box.png' alt='Box symbol to sort the list' height='13px' width='13px' class='sortbutton'></form>";
    }

    public function sortButtonDateAsc(): string {
        return "<form action='./?route=monthly-page' method='POST'><input type='hidden' name='sort' value='sortDateDesc'><input type='hidden' name='sortingProperty' value='date'><input type='image' src='./img/arrow_down.png' alt='Small arrow to sort the list' height='13px' width='13px' class='sortbutton'></form>";
    }
    
    public function sortButtonDateDesc(): string {
        return "<form action='./?route=monthly-page' method='POST'><input type='hidden' name='sort' value='sortDateAsc'><input type='hidden' name='sortingProperty' value='date'><input type='image' src='./img/arrow_up.png' alt='Small arrow to sort the list' height='13px' width='13px' class='sortbutton'></form>";
    }

}