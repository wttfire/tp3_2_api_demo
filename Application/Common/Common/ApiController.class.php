<?php
namespace Common\Common;

class ApiController extends BaseController{
	
	private $_token = NULL;
	private $_nonce = NULL;
	private $_timestamp = NULL;
	private $_sign = NULL;

	public funciton __construct()
	{
		parent::__construct();
		$this->_token = I("post.accessToken");
		$this->_nonce = I("post.nonce");
		$this->_timestamp = I("post.timestamp");
		$this->_sign = I("post.sign");
	}

	//验证随机数参数
	protected function checkNonce()
	{
		$_nonce = $this->_nonce;
		!empty($_nonce) || $this->apiReturn("缺少随机数参数");
	}

	//验证接口调用时间
	protected function checkTime()
	{
		$now = time();
		$_time = $this->_timestamp;
		!empty($_sign) || $this->apiReturn("缺少时间参数");

		$now < $_time+60 || $this->apiReturn("接口已过期");
		
	}
	//验证签名
	protected function checkSign()
	{
		$this->checkNonce();
		$this->checkTime();

		$_sign = $this->_sign;
		!empty($_sign) || $this->apiReturn("缺少签名参数");
		$_post = I("post.");
		ksort($_post);
		$sign = md5(implode("",$_post));
		$sign == $_sign || $this->apiReturn("签名有误");
	}

	//验证token参数
	protected function checkToken()
	{
		$_token = $this->_token;
		!empty($_token) || $this->apiReturn("缺少token参数");
		$tokenInfo = M("user")->field("user_id,token_at")->where("token='$_token'")->find();
		!empty($tokenInfo) || $this->apiReturn("token不存在");
		$tokenInfo['token_at'] >= time() || $this->apiReturn("token已过期");

		return $tokenInfo['user_id'];
	}

	//验证用户接口
	protected function checkAPi()
	{	
		//签名
		$this->checkSign();
		//token
		$user_id = $this->checkToken();
		
		return $user_id;
	}

}