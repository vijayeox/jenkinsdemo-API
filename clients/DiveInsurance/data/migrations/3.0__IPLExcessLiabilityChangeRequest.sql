ALTER TABLE `premium_rate_card` ADD COLUMN `coverage_category` Text NOT NULL ;

UPDATE `premium_rate_card` SET `coverage_category`='EXCESS_LIABILITY'  where `product` = 'Individual Professional Liability' and `key` in ('excessLiabilityCoverage1000000','excessLiabilityCoverage2000000','excessLiabilityCoverage3000000','excessLiabilityCoverage4000000','excessLiabilityCoverage9000000','excessLiabilityCoverageDeclined');
UPDATE `premium_rate_card` SET `previous_key` = 0 where `product` = 'Individual Professional Liability' and `key` = 'excessLiabilityCoverageDeclined' and `is_upgrade` = 0;
UPDATE `premium_rate_card` SET `previous_key` = 1 where `product` = 'Individual Professional Liability' and `key` = 'excessLiabilityCoverage1000000' and `is_upgrade` = 0; 
UPDATE `premium_rate_card` SET `previous_key` = 2 where `product` = 'Individual Professional Liability' and `key` = 'excessLiabilityCoverage2000000' and `is_upgrade` = 0; 
UPDATE `premium_rate_card` SET `previous_key` = 3 where `product` = 'Individual Professional Liability' and `key` = 'excessLiabilityCoverage3000000' and `is_upgrade` = 0; UPDATE `premium_rate_card` SET `previous_key` = 4 where `product` = 'Individual Professional Liability' and `key` = 'excessLiabilityCoverage4000000' and `is_upgrade` = 0;
UPDATE `premium_rate_card` SET `previous_key` = 5 where `product` = 'Individual Professional Liability' and `key` = 'excessLiabilityCoverage9000000' and `is_upgrade` = 0; 
