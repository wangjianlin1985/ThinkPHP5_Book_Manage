/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50051
Source Host           : localhost:3306
Source Database       : php_db

Target Server Type    : MYSQL
Target Server Version : 50051
File Encoding         : 65001

Date: 2018-10-10 21:53:41
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `t_admin`
-- ----------------------------
DROP TABLE IF EXISTS `t_admin`;
CREATE TABLE `t_admin` (
  `username` varchar(20) NOT NULL default '',
  `password` varchar(32) default NULL,
  PRIMARY KEY  (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_admin
-- ----------------------------
INSERT INTO `t_admin` VALUES ('a', 'a');

-- ----------------------------
-- Table structure for `t_book`
-- ----------------------------
DROP TABLE IF EXISTS `t_book`;
CREATE TABLE `t_book` (
  `barcode` varchar(20) NOT NULL COMMENT 'barcode',
  `bookName` varchar(20) NOT NULL COMMENT '图书名称',
  `bookTypeObj` int(11) NOT NULL COMMENT '图书所在类别',
  `price` float NOT NULL COMMENT '图书价格',
  `count` int(11) NOT NULL COMMENT '库存',
  `publishDate` varchar(20) default NULL COMMENT '出版日期',
  `publish` varchar(20) default NULL COMMENT '出版社',
  `bookPhoto` varchar(60) NOT NULL COMMENT '图书图片',
  `bookDesc` varchar(5000) default NULL COMMENT '图书简介',
  `bookFile` varchar(60) NOT NULL COMMENT '图书文件',
  PRIMARY KEY  (`barcode`),
  KEY `bookTypeObj` (`bookTypeObj`),
  CONSTRAINT `t_book_ibfk_1` FOREIGN KEY (`bookTypeObj`) REFERENCES `t_booktype` (`bookTypeId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_book
-- ----------------------------
INSERT INTO `t_book` VALUES ('TS001', 'php网站开发', '2', '35', '12', '2018-10-03', '四川大学出版社', 'upload/9c1d6791f2b8055bafa1d06b4997ef12.jpg', '<p>好书一本啊啊！</p>', 'upload/ed29a6f71711ce5a1ecb0a9e3db61414.jpg');
INSERT INTO `t_book` VALUES ('TS002', '安卓程序开发', '1', '42', '30', '2018-10-05', '人民教育出版社', 'upload/NoImage.jpg', '<p>啊啊啊啊<br/></p>', 'upload/d48b5204b1837a229fcac5ba65f07394.jpg');
INSERT INTO `t_book` VALUES ('TS003', '中国近代史', '1', '43', '12', '2018-10-07', '人民教育出版社', 'upload/NoImage.jpg', '<p>啊啊啊啊啊啊啊啊</p>', '');
INSERT INTO `t_book` VALUES ('TS004', 'html5网站开发11', '1', '38', '12', '2018-10-05', '人民教育出版社', 'upload/77c416333c7c04e28f6f78baef7ec5dd.jpg', '<p>测试图书</p>', '');
INSERT INTO `t_book` VALUES ('TS005', '中国古代史', '2', '39', '10', '2018-10-01', '人民教育出版社', 'upload/ae321289a9603919afa2a42e362d3b25.jpg', '<p>测试历史书籍</p>', '');
INSERT INTO `t_book` VALUES ('TS006', 'html5网站开发', '1', '56.5', '12', '2018-10-02', '电子科技大学出版社', 'upload/e69946b4e475207fb744780cd1e89e50.jpg', '<p>教大家开发响应式网站的书籍</p>', '');

-- ----------------------------
-- Table structure for `t_booktype`
-- ----------------------------
DROP TABLE IF EXISTS `t_booktype`;
CREATE TABLE `t_booktype` (
  `bookTypeId` int(11) NOT NULL auto_increment COMMENT '图书类别',
  `bookTypeName` varchar(18) NOT NULL COMMENT '类别名称',
  `days` int(11) NOT NULL COMMENT '可借阅天数',
  PRIMARY KEY  (`bookTypeId`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_booktype
-- ----------------------------
INSERT INTO `t_booktype` VALUES ('1', '计算机类', '25');
INSERT INTO `t_booktype` VALUES ('2', '历史类', '30');

-- ----------------------------
-- Table structure for `t_loaninfo`
-- ----------------------------
DROP TABLE IF EXISTS `t_loaninfo`;
CREATE TABLE `t_loaninfo` (
  `loadId` int(11) NOT NULL auto_increment COMMENT '借阅编号',
  `book` varchar(20) NOT NULL COMMENT '图书对象',
  `reader` varchar(20) NOT NULL COMMENT '读者对象',
  `borrowDate` varchar(20) default NULL COMMENT '借阅时间',
  `returnDate` varchar(20) default NULL COMMENT '归还时间',
  PRIMARY KEY  (`loadId`),
  KEY `book` (`book`),
  KEY `reader` (`reader`),
  CONSTRAINT `t_loaninfo_ibfk_1` FOREIGN KEY (`book`) REFERENCES `t_book` (`barcode`),
  CONSTRAINT `t_loaninfo_ibfk_2` FOREIGN KEY (`reader`) REFERENCES `t_reader` (`readerNo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_loaninfo
-- ----------------------------
INSERT INTO `t_loaninfo` VALUES ('1', 'TS002', 'DZ001', '2018-10-04 14:49:28', '2018-10-17 14:49:32');
INSERT INTO `t_loaninfo` VALUES ('2', 'TS006', 'DZ002', '2018-10-09 21:42:52', '2018-10-18 21:42:54');

-- ----------------------------
-- Table structure for `t_reader`
-- ----------------------------
DROP TABLE IF EXISTS `t_reader`;
CREATE TABLE `t_reader` (
  `readerNo` varchar(20) NOT NULL COMMENT 'readerNo',
  `readerTypeObj` int(11) NOT NULL COMMENT '读者类型',
  `readerName` varchar(20) NOT NULL COMMENT '姓名',
  `sex` varchar(2) NOT NULL COMMENT '性别',
  `birthday` varchar(20) default NULL COMMENT '读者生日',
  `telephone` varchar(20) default NULL COMMENT '联系电话',
  `email` varchar(50) default NULL COMMENT '联系Email',
  `qq` varchar(20) default NULL COMMENT '登录密码',
  `address` varchar(80) default NULL COMMENT '读者地址',
  `photo` varchar(60) NOT NULL COMMENT '读者头像',
  PRIMARY KEY  (`readerNo`),
  KEY `readerTypeObj` (`readerTypeObj`),
  CONSTRAINT `t_reader_ibfk_1` FOREIGN KEY (`readerTypeObj`) REFERENCES `t_readertype` (`readerTypeId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_reader
-- ----------------------------
INSERT INTO `t_reader` VALUES ('DZ001', '1', '王丽丽', '女', '2018-10-10', '13519834934', 'lili@126.com', '123', '广东深圳', 'upload/2220aabc0e8550128ecc106c62065b6f.jpg');
INSERT INTO `t_reader` VALUES ('DZ002', '2', '王建军', '男', '2018-10-02', '13980835343', 'jianjun@163.com', '123', '四川成都龙潭寺', 'upload/e50ba9b5b75b163d67a90d5f5df58b8b.jpg');

-- ----------------------------
-- Table structure for `t_readertype`
-- ----------------------------
DROP TABLE IF EXISTS `t_readertype`;
CREATE TABLE `t_readertype` (
  `readerTypeId` int(11) NOT NULL auto_increment COMMENT '读者类型编号',
  `readerTypeName` varchar(20) NOT NULL COMMENT '读者类型',
  `number` int(11) NOT NULL COMMENT '可借阅数目',
  PRIMARY KEY  (`readerTypeId`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_readertype
-- ----------------------------
INSERT INTO `t_readertype` VALUES ('1', '学生类', '3');
INSERT INTO `t_readertype` VALUES ('2', '教师类', '5');
