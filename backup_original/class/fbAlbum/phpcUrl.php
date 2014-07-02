<?php
class FacePageAlbum
{
		private $URL;
		private $TOKEN;
		private $PAGE;
		private $PHOTOS;
		private $resultado;
		private function setPage()
		{
				$protocol = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
				$this->PAGE = $protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
		}

		private function setAlbumUrl($id)
		{
			if(is_numeric($id))
			{
				if($this->TOKEN)
				{
					$this->URL = "http://graph.facebook.com/".$id."/albums?".$this->TOKEN;
					return true;
				}
				else
				{
					$this->URL = "http://graph.facebook.com/".$id."/albums";
					return true;				
				}
			}
			else
			{
				return false;
			}
		}
		private function setToken($appId, $appSecret)
		{
			$this->TOKEN = '';//$this->curlGetFile('https://graph.facebook.com/oauth/access_token?type=client_cred&client_id='.$appId.'&client_secret='.$appSecret);
		}
		
		public function FacePageAlbum($id, $albumId, $aurl, $appId, $appSecret)
		{
			$this->setPage();
			if($id)
			{
				if($appId && $appSecret)
					$this->setToken($appId, $appSecret);
				$this->setAlbumUrl($id);
				if($albumId && $this->albumChk($albumId))
				{
					$this->PHOTOS = 'http://graph.facebook.com/'.$albumId.'/photos';
					$json  = json_decode($this->curlGetFile($this->PHOTOS));
					
					if($json -> error) die("THERE HAS BEEN AN ERROR:album id invalid");
					$this->resultado = $json->data;
					
//					echo '<a id="back" href="javascript:history.go(-1)">Go Back</a>';
//					echo '<a id="backAlbums" href="'.$this->PAGE.'">Back to albums</a>';
//					echo '<br clear="all" />';
//					
//					if($json->paging->previous)
//						echo '<a id="prev" href="'.$PAGE.'?aurl='.urlencode($json->paging->previous).'">Previous</a>';
//					if($json->paging->next)
//						echo '<a id="next" href="'.$PAGE.'?aurl='.urlencode($json->paging->next).'">Next</a>';
//					echo '<br clear="all" />';
//					foreach($json->data as $v)
//					{
//						echo "<a class='ImageLink' href = '".$v->source."'><img width='110px' src='".$v->picture."' /></a>";
//					}
//					return true;
					
				}
				else if ($aurl)
				{
					$this->PHOTOS = urldecode($aurl);
					$json  = json_decode($this->curlGetFile($this->PHOTOS));
					if($json -> error) die("THERE HAS BEEN AN ERROR: album url invalid");
					$this->resultado = $json->data;
					
					
//					echo '<a id="back" href="javascript:history.go(-1)">Go Back</a>';
//					echo '<a id="backAlbums" href="'.$this->PAGE.'">Back to albums</a>';
//					echo '<br clear="all" />';
//					if($json->paging->previous)
//						echo '<a id="prev" href="'.$PAGE.'?aurl='.urlencode($json->paging->previous).'">Previous</a>';
//					if($json->paging->next)
//						echo '<a id="next" href="'.$PAGE.'?aurl='.urlencode($json->paging->next).'">Next</a>';
//					echo '<br clear="all" />';
//					foreach($json->data as $v)
//					{
//						echo "<a class='ImageLink' href = '".$v->source."'><img width='110px' src='".$v->picture."' /></a>";
//					}
//					return true;
					
					 
					
				}
				else
				{
					//echo $this->URL; //exit;
					$json = json_decode($this->curlGetFile($this->URL));
					if($json -> error) die("THERE HAS BEEN AN ERROR: pageId invalid");
					$this->resultado = $json->data;
//					foreach($json->data as $v)
//					{
//					echo "<div class ='ImgWrapper'>";
//					echo "<div style = 'width:125px; height:120px;overflow:hidden'>
//							<img width='125px' src='https://graph.facebook.com/".$v->id."/picture' />
//					      </div>";
//						echo  $v->from->name."<br>";
//						echo  "<a href = '".$this->PAGE;
//						echo  "?";
//						echo  "aid=".$v->id."'>".$v->name."</a>";
//						echo  "<br>Num of images:".$v->count."<br>";
//						echo "<br clear='all'></div>";
//					}
				}
			}
			return false;
		}
		
		
		public function retornaAlbum(){
			return $this->resultado;
		}
		
		public function retornaArray(){
			return $this->objectsIntoArray($this->resultado);
		}
		
		public function detalhesAlbum($albumId){
			$detalhes = 'http://graph.facebook.com/'.$albumId.'';
			$json  = json_decode($this->curlGetFile($detalhes));
			if($json -> error) die("THERE HAS BEEN AN ERROR:album id invalid");
			$this->resultado = $json;
			return $this->objectsIntoArray($this->resultado);
		}

		
		public function objectsIntoArray($arrObjData, $arrSkipIndices = array())
		{
			$arrData = array();
			
			// if input is object, convert into array
			if (is_object($arrObjData)) {
				$arrObjData = get_object_vars($arrObjData);
			}
			
			if (is_array($arrObjData)) {
				foreach ($arrObjData as $index => $value) {
					if (is_object($value) || is_array($value)) {
						$value = $this->objectsIntoArray($value, $arrSkipIndices); // recursive call
					}
					if (in_array($index, $arrSkipIndices)) {
						continue;
					}
					$arrData[$index] = $value;
				}
			}
			return $arrData;
		}
		
		
		public function curlGetFile($curlUrl)
		{
			$ch = curl_init(); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, $curlUrl); 
			$data = curl_exec($ch); 
			curl_close($ch);
			return $data;
		}
		
		public function albumChk($albumId)
		{
			$json = json_decode($this->curlGetFile($this->URL));
			$arrayId = array();
			foreach($json->data as $v)
						array_push($arrayId, $v->id);
			if(!in_array($albumId,$arrayId)) return false;
			return true;
		}
};
?>
