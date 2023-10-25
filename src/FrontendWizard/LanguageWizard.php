<?php

namespace App\FrontendWizard;

class LanguageWizard {
    
    protected string $language;

    #TODO: Implementierung  index.php inkl. userSettings/adjustLanguage
    // Frontend implementierung bei userSettings.view.php
    // Zus. Speicherung in COOKIE, sodass es erhalten bleibt nach dem ausloggen
    // English, Deutsch, Espanol
    public function __construct() {
            $this->language = strtolower($_SESSION['language']);
        }
}