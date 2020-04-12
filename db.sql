CREATE TABLE `flood` (
  `id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip` varchar(64) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE `watchdog` (
  `id` int(11) NOT NULL,
  `users_id` int(11) DEFAULT NULL,
  `type` enum('notice','error') NOT NULL,
  `message` mediumtext NOT NULL,
  `input` mediumtext NOT NULL,
  `output` mediumtext NOT NULL,
  `file` varchar(255) NOT NULL,
  `url` mediumtext NOT NULL,
  `c_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

ALTER TABLE `flood`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `watchdog`
  ADD PRIMARY KEY (`id`),
  ADD KEY `watchdog_users_id` (`users_id`);

ALTER TABLE `flood`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `watchdog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
