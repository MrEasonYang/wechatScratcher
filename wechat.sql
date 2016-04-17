--
-- 数据库: `wechat`
--

-- --------------------------------------------------------

--
-- 表的结构 `wechat_admin`
--

CREATE TABLE IF NOT EXISTS `wechat_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` char(32) NOT NULL,
  `appid` char(32) NOT NULL,
  `password` char(32) NOT NULL,
  `salt` char(32) NOT NULL,
  `key` char(32) NOT NULL,
  `last_login_time` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`,`appid`,`salt`,`key`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `wechat_app`
--

CREATE TABLE IF NOT EXISTS `wechat_app` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `appid` char(50) NOT NULL,
  `appsecret` char(50) NOT NULL,
  `token` char(50) NOT NULL,
  `encodingaeskey` char(50) NOT NULL,
  `menu` int(1) NOT NULL,
  `username` char(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `appid` (`appid`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `wechat_award`
--

CREATE TABLE IF NOT EXISTS `wechat_award` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `appid` char(50) NOT NULL,
  `name` char(30) NOT NULL,
  `probility` int(3) NOT NULL,
  `amount` int(10) NOT NULL,
  `showimage` char(50) NOT NULL,
  `scratchimage` longtext NOT NULL,
  `username` char(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- 表的结构 `wechat_introduction`
--

CREATE TABLE IF NOT EXISTS `wechat_introduction` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `appid` char(50) NOT NULL,
  `content` text NOT NULL,
  `username` char(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `appid` (`appid`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- 表的结构 `wechat_user`
--

CREATE TABLE IF NOT EXISTS `wechat_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` char(50) NOT NULL,
  `appid` char(50) NOT NULL,
  `award` char(30) NOT NULL,
  `share` int(1) NOT NULL,
  `twice` int(1) NOT NULL,
  `result` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `openid` (`openid`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `wechat_winner`
--

CREATE TABLE IF NOT EXISTS `wechat_winner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` char(50) NOT NULL,
  `appid` char(50) NOT NULL,
  `name` char(20) NOT NULL,
  `tel` char(30) NOT NULL,
  `address` varchar(255) NOT NULL,
  `zipcode` int(6) NOT NULL,
  `receiver` char(20) NOT NULL,
  `award` char(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `openid` (`openid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
