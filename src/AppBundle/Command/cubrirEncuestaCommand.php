<?php
/**
 * @author Marcos Redondo <kusflo@gmail.com>
 */

namespace AppBundle\Command;

use AppBundle\Entity\Contacto;
use AppBundle\Entity\ContactoRespuesta;
use AppBundle\Entity\Encuesta;
use AppBundle\Entity\Pregunta;
use AppBundle\Entity\Respuesta;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class cubrirEncuestaCommand extends ContainerAwareCommand
{
    /**@var EntityManager*/
    private $em;

    protected function configure(){

        $this->setName('app:cubrirEncuesta')
            ->setDescription('Cubrir una encuesta creada.')
            ->setHelp('Este comando te permite cubrir una encuesta ya creada');

    }


    protected function execute(InputInterface $input, OutputInterface $output){

        $arrayNombresEncuesta = array();
        $helper = $this->getHelper('question');
        $question = new Question('Nombre del contacto: ', 'Jose');
        $txtName = $helper->ask($input, $output, $question);
        $question = new Question('Mail del contacto: ', 'prueba@prueba.com');
        $txtMail = $helper->ask($input, $output, $question);
        $question = new Question('Descripción del contacto: ', 'Descripción por defecto');
        $txtDescription = $helper->ask($input, $output, $question);
        //*****************
        $this->em = $this->getContainer()->get('doctrine')->getManager();
        $contacto = new Contacto();
        $contacto->setName($txtName)
                 ->setMail($txtMail)
                 ->setDescription($txtDescription);
        $this->em->persist($contacto);
        //*****************
        $repoEncuestas = $this->em->getRepository(Encuesta::class);
        $listaEncuestas = $repoEncuestas->findAll();
        /**@var Encuesta $encuesta*/
        foreach($listaEncuestas as $encuesta){
            $arrayNombresEncuesta [] = $encuesta->getTitulo();
        }

        $question = new ChoiceQuestion(
            'Indique la encuesta que desea cubrir: ',
            $arrayNombresEncuesta,
            0
        );
        $question->setErrorMessage('La Encuesta %s no existe');
        $txtEncuestaSelected = $helper->ask($input, $output, $question);
        $encuesta = $repoEncuestas->findOneByTitulo($txtEncuestaSelected);
        $repoRespuesta = $this->em->getRepository(Respuesta::class);
        /**@var Pregunta $pregunta*/
        foreach($encuesta ->getPreguntas() as $pregunta) {
            $question = new ChoiceQuestion(
                $pregunta->getTitulo(),
                $pregunta->getRespuestas()->getValues()
            );
            $question->setErrorMessage('La Respuesta %s no existe');
            $txtRespuesta = $helper->ask($input, $output, $question);
            $respuesta = $repoRespuesta->findOneBy(
                array(
                    'pregunta' => $pregunta,
                    'titulo' => $txtRespuesta
                )
            );
            $contactoRespuesta = new ContactoRespuesta();
            $contactoRespuesta->setContacto($contacto)
                ->setRespuesta($respuesta);
            $this->em->persist($contactoRespuesta);
        }

        //Subida a la base de datos
        $this->em->flush();

        $output->writeln("***********************************");
        $output->writeln("Encuesta cubierta !!");
        $output->writeln("***********************************");


    }

}