<?php
/*
Autor:      Eder Martins Franco
Data:       21/08/2012
Atualizado: -
Versão:     1.0
Descrição:  Classe que coleta conteúdos de postagens no twitter (hashtag) e facebook (mural de uma página)

*/
class socialCrawler{

    private $fb_token;
    public  $fb_pageID, $fb_appID, $fb_secret, $fb_posts, $tw_posts, $hashtag, $limite;

    function __construct($fb_pageID,$fb_appID,$fb_secret){
        $this->fb_pageID    = $fb_pageID;
        $this->fb_appID     = $fb_appID;
        $this->fb_secret    = $fb_secret;
        $this->fbGetToken();
    }

    public function setHashtag($hashtag){
        $this->hashtag = $hashtag;
    }

    private function fbGetToken(){
        $token = file_get_contents('https://graph.facebook.com/oauth/access_token?type=client_cred&client_id='.$this->fb_appID.'&client_secret='.$this->fb_secret);
        $token = str_replace('access_token=','',$token);
        $this->fb_token = $token;
    }

    public function fbGetPosts(){
        $posts = file_get_contents('https://graph.facebook.com/393760197354749/feed?access_token='.$this->fb_token);
        $this->fb_posts = json_decode($posts);
        return $this->fb_posts;
    }

    public function twGetPosts(){
        $posts = file_get_contents("http://search.twitter.com/search.json?rpp=$limite&q=%23".$this->hashtag);
        $this->tw_posts = json_decode($posts)->results;
        return $this->tw_posts;
    }

    public function objToArray($arrObjData, $arrSkipIndices = array()){
        $arrData = array();

        // if input is object, convert into array
        if (is_object($arrObjData)) {
            $arrObjData = get_object_vars($arrObjData);
        }

        if (is_array($arrObjData)) {
            foreach ($arrObjData as $index => $value) {
                if (is_object($value) || is_array($value)) {
                    $value = $this->objToArray($value, $arrSkipIndices); // recursive call
                }
                if (in_array($index, $arrSkipIndices)) {
                    continue;
                }
                $arrData[$index] = $value;
            }
        }
        return $arrData;
    }
} ?>