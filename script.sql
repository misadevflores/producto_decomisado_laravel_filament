


ALTER TABLE `seizures` CHANGE `suggested_price` `suggested_price` DECIMAL(10,2) NULL DEFAULT NULL;
ALTER TABLE `seizures` CHANGE `observation` `observation` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `seizures` CHANGE `observation` `observation_pm` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `seizures` CHANGE `reason` `accesorio` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `seizures` ADD `obs_product` TEXT NULL AFTER `accesorio`;
ALTER TABLE `seizures` ADD `obs_almacen` TEXT NOT NULL AFTER `suggested_price`;
ALTER TABLE `seizures` ADD `attachment` TEXT NULL AFTER `obs_almacen`; 