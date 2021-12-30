<?php
    namespace Fitin\Controllers;

    class Home {
        public function show() {
            $title = 'Home';
    
            return ['template' => 'home.html.php', 'title' => $title];
        }
    }