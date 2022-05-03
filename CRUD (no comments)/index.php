<?php

include_once 'conexao/base.php';

Class Aluno extends Base{
    public function __construct() {
        parent::__construct();

        $this->load();
    }  
    public function load() {
        require 'index.html';
    }
    public function listD() {
        $sth = $this->dbh->prepare("SELECT D_Nome FROM C_Diretor ORDER BY D_ID ASC");
        $sth->execute();
        $result = $sth->fetchall();
        
        return (is_array($result) && count($result)>0) ? $result : false;
    }
    public function listS() {
        $sth = $this->dbh->prepare("SELECT S_Nome FROM C_Secretarias ORDER BY S_ID ASC");
        $sth->execute();
        $result = $sth->fetchall();
        
        return (is_array($result) && count($result)>0) ? $result : false;
    }
    public function listP() {
        $sth = $this->dbh->prepare("SELECT P_Nome FROM C_Professores ORDER BY P_ID ASC");
        $sth->execute();
        $result = $sth->fetchall();
        
        return (is_array($result) && count($result)>0) ? $result : false;
    }
    public function listA() {
        $sth = $this->dbh->prepare("SELECT A_Nome FROM C_Alunos ORDER BY A_ID ASC");
        $sth->execute();
        $result = $sth->fetchAll();
        
        return (is_array($result) && count($result)>0) ? $result : false;
    }
}

$aluno = new Aluno();