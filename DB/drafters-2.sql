-- phpMyAdmin SQL Dump
-- version 3.4.8
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2018 年 04 月 23 日 21:33
-- 服务器版本: 5.5.28
-- PHP 版本: 5.6.33

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `telegram`
--

-- --------------------------------------------------------

--
-- 表的结构 `drafters`
--

CREATE TABLE IF NOT EXISTS `drafters` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` varchar(255) DEFAULT NULL COMMENT 'telegram uid',
  `activity_id` int(10) DEFAULT NULL COMMENT '活动ID',
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  `content` text COMMENT '内容',
  `chat_bot_id` int(10) NOT NULL,
  `telegram_uid` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_id` (`activity_id`),
  KEY `chat_bot_id` (`chat_bot_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=36 ;

--
-- 转存表中的数据 `drafters`
--

INSERT INTO `drafters` (`id`, `uid`, `activity_id`, `created_at`, `updated_at`, `content`, `chat_bot_id`, `telegram_uid`) VALUES
(1, '123', 17, 1520260616, NULL, '["12312"]', 0, ''),
(2, '131', 17, 1520260773, NULL, '["23123","231231"]', 0, ''),
(3, '13123', 17, 1520260791, NULL, '["1312312","3123","adsad"]', 0, ''),
(4, '2634369', 10, 1520413436, NULL, '["http:\\/\\/daily.zhihu.com\\/story\\/9672607","http:\\/\\/daily.zhihu.com\\/story\\/9672607","http:\\/\\/daily.zhihu.com\\/story\\/9672607"]', 1, ''),
(5, 'fhjdnd', 33, 1521114270, NULL, '["hfhehehdj","nfjfnfnnf"]', 25, '18301388078'),
(6, 'fhjdnd', 33, 1521114276, NULL, '["hfhehehdj","nfjfnfnnf"]', 25, '18301388078'),
(7, 'fhjdnd', 33, 1521114294, NULL, '["hfhehehdj","nfjfnfnnf"]', 25, '18301388078'),
(8, 'fhjdnd', 33, 1521114327, NULL, '["hfhehehdj","nfjfnfnnf"]', 25, 'Cohenkheda'),
(9, '哈哈', 33, 1521114573, NULL, '["http:\\/\\/m.sp.uczzd.cn\\/webview\\/news?app=huawei-iflow&aid=17449980561189390689&cid=100&zzd_from=huawei-iflow&uc_param_str=dndsfrvesvntnwpfgibicpkt&recoid=11300696251848505496&rd_type=reco&sp_gz=1"]', 25, 'Cohenkheda'),
(10, '哈哈', 33, 1521114581, NULL, '["http:\\/\\/m.sp.uczzd.cn\\/webview\\/news?app=huawei-iflow&aid=17449980561189390689&cid=100&zzd_from=huawei-iflow&uc_param_str=dndsfrvesvntnwpfgibicpkt&recoid=11300696251848505496&rd_type=reco&sp_gz=1"]', 25, 'Cohenkheda'),
(11, '梵蒂冈', 33, 1521114688, NULL, '["http:\\/\\/m.sp.uczzd.cn\\/webview\\/news?app=huawei-iflow&aid=17449980561189390689&cid=100&zzd_from=huawei-iflow&uc_param_str=dndsfrvesvntnwpfgibicpkt&recoid=11300696251848505496&rd_type=reco&sp_gz=1",""]', 25, '18301388078'),
(12, 'djdjsj', 33, 1521114827, NULL, '["http:\\/\\/m.sp.uczzd.cn\\/webview\\/news?app=huawei-iflow&aid=17449980561189390689&cid=100&zzd_from=huawei-iflow&uc_param_str=dndsfrvesvntnwpfgibicpkt&recoid=11300696251848505496&rd_type=reco&sp_gz=1"]', 25, 'Cohen kheda'),
(13, 'djdjsj', 33, 1521114836, NULL, '["http:\\/\\/m.sp.uczzd.cn\\/webview\\/news?app=huawei-iflow&aid=17449980561189390689&cid=100&zzd_from=huawei-iflow&uc_param_str=dndsfrvesvntnwpfgibicpkt&recoid=11300696251848505496&rd_type=reco&sp_gz=1"]', 25, 'Cohen kheda'),
(14, 'djdjsj', 33, 1521114980, NULL, '["http:\\/\\/m.sp.uczzd.cn\\/webview\\/news?app=huawei-iflow&aid=17449980561189390689&cid=100&zzd_from=huawei-iflow&uc_param_str=dndsfrvesvntnwpfgibicpkt&recoid=11300696251848505496&rd_type=reco&sp_gz=1"]', 25, 'Cohen kheda'),
(15, 'djdjsj', 33, 1521114983, NULL, '["http:\\/\\/m.sp.uczzd.cn\\/webview\\/news?app=huawei-iflow&aid=17449980561189390689&cid=100&zzd_from=huawei-iflow&uc_param_str=dndsfrvesvntnwpfgibicpkt&recoid=11300696251848505496&rd_type=reco&sp_gz=1"]', 25, 'Cohen kheda'),
(16, 'djdjsj', 33, 1521114994, NULL, '["http:\\/\\/m.sp.uczzd.cn\\/webview\\/news?app=huawei-iflow&aid=17449980561189390689&cid=100&zzd_from=huawei-iflow&uc_param_str=dndsfrvesvntnwpfgibicpkt&recoid=11300696251848505496&rd_type=reco&sp_gz=1"]', 25, 'Cohen Kheda'),
(17, 'djdjsj', 33, 1521115015, NULL, '["http:\\/\\/m.sp.uczzd.cn\\/webview\\/news?app=huawei-iflow&aid=17449980561189390689&cid=100&zzd_from=huawei-iflow&uc_param_str=dndsfrvesvntnwpfgibicpkt&recoid=11300696251848505496&rd_type=reco&sp_gz=1"]', 25, 'Cohen Kheda'),
(18, 'djdjsj', 33, 1521115028, NULL, '["http:\\/\\/m.sp.uczzd.cn\\/webview\\/news?app=huawei-iflow&aid=17449980561189390689&cid=100&zzd_from=huawei-iflow&uc_param_str=dndsfrvesvntnwpfgibicpkt&recoid=11300696251848505496&rd_type=reco&sp_gz=1"]', 25, 'Cohen Kheda'),
(19, 'djdjsj', 33, 1521115105, NULL, '["http:\\/\\/m.sp.uczzd.cn\\/webview\\/news?app=huawei-iflow&aid=17449980561189390689&cid=100&zzd_from=huawei-iflow&uc_param_str=dndsfrvesvntnwpfgibicpkt&recoid=11300696251848505496&rd_type=reco&sp_gz=1"]', 25, 'Cohen Kheda'),
(20, '加入', 33, 1521115215, NULL, '["http:\\/\\/m.name-technology.fun\\/Index\\/code\\/775fbbe9be2f2819"]', 25, 'Cohen Kheda'),
(21, '加入', 33, 1521115225, NULL, '["http:\\/\\/m.name-technology.fun\\/Index\\/code\\/775fbbe9be2f2819"]', 25, 'Cohen Kheda'),
(22, '加入', 33, 1521115240, NULL, '["http:\\/\\/m.name-technology.fun\\/Index\\/code\\/775fbbe9be2f2819"]', 25, 'Cohen Kheda'),
(23, '咯', 33, 1521115457, NULL, '["http:\\/\\/m.name-technology.fun\\/Index\\/code\\/775fbbe9be2f2819"]', 25, 'Cohen Kheda'),
(24, '咯', 33, 1521115513, NULL, '["http:\\/\\/m.name-technology.fun\\/Index\\/code\\/775fbbe9be2f2819"]', 25, 'Cohen Kheda'),
(25, '咯', 33, 1521115557, NULL, '["http:\\/\\/m.name-technology.fun\\/Index\\/code\\/775fbbe9be2f2819"]', 25, '18301388078'),
(26, '啦啦啦啦', 33, 1521115704, NULL, '["www.baidu.com"]', 25, 'CharlieChoi'),
(27, '123', 33, 1521115850, NULL, '[""]', 25, '2644441'),
(28, '3123', 33, 1521119347, NULL, '["3123123"]', 25, '1231'),
(29, '12312', 33, 1521119708, NULL, '["3123123"]', 25, '123'),
(30, 'dhhd', 33, 1521167239, NULL, '["www.baidu.com"]', 25, 'Cohen Kheda'),
(31, 'fhfh', 33, 1521167330, NULL, '["www.baidu.com"]', 25, 'Cohen Kheda'),
(32, 'fhfh', 33, 1521167664, NULL, '["www.baidu.com"]', 25, 'Cohen Kheda'),
(33, 'fhfh', 33, 1521167872, NULL, '["www.baidu.com"]', 25, 'Cohen Kheda'),
(34, 'fhfh', 33, 1521168126, NULL, '["www.baidu.com"]', 25, 'Cohen Kheda'),
(35, 'fhfh', 33, 1521168225, NULL, '["www.baidu.com"]', 25, 'Cohen Kheda');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
