<?php
include_once('storage.php');

class AppStorage extends Storage{
        public function __construct(){
            parent::__construct(new JsonIO('appointments.json'));
        }
}

