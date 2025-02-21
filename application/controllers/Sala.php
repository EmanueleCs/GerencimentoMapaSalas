<?php 
defined('BASEPATH') OR exit('NO direct script acess allowed');

class Sala extends CI_Controller{
    //Atributos privados da classe
    private $codigo;
    private $descricao;
    private $andar;
    private $capacidade;
    private $estatus;

    //Getters dos atributos
    public function getCodigo(){
        return $this->codigo;
    }

    public function getDescricao(){
        return $this->descricao;
    }

    public function getAndar(){
        return $this->andar;
    }

    public function getCapacidade(){
        return $this->capacidade;
    }

    public function getEstatus(){
        return $this->estatus;
    }

    //Setters dos atributos
    public function setCodigo($codigoFront){
        $this->codigo = $codigoFront;
    }

    public function setDescricao($descricaoFront){
        $this->descricao = $descricaoFront;
    }

    public function setAndar($andarFront){
        $this->andar = $andarFront;
    }

    public function setCapacidade($capacidadeFront){
        $this->capacidade = $capacidadeFront;
    }

    public function setEstatus($estatusFront){
        $this->tipoUsuario = $estatusFront;
    }

    public function inserir(){
        //Codigo, Descricao e Andar
        //recebidos via JSON e colocados em variáveis
        //Retornos possiíveis
        //1 - Sala cadastrada corretamente (Banco)
        //2 - Faltou informar o codigo da sala (Frontend)
        //3 - Faltou informar a descricao (Frontend)
        //4 - Faltou informar o andar (Frontend)
        //5 - Sala cadastrada no sistema
        //6 - Houve algum problema no insert da tabela (Banco)
        //7 - Sala já cadastrada no Sistema
        try{
            //Dados recebidos via JSON e colocados em atributos
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            $lista = array(
                "codigo" => '0',
                "descricao" => '0',
                "andar" => '0',
                "capacidade" => '0'
            );

            if (verificarParam($resultado, $lista) == 1) {
                //Fazendo os setters
                $this -> setCodigo($resultado -> codigo);
                $this -> setDescricao($resultado -> descricao);
                $this -> setAndar($resultado -> andar);
                $this -> setCapacidade($resultado -> capacidade);

                //Faremos uma validação para sabermos se todos os dados foram enviados
                if(trim($this -> getCodigo()) == '' || $this -> getCodigo() == 0){
                    $retorno = array('codigo' => 2, 'msg' => 'Código não informado.');

                }elseif (trim($this -> getDescricao()) == ''){
                    $retorno = array('codigo' => 3, 'msg' => 'Descrição não informada.');

                }elseif (trim($this -> getAndar()) == '' || trim($this -> getCapacidade()) == 0) {
                    $retorno = array('codigo' => 4, 'msg' => 'Andar não informado.');

                }elseif (trim($this -> getCapacidade()) == '' || trim($this->getCapacidade()) == 0) {
                    $retorno = array('codigo' => 5, 'msg' => 'Capacidade não informada.');

                }else {
                    //Reaalizo a instânca da Model
                    $this -> load -> model('M_sala');

                    //Atributos $retorno recebe array com informações da validação do acesso
                    $retorno = $this -> M_sala -> inserir ($this -> getCodigo(), $this -> getDescricao(),
                                                            $this -> getAndar(), $this -> getCapacidade());
                };
            }

        }catch(Exception $e) {
            $retorno = array('codigo' => 0,
                                'msg' => 'ATENÇÃO: O seguinte erro aconteceu ->',
                                $e -> getMessage());
        }
        //Retorno no formato JSON
        echo json_encode($retorno);
    }

    public function consultar(){
        //Código, descrição e tipo (Administrador ou Comum)
        //recebidos via JSON e colocados
        //em variáveis
        //Retornos possíveis:
        // 1 - Dados consultados corretamente (Banco)
        // 6 - Dados não encontrados (Banco)
        try{
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            //Array com os dados que deverão vir do Front
            $lista = array(
                "codigo" => '0',
                "descricao" => '0',
                "andar" => '0',
                "capacidade" => '0'
            );

            if (verificaraParam($resultado, $lista) == 1){
                $this -> setCodigo($resultado -> codigo);
                $this -> setDescricao($resultado -> descricao);
                $this -> setAndar($resultado -> andar);
                $this -> setCapacidade($resultado -> capacidade);
                
                //Realizo a instância da Model
                $this -> load->model('M_sala');

                //Atributos $retorno recebe array com informações
                // da consulta dos dados
                $retorno = $this -> M_sala -> consultar($this->getCodigo(),
                                                        $this->getDescricao(),
                                                        $this->getAndar(),
                                                        $this->getCapacidade());
            }else{
                $retorno = array('codigo' => 99,
                                'msg' => 'Os campos vindos do Frontend não 
                                representam o método de Consulta. Verifique.'
                            );
            }
        }catch(Exception $e){
            $retorno = array('codigo' => 0,
                            'msg' => 'ATENÇÃO: O seguinte erro aconteceu ->', 
                            $e->getMessage());
        }
    }
}
