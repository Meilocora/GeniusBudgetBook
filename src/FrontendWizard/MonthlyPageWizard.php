<?php

namespace App\FrontendWizard;

class MonthlyPageWizard {
    // ===> SORTING BUTTONS
    // CATEGORY
    public function sortButtonCategoryNoSort(): string {
        return "<form action='./?route=monthly-page' method='POST'>
        <input type='hidden' name='sort' value='sortCategoryAsc'><input type='hidden' name='sortingProperty' value='category'>
        <div class='image-box'>
        <input type='image' src='./img/box.png' alt='Box symbol to sort the list' height='13px' width='13px' class='sortbutton fadeout'>
        <input type='image' src='./img/arrow_double.png' alt='Double arrow symbol to sort the list' height='13px' width='13px' class='sortbutton fadein'></div>
        </form>";
    }

    public function sortButtonCategoryAsc(): string {
        return "<form action='./?route=monthly-page' method='POST'><input type='hidden' name='sort' value='sortCategoryDesc'><input type='hidden' name='sortingProperty' value='category'>
        <div class='image-box'>
        <input type='image' src='./img/arrow_down.png' alt='Small arrow to sort the list' height='13px' width='13px' class='sortbutton'></div>
        </form>";
    }
    
    public function sortButtonCategoryDesc(): string {
        return "<form action='./?route=monthly-page' method='POST'><input type='hidden' name='sort' value='sortCategoryAsc'><input type='hidden' name='sortingProperty' value='category'>
        <div class='image-box'>
        <input type='image' src='./img/arrow_up.png' alt='Small arrow to sort the list' height='13px' width='13px' class='sortbutton'></div>
        </form>";
        
    }

    // TITLE
    public function sortButtonTitleNoSort(): string {
        return "<form action='./?route=monthly-page' method='POST'>
        <input type='hidden' name='sort' value='sortTitleAsc'><input type='hidden' name='sortingProperty' value='title'>
        <div class='image-box'>
        <input type='image' src='./img/box.png' alt='Box symbol to sort the list' height='13px' width='13px' class='sortbutton fadeout'>
        <input type='image' src='./img/arrow_double.png' alt='Double arrow symbol to sort the list' height='13px' width='13px' class='sortbutton fadein'></div>
        </form>";
    }

    public function sortButtonTitleAsc(): string {
        return "<form action='./?route=monthly-page' method='POST'><input type='hidden' name='sort' value='sortTitleDesc'><input type='hidden' name='sortingProperty' value='title'>
        <div class='image-box'>
        <input type='image' src='./img/arrow_down.png' alt='Small arrow to sort the list' height='13px' width='13px' class='sortbutton'></div>
        </form>";
    }
    
    public function sortButtonTitleDesc(): string {
        return "<form action='./?route=monthly-page' method='POST'><input type='hidden' name='sort' value='sortTitleAsc'><input type='hidden' name='sortingProperty' value='title'>
        <div class='image-box'>
        <input type='image' src='./img/arrow_up.png' alt='Small arrow to sort the list' height='13px' width='13px' class='sortbutton'></div>
        </form>";
    }

    // AMOUNT
    public function sortButtonAmountNoSort(): string {
        return "<form action='./?route=monthly-page' method='POST'>
        <input type='hidden' name='sort' value='sortAmountAsc'><input type='hidden' name='sortingProperty' value='amount'>
        <div class='image-box'>
        <input type='image' src='./img/box.png' alt='Box symbol to sort the list' height='13px' width='13px' class='sortbutton fadeout'>
        <input type='image' src='./img/arrow_double.png' alt='Double arrow symbol to sort the list' height='13px' width='13px' class='sortbutton fadein'></div>
        </form>";
    }

    public function sortButtonAmountAsc(): string {
        return "<form action='./?route=monthly-page' method='POST'><input type='hidden' name='sort' value='sortAmountDesc'><input type='hidden' name='sortingProperty' value='amount'>
        <div class='image-box'>
        <input type='image' src='./img/arrow_down.png' alt='Small arrow to sort the list' height='13px' width='13px' class='sortbutton'></div>
        </form>";
    }
    
    public function sortButtonAmountDesc(): string {
        return "<form action='./?route=monthly-page' method='POST'><input type='hidden' name='sort' value='sortAmountAsc'><input type='hidden' name='sortingProperty' value='amount'>
        <div class='image-box'>
        <input type='image' src='./img/arrow_up.png' alt='Small arrow to sort the list' height='13px' width='13px' class='sortbutton'></div>
        </form>";
    }

    // DATE
    public function sortButtonDateNoSort(): string {
        return "<form action='./?route=monthly-page' method='POST'>
        <input type='hidden' name='sort' value='sortDateAsc'><input type='hidden' name='sortingProperty' value='dateslug'>
        <div class='image-box'>
        <input type='image' src='./img/box.png' alt='Box symbol to sort the list' height='13px' width='13px' class='sortbutton fadeout'>
        <input type='image' src='./img/arrow_double.png' alt='Double arrow symbol to sort the list' height='13px' width='13px' class='sortbutton fadein'></div>
        </form>";
    }

    public function sortButtonDateAsc(): string {
        return "<form action='./?route=monthly-page' method='POST'><input type='hidden' name='sort' value='sortDateDesc'><input type='hidden' name='sortingProperty' value='dateslug'>
        <div class='image-box'>
        <input type='image' src='./img/arrow_down.png' alt='Small arrow to sort the list' height='13px' width='13px' class='sortbutton'></div>
        </form>";
    }
    
    public function sortButtonDateDesc(): string {
        return "<form action='./?route=monthly-page' method='POST'><input type='hidden' name='sort' value='sortDateAsc'><input type='hidden' name='sortingProperty' value='dateslug'>
        <div class='image-box'>
        <input type='image' src='./img/arrow_up.png' alt='Small arrow to sort the list' height='13px' width='13px' class='sortbutton'></div>
        </form>";
    }

}