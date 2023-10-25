<?php 

namespace App\Controller;

class ColorThemeController extends AbstractController{

    public string $colorTheme;
    public string $chartColorTheme;

    public function __construct() {
            $this->colorTheme = ($_COOKIE['colorTheme']);
        }

    public function adjustColorTheme() {
        if(isset($_POST['colorTheme'])) {
            header('Location: ./?route=homepage');
        } elseif (isset($_POST['customChartColor'])) {
            $customChartColorTheme = [];
            for ($i = 1; $i < 11; $i++) {
                $customChartColorTheme[] = $_POST["color{$i}"];
            }
            $customChartColorTheme = implode(",", $customChartColorTheme);
            setcookie('customChartColorTheme', $customChartColorTheme);
            header('Location: ./?route=homepage');
        } elseif (isset($_POST['customColorTheme'])) {
            $customColorTheme = [];
            $customColorTheme[] = $_POST['theme-color-light'];
            $customColorTheme[] = $_POST['theme-color-medium'];
            $customColorTheme[] = $_POST['theme-color-heavy'];
            $customColorTheme[] = $_POST['theme-color-neon'];
            $customColorTheme[] = $_POST['theme-color-background-1'];
            $customColorTheme[] = $_POST['theme-color-background-2'];
            $customColorTheme = implode(",", $customColorTheme);
            setcookie('customColorTheme', $customColorTheme);
            header('Location: ./?route=homepage');
        }
    }

    public function giveChartColors($chartColorSet, $transparency) {
        $colorsArray = [];
        if($chartColorSet === 'default' && $this->colorTheme === 'customTheme') {
            $chartColorSet = 'colorful';
        }
        switch ($chartColorSet) {
            case 'default':
                $chartColors = $this->giveChartColorsByTheme($chartColorSet, $transparency);
                $colors10 = $chartColors['colors10'];
                $colors2 = $chartColors['colors2'];
                break;
            case 'colorful':
                $colors10 = ["rgb(255,0,0,$transparency)", "rgb(255,127,0,$transparency)", "rgb(255,255,0,$transparency)", "rgb(127,255,0,$transparency)", "rgb(0,255,0,$transparency)", "rgb(0,255,127,$transparency)", "rgb(0,255,255,$transparency)", "rgb(0,127,255,$transparency)", "rgb(0,0,255,$transparency)", "rgb(127,0,255,$transparency)"];
                $colors2 = ["rgb(255,0,0,$transparency)", "rgb(127,0,255,$transparency)"];
                break;
            case 'custom':
                $customChartColorTheme = explode(',', $_COOKIE['customChartColorTheme']);
                $colors10 = [];
                foreach ($customChartColorTheme as $color) {
                    $colors10[] = $color;
                }
                $colors2 = [$colors10[0], $colors10[9]];
                break;
            }
        $colorsArray[] = $colors10;
        $colorsArray[] = $colors2;
        return $colorsArray;
    } 

    public function giveChartColorsByTheme($chartColorTheme, $transparency) {
        $chartColors = [];

        switch ($this->colorTheme) {
            case 'greenTheme':
                $chartColors['colors10'] = ["rgb(20,113,73,$transparency)", "rgb(25,128,83,$transparency)", "rgb(33,149,99,$transparency)", "rgb(44,175,118,$transparency)", "rgb(54,189,128,$transparency)", "rgb(75,197,133,$transparency)", "rgb(101,208,141,$transparency)", "rgb(128,218,144,$transparency)", "rgb(142,221,145,$transparency)", "rgb(207,245,191,$transparency)"];
                $chartColors['colors2'] = ["rgb(20,113,73,$transparency)", "rgb(207,245,191,$transparency)"];
                return $chartColors;
            case 'blueTheme':
                $chartColors['colors10'] = ["rgb(20,73,113,$transparency)", "rgb(25,83,128,$transparency)", "rgb(33,99,149,$transparency)", "rgb(44,118,175,$transparency)", "rgb(54,128,189,$transparency)", "rgb(75,133,197,$transparency)", "rgb(101,141,208,$transparency)", "rgb(128,144,218,$transparency)", "rgb(142,145,221,$transparency)", "rgb(207,191,245,$transparency)"];
                $chartColors['colors2'] = ["rgb(20,73,113,$transparency)", "rgb(207,191,245,$transparency)"];
                return $chartColors;
            case 'redTheme':
                $chartColors['colors10'] = ["rgb(113,20,20,$transparency)", "rgb(128,25,25,$transparency)", "rgb(149,33,33,$transparency)", "rgb(175,44,44,$transparency)", "rgb(189,54,54,$transparency)", "rgb(197,75,75,$transparency)", "rgb(208,101,101,$transparency)", "rgb(218,128,128,$transparency)", "rgb(221,142,142,$transparency)", "rgb(245,191,191,$transparency)"];
                $chartColors['colors2'] = ["rgb(113,20,20,$transparency)", "rgb(245,191,191,$transparency)"];
                return $chartColors;
            }   
    }
}
