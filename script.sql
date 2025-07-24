


ALTER TABLE `seizures` CHANGE `suggested_price` `suggested_price` DECIMAL(10,2) NULL DEFAULT NULL;
ALTER TABLE `seizures` CHANGE `observation` `observation` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `seizures` CHANGE `observation` `observation_pm` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `seizures` CHANGE `reason` `accesorio` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `seizures` ADD `obs_product` TEXT NULL AFTER `accesorio`;
ALTER TABLE `seizures` ADD `obs_almacen` TEXT NOT NULL AFTER `suggested_price`;
ALTER TABLE `seizures` ADD `attachment` TEXT NULL AFTER `obs_almacen`;


ALTER TABLE `seizures` ADD `suggested_price_gerencia` DECIMAL(10,2)  NULL AFTER `attachment`;



UPDATE `seizures` SET `sucursal` = 'ISABEL' WHERE `sucursal` = 'Isabel';
UPDATE `seizures` SET `sucursal` = 'CAÑOTO' WHERE `sucursal` = 'Cañoto';
UPDATE `seizures` SET `sucursal` = 'BRISA' WHERE `sucursal` = 'Brisa';
UPDATE `seizures` SET `sucursal` = 'VENTURA' WHERE `sucursal` = 'Ventura';
UPDATE `seizures` SET `sucursal` = 'ISUTO' WHERE `sucursal` = 'Isuto';

ALTER TABLE `seizures` CHANGE `status` `status` ENUM('DISPONIBLE', 'REVENTA', 'BAJA', 'REPUESTO', 'ACTIVO FIJO') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DISPONIBLE';

ALTER TABLE `seizures` CHANGE `status_producto` `status_producto` ENUM('REGULAR', 'BUENO', 'MALO','EXCELENTE') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'REGULAR';