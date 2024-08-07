<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Routing\Attribute\Route;

class ApiController extends AbstractController
{
    #[Route('/v3/postingStats', methods: ['GET'])]
    #[Cache(public: true, maxage: 60, smaxage: 120)]
    public function getAverageNumberOfWords(Request $request, PostRepository $postRepository): Response
    {
        $params = $request->query->all();
        $results = $postRepository->findPostsFromParams($params);
        $average = 0;
        $totalResult = count($results);
        if ($totalResult > 0) {
            $totalWords = 0;
            foreach ($results as $post) {
                $postWords = explode(" ", $post->getBody());
                $totalPostWords = count($postWords);
                $totalWords += $totalPostWords;
            }
            $average = $totalWords / $totalResult;
        }
        // The following headers could be defined to limit the api call in production
        // Implement the rate-limiter from symfony
        /*$headers = [
            'X-RateLimit-Remaining' => $limit->getRemainingTokens(),
            'X-RateLimit-Retry-After' => $limit->getRetryAfter()->getTimestamp() - time(),
            'X-RateLimit-Limit' => $limit->getLimit(),
        ];*/
        $json = new JsonResponse(['average' => floor($average)]);
        //$json->headers->add($headers);
        return $json;
    }
}