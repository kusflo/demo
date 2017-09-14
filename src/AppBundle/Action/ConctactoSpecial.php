<?php
/**
 * @author Marcos Redondo <kusflo@gmail.com>
 */

namespace AppBundle\Action;

use AppBundle\Entity\Contact;
use AppBundle\Services\MyService;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactoSpecial
{
    private $myService;

    public function __construct(MyService $myService)
    {
        $this->myService = $myService;
    }

    public function __invoke($data)
    {
        $this->myService->doSomething($data);
        //Por defecto se valida, se persiste ( si usamos doctrine) y se serializa la entidad en json para ser devuelta.
        // Si queremos manejarla nosotros retornamos una instancia de Response.
        return $data;

//        $response = new Response(json_encode($data));
//        $response->headers->set('Content-Type', 'application/json');
//        return $response;
    }
}