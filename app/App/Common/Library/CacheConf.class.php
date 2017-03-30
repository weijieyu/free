<?php
namespace Common\Library;


/**
 * 缓存键 类常量
 */
class CacheConf{
	 
	//前缀
	const PREFIX='qgzs_';
	
	
	/****************************************************************************/
	/*								API缓存  								  */
	/***************************************************************************/
	
	//---------------------用户模块-------------------------
	const USER_KEY='user_';   //用户主表缓存          + user_id  /*已处理*/
	const USER_TTL=300;   //缓存时间
	
	const USERSTAT_KEY='user_stat_';   //用户计数表缓存        + user_id  /*已处理*/
	const USERSTAT_TTL=300;    //缓存时间
	
	const USERCONF_KEY='user_config_';   //用户配置表缓存    /*已处理*/
	const USERCONF_TTL=0;    //缓存时间,永久
	
	const DEVICECONF_KEY='device_config_';   //设备配置表缓存  /*已处理*/
	const DEVICECONF_TTL=1800;    //缓存时间
	
	const USER_TAGS_KEY='user_tags_';  //新版根据用户传递的shops，返回tag /*已处理*/
	const USER_TAGS_TTL=300; //将来改成永久缓存：0
	
	const DEVICE_KEY='device_';   //设备配置表缓存 /*已处理*/
	const DEVICE_TTL=300;    //缓存时间
	

	//---------------------预报---------------------------
	const PREDICTIONLIST_KEY='predictionList'; //今日预报总缓存 /*已处理*/
	const PREDICTIONLIST_TTL=300; //缓存时间
	
	const PREDICTIONNOTESLIST_KEY='predictionNotesList_'; //历史预报总缓存 + mktime(12,0,0) 某一天12:0:0的时间戳 /*已处理*/
	const PREDICTIONNOTESLIST_TTL=1800;   //缓存时间
	
	const PREDICTION_KEY='prediction';  //今日预报 /*已处理*/
	const PREDICTION_TTL=1800;  //缓存时间
	
	const PREDICTIONNOTES_KEY='prediction_notes_';  //历史预报    /*已处理*/ + mktime(12,0,0) 某一天12:0:0的时间戳
	const PREDICTIONNOTES_TTL=1800;   //缓存时间
	
	
	//------------------商城模块-----------------------
	const SHOPATTRIBUTE_KEY='shops_attribute'; //商城属性排序  /*已处理*/
	const SHOPATTRIBUTE_TTL=300;
	
	const SHOPS_KEY='shops';  //商城列表 /*已处理*/
	const SHOPS_TTL=1800;  //缓存时间
	
	const SHOPTAGS_KEY='shops_tag';  //商城白名单tag列表 /*已处理*/
	const SHOPTAGS_TTL=1800;  //缓存时间
	
	const SHOPS_ALL_KEY='shops_alls';  //所有商城列表 /*已处理*/
	const SHOPS_ALL_TTL=43200;  //缓存时间   1800->43200
		
	
	//----------------------订阅模块----------------------
	const CHANNEL_AND_SHOPS_KEY='channel_and_shops_'; //指定商城的分类下的生效的商品数据总量 /*已处理*/
	const CHANNEL_AND_SHOPS_TTL=300;
	
	const ACTIVITYROWS_KEY='activity_rows';   // 全部活动Activity /*已处理*/
	const ACTIVITYROWS_TTL=1800;  //缓存时间
	
	const CHANNELS_KEY='channels';   // 分类 /*已处理*/
	const CHANNELS_TTL=86400;   //缓存时间  300->86400
	
	const CHILDCHANNEL_KEY='child_channel';        //二级分类缓存 /*已处理*/
	const CHILDCHANNEL_TTL=86400;    //缓存时间  300->86400
	
	const CHILDCHANNEL_ALL_KEY='child_channel_alls';        //所有二级分类缓存 /*已处理*/
	const CHILDCHANNEL_ALL_TTL=86400;    //缓存时间  300->86400
	
	const HOTWORDS_KEY='hot_search_words'; //Keyword关键词 /*已处理*/
	const HOTWORDS_TTL=7200; //2小时
	
	const USERSUBSCRIBETYPEID_KEY='_subscribe_';    //用户订阅   $userId.‘Subscribe’.$typeId /*已处理*/
	const USERSUBSCRIBETYPEID_TTL=300;   //缓存时间
	
	 
	
	
	//----------------Tag-----------------------
	const TAGS_KEY='tags';  //所有有效的标签 /*已处理*/
	const TAGS_TTL=300;
	
	const SOME_TAGS_KEY='some_tags';  //取指定tagId /*已处理*/
	const SOME_TAGS_TTL=300;
	
	const TAG_INFO_KEY='tag_info';  //某个tag的详细信息  /*已处理*/
	const TAG_INFO_TTL=300;
	
	const TAGLIST_FOR_CHANNEL_KEY ='taglist_for_channel_'; //根据分类进去的tag的缓存 /*已处理*/
	const TAGLIST_FOR_CHANNEL_TTL = 600;
	
