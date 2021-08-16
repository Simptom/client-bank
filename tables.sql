CREATE TABLE IF NOT EXISTS `client_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID платежа',
  `money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT 'Сумма',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT 'Результат оплаты',
  `time` int(11) NOT NULL COMMENT 'Время инициализации платежа',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Список платежей на стороне клиента';



CREATE TABLE IF NOT EXISTS `bank_platform` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID площадки',
  `id_user` int(11) NOT NULL DEFAULT '0' COMMENT 'ID пользователя',
  `name` text NOT NULL COMMENT 'Наименование площадки',
  `url` text NOT NULL COMMENT 'URL сайта',
  `url_result` text NOT NULL COMMENT 'URL Result',
  `url_error` text NOT NULL COMMENT 'URL Error',
  `url_success` text NOT NULL COMMENT 'URL Success',
  `open_key` text NOT NULL COMMENT 'Публичный ключ',
  `secret_key` text NOT NULL COMMENT 'Секретный ключ',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT 'Работоспособность площадки',
  `time` int(11) NOT NULL COMMENT 'Время добавления площадки',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Список площадок на стороне банка';
INSERT INTO `bank_platform` (`id`, `id_user`, `name`, `url`, `url_result`, `url_error`, `url_success`, `open_key`, `secret_key`, `status`, `time`) VALUES
(1, 1, 'Test 1', 'https://test1.com', 'https://test1.com/result.php', 'https://test1.com/error.php', 'https://test1.com/success.php', 'mslrYftbPPifCfWMVqxv3H', '9rrLkAtifxHnbSwKBu95XWJt', 1, 1629001871),
(2, 1, 'Test 2', 'https://test2.com', 'https://test2.com/result.php', 'https://test2.com/error.php', 'https://test2.com/success.php', 'SPgeD5uPX5gU1784h9p6JP4', 'MYygHHqI717k9whikNeNpd4UL', 0, 1629001871),
(3, 5, 'Test 3', 'https://test3.com', 'https://test3.com/result.php', 'https://test3.com/error.php', 'https://test3.com/success.php', 'HPegcxGXQWS8kI2T96e4gu', 'M4UxMUQirkxJvaASFCiiZ', 2, 1629001871);


CREATE TABLE IF NOT EXISTS `bank_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID платежа',
  `id_data` int(11) NOT NULL DEFAULT '0' COMMENT 'ID площадки',
  `money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT 'Сумма',
  `time` int(11) NOT NULL COMMENT 'Время инициализации платежа',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Список платежей на стороне банка';