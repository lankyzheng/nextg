<?php
/**
  * wechat php test
  */

//define your token
define("TOKEN", "wannacool");
$wechatObj = new wechatCallbackapiTest();
//$wechatObj->valid();
$wechatObj->responseMsg();



class wechatCallbackapiTest
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
	if (!empty($postStr)){
                
		$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        	$fromUsername = $postObj->FromUserName;
        	$toUsername = $postObj->ToUserName;
        	$keyword = trim($postObj->Content);
        	$time = time();
        /*	$textTpl = "<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[%s]]></MsgType>
			<Content><![CDATA[%s]]></Content>
			<FuncFlag>0</FuncFlag>
			</xml>";
	*/
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
			<FuncFlag>1</FuncFlag>
			</xml>";

			if(!empty( $keyword ))
	                {
				$picArr = array(
					"12.png", 
					"Newzoo_2011_Infograph_US.png", 
					"infographic_banking.png", 
					"23443102108110994.png", 
					"The-Internet-of-Things.png", 
					"martin.png", 
					"7n6Y.png", 
					"africa-mobile-growth.png", 
					"tripl-social-travel-infographic-640-1.png");

				$picTitle = $my_array[rand(1,9)];
        	      		$msgType = "news";
                		// $contentStr = "Welcome to wechat world!";
				$picTitle = "N for next";
				$picDesc = "just reply N to see next";
				$picUrl = "http://v2.wannacool.com/pics/".$picTitle;
				$picClickUrl = "http://v2.wannacool.com/pics/".$picTitle;
                		$resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time, $msgType, $picTitle, $picDesc, $picUrl, $picClickUrl);
                		echo $resultStr;
                	}else{
                		echo "Input something...";
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
