-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Хост: MySQL-8.4
-- Время создания: Июн 18 2025 г., 23:02
-- Версия сервера: 8.4.4
-- Версия PHP: 8.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `voronovs`
--

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `aktīvākais_piegādes_darbinieks`
-- (См. Ниже фактическое представление)
--
CREATE TABLE `aktīvākais_piegādes_darbinieks` (
`Piegazu_skaits` bigint
,`Uzvards` varchar(25)
,`Vards` varchar(25)
);

-- --------------------------------------------------------

--
-- Структура таблицы `Amats`
--

CREATE TABLE `Amats` (
  `id` int NOT NULL,
  `Nosaukums` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `Amats`
--

INSERT INTO `Amats` (`id`, `Nosaukums`) VALUES
(3, 'Gramatvedis'),
(4, 'Noliktavas operators'),
(5, 'Autovaditajs'),
(12, 'Direktors');

-- --------------------------------------------------------

--
-- Структура таблицы `Darbinieks`
--

CREATE TABLE `Darbinieks` (
  `id` int NOT NULL,
  `Vards` varchar(25) DEFAULT NULL,
  `Uzvards` varchar(25) DEFAULT NULL,
  `Amats_ID` int DEFAULT NULL,
  `Talrunis` int DEFAULT NULL,
  `Lietotajs` varchar(25) DEFAULT NULL,
  `Parole` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `Darbinieks`
--

INSERT INTO `Darbinieks` (`id`, `Vards`, `Uzvards`, `Amats_ID`, `Talrunis`, `Lietotajs`, `Parole`) VALUES
(1, 'Janis', 'Berzins', 4, 123456789, 'Janis', '1234'),
(3, 'Marta', 'Liepina', 3, 567890123, 'UDENS', 'udens'),
(4, 'Oskars', 'Kalnins', 4, 234567890, 'OSKARS2043', 'udens'),
(5, 'Elina', 'Silina', 5, 345678901, 'FG', 'JJ'),
(6, 'ruslans', 'voronovs', 12, 222222224, 'moon2005', 'uuuud'),
(9, 'rujsaaaa', 'voronovs', 3, 28014173, 'ggigigii', '2424');

-- --------------------------------------------------------

--
-- Структура таблицы `Klients`
--

CREATE TABLE `Klients` (
  `id` int NOT NULL,
  `Uznenuma_nosaukums` varchar(25) DEFAULT NULL,
  `Adrese` varchar(50) DEFAULT NULL,
  `Talrunis` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `Klients`
--

INSERT INTO `Klients` (`id`, `Uznenuma_nosaukums`, `Adrese`, `Talrunis`) VALUES
(1, 'AquaLux', 'Udens iela 102', 4343434),
(3, 'H2O Pro', 'Ezera iela 5', 777888999),
(4, 'Kristals', 'Ledus iela 15', 123123123),
(5, 'Dziva Straume', 'Avota iela 20', 456456456);

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `kopējā_piegādes_summa`
-- (См. Ниже фактическое представление)
--
CREATE TABLE `kopējā_piegādes_summa` (
`Kopēja_summa` double
);

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `maksimālā_un_minimālā_piegādes_summa`
-- (См. Ниже фактическое представление)
--
CREATE TABLE `maksimālā_un_minimālā_piegādes_summa` (
`Maksimalā_summa` float
,`Minimalā_summa` float
);

-- --------------------------------------------------------

--
-- Структура таблицы `Piegade`
--

CREATE TABLE `Piegade` (
  `id` int NOT NULL,
  `Piegades_datums` date DEFAULT NULL,
  `Tvertne_ID` int DEFAULT NULL,
  `Piegade_summa` float DEFAULT NULL,
  `Klients_ID` int DEFAULT NULL,
  `Piegades_darbinieks_ID` int DEFAULT NULL,
  `Daudzums_tvertni` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `Piegade`
--

INSERT INTO `Piegade` (`id`, `Piegades_datums`, `Tvertne_ID`, `Piegade_summa`, `Klients_ID`, `Piegades_darbinieks_ID`, `Daudzums_tvertni`) VALUES
(1, '2024-01-01', 5, 100, 5, 1, 10),
(2, '2024-02-02', 4, 50, 1, 3, 5),
(3, '2024-03-01', 4, 60, 3, 1, 6),
(4, '2024-04-03', 4, 90, 4, 3, 9),
(5, '2024-01-01', 1, 150, 3, 9, 10),
(6, '2024-06-03', 1, 50, 1, 9, 5),
(7, '2024-07-04', 1, 70, 3, 5, 7),
(8, '2024-08-01', 5, 60, 4, 3, 6),
(9, '2024-09-02', 4, 80, 5, 1, 8),
(14, '2025-06-30', 1, 80, 1, 1, 14);

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `piegāžu_skaits_pa_mēnešiem`
-- (См. Ниже фактическое представление)
--
CREATE TABLE `piegāžu_skaits_pa_mēnešiem` (
`Menesis` varchar(7)
,`Piegazu_skaits` bigint
);

-- --------------------------------------------------------

--
-- Структура таблицы `Tvertne`
--

CREATE TABLE `Tvertne` (
  `id` int NOT NULL,
  `Nosaukums` varchar(50) DEFAULT NULL,
  `UdensApjoms_L` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `Tvertne`
--

INSERT INTO `Tvertne` (`id`, `Nosaukums`, `UdensApjoms_L`) VALUES
(1, 'Plastmasas tvertnee', 10),
(4, 'Liela plastmasas tvertne', 15),
(5, 'Loti liela plastmas tvertne', 20);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `Amats`
--
ALTER TABLE `Amats`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `Darbinieks`
--
ALTER TABLE `Darbinieks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Amats_ID` (`Amats_ID`);

--
-- Индексы таблицы `Klients`
--
ALTER TABLE `Klients`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `Piegade`
--
ALTER TABLE `Piegade`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Tvertne_ID` (`Tvertne_ID`),
  ADD KEY `Piegadatajs_ID` (`Klients_ID`),
  ADD KEY `Pasutijuma_sanemejs_ID` (`Piegades_darbinieks_ID`);

--
-- Индексы таблицы `Tvertne`
--
ALTER TABLE `Tvertne`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `Amats`
--
ALTER TABLE `Amats`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `Darbinieks`
--
ALTER TABLE `Darbinieks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT для таблицы `Klients`
--
ALTER TABLE `Klients`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `Piegade`
--
ALTER TABLE `Piegade`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `Tvertne`
--
ALTER TABLE `Tvertne`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

-- --------------------------------------------------------

--
-- Структура для представления `aktīvākais_piegādes_darbinieks`
--
DROP TABLE IF EXISTS `aktīvākais_piegādes_darbinieks`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `aktīvākais_piegādes_darbinieks`  AS SELECT `D`.`Vards` AS `Vards`, `D`.`Uzvards` AS `Uzvards`, count(`P`.`id`) AS `Piegazu_skaits` FROM (`piegade` `P` join `darbinieks` `D` on((`P`.`Piegades_darbinieks_ID` = `D`.`id`))) GROUP BY `D`.`id` ORDER BY `Piegazu_skaits` DESC LIMIT 0, 1 ;

-- --------------------------------------------------------

--
-- Структура для представления `kopējā_piegādes_summa`
--
DROP TABLE IF EXISTS `kopējā_piegādes_summa`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `kopējā_piegādes_summa`  AS SELECT sum(`piegade`.`Piegade_summa`) AS `Kopēja_summa` FROM `piegade` ;

-- --------------------------------------------------------

--
-- Структура для представления `maksimālā_un_minimālā_piegādes_summa`
--
DROP TABLE IF EXISTS `maksimālā_un_minimālā_piegādes_summa`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `maksimālā_un_minimālā_piegādes_summa`  AS SELECT max(`piegade`.`Piegade_summa`) AS `Maksimalā_summa`, min(`piegade`.`Piegade_summa`) AS `Minimalā_summa` FROM `piegade` ;

-- --------------------------------------------------------

--
-- Структура для представления `piegāžu_skaits_pa_mēnešiem`
--
DROP TABLE IF EXISTS `piegāžu_skaits_pa_mēnešiem`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `piegāžu_skaits_pa_mēnešiem`  AS SELECT date_format(`piegade`.`Piegades_datums`,'%Y-%m') AS `Menesis`, count(0) AS `Piegazu_skaits` FROM `piegade` GROUP BY `Menesis` ORDER BY `Menesis` ASC ;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `Darbinieks`
--
ALTER TABLE `Darbinieks`
  ADD CONSTRAINT `FK_darbinieks_amats` FOREIGN KEY (`Amats_ID`) REFERENCES `Amats` (`id`);

--
-- Ограничения внешнего ключа таблицы `Piegade`
--
ALTER TABLE `Piegade`
  ADD CONSTRAINT `FK_piegade_piegadatajs` FOREIGN KEY (`Klients_ID`) REFERENCES `Klients` (`id`),
  ADD CONSTRAINT `FK_piegade_tvertne` FOREIGN KEY (`Tvertne_ID`) REFERENCES `Tvertne` (`id`),
  ADD CONSTRAINT `Piegade_ibfk_1` FOREIGN KEY (`Piegades_darbinieks_ID`) REFERENCES `Darbinieks` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
