<?php

namespace App\Helpers\HelperClasses\WordAi;

class WordAi_API
{
    const REWRITE_ENDPOINT = "https://wai.wordai.com/api/rewrite";

    private $EMAIL;
    private $KEY;
    
    /**
     * rewrite
     *
     * @param  string $content
     * @param  int $rewrite_num
     * @param  int $uniqueness
     * @param  boolean $return_rewrites
     * @return string
     */
    public function rewrite($content,$rewrite_num,$uniqueness,$return_rewrites)
    {
        $this->EMAIL  =  config('constant.WORD_AI_EMAIL');
        $this->KEY  = config('constant.WORD_AI_KEY');


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::REWRITE_ENDPOINT);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
                'email' => $this->EMAIL,
                'key' =>  $this->KEY,
                'input' => $content,
                'rewrite_num' => $rewrite_num,
                'uniqueness' => $uniqueness,
                'return_rewrites' => $return_rewrites,
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // set curl without result echo
        $result = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($result,true);

        $result['text'] = explode('|',str_replace(array("{", "}"),"",$result['text']));

        foreach ($result['text'] as $count => $value) {
            if ($count > 0) {
                $result['rewrite'][] = $value;
            }
        }
        
        return $result;
    }


    public static function response($content,$rewrite_num = 1,$uniqueness = 3,$return_rewrites = true)
    {
        $result = new WordAi_API();
        return $result->rewrite($content,$rewrite_num,$uniqueness,$return_rewrites);
    }
}
