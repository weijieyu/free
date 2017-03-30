<?php
namespace Common\Library;

/**
 * 返回提示信息 类常量
 */
class Tip{
	//用户模块提示信息
	const LOGIN_SUCCESS=1100;          //登录成功
	const LOGIN_SUCCESS_MSG='登录成功';  
	
	const LOGIN_FAIL='1101';      //登录失败
	const LOGIN_FAIL_MSG='登录失败'; 
	
	const OPENID_CHECK_FAIL='1102';      //第三方登录校验失败
	const OPENID_CHECK_FAIL_MSG='第三方登录校验失败';  
	
	const REGISTER_SUCCESS='1103';      //注册成功
	const REGISTER_SUCCESS_MSG='注册成功';
	
	const REGISTER_FAIL='1104';      //注册失败
	const REGISTER_FAIL_MSG='注册失败';
	
	const EMAIL_EXISTS='1105';      //该邮箱已注册
	const EMAIL_EXISTS_MSG='该邮箱已注册';
	
	const EMAIL_NOT_EXISTS='1106';      //该登录邮箱未注册\账号不存在
	const EMAIL_NOT_EXISTS_MSG='该登录邮箱不存在';
	
	const PASSWORD_ERROR='1107';      //密码错误
	const PASSWORD_ERROR_MSG='密码错误';
	
	const USERINFO_GET_SUCCESS='1108';      //查询成功
	const USERINFO_GET_SUCCESS_MSG='查询成功';
	
	const USERINFO_GET_FAIL='1109';      //查询失败
	const USERINFO_GET_FAIL_MSG='查询失败';
	
	const USERINFO_UPDATE_SUCCESS='1110';      //修改成功
	const USERINFO_UPDATE_SUCCESS_MSG='修改成功';
	
	const USERINFO_UPDATE_FAIL='1111';      //修改失败
	const USERINFO_UPDATE_FAIL_MSG='修改失败';
	
	const USER_DENY='1112';      //账号已被封禁
	const USER_DENY_MSG='账号已被封禁';
	
	const USER_PASSWORD_NO_EQUEL='1113';      //两次密码不相同
	const USER_PASSWORD_NO_EQUEL_MSG='两次密码不相同';
	
	const MOBILE_NUMBER_IS_EXISTS='1114';      //手机号码已存在
	const MOBILE_NUMBER_IS_EXISTS_MSG='手机号码已存在';
	
	const MOBILE_NUMBER_NOT_IS_EXISTS='1115';      //手机号码不存在
	const MOBILE_NUMBER_NOT_IS_EXISTS_MSG='手机号码没有注册过';
	
	const MOBILE_MESSAGE_CODE_IS_ERROR='1116';      //验证码错误
	const MOBILE_MESSAGE_CODE_IS_ERROR_MSG='验证码错误';
	
	const ACUNT_IS_ABNORMAL='1117';      //账号异常
	const ACUNT_IS_ABNORMAL_MSG='账号异常';
	
	const MOBILE_NUMBER_IS_EXISTS_BANG='1118';      //已绑定过手机号码
	const MOBILE_NUMBER_IS_EXISTS_BANG_MSG='已绑定过手机号码';
	
	const MOBILE_NUMBER_BANG_SUCCESS='1119';      //手机号码已绑成功
	const MOBILE_NUMBER_BANG_SUCCESS_MSG='手机号码已绑成功';
	
	const MOBILE_NUMBER_BANG_FAIL='1120';      //手机号码已绑失败
	const MOBILE_NUMBER_BANG_FAIL_MSG='手机号码已绑失败';
	
	const UPLOAD_AVATAR_FAIL='1121';      //修改头像失败
	const UPLOAD_AVATAR_FAIL_MSG='修改头像失败';
	
	const UPLOAD_AVATAR_SUCCESS='1122';      //修改头像成功
	const UPLOAD_AVATAR_SUCCESS_MSG='修改头像成功';
	
	//基础模块提示信息
	const GET_SUCCESS=1200;          //获取成功
	const GET_SUCCESS_MSG='获取成功';
	
	const GET_FAIL=1201;          //获取失败
	const GET_FAIL_MSG='获取失败';
	
