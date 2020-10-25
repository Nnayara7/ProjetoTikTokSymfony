<?php
namespace App\Controller;

use App\Entity\Funcionario;
use App\Entity\FuncionarioProjeto;
use App\Entity\Projeto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FuncionarioProjetoController extends AbstractController
{
    /**
     * @Route("/funcionarioProjeto/mostra",methods="GET")
     */
    public function mostraAction()
    {
        return $this->render('FuncionarioProjeto/mostra.html.twig',["funcionarioProjeto" => new FuncionarioProjeto()]);
    }

    /**
     * @Route("/funcionarioProjeto/novo",methods="GET")
     */
    public function formulario()
    {
        $form = $this->createFormBuilder(new FuncionarioProjeto($this->getDoctrine()->getManager()))
            ->add('funcionario')
            ->add('projeto')
            ->setAction('/funcionarioProjeto/novo')
            ->getForm();

        return $this->render("FuncionarioProjeto/novo.html.twig",["form" => $form->createView()]);
    }

    /**
     * @Route("/funcionarioProjeto/novo",methods="POST")
     */
    public function cria(Request $request)
    {
        $projeto = new FuncionarioProjeto($this->getDoctrine()->getManager());
        $form = $this->createFormBuilder($projeto)
            ->add('funcionario')
            ->add('projeto')
            ->getForm();
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $em->persist($projeto);
        $em->flush();

        return $this->redirect("/funcionarioProjeto/lista");
    }

    /**
     * @Route("/funcionarioProjeto/lista",methods="GET")
     */
    public function lista()
    {
        $repository = new FuncionarioProjeto($this->getDoctrine()->getManager());

        return $this->render("FuncionarioProjeto/lista.html.twig",["projetos" => $repository->getAllFuncionarioProjeto()]);
    }

    /**
     * @Route("/funcionarioProjeto/edita/{id}",methods="GET")
     */
    public function mostra(FuncionarioProjeto $funcionarioProjeto)
    {
        $form = $this->createFormBuilder($funcionarioProjeto)
            ->add('funcionario')
            ->add('projeto')
            ->setAction("/funcionarioProjeto/edita/".$funcionarioProjeto->getId())
            ->getForm();

        return $this->render('FuncionarioProjeto/edita.html.twig',["projeto" => $funcionarioProjeto,"form" => $form->createView()]);
    }

    /**
     * @Route("/funcionarioProjeto/edita/{id}",methods="POST")
     */
    public function edita(FuncionarioProjeto $funcionarioProjeto, Request $request)
    {
        $form = $this->createFormBuilder($funcionarioProjeto)
            ->add('funcionario')
            ->add('projeto')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $projeto = $form->getData();

            $em = $this->getDoctrine()->getManager();

            foreach($funcionarioProjeto->getFuncionarios() as $funcionario){
                $funcionario->setProjeto($funcionarioProjeto);
                $em->merge($funcionario);
            }

            $em->merge($funcionarioProjeto);
            $em->flush();

            return $this->redirect("/projeto/edita/".$funcionarioProjeto->getId());
        }

        return $this->render('FuncionarioProjeto/edita.html.twig',["funcionarioProjeto" => $funcionarioProjeto]);
    }

    /**
     * @Route("/funcionarioProjeto/remove/{id}",methods="GET")
     */
    public function delete(FuncionarioProjeto $funcionarioProjeto)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($funcionarioProjeto);
        $em->flush();

        return $this->redirect("/funcionarioProjeto/lista");
    }
}
