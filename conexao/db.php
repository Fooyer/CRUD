<?php

class DB {
    protected $dbh;

    function __construct() {
        try {
            
            $this->dbh = new PDO('mysql:host=sql211.epizy.com;dbname=epiz_33192166_basecadastro', 'epiz_33192166', 'hHkvNSPBVeM78d');
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        } catch(PDOException $e) {
            die($e->getMessage());
        }
    }
}