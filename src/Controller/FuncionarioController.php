<?php
namespace App\Controller;
use App\Utils\Validate;

use App\Entity\Funcionario;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FuncionarioController extends AbstractController
{
    /**
     * @Route("/funcionario/mostra/{id}")
     */
    public function mostraAction(Funcionario $funcionario)
    {
        return $this->render('Funcionario/mostra.html.twig',["funcionario" => $funcionario]);
    }

    /**
     * @Route("/funcionario/lista")
     */
    public function lista()
    {
        $funcionarios = $this->getDoctrine()->getManager()->getRepository(Funcionario::class)->findAll();

        return $this->render('Funcionario/lista.html.twig',["funcionarios" => $funcionarios]);
    }

    /**
     * @Route("/funcionario/novo",methods="GET")
     */
    public function formulario()
    {
        return $this->render("Funcionario/novo.html.twig");
    }

    /**
     * @Route("/funcionario/novo",methods="POST")
     */
    public function cria(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $funcionario = new Funcionario($em);
        $nome = $request->get("nome");
        $nuCpf = $request->get("nuCpf");
        $dataDeNascimento = new \DateTime($request->get("dataDeNascimento"));

        $data = [
            'nuCpf' => $nuCpf,
            'nome' => $nome,
            'dataDeNascimento' => $dataDeNascimento
        ];
        $validate = new Validate;

        $filled = $validate->ValidateCpf($data);
        if ($filled != "true") {
            return new Response($filled);
        }
        $funcionario->inserir($data, $em);
        $em->flush();
        return $this->redirect("/funcionario/lista");
    }

    /**
     * @Route("/funcionario/edita/{id}",methods="GET")
     */
    public function mostra(Funcionario $funcionario)
    {
        $form = $this->createFormBuilder($funcionario)
            ->add("nome")
            ->add("dataDeNascimento")
            ->add("nuCpf")
            ->getForm();

        return $this->render('Funcionario/edita.html.twig',["funcionario" => $funcionario,"form" => $form->createView()]);
    }

    /**
     * @Route("funcionario/edita/{id}",methods="POST")
     */
    public function edita(Funcionario $funcionario,Request $request)
    {
        $form = $this->createFormBuilder($funcionario)
            ->add("nome")
            ->add("dataDeNascimento")
            ->add("nuCpf")
            ->getForm();

        $form->handleRequest($request);

        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->merge($funcionario);
            $em->flush();
        }

        return $this->render('Funcionario/edita.html.twig',["funcionario" => $funcionario,"form" => $form->createView()]);
    }

    /**
     * @Route("funcionario/inativar/{id}")
     */
    public function inativar(Funcionario $funcionario)
    {
        $em = $this->getDoctrine()->getManager();
        $repositorio = new Funcionario($em);
        $repositorio->updateFuncionario($funcionario);
        return $this->redirect("/funcionario/lista");
    }
}
