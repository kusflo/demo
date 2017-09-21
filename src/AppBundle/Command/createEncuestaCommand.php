<?php
/**
 * @author Marcos Redondo <kusflo@gmail.com>
 */

namespace AppBundle\Command;

use AppBundle\Entity\Encuesta;
use AppBundle\Entity\Pregunta;
use AppBundle\Entity\Respuesta;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class createEncuestaCommand extends ContainerAwareCommand
{
    /**@var EntityManager*/
    private $em;

    protected function configure(){

        $this->setName('app:createEncuesta')
            ->setDescription('Crea una encuesta nueva.')
            ->setHelp('Este comando te permite crear una encuesta nueva');

    }


    protected function execute(InputInterface $input, OutputInterface $output){

        $arrayRespuestas = array();
        $helper = $this->getHelper('question');
        $question = new Question('Título de la encuesta: ', 'Encuesta por defecto');
        $txtTitulo = $helper->ask($input, $output, $question);
        $question = new Question('Descripcion de la encuesta: ', 'Encuesta de prueba');
        $txtDescription = $helper->ask($input, $output, $question);
        //*****************
        $this->em = $this->getContainer()->get('doctrine')->getManager();
        $encuesta = new Encuesta();
        $encuesta->setTitulo($txtTitulo);
        $encuesta->setDescripcion($txtDescription);
        $this->em->persist($encuesta);
        while(true) {
            $question = new Question('Título de de la pregunta: (sin interrogantes) ', 'De que color es el mar');
            $txtPregunta = $helper->ask($input, $output, $question);
            while (true){
                $question = new Question('Respuesta: ', 'rojo');
                $arrayRespuestas [] = $helper->ask($input, $output, $question);
                $question = new ConfirmationQuestion('¿Quiere crear otra respuesta? ( y | n): ', false);
                if (!$helper->ask($input, $output, $question)) {
                    break;
                }
            }
            //*****************
            $pregunta = new Pregunta();
            $pregunta->setTitulo($txtPregunta)
                ->setEncuesta($encuesta);
            $this->em->persist($pregunta);
            //*****************
            foreach($arrayRespuestas as $txt){
                $respuesta = new Respuesta();
                $respuesta->setTitulo($txt)
                    ->setPregunta($pregunta);
                $this->em->persist($respuesta);
            }
            //***************************

            $output->writeln("***********************************");
            $question = new ConfirmationQuestion('¿Quiere crear otra pregunta? ( y | n): ', false);
            if (!$helper->ask($input, $output, $question)) {
                break;
            }
            $arrayRespuestas = array();
        }

        //Subida a la base de datos
        $this->em->flush();

        $output->writeln("***********************************");
        $output->writeln("Encuesta creada !!");
        $output->writeln("***********************************");


    }

}