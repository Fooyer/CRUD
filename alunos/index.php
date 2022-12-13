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
        if (isset($_POST['Nome']) and isset($_POST['Tell']) and isset($_POST['Email']) and isset($_POST['Idade']) and isset($_POST['MC']) and isset($_POST['Endereco']) and $action == 'salvar' and $id == true){
             $this->editaluno();
             $this->formData = False;
             $this->msg1 = "Processo Concluído.";
         }
        elseif(isset($_POST['Nome']) and $_POST['Nome']!=null and isset($_POST['Tell']) and $_POST['Tell']!=null and isset($_POST['Email']) and $_POST['Email']!=null and isset($_POST['Idade']) and $_POST['Idade']!=null and isset($_POST['MC']) and $_POST['MC']!=null and isset($_POST['Endereco']) and $_POST['Endereco']!=null and $action == 'salvar' and $id == false){
                $this->insereAluno();
                $this->msg1 = "Processo Concluído.";
        }
        elseif($action == 'remove'){
            $this->remove();
        }
        elseif(isset($_POST['Nome']) and $_POST['Nome']==false or isset($_POST['Tell']) and $_POST['Tell']==false or isset($_POST['Email']) and $_POST['Email']==false or isset($_POST['Idade']) and $_POST['Idade']==false or isset($_POST['MC']) and $_POST['MC']==false or isset($_POST['Endereco']) and $_POST['Endereco']==false){
            $this->msg = "Erro: Preencha TODOS os campos com valores válidos!";
        }
        elseif($action == 'salvar' and isset($_POST['Nome']) and $_POST['Nome']!=null and isset($_POST['Tell']) and $_POST['Tell']!=null and isset($_POST['Email']) and $_POST['Email']!=null and isset($_POST['Idade']) and $_POST['Idade']!=null and isset($_POST['MC']) and $_POST['MC']!=null and isset($_POST['Endereco']) and $_POST['Endereco']!=null and $action == 'salvar' and $id == false){
            $this->msg = "Erro Desconhecido!";
        }
        $this->loadPage();
    }
    private function loadPage() {

        require 'alunos.html';
    }

    public function insereAluno() {
        $sth = $this->dbh->prepare("INSERT INTO C_Alunos (A_Nome, A_Telefone, A_Email, A_Idade, A_Endereco, A_MateriasCursadas) VALUES (:A_Nome, :A_Telefone, :A_Email, :A_Idade, :A_Endereco, :A_MateriasCursadas)");
        $sth->execute([':A_Nome'=>$_POST['Nome'], ':A_Telefone'=>$_POST['Tell'],':A_Email'=>$_POST['Email'],'A_Idade'=>$_POST['Idade'], 'A_MateriasCursadas'=>$_POST['MC'], 'A_Endereco'=>$_POST['Endereco']]);
    }
    public function editaluno() {
        $this->getAluno($this->getParam('id'));
        $id = $this->formData['A_ID'] ?? Null;
        $nome = strip_tags($_POST['Nome']);
        $tell = strip_tags($_POST['Tell']);
        $email = strip_tags($_POST['Email']);
        $idade = strip_tags($_POST['Idade']);
        $mc = strip_tags($_POST['MC']);
        $endereco = strip_tags($_POST['Endereco']);
        $sth = $this->dbh->prepare("UPDATE C_Alunos SET A_Nome=:A_Nome, A_Telefone=:A_Telefone, A_Email=:A_Email, A_Idade=:A_Idade, A_Endereco=:A_Endereco, A_MateriasCursadas=:A_MateriasCursadas WHERE A_ID = :A_ID LIMIT 1");
        $sth->bindParam(':A_ID',$id, PDO::PARAM_INT);
        $sth->bindParam(':A_Nome',  $nome);
        $sth->bindParam(':A_Telefone', $tell);
        $sth->bindParam(':A_Email', $email);
        $sth->bindParam(':A_Idade', $idade);
        $sth->bindParam(':A_MateriasCursadas', $mc);
        $sth->bindParam(':A_Endereco', $endereco);
        $sth->execute();
    }
    public function listAlunos() {
        $sth = $this->dbh->prepare("SELECT A_ID, A_Nome, A_Telefone, A_Email, A_Idade, A_MateriasCursadas, A_Endereco FROM C_Alunos ORDER BY A_ID ASC");
        $sth->execute();
        $result = $sth->fetchAll();
        
        return (is_array($result) && count($result)>0) ? $result : false;
    }
    public function getAluno($id) {
        $sth = $this->dbh->prepare("SELECT A_ID, A_Nome, A_Telefone, A_Email, A_Idade, A_MateriasCursadas, A_Endereco FROM C_Alunos WHERE A_ID = :A_ID LIMIT 1");
        $sth->bindParam(':A_ID', $id, PDO::PARAM_INT);
        $sth->execute();
        $result = $sth->fetch();

        $this->formData = (is_array($result) && count($result)>0) ? $result : false;
    }
    public function remove(){
        $this->getAluno($this->getParam('id'));
        $id = $this->formData['A_ID'];
        $sth = $this->dbh->prepare("DELETE FROM C_Alunos WHERE A_ID = :A_ID LIMIT 1");
        $sth->bindParam(':A_ID',$id, PDO::PARAM_INT);
        $sth->execute();
        header("Location: index.php");
    }

    public function getFormData() {
        return $this->formData;
        }
}

$aluno = new Aluno();