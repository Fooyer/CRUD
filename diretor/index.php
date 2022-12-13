<?php

include_once '../conexao/base.php';

class Aluno extends Base {
    private $formData = false;
    private $msg='';
    private $msg1='';
    public function __construct() {
        parent::__construct();

        $action = $this->getParam('action');
        $id = $this->getParam('id');

        switch($action) {
            case 'edit':
                $this->getAluno($this->getParam('id'));
                break;
            }
        if (isset($_POST['Nome']) and isset($_POST['Tell']) and isset($_POST['Email']) and isset($_POST['Endereco']) and $action == 'salvar' and $id == true){
             $this->editaluno();
             $this->formData = False;
             $this->msg1 = "Processo Concluído.";
         }
        elseif(isset($_POST['Nome']) and $_POST['Nome']!=null and isset($_POST['Tell']) and $_POST['Tell']!=null and isset($_POST['Email']) and $_POST['Email']!=null and isset($_POST['Endereco']) and $_POST['Endereco']!=null and $action == 'salvar' and $id == false){
                $this->insereAluno();
                $this->msg1 = "Processo Concluído.";
        }
        elseif($action == 'remove'){
            $this->remove();
        }
        elseif(isset($_POST['Nome']) and $_POST['Nome']==false or isset($_POST['Tell']) and $_POST['Tell']==false or isset($_POST['Email']) and $_POST['Email']==false or isset($_POST['Endereco']) and $_POST['Endereco']==false){
            $this->msg = "Erro: Preencha TODOS os campos com valores válidos!";
        }
        elseif($action == 'salvar' and isset($_POST['Nome']) and $_POST['Nome']!=null and isset($_POST['Tell']) and $_POST['Tell']!=null and isset($_POST['Email']) and $_POST['Email']!=null and isset($_POST['Endereco']) and $_POST['Endereco']!=null and $action == 'salvar' and $id == false){
            $this->msg = "Erro Desconhecido!";
        }
        $this->loadPage();
    }
    private function loadPage() {

        require 'alunos.html';
    }

    public function insereAluno() {
        $sth = $this->dbh->prepare("INSERT INTO C_Diretor (D_Nome, D_Telefone, D_Email, D_Endereco) VALUES (:A_Nome, :A_Telefone, :A_Email, :A_Endereco)");
        $sth->execute([':A_Nome'=>strip_tags($_POST['Nome']), ':A_Telefone'=>strip_tags($_POST['Tell']),':A_Email'=>strip_tags($_POST['Email']), 'A_Endereco'=>strip_tags($_POST['Endereco'])]);
    }
    public function editaluno() {
        $this->getAluno($this->getParam('id'));
        $id = $this->formData['D_ID'] ?? Null;
        $nome = strip_tags($_POST['Nome']);
        $tell = strip_tags($_POST['Tell']);
        $email = strip_tags($_POST['Email']);
        $endereco = strip_tags($_POST['Endereco']);
        $sth = $this->dbh->prepare("UPDATE C_Diretor SET D_Nome=:D_Nome, D_Telefone=:A_Telefone, D_Email=:A_Email, D_Endereco=:A_Endereco WHERE D_ID = :D_ID LIMIT 1");
        $sth->bindParam(':D_ID',$id, PDO::PARAM_INT);
        $sth->bindParam(':D_Nome', $nome);
        $sth->bindParam(':A_Telefone', $tell);
        $sth->bindParam(':A_Email', $email);
        $sth->bindParam(':A_Endereco', $endereco);
        $sth->execute();
    }
    public function listAlunos() {
        $sth = $this->dbh->prepare("SELECT D_ID, D_Nome, D_Telefone, D_Email, D_Endereco FROM C_Diretor ORDER BY D_ID ASC");
        $sth->execute();
        $result = $sth->fetchall();
        
        return (is_array($result) && count($result)>0) ? $result : false;
    }
    public function getAluno($id) {
        $sth = $this->dbh->prepare("SELECT D_ID, D_Nome, D_Telefone, D_Email, D_Endereco FROM C_Diretor WHERE D_ID = :D_ID LIMIT 1");
        $sth->bindParam(':D_ID', $id, PDO::PARAM_INT);
        $sth->execute();
        $result = $sth->fetch();

        $this->formData = (is_array($result) && count($result)>0) ? $result : false;
    }
    public function remove(){
        $this->getAluno($this->getParam('id'));
        $id = $this->formData['D_ID'];
        $sth = $this->dbh->prepare("DELETE FROM C_Diretor WHERE D_ID = :D_ID LIMIT 1");
        $sth->bindParam(':D_ID',$id, PDO::PARAM_INT);
        $sth->execute();
        header("Location: index.php");
    }

    public function getFormData() {
        return $this->formData;
        }
}

$aluno = new Aluno();