<?php

namespace EditLyricsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('@EditLyrics/Default/index.html.twig');
    }
    
    public function getLyricAction(Request $request)
    {
        if ($request->isXMLHttpRequest()) {

            $em = $this->getDoctrine()->getManager();
            
            $video_id = $_POST['current_video_id'];
            
            $lyricsLine = $em->createQuery(
                "SELECT ll.lyricslineId 
                FROM HomeBundle:Lyricsline ll 
                JOIN HomeBundle:Video v 
                WITH v.videoId = ll.video 
                JOIN HomeBundle:Lyricsword lw 
                WITH lw.lyricsline = ll.lyricslineId 
                WHERE v.videoId = '$video_id'"
            );

            $lyricsLine_e = $lyricsLine->getResult();
            
            $currentPosition = 0;
            $nextPosition = 0;
            
            
            $amountLyricsLine = 0;
            while (isset($lyricsLine_e[$currentPosition]['lyricslineId'])) {
                
                $nextPosition = $currentPosition + 1;
                
                if (isset($lyricsLine_e[$nextPosition]['lyricslineId']))
                {
                    $currentLyricLine = $lyricsLine_e[$currentPosition]['lyricslineId'];
                    $nextLyricLine = $lyricsLine_e[$nextPosition]['lyricslineId'];

                    if ($currentLyricLine != $nextLyricLine)
                    {
                        $amountLyricsLine++;
                    }
                }
                $currentPosition++;
            }
            
            
            
            
            
            
            
            
            
            $word = $em->createQuery(
                "SELECT k.keywordId, k.keywordContent, 
                ll.lyricslineId, 
                lw.lyricswordId, lw.lyricswordStarttime, lw.lyricswordEndtime 
                FROM HomeBundle:Keyword k 
                JOIN HomeBundle:Lyricsword lw 
                WITH k.keywordId = lw.keyword 
                JOIN HomeBundle:Lyricsline ll
                WITH ll.lyricslineId = lw.lyricsline 
                JOIN HomeBundle:Video v 
                WITH v.videoId = ll.video 
                WHERE v.videoId = '$video_id'"
            );

            $word_e = $word->getResult();
            
            $totalAmountKeywords = 0;
            while (isset($word_e[$totalAmountKeywords]['keywordId'])) {
                $totalAmountKeywords++;
            }
            
            
            
            
            $currentWordPosition = 0;
            $currentLine = 0;
            // HACER WHILE POR LA CANTIDAD MAXIMA DE ESTROFAS QUE HAYA
            $estrofa = 0;
            while ($estrofa < $amountLyricsLine)
            {
                $amountWord = 0;

                $currentTemporalPosition = $currentWordPosition;
                $nextTemporalPosition = $currentTemporalPosition + 1;
                
                $i = 0;
                while ($i <= $amountWord)
                {
                    if (isset($word_e[$currentTemporalPosition]['lyricslineId']) 
                     && isset($word_e[$nextTemporalPosition]['lyricslineId']))
                    {
                        $currentKeywordLine = $word_e[$currentTemporalPosition]['lyricslineId'];
                        $nextKeywordLine = $word_e[$nextTemporalPosition]['lyricslineId'];                    

                        if ($currentKeywordLine === $nextKeywordLine)
                        {
                            $currentTemporalPosition++;
                            $nextTemporalPosition = $currentTemporalPosition + 1;

                            $amountWord++;
                            $i++;
                        } else
                        {
                            $i++;
                        }
                    }
                }

                
                
                
                // HACER WHILE POR LA CANTIDAD MAXIMA DE PALABRAS QUE SE ENCUENTRE EN LA RESPECTIVA ESTROFA
                $palabra = 0;
                while ($palabra <= $amountWord)
                {
                    $keywordId_Value = $word_e[$currentWordPosition]['keywordId'];
                    $keywordContent_Value = $word_e[$currentWordPosition]['keywordContent'];
                    $lyricslineId_Value = $word_e[$currentWordPosition]['lyricslineId'];
                    $lyricswordId_Value = $word_e[$currentWordPosition]['lyricswordId'];
                    $lyricswordStarttime_Value = $word_e[$currentWordPosition]['lyricswordStarttime'];
                    $lyricswordEndtime_Value = $word_e[$currentWordPosition]['lyricswordEndtime'];
                    $totalAmountKeywords_Value = $totalAmountKeywords;

                    $lyric_word[$estrofa][$palabra] = array(
                        'keywordId' => $keywordId_Value,
                        'keywordContent' => $keywordContent_Value,
                        'lyricslineId' => $lyricslineId_Value,
                        'lyricswordId' => $lyricswordId_Value,
                        'lyricswordStarttime' => $lyricswordStarttime_Value,
                        'lyricswordEndtime' => $lyricswordEndtime_Value,
                        'amountKeywords' => $totalAmountKeywords_Value,
                        'palabra' => $palabra, // 0, 1, 2, 3, 4, 5
                        'estrofa' => $estrofa, // 0, 1, 2, 3
                        'amountWord' => $amountWord, // 5, 5, 5, 5, 5, 5 (siempre es el máximo valor)
                        'amountLyricsLine' => $amountLyricsLine, // 3, 3, 3, 3 (siempre es el máximo valor)
                        'currentWordPosition' => $currentWordPosition
                    );
                    
                    $palabra++;
                    $currentWordPosition++;
                }
                $estrofa++;
            }
            
            
            if ( $totalAmountKeywords === 0 )
            {
                // NO HAY NI UNA SOLA PALABRA
                $lyric_word[0][0] = array(
                    'keywordId' => "_",
                    'keywordContent' => "_",
                    'lyricslineId' => "_",
                    'lyricswordId' => "_",
                    'lyricswordStarttime' => "_",
                    'lyricswordEndtime' => "_",
                    'amountKeywords' => $totalAmountKeywords,
                    'palabra' => 0, // 0, 1, 2, 3, 4, 5
                    'estrofa' => 0, // 0, 1, 2, 3
                    'amountWord' => 0, // 5, 5, 5, 5, 5 (siempre es el máximo valor)
                    'amountLyricsLine' => 0, // 3, 3, 3, 3
                    'currentWordPosition' => 0
                );
            }
            
            return new Response(json_encode($lyric_word), 200, array('Content-Type' => 'application/json'));
        }
    }

    public function deleteLyricsWordAction(Request $request)
    {
        if ($request->isXMLHttpRequest()) {

            $em = $this->getDoctrine()->getManager();
            
            $users2 = array();
            $users2[0] = array(
                'variable' => "funciona"
            );
            return new Response(json_encode($users2), 200, array('Content-Type' => 'application/json'));
        }
    }

    public function saveLyricsWordAction(Request $request)
    {
        if ($request->isXMLHttpRequest()) {
            
            $em = $this->getDoctrine()->getManager();

            $write_lyricsWord = $_POST['write_lyricsWord'];
            $current_video_id = $_POST['current_video_id'];
            $startTime = $_POST['startTime'];
            $endTime = $_POST['endTime'];
            
            $keyword = $em->createQuery(
                "SELECT k.keywordId, k.keywordContent 
                FROM HomeBundle:Keyword k 
                WHERE k.keywordContent = '$write_lyricsWord'"
            );
            
            $keyword_e = $keyword->getResult();
            
            if (isset($keyword_e[0]['keywordContent'])) {
                // NO insertar la palabra ya que se encuntra en la bd
            } else
            {
                // insertar la palabra ya que NO se encuntra en la bd
                $keywordEntity = new \HomeBundle\Entity\Keyword();
                $keywordEntity->setKeywordContent($write_lyricsWord);
                
                $em->persist($keywordEntity);
                $em->flush();
                $keywordId = $keywordEntity->getKeywordId();
            }
            
            $video_value = $em->getRepository('HomeBundle:Video')->findOneByVideoId($current_video_id);
            $LyricslineEntity = new \HomeBundle\Entity\Lyricsline();
            $LyricslineEntity->setVideo($video_value);
            $em->persist($LyricslineEntity);
            $em->flush();
            $lyricsLineId = $LyricslineEntity->getLyricslineId();
            
            
            $keyword_value = $em->getRepository('HomeBundle:Keyword')->findOneByKeywordId($keywordId);
            $lyricsLine_value = $em->getRepository('HomeBundle:Lyricsline')->findOneByLyricslineId($lyricsLineId);
            $LyricswordEntity = new \HomeBundle\Entity\Lyricsword();
            $LyricswordEntity->setKeyword($keyword_value);
            $LyricswordEntity->setLyricsline($lyricsLine_value);
            $LyricswordEntity->setLyricswordEndtime($endTime);
            $LyricswordEntity->setLyricswordStarttime($startTime);
            $em->persist($LyricswordEntity);
            $em->flush();
            
            
            $users2 = array();
            $users2[0] = array(
                'write_lyricsWord' => $write_lyricsWord,
                'current_video_id' => $current_video_id,
                'startTime' => $startTime,
                'endTime' => $endTime
            );
            
            return new Response(json_encode($users2), 200, array('Content-Type' => 'application/json'));
        }
    }
    
}
