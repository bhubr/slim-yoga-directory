CREATE TABLE `place_school` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `place_id` int(10) unsigned NOT NULL,
  `school_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (place_id) REFERENCES places(id),
  FOREIGN KEY (school_id) REFERENCES schools(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

