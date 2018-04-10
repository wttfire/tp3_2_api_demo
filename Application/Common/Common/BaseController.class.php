<?php
namespace Common\Common;

use Think\Controller;

class BaseController extends Controller{
	
	private $_appid = "test";
	private $_appsecret = "test";

	public function __construct(){
		parent::__construct();
	}
	//返回数据
	protected function ApiReturn($msg='',$code=1,$data=[])
	{
		if($code=1){
			$status="M101";
		}elseif($code=0){
			$status = "M100";
		}

		$json_data['status'] = $status;
		$json_data['msg'] = $msg;
		$json_data['data'] = $data;

		echo json_encode($json_data,JSON_UNESCAPED_UNICODE);exit;
	}

	//生成accessToken
	protected function getAccessToken($user_id)
	{
		$arr = [$user_id,$this->appid,$this->_appsecret,time()];
		ksort($arr);
		$_token = md5(implode("",$arr));
		
		$expire_time = 3600*24*7;//token过期时间
		$_token_at = time()+$expire_time;
		
		$upd_data = array(
			"user_id"=>$user_id,
			"token"=>$_token,
			"token_at"=>$_token_at
			);
		$res = M("user")->save($upd_data);
		if($res){
			return $_token;
		}else{
			return false;
		}
	}

	//用户登录
	protected function userLogin($userInfo)
	{
		//除去用户密码
		unset($userInfo['user_pwd']);
		//获取token
		$token = $this->getAccessToken($userInfo['user_id']);
		$userInfo['accessToken'] = $token;

		$this->ApiReturn("成功","0",$userInfo);
	}

}