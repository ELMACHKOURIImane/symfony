<?php

namespace App\Controller ;

use App\Entity\Cathegorie;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\Product;
use App\Form\CathegorieType;
use App\Form\ProductType;
use App\Repository\CathegorieRepository;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


class FirstController extends AbstractController{
 private  $productrepository ;
 private $cathegorierepository ; 

public function __construct(ProductRepository $productrepository , CathegorieRepository $cathegorierepository , private ManagerRegistry $doctrine)
   {
     $this->productrepository = $productrepository;
     $this->doctrine;
     $this->cathegorierepository = $cathegorierepository ;
   }
   /**
    * @Route("/" , name="home")
    */
    public function Home()
   {
      $products = $this->productrepository->findAll() ;
    return $this->render('home.html.twig' , [
      'products' => $products 
    ]);
   }
   /**
 * @Route("/product/{id}" , name="show_product")
 */
  public function showProduct($id) {
   $product = $this->productrepository->find($id) ;
  return $this->render('show.html.twig' , [
    "product" => $product
  ]);
 }
 /**
   * @Route("/AddProd" , name="addProduct")
   */
  public function AddProd(Request $request , SluggerInterface $slugger )
    {
      $product = new Product(); 
      $form = $this->createForm(ProductType::class, $product);
      $form->handleRequest($request);
      
      if ($form->isSubmitted() && $form->isValid()) 
      { 
        $brochureFile = $form->get('image')->getData();
        // this condition is needed because the 'brochure' field is not required
        // so the PDF file must be processed only when a file is uploaded
        if ($brochureFile) {
            $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

            // Move the file to the directory where brochures are stored
            try {
                $brochureFile->move(
                    $this->getParameter('image_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            // updates the 'brochureFilename' property to store the PDF file name
            // instead of its contents
            $product->setImage($newFilename);
            $category = $product->getCategory();
            $product->setCategory($category);
            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($product);
            $entityManager->flush();
        }

        // ... persist the $product variable or any other work
        return $this->redirectToRoute('home');
    }
    return $this->render('add.html.twig', [
      'form' => $form->createView(),
    ]);
}
 /**
     * @Route("/Edit/{id}" , name="edit")
     */
    public function Edit( Product  $product ,Request $request )
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
           $product = $form->getData();
           $entityManager = $this->doctrine->getManager();
           $entityManager->persist($product);
           $entityManager->flush();
       //dd($product); // pour afficher les information du form
  
           return $this->redirectToRoute('home');
        }
  
        return $this->render('edit.html.twig' ,[
            'form' => $form->createView()
        ]) ; 
    }
 /**
     * @Route("/Delete/{id}" , name="delete")
     */
    public function delete( Product  $product ,Request $request )
    {
           $entityManager = $this->doctrine->getManager();
           $entityManager->remove($product);
           $entityManager->flush();
       //dd($product); // pour afficher les information du form
  
           return $this->redirectToRoute('home');
    }
     /**
   * @Route("/AddCathegorie" , name="AddCathegorie")
   */
  public function AddCathegorie(Request $request )
  {
      $cathegorie = new Cathegorie();

      $form = $this->createForm(CathegorieType::class, $cathegorie);
      $form->handleRequest($request);
      if($form->isSubmitted() && $form->isValid()){
         $entityManager = $this->doctrine->getManager();
         $entityManager->persist($cathegorie);
         $entityManager->flush();
     //dd($product); // pour afficher les information du form

         return $this->redirectToRoute('home');
      }

      return $this->render('addCathegorie.html.twig' ,[
          'form' => $form->createView()
      ]) ; 
  }
  /**
    * @Route("/ViewCathegories" , name="Cathegorie")
    */
    public function Categories()
   {
    $cathegorie = $this->cathegorierepository->findAll() ;
    return $this->render('cathegorie.html.twig' , [
      'cathegories' => $cathegorie
    ]);
   }
     /**
 * @Route("/cathegorie/{id}" , name="show_cathegorie")
 */
  public function showcathegorie($id) {
    $cathegorie = $this->cathegorierepository->find($id) ;
   return $this->render('showCathegorie.html.twig' , [
     "cathegorie" => $cathegorie
   ]);
  }
}
  
      
