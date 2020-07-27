-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- 主机： localhost:3306
-- 生成日期： 2020-07-27 23:31:25
-- 服务器版本： 5.6.37-log
-- PHP 版本： 7.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `app`
--

-- --------------------------------------------------------

--
-- 表的结构 `boardroom`
--

CREATE TABLE `boardroom` (
  `id` int(6) NOT NULL COMMENT 'id',
  `ybuid` varchar(10) NOT NULL COMMENT '易班用户id',
  `department` varchar(20) NOT NULL COMMENT '申请部门',
  `room` varchar(20) NOT NULL,
  `person` varchar(10) NOT NULL COMMENT '负责人',
  `tel` varchar(11) NOT NULL COMMENT '电话号码',
  `name` varchar(20) NOT NULL COMMENT '活动名称',
  `property` varchar(20) NOT NULL COMMENT '活动性质',
  `date` date NOT NULL COMMENT '日期',
  `time` varchar(20) NOT NULL COMMENT '时间段',
  `apparatus` varchar(20) NOT NULL COMMENT '所需器材',
  `status` int(2) NOT NULL DEFAULT '-1' COMMENT '审核状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `boardroom`
--

INSERT INTO `boardroom` (`id`, `ybuid`, `department`, `room`, `person`, `tel`, `name`, `property`, `date`, `time`, `apparatus`, `status`) VALUES
(33, '1125770', '校友讲座', '2001学生学业辅导讲堂', '易班', '15285151648', '校友讲座', '公益', '2018-04-18', '2:00-4:00', '话筒', 1);

--
-- 转储表的索引
--

--
-- 表的索引 `boardroom`
--
ALTER TABLE `boardroom`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `boardroom`
--
ALTER TABLE `boardroom`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT COMMENT 'id', AUTO_INCREMENT=34;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
