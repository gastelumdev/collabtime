<?php
    namespace Fitin;

    class FitinConfig implements \Ninja\Config {
        public function getKeys(): array {
            $keys = [
                'googleMaps' => 'AIzaSyDYYytn7rgzNTqOVxceHDusSxSxxGZ8n-s'
            ];

            return $keys;
        }
    }