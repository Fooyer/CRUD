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
        if (isset($_POST['Nome']) and isset($_POST['Tell']) and isset($_POST['Email']) and isset($_POST['Endereco']) and isset($_POST['MM']) and isset($_POST['Bio']) and $action == 'salvar' and $id == true){
             $this->editaluno();
             $this->formData = False;
             $this->msg1 = "Processo Concluído.";
         }
        elseif(isset($_POST['Nome']) and $_POST['Nome']!=null and isset($_POST['Tell']) and $_POST['Tell']!=null and isset($_POST['Email']) and $_POST['Email']!=null and isset($_POST['Endereco']) and $_POST['Endereco']!=null and isset($_POST['MM']) and $_POST['MM']!=null and isset($_POST['Bio']) and $_POST['Bio']!=null and $action == 'salvar' and $id == false){
                $this->insereAluno();
                $this->msg1 = "Processo Concluído.";
        }
        elseif($action == 'remove'){
            $this->remove();
        }
        elseif(isset($_POST['Nome']) and $_POST['Nome']==false or isset($_POST['Tell']) and $_POST['Tell']==false or isset($_POST['Email']) and $_POST['Email']==false or isset($_POST['Endereco']) and $_POST['Endereco']==false or isset($_POST['MM']) and $_POST['MM']==false or isset($_POST['Bio']) and $_POST['Bio']==false){
            $this->msg = "Erro: Preencha TODOS os campos com valores válidos!";
        }
        elseif($action == 'salvar' and isset($_POST['Nome']) and $_POST['Nome']!=null and isset($_POST['Tell']) and $_POST['Tell']!=null and isset($_POST['Email']) and $_POST['Email']!=null and isset($_POST['Endereco']) and $_POST['Endereco']!=null  and isset($_POST['MM']) and $_POST['MM']!=null and isset($_POST['Bio']) and $_POST['Bio']!=null and $action == 'salvar' and $id == false){
            $this->msg = "Erro Desconhecido!";
        }
        $this->loadPage();
    }
    private function loadPage() {

        require 'alunos.html';
    }

    public function insereAluno() {
        $sth = $this->dbh->prepare("INSERT INTO C_Professores (P_Nome, P_Telefone, P_Email, P_Endereco,P_MateriasMinistradas, P_Biografia) VALUES (:D_Nome, :A_Telefone, :A_Email, :A_Endereco, :A_MateriasMinistradas, :A_Biografia)");
        $nome = strip_tags($_POST['Nome']);
        $tell = strip_tags($_POST['Tell']);
        $email = strip_tags($_POST['Email']);
        $endereco = strip_tags($_POST['Endereco']);
        $bio = strip_tags($_POST['Bio']);
        $mm = strip_tags($_POST['MM']);
        $sth->bindParam(':D_Nome', $nome);
        $sth->bindParam(':A_Telefone', $tell);
        $sth->bindParam(':A_Email', $email);
        $sth->bindParam(':A_Endereco', $endereco);
        $sth->bindParam(':A_MateriasMinistradas', $mm);
        $sth->bindParam(':A_Biografia', $bio);
        $sth->execute();
    }
    public function editaluno() {
        $this->getAluno($this->getParam('id'));
        $id = $this->formData['P_ID'] ?? Null;
        $nome = strip_tags($_POST['Nome']);
        $tell = strip_tags($_POST['Tell']);
        $email = strip_tags($_POST['Email']);
        $endereco = strip_tags($_POST['Endereco']);
        $bio = strip_tags($_POST['Bio']);
        $mm = strip_tags($_POST['MM']);
        $sth = $this->dbh->prepare("UPDATE C_Professores SET P_Nome=:D_Nome, P_Telefone=:A_Telefone, P_Email=:A_Email, P_Endereco=:A_Endereco, P_MateriasMinistradas=:A_MateriasMinistradas, P_Biografia=:A_Biografia WHERE P_ID = :D_ID LIMIT 1");
        $sth->bindParam(':D_ID',$id, PDO::PARAM_INT);
        $sth->bindParam(':D_Nome', $nome);
        $sth->bindParam(':A_Telefone', $tell);
        $sth->bindParam(':A_Email', $email);
        $sth->bindParam(':A_Endereco', $endereco);
        $sth->bindParam(':A_MateriasMinistradas', $mm);
        $sth->bindParam(':A_Biografia', $bio);
        $sth->execute();
    }
    public function listAlunos() {
        $sth = $this->dbh->prepare("SELECT P_ID, P_Nome, P_Telefone, P_Email, P_Endereco, P_MateriasMinistradas, P_Biografia FROM C_Professores ORDER BY P_ID ASC");
        $sth->execute();
        $result = $sth->fetchall();
        
        return (is_array($result) && count($result)>0) ? $result : false;
    }
    public function getAluno($id) {
        $sth = $this->dbh->prepare("SELECT P_ID, P_Nome, P_Telefone, P_Email, P_Endereco, P_MateriasMinistradas, P_Biografia FROM C_Professores WHERE P_ID = :D_ID LIMIT 1");
        $sth->bindParam(':D_ID', $id, PDO::PARAM_INT);
        $sth->execute();
        $result = $sth->fetch();

        $this->formData = (is_array($result) && count($result)>0) ? $result : false;
    }
    public function remove(){
        $this->getAluno($this->getParam('id'));
        $id = $this->formData['P_ID'];
        $sth = $this->dbh->prepare("DELETE FROM C_Professores WHERE P_ID = :D_ID LIMIT 1");
        $sth->bindParam(':D_ID',$id, PDO::PARAM_INT);
        $sth->execute();
        header("Location: index.php");
    }

    public function getFormData() {
        return $this->formData;
        }
}

$aluno = new Aluno();