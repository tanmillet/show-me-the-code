<?php
/*
 * 文件保存地址 /upload/{$dir_id}/{$dir}/{$filename}
 * */
class Config_Upload{
	public static $conf = array(
		"www.3595.com" => array( //来路域名
			"dir_id" => 1, //每个来路域名都有不同的id
			"type" => array( //图片类型
				"news_thumb" => array(
					"dir" => "news", //目录名
					"type" => "image", //文件类型
					"max_size" => "500k", //大小限制
					"allow_ext" => array("jpg","jpeg","png","gif"), //后缀名限制
				),
				"ad_img" => array(
					"dir" => "ad",
					"type" => "image",
					"max_size" => "1024k",
					"allow_ext" => array("jpg","jpeg","png","gif"),
				),
				"game_pic" => array(
					"dir" => "game",
					"type" => "image",
					"max_size" => "1024k",
					"allow_ext" => array("jpg","jpeg","png","gif"),
				),
				"friend_pic" => array(
					"dir"=>"flink",
					"type" => "image",
					"max_size" => "50k",
					"allow_ext" =>array("jpg","jpeg","png","gif"),
				),
				"video_img" => array(
					"dir"=>"video",
					"type" => "image",
					"max_size" => "100k",
					"allow_ext" =>array("jpg","jpeg","png","gif"),
				),
			),
		),

		"test3595.3595.com" => array(
			"dir_id" => 2,
			"type" => array(
				"news_thumb" => array(
					"dir" => "news",
					"type" => "image",
					"max_size" => "500k",
					"allow_ext" => array("jpg","jpeg","png","gif"),
				),
				"ad_img" => array(
					"dir" => "ad",
					"type" => "image",
					"max_size" => "1024k",
					"allow_ext" => array("jpg","jpeg","png","gif"),
				),
				"game_pic" => array(
					"dir" => "game",
					"type" => "image",
					"max_size" => "1024k",
					"allow_ext" => array("jpg","jpeg","png","gif"),
				),
				"friend_pic" => array(
					"dir"=>"flink",
					"type" => "image",
					"max_size" => "50k",
					"allow_ext" =>array("jpg","jpeg","png","gif"),
				),
				"video_img" => array(
					"dir"=>"video",
					"type" => "image",
					"max_size" => "100k",
					"allow_ext" =>array("jpg","jpeg","png","gif"),
				),
			),
		),
		"www.78youxi.com" => array( //来路域名
			"dir_id" => 3, //每个来路域名都有不同的id
			"type" => array( //图片类型
				"news_thumb" => array(
					"dir" => "news", //目录名
					"type" => "image", //文件类型
					"max_size" => "500k", //大小限制
					"allow_ext" => array("jpg","jpeg","png","gif"), //后缀名限制
				),
				"ad_img" => array(
					"dir" => "ad",
					"type" => "image",
					"max_size" => "1024k",
					"allow_ext" => array("jpg","jpeg","png","gif"),
				),
				"game_pic" => array(
					"dir" => "game",
					"type" => "image",
					"max_size" => "1024k",
					"allow_ext" => array("jpg","jpeg","png","gif"),
				),
				"friend_pic" => array(
					"dir"=>"flink",
					"type" => "image",
					"max_size" => "50k",
					"allow_ext" =>array("jpg","jpeg","png","gif"),
				),
				"video_img" => array(
					"dir"=>"video",
					"type" => "image",
					"max_size" => "100k",
					"allow_ext" =>array("jpg","jpeg","png","gif"),
				),
			),
		),
		"www.96youxi.com" => array( //来路域名
			"dir_id" => 4, //每个来路域名都有不同的id
			"type" => array( //图片类型
				"news_thumb" => array(
					"dir" => "news", //目录名
					"type" => "image", //文件类型
					"max_size" => "500k", //大小限制
					"allow_ext" => array("jpg","jpeg","png","gif"), //后缀名限制
				),
				"ad_img" => array(
					"dir" => "ad",
					"type" => "image",
					"max_size" => "1024k",
					"allow_ext" => array("jpg","jpeg","png","gif"),
				),
				"game_pic" => array(
					"dir" => "game",
					"type" => "image",
					"max_size" => "1024k",
					"allow_ext" => array("jpg","jpeg","png","gif"),
				),
				"friend_pic" => array(
					"dir"=>"flink",
					"type" => "image",
					"max_size" => "50k",
					"allow_ext" =>array("jpg","jpeg","png","gif"),
				),
				"video_img" => array(
					"dir"=>"video",
					"type" => "image",
					"max_size" => "100k",
					"allow_ext" =>array("jpg","jpeg","png","gif"),
				),
			),
		),
		"xuqiu.3595.com" => array( //来路域名
			"dir_id" => 5, //每个来路域名都有不同的id
			"type" => array( //图片类型
				"attachment" => array(
					"dir" => "demand", //目录名
					"type" => "file", //文件类型
					"max_size" => "2048K", //大小限制
					"allow_ext" => array("doc","txt","xls","rar","zip","jpg","jpeg","png","gif"), //后缀名限制
				),
			),
		),
		"www.tangsan.com" => array( //来路域名
			"dir_id" => 6, //每个来路域名都有不同的id
			"type" => array( //图片类型
				"news_thumb" => array(
					"dir" => "news", //目录名
					"type" => "image", //文件类型
					"max_size" => "500k", //大小限制
					"allow_ext" => array("jpg","jpeg","png","gif"), //后缀名限制
				),
				"ad_img" => array(
					"dir" => "ad",
					"type" => "image",
					"max_size" => "1024k",
					"allow_ext" => array("jpg","jpeg","png","gif"),
				),
				"game_pic" => array(
					"dir" => "game",
					"type" => "image",
					"max_size" => "1024k",
					"allow_ext" => array("jpg","jpeg","png","gif"),
				),
				"friend_pic" => array(
					"dir"=>"flink",
					"type" => "image",
					"max_size" => "50k",
					"allow_ext" =>array("jpg","jpeg","png","gif"),
				),
				"video_img" => array(
					"dir"=>"video",
					"type" => "image",
					"max_size" => "100k",
					"allow_ext" =>array("jpg","jpeg","png","gif"),
				),
			),
		),
	);
}