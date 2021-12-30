<?php
    namespace Fitin\Controllers;

    class About {
        public function show() {
            $title = 'About';
    
            return ['template' => 'about.html.php', 'title' => $title];
        }
    }