	//商城模块提示信息
	const SHOP_ATTRIBUTE_GET_SUCCESS=1300;          //获取成功
	const SHOP_ATTRIBUTE_GET_SUCCESS_MSG='获取成功';
	
	//订阅模块提示信息
	const SUBSCRIBE_SUCCESS=1400;          //订阅成功
	const SUBSCRIBE_SUCCESS_MSG='订阅成功';
	
	const SUBSCRIBE_FAIL=1401;          //订阅失败
	const SUBSCRIBE_FAIL_MSG='订阅失败';
	
	const SUBSCRIBE_EXISTS=1402;          //已存在该订阅
	const SUBSCRIBE_EXISTS_MSG='已存在该订阅';
	
	const SUBSCRIBE_EXISTS_BE_DELETE=1403;          //已存在被删除的该订阅
	const SUBSCRIBE_EXISTS_BE_DELETE_MSG='已存在被删除的该订阅';
	
	const SUBSCRIBE_NOT_EXISTS=1404;          //该订阅不存在
	const SUBSCRIBE_NOT_EXISTS_MSG='该订阅不存在';
	
	const SUBSCRIBE_CANCEL_SUCCESS=1405;          //取消成功
	const SUBSCRIBE_CANCEL_SUCCESS_MSG='取消成功';
	
	const SUBSCRIBE_CANCEL_FAIL=1406;          //取消失败
	const SUBSCRIBE_CANCEL_FAIL_MSG='取消失败';
	
	const SUBSCRIBE_GET_SUCCESS=1407;          //获取成功
	const SUBSCRIBE_GET_SUCCESS_MSG='获取成功';
	
	const PARAMS_ERROR=1408;          //参数不正确
	const PARAMS_ERROR_MSG='参数不正确';
	
	const KEYWORD_BE_DENY=1409;          //该词已被禁用
	const KEYWORD_BE_DENY_MSG='该词已被禁用';
	
	const SUBSCRIBE_DEAL_FINISH=1410;          //处理完成
	const SUBSCRIBE_DEAL_FINISH_MSG='处理完成';
	
	//商品模块
	const GOODS_DIGG_SUCCESS=1500;          //该点‘值’成功
	const GOODS_DIGG_SUCCESS_MSG='该点‘值’成功';
	
	const GOODS_DIGG_FAIL=1501;          //点‘值’失败
	const GOODS_DIGG_FAIL_MSG='点‘值’失败';
	
	const GOODS_COLLECTION_SUCCESS=1502;          //收藏成功
	const GOODS_COLLECTION_SUCCESS_MSG='收藏成功';
	
	const GOODS_COLLECTION_FAIL=1503;          //收藏失败
	const GOODS_COLLECTION_FAIL_MSG='收藏失败';
	
	const GOODS_CANCEL_COLLECTION_SUCCESS=1504;          //取消收藏成功
	const GOODS_CANCEL_COLLECTION_SUCCESS_MSG='取消收藏成功';
	
	const GOODS_CANCEL_COLLECTION_FAIL=1505;          //取消收藏失败
	const GOODS_CANCEL_COLLECTION_FAIL_MSG='取消收藏失败';
	
	const GET_ACROSS_CODE_SUCCESS=1506;          //获取跨屏码成功
	const GET_ACROSS_CODE_SUCCESS_MSG='获取跨屏码成功';
	
	const GET_ACROSS_CODE_FAIL=1507;          //获取跨屏码失败
	const GET_ACROSS_CODE_FAIL_MSG='获取跨屏码失败';
	
	const ADD_LOOKED_SUCCESS=1508;          //添加成功
	const ADD_LOOKED_SUCCESS_MSG='添加成功';
	
	const ADD_LOOKED_FAIL=1509;          //添加失败
	const ADD_LOOKED_FAIL_MSG='添加失败';
	
	const GOODS_GET_SUCCESS=1510;          //获取成功
	const GOODS_GET_SUCCESS_MSG='获取成功';
	
	const GOODS_EXISTS_DIGG=1511;          //已点过‘值’
	const GOODS_EXISTS_DIGG_MSG='已点过‘值’';
	
	const GOODS_EXISTS_COLLECTION=1512;          //已经收藏过
	const GOODS_EXISTS_COLLECTION_MSG='已经收藏过';
	
