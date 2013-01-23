<?php
/**
  * wechat backbone for NextG
  * lankyzheng
  * 2013-01
  */


//define your token
define("TOKEN", "wannacool");
$wechatObj = new wechatCallbackapi();
//$wechatObj->valid();
$wechatObj->responseMsg();


class wechatCallbackapi
{
	public function valid()
	{
		$echoStr = $_GET["echostr"];

		//valid signature , option
		if($this->checkSignature()){
			echo $echoStr;
			exit;
		}
	}

	public function responseMsg()
	{
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

		//extract post data
		if (!empty($postStr))
		{
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			$fromUsername = $postObj->FromUserName;
			$toUsername = $postObj->ToUserName;
			$keyword = trim($postObj->Content);
			$time = time();

			//if(!empty( $keyword ))
			if(($keyword == "N") or ($keyword == "n"))
			{
				$newsTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<Content><![CDATA[]]></Content>
					<ArticleCount>1</ArticleCount>
					<Articles>
						<item>
						<Title><![CDATA[%s]]></Title>
						<Description><![CDATA[%s]]></Description>
						<PicUrl><![CDATA[%s]]></PicUrl>
						<Url><![CDATA[%s]]></Url>
						</item>
					</Articles>
					<FuncFlag>0</FuncFlag>
					</xml>";

				include 'picarray.php';

				$picFile = $picArr[rand(0,(count($picArr) - 1))];
				$msgType = "news";
				$picTitle = $picFile;
				$picDesc = "just reply N to see next";
				$picUrl = "http://v2.wannacool.com/pics/".$picFile;
				$picClickUrl = "http://v2.wannacool.com/pics/".$picFile;
				$resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time, $msgType, $picTitle, $picDesc, $picUrl, $picClickUrl);
				echo $resultStr;

			}else{

				$textTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<Content><![CDATA[%s]]></Content>
					<FuncFlag>0</FuncFlag>
					</xml>";

				$msgType = "text";
				$contentStr = "Welcome to wechat world!";
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
				echo $resultStr;

			}	
		}else {
			echo "";
			exit;
		}
	}
		
	private function checkSignature()
	{
		$signature = $_GET["signature"];
		$timestamp = $_GET["timestamp"];
		$nonce = $_GET["nonce"];	

		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}

?>
