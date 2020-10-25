<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManager;

/**
 * @ORM\Entity
 */
class Funcionario
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $nome;
    /**
     * @ORM\Column(type="datetime")
     */
    private $dataDeNascimento;
    /**
     * @ORM\Column(type="datetime")
     */
    private $dataDeEntrada;

    /**
     * @ORM\Column(type="boolean")
     */
    private $boAtivo;
    /**
     * @ORM\Column(type="string")
     */
    private $nuCpf;

    private  $entityManager;
     
    public function __construct(EntityManager $em) {
       $this->entityManager = $em;
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param mixed $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    }
     /**
     * @return mixed
     */
    public function getNuCpf()
    {
        return $this->nuCpf;
    }

    /**
     * @param mixed $nuCpf
     */
    public function setNuCpf($nuCpf)
    {
        $this->nuCpf = $nuCpf;
    }

    /**
     * @return mixed
     */
    public function getDataDeNascimento()
    {
        return $this->dataDeNascimento;
    }

    /**
     * @param mixed $dataDeNascimento
     */
    public function setDataDeNascimento($dataDeNascimento)
    {
        $this->dataDeNascimento = $dataDeNascimento;
    }

    /**
     * @return mixed
     */
    public function getDataDeEntrada()
    {
        return $this->dataDeEntrada;
    }

    /**
     * @param mixed $dataDeEntrada
     */
    public function setDataDeEntrada($dataDeEntrada)
    {
        $this->dataDeEntrada = $dataDeEntrada;
    }

     /**
     * @return mixed
     */
    public function getBoAtivo()
    {
        return $this->boAtivo;
    }

    /**
     * @param mixed $boAtivo
     */
    public function setBoAtivo($boAtivo)
    {
        $this->boAtivo = $boAtivo;
    }


    /**
     * @return mixed
     */
    public function getHorasLancadas()
    {
        return $this->horasLancadas;
    }

    /**
     * @param mixed $horasLancadas
     */
    public function setHorasLancadas($horasLancadas)
    {
        $this->horasLancadas = $horasLancadas;
    }

    public function getTempoNaEmpresa()
    {
        $hoje = new \DateTime();
        $diferenca = $hoje->diff($this->dataDeEntrada);

        return $diferenca;
    }

    public function __toString()
    {
        return $this->getNome();
    }

    public function updateFuncionario(Funcionario $funcionario) 
    {
        $em = $this->entityManager;
        $qb = $em->createQueryBuilder();
        $qb->update('App\Entity\Funcionario', 'a')
        ->set('a.boAtivo', $qb->expr()->literal(!$funcionario->boAtivo))
        ->where('a.id = ?1')
        ->setParameter(1, $funcionario->id);
        $query = $qb->getQuery();
        return $query->execute(); 
    }

    public function inserir($data, $em) {
        $funcionario = new Funcionario($em);
        $funcionario->setNome($data['nome'], $em);
        $funcionario->setDataDeNascimento($data['dataDeNascimento']);
        $funcionario->setDataDeEntrada(new \DateTime());
        $funcionario->setBoAtivo(true);
        $funcionario->setNuCpf($data['nuCpf']);
        $em->persist($funcionario);
        $em->flush();
    }
}
