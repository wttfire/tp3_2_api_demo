<?php
namespace Common\Common;
use Think\Controller;

use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Api\Sms\Request\V20170525\QuerySendDetailsRequest;
class SmsController extends Controller {

    public function sendMsg($mobile,$verify=0,$tmp_id,$order_sn="",$data=[]){
        // var_dump($mobile,$verify,$tmp_id,$order_sn,$data);exit;
    	vendor("alisms.vendor.autoload");
    	vendor("alisms.lib.Core.Config");
    	vendor("alisms.lib.Core.Profile.DefaultProfile");
    	vendor("alisms.lib.Core.DefaultAcsClient");
    	vendor("alisms.lib.Api.Sms.Request.V20170525.SendSmsRequest");
    	vendor("alisms.lib.Api.Sms.Request.V20170525.QuerySendDetailsRequest");

    	// 加载区域结点配置
		Config::load();

		$accessKeyId = "LTAI3gDsoy17Sxj2";
    	$accessKeySecret = "7h5qYbQhEIVImfqtMknCEl1voblflJ";
		// 短信API产品名
        $product = "Dysmsapi";

        // 短信API产品域名
        $domain = "dysmsapi.aliyuncs.com";

        // 暂时不支持多Region
        $region = "cn-hangzhou";

        // 服务结点
        $endPointName = "cn-hangzhou";

        // 初始化用户Profile实例
        $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);

        // 增加服务结点
        DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);

        // 初始化AcsClient用于发起请求
        $acsClient = new DefaultAcsClient($profile);

        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();

        // 必填，设置雉短信接收号码
        $request->setPhoneNumbers($mobile);

        // 必填，设置签名名称
        $request->setSignName("银杏科技");

        // 必填，设置模板CODE
        $request->setTemplateCode($tmp_id);
        // var_dump($order_sn,$data);exit;
        // 可选，设置模板参数
        if($verify){
            $templateParam = array("code"=>$verify);
            if($templateParam) {
                $request->setTemplateParam(json_encode($templateParam));
            }
        }elseif($order_sn){
            $order_sn = substr($order_sn, 3);
            $templateParam = array("order_sn"=>$order_sn);
            if($templateParam) {
                $request->setTemplateParam(json_encode($templateParam));
            }
        }elseif (!empty($data)){
            $templateParam = array("order_sn"=>$data['order_sn'],"status"=>$data['status'],"time"=>$data['time']);
            if($templateParam) {
                $request->setTemplateParam(json_encode($templateParam));
            }
        }

        // 发起访问请求
        $acsResponse = $acsClient->getAcsResponse($request);

        // 打印请求结果

        $res = (array)$acsResponse;
        if($res['Code'] != "OK"){
            $data["status"] = "M101";
            $data["msg"] = "发送失败";
            $data["reson"] = $res;
            if($res['Code']=='isv.BUSINESS_LIMIT_CONTROL'){
                $data["msg"] = "您发送的短信过于频繁或已达上限,请稍后再试！";
            }
            echo json_encode($data,JSON_UNESCAPED_UNICODE);exit();
        }
    }		

}