	const GOODS_NOT_COLLECTION=1513;          //未收藏该商品
	const GOODS_NOT_COLLECTION_MSG='未收藏该商品';
	
	const GOODS_GET_FAIL=1514;          //获取失败
	const GOODS_GET_FAIL_MSG='获取失败';
	
	//积分任务模块
	const SCORE_TASK_GET_SUCCESS=1600;          //获取成功
	const SCORE_TASK_GET_SUCCESS_MSG='获取成功';
	
	const CREATE_REQUESTCODE_FAIL=1601;          //创建邀请码失败
	const CREATE_REQUESTCODE_FAIL_MSG='创建邀请码失败';
	
	const USER_SCORE_DENY=1602;          //你已被加入黑名单，不能加积分
	const USER_SCORE_DENY_MSG='你已被加入黑名单，不能加积分';
	
	const TASK_NOT_EXISTS=1603;          //该任务不存在
	const TASK_NOT_EXISTS_MSG='该任务不存在';
	
	const SCORE_RULE_NOT_EXISTS=1604;          //积分规则不存在
	const SCORE_RULE_NOT_EXISTS_MSG='积分规则不存在';
	
	const NOWDAY_TASK_ALREADY_FINISHED=1605;          //今天的任务已完成
	const NOWDAY_TASK_ALREADY_FINISHED_MSG='今天的任务已完成';
	
	const UPDATE_USER_TASK_FAIL=1606;          //更新任务失败
	const UPDATE_USER_TASK_FAIL_MSG='更新任务失败';
	
	const ADD_USER_SCORE_FAIL=1607;          //加积分失败
	const ADD_USER_SCORE_FAIL_MSG='加积分失败';
	
	const ADD_USER_SCORENOTES_FAIL=1608;          //添加用户积分记录失败
	const ADD_USER_SCORENOTES_FAIL_MSG='添加用户积分记录失败';
	
	const DO_TASK_SUCCESS=1609;          //做任务成功
	const DO_TASK_SUCCESS_MSG='做任务成功';
	
	const TASK_NUM_ALREADY_FINISHED=1610;          //该任务的次数已做完
	const TASK_NUM_ALREADY_FINISHED_MSG='该任务的次数已做完';
	
	const PHONE_ALREADY_USE_REQUESTCODE=1611;          //你的手机已经填写过邀请码
	const PHONE_ALREADY_USE_REQUESTCODE_MSG='你的手机已经填写过邀请码';
	
	const ACOUNT_ALREADY_USE_REQUESTCODE=1612;          //你的账号已经填写过邀请码
	const ACOUNT_ALREADY_USE_REQUESTCODE_MSG='你的账号已经填写过邀请码';
	
	const REQUEST_ALREADY_LOSTTIME=1613;          //邀请码已过期
	const REQUEST_ALREADY_LOSTTIME_MSG='邀请码已过期';
	
	const REQUESTCODE_IS_OURSELF=1614;          //你不能填写自己的邀请码
	const REQUESTCODE_IS_OURSELF_MSG='你不能填写自己的邀请码';
	
	const REQUESTCODE_USELOG_FAIL=1615;          //邀请码使用记录失败
	const REQUESTCODE_USELOG_FAIL_MSG='邀请码使用记录失败';
	
	const UPDATE_REQUEST_NUM_FAIL=1616;          //更新邀请码剩余使用次数失败
	const UPDATE_REQUEST_NUM_FAIL_MSG='更新邀请码剩余使用次数失败';
	
	const GOODSGIFT_NOT_EXISTS=1617;          //该兑换商品不存在
	const GOODSGIFT_NOT_EXISTS_MSG='该兑换商品不存在';
	
	const GOODSGIFT_NOT_NUM=1618;          //该商品已被兑换完
	const GOODSGIFT_NOT_NUM_MSG='该商品已被兑换完';
	
	const GOODSGIFT_CODE_NOT_NUM=1619;          //暂时没有该商品的兑换码
	const GOODSGIFT_CODE_NOT_NUM_MSG='暂时没有该商品的兑换码';
	
	const EXCHANGE_FAIL=1620;          //商品兑换失败
	const EXCHANGE_FAIL_MSG='商品兑换失败';
	
