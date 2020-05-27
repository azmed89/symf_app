<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Video;
use App\Form\CategoryType;
use App\Utils\CategoryTreeAdminList;
use App\Utils\CategoryTreeAdminOptionList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route ("/admin")
 */
class AdminController extends AbstractController
{
//
//    /**
//     * @Route("/", name="admin_main_page")
//     */
//    public function index()
//    {
//        return $this->render('admin/my_profile.html.twig');
//    }
//
//    /**
//     * @Route("/su/categories", name="categories", methods={"GET", "POST"})
//     */
//    public function categories(CategoryTreeAdminList $categories, Request $request)
//    {
//        $categories->getCategoryList($categories->buildTree());
//
//        $category = new Category();
//        $form = $this->createForm(CategoryType::class, $category);
//        $is_valid = null;
//        if($this->saveCategory($category, $form, $request)) {
//            return $this->redirectToRoute('categories');
//        } elseif($request->isMethod('post')) {
//            $is_valid = ' is-invalid';
//        }
//
//        return $this->render('admin/categories.html.twig', [
//            'categories' => $categories->categoryList,
//            'form' => $form->createView(),
//            'is_valid' => $is_valid
//        ]);
//    }
//
//    /**
//     * @Route("/su/edit-category/{id}", name="edit_category", methods={"GET", "POST"})
//     */
//    public function editCategory(Category $category, Request $request)
//    {
//        $form = $this->createForm(CategoryType::class, $category);
//        $is_valid = null;
//
//        if($this->saveCategory($category, $form, $request)) {
//            return $this->redirectToRoute('categories');
//        } elseif($request->isMethod('post')) {
//            $is_valid = ' is-invalid';
//        }
//
//        return $this->render('admin/edit_category.html.twig', [
//            'category' => $category,
//            'form' => $form->createView(),
//            'is_valid' => $is_valid
//        ]);
//    }
//
//    /**
//     * @Route("/su/delete-category/{id}", name="delete_category")
//     */
//    public function deleteCategory(Category $category)
//    {
//        $em = $this->getDoctrine()->getManager();
//        $em->remove($category);;
//        $em->flush();
//        return $this->redirectToRoute('categories');
//    }
//
//    /**
//     * @Route("/videos", name="videos")
//     */
//    public function videos()
//    {
//        if($this->isGranted('ROLE_ADMIN')) {
//            $videos = $this->getDoctrine()->getRepository(Video::class)->findAll();
//        } else {
//            $videos = $this->getUser()->getLikedVideos();
//        }
//        return $this->render('admin/videos.html.twig', [
//            'videos' => $videos
//        ]);
//    }
//
//    /**
//     * @Route("/su/users", name="users")
//     */
//    public function users()
//    {
//        return $this->render('admin/users.html.twig');
//    }
//
//    /**
//     * @Route("/su/upload-video", name="upload_video")
//     */
//    public function uploadVideo()
//    {
//        return $this->render('admin/upload_video.html.twig');
//    }
//
//    public function getAllCategories(CategoryTreeAdminOptionList $categories, $editedCategory = null)
//    {
//        $this->denyAccessUnlessGranted('ROLE_ADMIN');
//        $categories->getCategoryList($categories->buildTree());
//        return $this->render('admin/_all_categories.html.twig', [
//            'categories' => $categories,
//            'editedCategory' => $editedCategory
//        ]);
//    }
//
//    private function saveCategory($category, $form, Request $request) {
//        $form->handleRequest($request);
//        if($form->isSubmitted() && $form->isValid()) {
//            $category->setName($request->request->get('category')['name']);
//            $repository = $this->getDoctrine()->getRepository(Category::class);
//            $parent = $repository->find($request->request->get('category')['parent']);
//            $category->setParent($parent);
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($category);
//            $em->flush();
//            return true;
//        }
//        return false;
//    }
}
