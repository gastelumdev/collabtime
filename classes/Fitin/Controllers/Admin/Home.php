<?php
    namespace Fitin\Controllers\Admin;

    class Home {
        public function show() {
            $title = 'Admin - Home';
    
            return ['template' => 'admin_home.html.php', 'title' => $title];
        }
    }