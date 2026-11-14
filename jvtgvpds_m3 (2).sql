-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Хост: 192.168.10.251
-- Время создания: Ноя 12 2026 г., 14:42
-- Версия сервера: 8.0.41-0ubuntu0.22.04.1
-- Версия PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `jvtgvpds_m3`
--

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `ID` int NOT NULL,
  `Idsmena` int NOT NULL,
  `iduser` int DEFAULT NULL,
  `Status` varchar(50) NOT NULL DEFAULT 'Принят',
  `note` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`ID`, `Idsmena`, `iduser`, `Status`, `note`) VALUES
(2, 1, 13, 'Готовится', ''),
(3, 1, 14, 'Готовится', ''),
(4, 1, 13, 'Готово', 'все работает, время 3:09'),
(5, 1, 15, 'Готово', ''),
(6, 1, 13, 'Готово', ''),
(7, 19, NULL, 'Принят', NULL),
(8, 19, NULL, 'Принят', NULL),
(9, 19, NULL, 'Принят', NULL),
(10, 19, NULL, 'Принят', NULL),
(11, 19, NULL, 'Принят', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `PosInOrder`
--

CREATE TABLE `PosInOrder` (
  `ID` int NOT NULL,
  `IDorder` int NOT NULL,
  `IDeat` int NOT NULL,
  `Count` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `PosInOrder`
--

INSERT INTO `PosInOrder` (`ID`, `IDorder`, `IDeat`, `Count`) VALUES
(1, 2, 3, 2),
(2, 2, 5, 1),
(3, 3, 1, 1),
(4, 4, 2, 3),
(5, 5, 3, 3),
(6, 6, 1, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `pricemenu`
--

CREATE TABLE `pricemenu` (
  `ID` int NOT NULL,
  `Name` varchar(200) NOT NULL,
  `Price` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `pricemenu`
--

INSERT INTO `pricemenu` (`ID`, `Name`, `Price`) VALUES
(1, 'кофе', 300),
(2, 'омлет', 600),
(3, 'мороженое', 1000),
(4, 'чай', 200),
(5, 'лазанья', 1500);

-- --------------------------------------------------------

--
-- Структура таблицы `role`
--

CREATE TABLE `role` (
  `ID` int NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `role`
--

INSERT INTO `role` (`ID`, `role`) VALUES
(1, 'Админ'),
(2, 'Повар'),
(3, 'Официант');

-- --------------------------------------------------------

--
-- Структура таблицы `smeni`
--

CREATE TABLE `smeni` (
  `ID` int NOT NULL,
  `start_smena` datetime NOT NULL,
  `end_smena` datetime NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `smeni`
--

INSERT INTO `smeni` (`ID`, `start_smena`, `end_smena`, `active`, `update_at`, `created_at`) VALUES
(1, '2026-11-02 11:03:13', '2026-11-02 11:03:13', 1, '2026-11-02 11:03:32', '2026-11-02 11:03:32'),
(2, '2026-11-09 06:04:23', '2026-11-09 06:04:23', 0, '2026-11-09 06:04:24', '2026-11-09 06:04:24'),
(18, '2023-02-22 22:22:00', '2028-03-31 03:03:00', 0, '2026-11-11 07:36:07', '2026-11-11 07:36:07'),
(19, '2026-11-12 15:37:00', '2026-11-13 15:37:00', 0, '2026-11-12 11:47:54', '2026-11-12 11:47:54');

-- --------------------------------------------------------

--
-- Структура таблицы `Users`
--

CREATE TABLE `Users` (
  `ID` int NOT NULL,
  `login` varchar(120) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `Users`
--

INSERT INTO `Users` (`ID`, `login`, `password`, `role`) VALUES
(12, 'delondoff', '321321', 1),
(13, 'cook', '321321', 2),
(14, 'offic', '321321', 3),
(15, 'griz', '321321', 2),
(16, '1', '1', 2),
(17, '43243', '323232', 2);

-- --------------------------------------------------------

--
-- Структура таблицы `UsersOnSmena`
--

CREATE TABLE `UsersOnSmena` (
  `ID` int NOT NULL,
  `IDsmena` int NOT NULL,
  `IDuser` int NOT NULL,
  `Update_at` timestamp NOT NULL,
  `Created_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `UsersOnSmena`
--

INSERT INTO `UsersOnSmena` (`ID`, `IDsmena`, `IDuser`, `Update_at`, `Created_at`) VALUES
(16, 1, 13, '2026-11-09 07:14:03', '2026-11-09 07:14:03'),
(17, 1, 13, '2026-11-09 07:14:05', '2026-11-09 07:14:05'),
(18, 1, 13, '2026-11-09 07:37:56', '2026-11-09 07:37:56'),
(19, 1, 12, '2026-11-09 07:40:43', '2026-11-09 07:40:43'),
(20, 1, 12, '2026-11-09 07:43:08', '2026-11-09 07:43:08'),
(21, 2, 13, '2026-11-09 07:43:18', '2026-11-09 07:43:18'),
(22, 1, 14, '2026-11-09 07:47:43', '2026-11-09 07:47:43'),
(23, 2, 12, '2026-11-09 07:48:02', '2026-11-09 07:48:02'),
(24, 2, 12, '2026-11-09 07:48:26', '2026-11-09 07:48:26'),
(25, 2, 13, '2026-11-09 07:48:30', '2026-11-09 07:48:30'),
(26, 2, 15, '2026-11-09 08:03:35', '2026-11-09 08:03:35'),
(27, 1, 13, '2026-11-09 08:08:34', '2026-11-09 08:08:34'),
(28, 1, 14, '2026-11-09 08:09:34', '2026-11-09 08:09:34'),
(29, 1, 12, '2026-11-09 08:19:41', '2026-11-09 08:19:41'),
(30, 1, 13, '2026-11-09 08:20:22', '2026-11-09 08:20:22'),
(31, 1, 13, '2026-11-09 08:20:31', '2026-11-09 08:20:31'),
(32, 1, 12, '2026-11-09 08:20:39', '2026-11-09 08:20:39'),
(33, 2, 13, '2026-11-09 08:20:49', '2026-11-09 08:20:49'),
(34, 1, 16, '2026-11-09 08:20:53', '2026-11-09 08:20:53'),
(35, 2, 17, '2026-11-09 08:30:14', '2026-11-09 08:30:14'),
(36, 18, 12, '2026-11-11 07:36:15', '2026-11-11 07:36:15');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `idsmena` (`Idsmena`),
  ADD KEY `idusera` (`iduser`);

--
-- Индексы таблицы `PosInOrder`
--
ALTER TABLE `PosInOrder`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `posorder` (`IDeat`),
  ADD KEY `IDorder` (`IDorder`);

--
-- Индексы таблицы `pricemenu`
--
ALTER TABLE `pricemenu`
  ADD PRIMARY KEY (`ID`);

--
-- Индексы таблицы `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`ID`);

--
-- Индексы таблицы `smeni`
--
ALTER TABLE `smeni`
  ADD PRIMARY KEY (`ID`);

--
-- Индексы таблицы `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `rolenum` (`role`);

--
-- Индексы таблицы `UsersOnSmena`
--
ALTER TABLE `UsersOnSmena`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `useronsmena` (`IDuser`),
  ADD KEY `smena` (`IDsmena`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `PosInOrder`
--
ALTER TABLE `PosInOrder`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `pricemenu`
--
ALTER TABLE `pricemenu`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `role`
--
ALTER TABLE `role`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `smeni`
--
ALTER TABLE `smeni`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT для таблицы `Users`
--
ALTER TABLE `Users`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT для таблицы `UsersOnSmena`
--
ALTER TABLE `UsersOnSmena`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `idsmena` FOREIGN KEY (`Idsmena`) REFERENCES `smeni` (`ID`),
  ADD CONSTRAINT `idusera` FOREIGN KEY (`iduser`) REFERENCES `Users` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `PosInOrder`
--
ALTER TABLE `PosInOrder`
  ADD CONSTRAINT `IDorder` FOREIGN KEY (`IDorder`) REFERENCES `orders` (`ID`),
  ADD CONSTRAINT `posorder` FOREIGN KEY (`IDeat`) REFERENCES `pricemenu` (`ID`);

--
-- Ограничения внешнего ключа таблицы `Users`
--
ALTER TABLE `Users`
  ADD CONSTRAINT `rolenum` FOREIGN KEY (`role`) REFERENCES `role` (`ID`);

--
-- Ограничения внешнего ключа таблицы `UsersOnSmena`
--
ALTER TABLE `UsersOnSmena`
  ADD CONSTRAINT `smena` FOREIGN KEY (`IDsmena`) REFERENCES `smeni` (`ID`),
  ADD CONSTRAINT `useronsmena` FOREIGN KEY (`IDuser`) REFERENCES `Users` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
