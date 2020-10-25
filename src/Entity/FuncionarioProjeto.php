<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManager;

/**
 * @ORM\Entity
 */
class FuncionarioProjeto
{ 
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="Projeto", inversedBy="projeto")
     */
    private $projeto;
     /**
     * @ORM\ManyToOne(targetEntity="Funcionario", inversedBy="funcionarios")
     */
    private $funcionario;

    /**
     * @ORM\OneToMany(targetEntity="Funcionario", mappedBy="projeto")
     */
    private $funcionarios;

   /**
     * @ORM\OneToMany(targetEntity="HoraLancada", mappedBy="funcionarioProjeto")
     */
    private $horasLancadas;

    
    private  $entityManager;

    public function __construct(EntityManager $em) {
       $this->entityManager = $em;
       $this->funcionarios = new ArrayCollection();
       $this->horasLancadas = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

  
    public function setId($id): self
    {
        $this->id = $id;
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
    public function getProjeto()
    {
        return $this->projeto;
    }

    /**
     * @param mixed $projeto
     */
    public function setProjeto($projeto)
    {
        $this->projeto = $projeto;
    }

    /**
     * @return mixed
     */
    public function getFuncionario()
    {
        return $this->funcionario;
    }

    /**
     * @param mixed $projeto
     */
    public function setFuncionario($funcionario)
    {
        $this->funcionario = $funcionario;
    }

      /**
     * @return mixed
     */
    public function getFuncionarios()
    {
        return $this->funcionarios;
    }

    /**
     * @param mixed $funcionarios
     */
    public function setFuncionarios($funcionarios)
    {
        $this->funcionarios = $funcionarios;
    }


    /**
     * @return mixed
     */
    public function getHorasLancadas()
    {
        return $this->horasLancadas;
    }

    public function getTotalHorasLancadas()
    {
        $horas = 0;
        foreach($this->horasLancadas as $horasLancada){
            $horas += $horasLancada->getQuantidade();
        }

        return $horas;
    }

    /**
     * @param mixed $horasLancadas
     */
    public function setHorasLancadas($horasLancadas)
    {
        $this->horasLancadas = $horasLancadas;
    }

    public function addHorasLancada(HoraLancada $hora)
    {
        $this->horasLancadas->add($hora);
    }

    public function removeHorasLancada(HoraLancada $hora)
    {
        $this->horasLancadas->remove($hora);
    }

    public function getAllFuncionarioProjeto() 
    {
        $em = $this->entityManager;
        $qb = $em->createQueryBuilder('a');
        $qb->select('a')
            ->from('App\Entity\FuncionarioProjeto', 'a')
            ->join('a.funcionario','funcionario')
            ->join('a.projeto','projeto');  
        $query = $qb->getQuery();
        return $result = $query->getResult(); 
    }
}