	const EXCHANGE_SUCCESS=1621;          //兑换成功
	const EXCHANGE_SUCCESS_MSG='兑换成功';
	
	const REQUESTCODE_NOT_NUM=1622;          //邀请码的使用次数已用完
	const REQUESTCODE_NOT_NUM_MSG='邀请码的使用次数已用完';
	
	const ADD_ADDRESS_SUCCESS=1623;          //添加成功
	const ADD_ADDRESS_SUCCESS_MSG='添加成功';
	
	const ADD_ADDRESS_FAIL=1624;          //添加失败
	const ADD_ADDRESS_FAIL_MSG='添加失败';
	
	const DELETE_ADDRESS_SUCCESS=1625;          //删除成功
	const DELETE_ADDRESS_SUCCESS_MSG='删除成功';
	
	const DELETE_ADDRESS_FAIL=1626;          //删除失败
	const DELETE_ADDRESS_FAIL_MSG='删除失败';
	
	const UPDATE_ADDRESS_SUCCESS=1627;          //修改成功
	const UPDATE_ADDRESS_SUCCESS_MSG='修改成功';
	
	const UPDATE_ADDRESS_FAIL=1628;          //修改失败
	const UPDATE_ADDRESS_FAIL_MSG='修改失败';
	
	const SET_ADDRESS_SUCCESS=1629;          //设置成功
	const SET_ADDRESS_SUCCESS_MSG='设置成功';
	
	const SET_ADDRESS_FAIL=1630;          //设置失败
	const SET_ADDRESS_FAIL_MSG='设置失败';
	
	const MUST_USE_OLD_USEACOUNT_REQUESTCODE=1631;          //必须填写老用户的邀请码
	const MUST_USE_OLD_USEACOUNT_REQUESTCODE_MSG='必须填写老用户的邀请码';
	
	const REQUESTCODE_NOT_EXISTS=1632;          //邀请码不存在
	const REQUESTCODE_NOT_EXISTS_MSG='邀请码不存在';
	
	const USER_SCORE_LESS=1633;          //积分数不够
	const USER_SCORE_LESS_MSG='积分数不够';
	
	const ADDRESS_NOT_EXISTS=1634;          //收货地址不存在
	const ADDRESS_NOT_EXISTS_MSG='收货地址不存在';
	
	const SCORE_DEC_FAIL=1635;          //积分扣减失败
	const SCORE_DEC_FAIL_MSG='积分扣减失败';
	
	const SCORE_DEC_SUCCESS=1636;          //积分扣减成功
	const SCORE_DEC_SUCCESS_MSG='积分扣减成功';
	
	//‘发现’模块
	const GET_ARTICLE_SUCCESS=1700;
	const GET_ARTICLE_SUCCESS_MSG='获取文章成功'; //获取文章成功
	
	const GET_ARTICLE_FAIL=1701;
	const GET_ARTICLE_FAIL_MSG='获取文章失败'; //获取文章失败
	
	const GET_LOOP_PICTURE_SUCCESS=1702;
	const GET_LOOP_PICTURE_SUCCESS_MSG='获取成功'; //获取成功
	
	const GET_LOOP_PICTURE_DEAL_SUCCESS=1702;
	const GET_LOOP_PICTURE_DEAL_SUCCESS_MSG='处理成功'; //处理成功
	
	const GET_LOOP_PICTURE_DEAL_FAIL=1702;
	const GET_LOOP_PICTURE_DEAL_FAIL_MSG='处理失败'; //处理失败
	
	//‘短信’模块
	const GET_SEND_MOBILE_MESSAGE_SUCCESS=1800;
	const GET_SEND_MOBILE_MESSAGE_SUCCESS_MSG='发送短信验证码成功'; //发送短信验证码成功
	
	const GET_SEND_MOBILE_MESSAGE_FAIL=1801;
	const GET_SEND_MOBILE_MESSAGE_FAIL_MSG='发送短信验证码失败'; //发送短信验证码失败
	
	const GET_NOT_EXISTS_MOBILE_NUMBER_BANG=1802;
	const GET_NOT_EXISTS_MOBILE_NUMBER_BANG_MSG='该手机号码未绑定过'; //该手机号码未绑定过
}

