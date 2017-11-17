<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DomCrawler\Crawler;

class DefaultController extends Controller
{

    /**
     * @Route("/crawler", name="dom")
     */
    public function crawler(Request $request)
    {
        $starting_url='https://searchdns.netcraft.com/?restriction=site+contains&host=google&lookup=wait..&position=limited';
        $html = file_get_contents($starting_url);
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);
        $crawler2=$crawler->filter('.TBtable > tr')->each(function (Crawler $node, $i) {
            return $node->children()->eq(1)->text();
        });
        $crawler2_results=filter_var($crawler->filter('.blogbody > p >em')->text(),FILTER_SANITIZE_NUMBER_INT);
        $str_n='https://searchdns.netcraft.com'.$crawler->filter('.blogbody > p >a')->attr('href');
        $crawler2_next=str_replace('site contains', 'site%20contains',$str_n);
        
        print "<pre>";
        print_r('<br/>Total Results =');
        print_r($crawler2_results);
        print_r('<br/>Result =1-20');
        print_r('<br/>Link =');
        print_r($crawler2_next);
        print_r('<br/>');
        print_r($crawler2);
        print "</pre>";
        
        $limit = 100000; 
        set_time_limit($limit);
        print "<pre>";
        $y=0;
        for($x=20;$y<$crawler2_results-20;$x++)
        {
            
            $html = file_get_contents($crawler2_next);

            $crawler = new Crawler();
            $crawler->addHtmlContent($html);
            $crawler2=$crawler->filter('.TBtable > tr')->each(function (Crawler $node, $i) {
                return $node->children()->eq(1)->text();
            }); 
            $y=$y+20;
            if($y+21<$crawler2_results)
            {
                $str_n='https://searchdns.netcraft.com'.$crawler->filter('.blogbody > p >a')->attr('href');
                $crawler2_next=str_replace('site contains', 'site%20contains',$str_n);

            }
            print_r('<br/>Result =');
            print_r($y+1);
            print_r('-');
            print_r($y+20);
            print_r('<br/>Link =');
            print_r($crawler2_next);
            print_r('<br/>');
            print_r($crawler2);

            
            
            
                     
            
            
        }
        print "</pre>";




        die();


    }
}
