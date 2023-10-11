<?php 

namespace App\Controller;

abstract class AbstractController {

    public function render($path, array $data = []) {
        ob_start();
        extract($data);
        require __DIR__ . '/../../views/pages/' . $path . '.view.php';
        $content = ob_get_contents();
        ob_end_clean();
        
        require __DIR__ . '/../../views/layouts/main.view.php';
    }


}