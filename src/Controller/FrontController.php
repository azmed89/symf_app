<?php

namespace App\Controller;

use App\Controller\Traits\Likes;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\User;
use App\Entity\Video;
use App\Form\UserType;
use App\Repository\VideoRepository;
use App\Utils\CategoryTreeFrontPage;
use App\Utils\Interfaces\CacheInterface;
use App\Utils\VideoForNoValidSubscription;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    use Likes;

    /**
     * @Route("/", name="main_page")
     */
    public function index()
    {
        return $this->render('front/index.html.twig');
    }

    /**
     * @Route("/video-list/category/{categoryname},{id}/{page}", defaults={"page": "1"}, name="video_list")
     */
    public function videoList($id, $page, CategoryTreeFrontPage $categories,
                              Request $request,
                              VideoForNoValidSubscription $video_no_members,
                              CacheInterface $cache)
    {

        $cache = $cache->cache;
        $video_list = $cache->getItem('video_list'.$id.$page.$request->get('sort_by'));
        $video_list->expiresAfter(60);

        if(!$video_list->isHit()) {
            $categories->getCategoryListAndParent($id);
            $ids = $categories->getChildIds($id);
            array_push($ids, $id);
            $videos = $this->getDoctrine()->getRepository(Video::class)->findByChildIds($ids, $page, $request->get('sortby'));
            $response = $this->render('front/video_list.html.twig', [
                'subcategories' => $categories,
                'videos' => $videos,
                'video_no_members' => $video_no_members->check()
            ]);

            $video_list->set($response);
            $cache->save($video_list);
        }

        return $video_list->get();
    }

    /**
     * @Route("/video-details/{video}", name="video_details")
     */
    public function videoDetails(VideoRepository $repo, $video, VideoForNoValidSubscription $video_no_members)
    {
        return $this->render('front/video_details.html.twig', [
            'video' => $repo->videoDetails($video),
            'video_no_members' => $video_no_members->check()
        ]);
    }

    /**
     * @Route("/search-results/{page}", defaults={"page": "1"}, name="search_results", methods={"GET"})
     */
    public function searchResults($page, Request $request, VideoForNoValidSubscription $video_no_members)
    {
        $videos = null;
        $query = null;
        if($query = $request->get('query')) {
            $videos = $this->getDoctrine()
                ->getRepository(Video::class)
                ->findByTitle($query, $page, $request->get('sortby'));
            if(!$videos->getItems()) $videos = null;
        }
        return $this->render('front/search_results.html.twig', [
            'videos' => $videos,
            'query' => $query,
            'video_no_members' => $video_no_members->check()
        ]);
    }

    /**
     * @Route("/payment", name="payment")
     */
    public function payment()
    {
        return $this->render('front/payment.html.twig');
    }

    /**
     * @Route("/new-comment/{video}", methods={"POST"}, name="new_comment")
     */
    public function newComment(Video $video, Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        if (!empty(trim($request->request->get('comment')))) {
            $comment = new Comment();
            $comment->setContent($request->request->get('comment'));
            $comment->setUser($this->getUser());
            $comment->setVideo($video);

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
        }
        return $this->redirectToRoute('video_details', [
            'video' => $video->getId()
        ]);
    }

    /**
     * @Route("/video-list/{video}/like", methods={"POST"}, name="like_video")
     * @Route("/video-list/{video}/dislike", methods={"POST"}, name="dislike_video")
     * @Route("/video-list/{video}/unlike", methods={"POST"}, name="unlike_video")
     * @Route("/video-list/{video}/undodislike", methods={"POST"}, name="undo_dislike_video")
     */
    public function toggleLikesAjax(Video $video, Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        switch ($request->get('_route')) {
            case 'like_video':
                $result = $this->likeVideo($video);
                break;
            case 'dislike_video':
                $result = $this->dislikeVideo($video);
                break;
            case 'unlike_video':
                $result = $this->undoLikeVideo($video);
                break;
            case 'undo_dislike_video':
                $result = $this->undoDislikeVideo($video);
                break;
        }

        return $this->json(['action' => $request, 'id' => $video->getId()]);
    }

    /**
     * @Route("/delete-comment/{comment}", name="delete_comment")
     * @Security("user.getId() == comment.getUser().getId()")
     */
    public function deleteComment(Comment $comment, Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        $em = $this->getDoctrine()->getManager();
        $em->remove($comment);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    public function mainCategories()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findBy(['parent' => null], ['name' => 'ASC']);
        return $this->render('front/_main_categories.html.twig', ['categories' => $categories]);
    }
}