	const CHANNELLIST_FOR_TAG_KEY ='channellist_for_tag_'; //根据Tag进去的分类的缓存 /*已处理*/
	const CHANNELLIST_FOR_TAG_TTL = 600;
	
	const PURE_TAGLIST_KEY ='pure_taglist_'; //单纯的通过where条件获取TagList,rx,2015年3月6日 16:25:13 /*已处理*/
	const PURE_TAGLIST_TTL = 3600;
	
	const SEARCH_TAGS_KEY='search_tags';  //所有被设为“搜索”的有效标签 /*已处理*/
	const SEARCH_TAGS_TTL=300;
	
	const BUSINESS_TAGS_KEY='business_tags';  //所有有效的运营标签 /*已处理*/
	const BUSINESS_TAGS_TTL=300;
	
	const BUSINESS_TAGS_MD_KEY='business_tags_mingdan';  //所有的运营标签及运营标签的白名单和黑名单 /*已处理*/
	const BUSINESS_TAGS_MD_TTL=7200;
	
	const LOCATE_TAGS_KEY='locate_tags_';  //新版根据用户传递的shops，返回tag /*已处理*/
	const LOCATE_TAGS_TTL=300;
	
	const TAGS_V3_KEY='tags_v3_';  //新版根据用户传递的shops，返回tag /*已处理*/
	const TAGS_V3_TTL=300;
	
	const ALLTAGS_KEY='all_tages';   //所有标签 /*已处理*/
	const ALLTAGS_TTL=7200;  //缓存时间   300->7200
	
	const SX_TAG_LIST_KEY='sx_tag_list_';   // sx_tag_list_.$type.$content // 筛选 tag 列表 /*已处理*/
	const SX_TAG_LIST_TTL=1200;   //20分钟缓存
	 
	
	//--------------------商品模块-------------------------
	const GOODSITEM_KEY='goods_id_';   //单个商品缓存   + goods_id /*已处理*/
	const GOODSITEM_TTL=1800;//300;  //缓存时间
	
	const GOODS_LIST_KEY='goods_list_';   // goods_list_.$sql  商品列表缓存 /*已处理*/
	const GOODS_LIST_TTL=1800;//300;   //5分钟缓存
	
	const GOODS_TAG_LIST_KEY='goods_tag_list_';    //商品列表缓存 /*已处理*/
	const GOODS_TAG_LIST_TTL=1800;//300;   //5分钟缓存
	
	const GOODS_TAG_LIST_NEW_KEY='goods_tag_list_new_'; /*已处理*/
	const GOODS_TAG_LIST_NEW_TTL=1800;//300;   //5分钟缓存
	
	const GOODS_TAG_COUNT_KEY='goods_tag_count_'; /*已处理*/
	const GOODS_TAG_COUNT_TTL=300;   //5分钟缓存
	
	const ALL_BAOKUAN_ID_LIST_KEY='all_baokuan_id_list';   //所有爆款商品id /*已处理*/
	const ALL_BAOKUAN_ID_LIST_TTL=300;   //5分钟缓存
	
	const USER_ATTENTION_GOODS_TOTAL_KEY='user_attention_goods_total_';   //用户收藏的商品总缓存,rx,2015年3月19日 18:17:35 /*已处理*/
	const USER_ATTENTION_GOODS_TOTAL_TTL=300;   //5分钟缓存
	
	
	//发现模块列表
	const ARTICLE_ATTRIBUTE_KEY='article_attribute_list';   // 文章属性列表 /*已处理*/
	const ARTICLE_ATTRIBUTE_KEY_TTL=3600;   //60分钟缓存
	
	const ARTICLE_CHANNEL_KEY='article_channel_list';   // 文章类别列表 /*已处理*/
	const ARTICLE_CHANNEL_KEY_TTL=3600;   //60分钟缓存
	
	const ARTICLE_GOODS_TAG_KEY='article_goods_tag_list';   // 文章商品标签列表 /*已处理*/
	const ARTICLE_GOODS_TAG_KEY_TTL=3600;   //60分钟缓存
	
	const ARTICLE_NEW_COUNT_KEY='article_new_count_';   // 新增文章数量 /*已处理*/
	const ARTICLE_NEW_COUNT_TTL=300;   //5分钟缓存
	
	const USER_ATTENTION_KEY='user_attention_'; //用户收藏的总缓存 /*已处理*/
	const USER_ATTENTION_TTL=300;   //5分钟
	
	const ARTICLE_ITEM_KEY='article_item_';   // 文章 /*已处理*/
	const ARTICLE_ITEM_KEY_TTL = 300;   //5分钟
	
	const FAXIAN_ARTICLE_INFO_KEY ='faxian_article_info_'; //文章表qg_fx_article 表的缓存 /*已处理*/
	const FAXIAN_ARTICLE_INFO_TTL = 0; //永久缓存
	
