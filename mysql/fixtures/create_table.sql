CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE `player` (
  `id` int NOT NULL,
  `team_id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `team` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `balance` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);


ALTER TABLE `player`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_98197A65296CD8AE` (`team_id`);


ALTER TABLE `team`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `player`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `team`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `player`
  ADD CONSTRAINT `FK_98197A65296CD8AE` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`);
COMMIT;