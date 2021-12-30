<?php
    namespace Fitin\Controllers;

    class Location {
        public function show() {
            $title = 'Location';
    
            return ['template' => 'location.html.php', 'title' => $title];
        }
    }