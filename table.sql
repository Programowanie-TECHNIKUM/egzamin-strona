-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 20 Paź 2025, 13:54
-- Wersja serwera: 10.4.22-MariaDB
-- Wersja PHP: 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `win`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `inf04`
--

CREATE TABLE `inf04` (
  `Id` int(11) NOT NULL,
  `Nr` int(11) NOT NULL,
  `Pytanie` text COLLATE utf8_polish_ci NOT NULL,
  `Zalacznik` text COLLATE utf8_polish_ci NOT NULL,
  `Odp_A` text COLLATE utf8_polish_ci NOT NULL,
  `Odp_B` text COLLATE utf8_polish_ci NOT NULL,
  `Odp_C` text COLLATE utf8_polish_ci NOT NULL,
  `Odp_D` text COLLATE utf8_polish_ci NOT NULL,
  `Ok` varchar(1) COLLATE utf8_polish_ci NOT NULL,
  `Grafika` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `inf04`
--

INSERT INTO `inf04` (`Id`, `Nr`, `Pytanie`, `Zalacznik`, `Odp_A`, `Odp_B`, `Odp_C`, `Odp_D`, `Ok`, `Grafika`) VALUES
(1, 1, 'Przedstawiony zapis w języku C# oznacza definicję klasy Car, która:', 'zal_001.jpg', 'jest klasą bazową (nie dziedziczy po żadnej klasie)', ' jest zaprzyjaźniona z klasą Vehicle', 'dziedziczy po Vehicle', 'korzysta z pól prywatnych klasy Vehicle', 'C', b'0'),
(2, 3, 'Zapisane w kodzie szesnastkowym składowe RGB koloru #AA41FF po przekształceniu do kodu dziesiętnego wynoszą kolejno', '', '160, 64, 255', '160, 65, 255', '170, 64, 255', '170, 65, 255', 'D', b'0'),
(3, 2, 'Mechanizm obietnic (ang. promises) w języku JavaScript ma na celu', '', 'zastąpić mechanizm dziedziczenia w programowaniu obiektowym', 'obsłużyć przechwytywanie błędów aplikacji', 'poprawić czytelność kodu synchronicznego.', 'obsłużyć funkcjonalność związaną z kodem asynchronicznym', 'D', b'0');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `inf04`
--
ALTER TABLE `inf04`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `inf04`
--
ALTER TABLE `inf04`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;