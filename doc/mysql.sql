--
-- DB Table activities_log backup
--
-- Tabellenstruktur für Tabelle `activities_log`
--

CREATE TABLE `activities_log` (
  `id` int(11) NOT NULL,
  `contentobject_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `event_name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `data` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `activities_log`
--
ALTER TABLE `activities_log`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `activities_log`
--
ALTER TABLE `activities_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;