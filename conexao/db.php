<?php

class DB {
    protected $dbh;

    function __construct() {
        try {
            
            $this->dbh = new PDO('mysql:host=${{ secrets.DB_HOST }};dbname=${{ secrets.DB_NAME }}', '${{ secrets.DB_USERNAME }}', '${{ secrets.DB_PASSWORD }}');
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        } catch(PDOException $e) {
            die($e->getMessage());
        }
    }
}