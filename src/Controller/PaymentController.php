<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Produit;
use App\Entity\Commande;
use App\Entity\Transporteur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentController extends AbstractController
{
    private EntityManagerInterface $em;
    private UrlGeneratorInterface $generator;

    public function __construct(EntityManagerInterface $em, UrlGeneratorInterface $generator)
    {
        $this->em = $em;
        $this->generator = $generator;
    }

    #[Route('/order/create-session-stripe/{id}', name: 'payment_stripe',methods: ['POST'])]
    //il s'agit ici de l'id de la commande en cours qui nous sert aussi de référence
    public function stripeCheckout($id): RedirectResponse
    {
        $productStripe = [];
        //recupére la commande en cours
      $order = $this->em->getRepository(Commande::class)->findOneBy(['id' => $id]);
     //dd($order);
     //si commande introuvable ou n'existe pas
     if(!$order){
        return $this->redirectToRoute('panier_index');
     }
     $total= 0;
        $soustotal = 0;
        $fdp = 6;
        $totaltva = 0;


     foreach ($order->getCommandeDetails()->getValues() as $product) {
        //pour recup le nom du produit
        $productData = $this->em->getRepository(Produit::class)->findOneBy(['id' => $product->getPro()]);
        //dd($productData);
        //les info demandé par stripe
        $producStripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $product->getPrix() * 100,
                'product_data' => [
                    'name' => $productData->getNom()
                ]
                ],
                'quantity' => $product->getQuantite()
            ];

        
        $soustotal += $soustotal+ $product->getPrix() * $product->getQuantite();

     }

     // Calculate TVA based on user's role
 if ($this->isGranted('ROLE_USER')) {
    $tva =20 ;
} else {
    $tva = 0;
}

// Calculate total including TVA
$totaltva +=round( $soustotal+($soustotal*$tva/100),2);
// Add TVA as a separate line item in the stripe checkout
$producStripe[] = [
    'price_data' => [
        'currency' => 'eur',
        'unit_amount' => round(($soustotal*$tva/100),2) * 100,
        'product_data' => [
            'name' => 'tva'
        ]
    ],
    'quantity' => 1,
];
  // dd($totaltva);
     if($totaltva>100) {
        //$total =$totaltva+0; 
        $producStripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => 0,
                    'product_data' => [
                        'name' => 'fdp'
                    ]
                    ],
                    'quantity' => 1,
                ];
    }
    else {
        $producStripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => 6*100,
                'product_data' => [
                    'name' => 'fdp'
                ]
                ],
                'quantity' => 1,
            ];
    
}
 

    //  $transporteurData = $this->em->getRepository(Transporteur::class)->findOneBy(['id' => $order->getComTransporteur()]);
      
    //  $producStripe[] = [
    //     'price_data' => [
    //         'currency' => 'eur',
    //         'unit_amount' => $transporteurData->getTraPrix() * 100,
    //         'product_data' => [
    //             'name' => $transporteurData->getTraNom()
    //         ]
    //         ],
    //         'quantity' => 1,
    //     ];
//dd($producStripe);
    Stripe::setApiKey('sk_test_51O9QyeJcB7aIs6zZrucNH3s5gBlnUgquUXkR0KmrRdBGd3lVjWdp2jRc1OzsLGoj5LA5y1DISPIdT8pADgrT0DKX00qdskr1kk');

    //header('Content-Type: application/json');

//$YOUR_DOMAIN = 'http://localhost:4242';

$checkout_session = \Stripe\Checkout\Session::create([
    'customer_email' => $this->getUser()->getEmail(),
    'payment_method_types' => ['card'],
    'line_items' => [[
        $producStripe
    # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
    // 'price' => '{{PRICE_ID}}',
    // 'quantity' => 1,
    ]],
    'mode' => 'payment',
    'success_url' => $this->generator->generate('payment_success', [
      'id' => $order->getId()
    ],UrlGeneratorInterface::ABSOLUTE_URL),
    'cancel_url' => $this->generator->generate('payment_error', [
      'id' => $order->getId()
    ],UrlGeneratorInterface::ABSOLUTE_URL),
    //'success_url' => $YOUR_DOMAIN . '/success.html',
   // 'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
  ]);
  
      // $order->setComStripeSessionId($checkout_session->id);
      // $this->em->flush();
      return new RedirectResponse($checkout_session->url);
  
  
      }

    #[Route('/order/success/{id}', name: 'payment_success')]
    public function StripeSuccess(EntityManagerInterface $em,$id): Response{
        $order = $this->em->getRepository(Commande::class)->findOneBy(['id' => $id]);
        //$order->setComIsPaid(true);
        $em->persist($order);
        $em->flush();
        //return $this->render('order/succes.html.twig');
        return $this->render('commande/success.html.twig');
    }

    #[Route('/order/error/{id}', name: 'payment_error')]
    public function StripeError($id): Response{
        //return $this->render('order/error.html.twig');
        return $this->render('commande/error.html.twig');
    }


}