	const FAXIAN_ARTICLE_TOTAL_INFO_KEY ='faxian_article_total_info_'; //文章详情总缓存 /*已处理*/
	const FAXIAN_ARTICLE_TOTAL_INFO_TTL = 0; //永久缓存
	
	const FAXIAN_ARTICLE_CHANNEL_KEY ='faxian_article_channel'; //文章分类表qg_fx_channel 表的缓存 /*已处理（未使用）*/
	const FAXIAN_ARTICLE_CHANNEL_TTL = 3600; //60分钟，将来改成永久缓存，等待处理
	
	const FAXIAN_ARTICLE_CHANNEL_LINK_KEY ='faxian_article_channel_link_'; //文章分类和文章Id的对应关系表qg_fx_article_channel_link_ 表的缓存 /*已处理*/
	const FAXIAN_ARTICLE_CHANNEL_LINK_TTL = 3600; //60分钟
	
	const FAXIAN_ARTICLE_LINK_KEY ='faxian_article_link_'; //文章和content的对应关系表qg_fx_article_link_ 表的缓存 /*已处理（未使用）*/
	const FAXIAN_ARTICLE_LINK_TTL = 3600; //60分钟
	
	const FAXIAN_ARTICLE_TABLE_KEY ='faxian_article_tablek_'; //多表联合查询取数据的缓存，任兴，2015年3月16日 11:39:06 /*已处理*/
	const FAXIAN_ARTICLE_TABLE_TTL = 300; //5分钟
	
	//通用设置
	const COMMONS_KEY='coms_common';  //Common表的缓存信息 /*已处理*/
	const COMMONS_TTL=1800;  //缓存时间
	
	const COMMON_SETTING_KEY='common_setting_';   //通用设置相关 /*已处理*/
	const COMMON_SETTING_TTL=0;  //缓存时间
	
	
	//积分任务模块
	const SCORERULES_KEY='score_rules';   //  积分规则 /*已处理*/
	const SCORERULES_TTL=300;   //缓存时间
	
    const TASKS_KEY='tasks';   //任务模板 /*已处理*/
	const TASKS_TTL=300;  //缓存时间
    
    const GIFTS_KEY='gifts';   //兑换礼品列表 /*已处理*/
    const GIFTS_TTL=3600;   //缓存时间

    
    //短信验证码
    const MOBILE_MESSAGE_CODE_KEY='message_';   // message_手机号码 /*已处理*/
    const MOBILE_MESSAGE_CODE_TTL=600;   //10分钟缓存
    
    
    //首页 商品数量统计
    const HOME_INDEX_GOODS_COUNT_KEY='home_index_goods_count'; /*已处理*/
    const HOME_INDEX_GOODS_COUNT_TTL=300;
    
    
    //统计所需 /index.php/Mobile/AppFunc/statisGoods
    const STATIS_GOODS_COUNT_KEY='statis_goods_count_for_app';   // /*已处理*/
    const STATIS_GOODS_COUNT_TTL=3600;   //1小时缓存
    
    
    
    
    /****************************************************************************/
    /*								管理后台缓存								  */
    /***************************************************************************/
    /*任兴添加的后台的缓存,2015年02月06日11:42:19*/
    
    //后台分类
    const ADMIN_CHANNELS_NAME='admin_channels_name_';  /*已处理*/
    const ADMIN_CHANNELS_TIME=86400;   //1天缓存
    
    //后台商城
    const ADMIN_SHOPS_NAME='admin_shops_name_'; /*已处理*/
    const ADMIN_SHOPS_TIME=86400;   //1天缓存
    
    //后台活动
    const ADMIN_ACTIVITIES_NAME='admin_activities_name_'; /*已处理*/
    const ADMIN_ACTIVITIES_TIME=86400;   //1天缓存
    
    //后台标签Tag
    const ADMIN_TAGS_NAME='admin_tags_name_'; /*已处理*/
    const ADMIN_TAGS_TIME=86400;
    
    //后台爆款
    const ADMIN_GOODS_BAOKUAN_NAME='admin_goods_baokuan_name_'; /*已处理*/
    const ADMIN_GOODS_BAOKUAN_TIME=86400;   //1天缓存
    
    //标签系统
    const ADMIN_BQXT_NAME='admin_bqxt_name_'; /*已处理*/
    const ADMIN_BQXT_TIME=86400;   //1天缓存
    
    //商品是否被列入“不喜欢”和“喜欢”名单
    const ADMIN_GOODS_DISLIKE_NAME='admin_goods_dislike_name_'; /*已处理*/
    const ADMIN_GOODS_DISLIKE_TIME=86400;   //1天缓存
    
    //后台用户信息
    const ADMIN_USERS_NAME='admin_users_name_'; /*已处理*/
    const ADMIN_USERS_TIME=86400;   //1天缓存
    
    //后台管理员信息
    const ADMIN_MANAGERS_NAME='admin_managers_name_'; /*已处理*/
    const ADMIN_MANAGERS_TIME=86400;   //1天缓存
    
}