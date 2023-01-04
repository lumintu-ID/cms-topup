<?php

namespace App\Http\Controllers\frontend;

use KubAT\PhpSimple\HtmlDomParser;
use App\Http\Controllers\Controller;
use App\Repository\Frontend\GeneralRepository;

class HomeController extends Controller
{
    private $activeLink = 'home';
    
    public function __construct(GeneralRepository $generalRepository)
    {
        $this->generalRepository = $generalRepository;
    }


    private function _curlArticle()
    {
        // initialize the cURL request 
        $curl = curl_init();
        // set the URL to reach with a GET HTTP request 
        curl_setopt($curl, CURLOPT_URL, "https://fightoflegends.co.id/news");
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

            $article['articles'] = [];

            for ($i = 0; $i < 3; $i++) {
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

    public function index()
    {
        $games = $this->generalRepository->getAllDataGame();
        $articles = $this->_curlArticle();
        $activeLink = $this->activeLink;

        return view('frontend.home.index', compact('games', 'articles', 'activeLink'));
    }

    public function test()
    {
        try {
            $slug = 'fight-of-legends';
            $dataGame = $this->generalRepository->getDataGameBySlug($slug);
            $countries = $this->generalRepository->getAllDataCountry();
            
            return view('frontend.test.payment', compact('countries', 'dataGame'));
        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
