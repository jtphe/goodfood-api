<?php 

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Order; 

class OrderController extends AbstractController {

    /**
     * @Route(name="createNewOrder", path="/api/CreateNewOrder", methods={"POST"})
     * @param Request $request
     * @throws Exception
     * @return JsonResponse
     */
    public function createNewOrder(Request $request) {

        try {
            $orderData = json_decode($request->getContent(), true);
            $order = new Order; 


        } catch (PDOException $e) {
    
        }
    }
}