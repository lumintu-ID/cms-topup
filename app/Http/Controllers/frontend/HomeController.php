<?php

namespace App\Http\Controllers\frontend;

use KubAT\PhpSimple\HtmlDomParser;
use App\Http\Controllers\Controller;
use App\Repository\Frontend\GeneralRepository;

class HomeController extends Controller
{
    private $_activeLink = 'home';
    private $_generalRepository;

    public function __construct(GeneralRepository $generalRepository)
    {
        $this->_generalRepository = $generalRepository;
    }

    public function index()
    {
        try {
            $games = $this->_generalRepository->getAllDataGame();
            $articles = $this->_curlArticle();
            $activeLink = $this->_activeLink;
            $banners = $this->_generalRepository->getAllBanner();

            return view('frontend.home.index', compact('games', 'articles', 'activeLink', 'banners'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function test()
    {
        return 'test home';
    }

    private function _curlArticle()
    {
        $url = "https://fightoflegends.co.id/news";
        // initialize the cURL request 
        $curl = curl_init();
        // set the URL to reach with a GET HTTP request 
        curl_setopt($curl, CURLOPT_URL, $url);
        // get the data returned by the cURL request as a string 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // make the cURL request follow eventual redirects, 
        // and reach the final page of interest 
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        // execute the cURL request and 
        // get the HTML of the page as a string 
        $html = curl_exec($curl);

        // release the cURL resources 
        curl_close($curl);


        // get html with HtmlDomParser
        $dom = HtmlDomParser::str_get_html($html);

        if ($dom->find('section.news')) {
            $limitArictle = 3;
            $article['articles'] = [];

            for ($i = 0; $i < $limitArictle; $i++) {
                $data = array(
                    'image' => $dom->find('div.news__thumbnail.col-md-4 > img.img-fluid')[$i]->getAttribute('src'),
                    'title' => $dom->find('h5.news__title > a')[$i]->innertext,
                    'url' => $dom->find('h5.news__title > a')[$i]->getAttribute('href'),
                    'description' => $dom->find('div.news__description')[$i]->innertext,
                );

                array_push($article['articles'], $data);
            }

            return $article;
        }
    }
}
