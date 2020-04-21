
DELETE from `premium_rate_card` WHERE `product`='Dive Store' and `is_upgrade`=0;
ALTER TABLE premium_rate_card MODIFY COLUMN premium decimal(10,5) NULL;

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingCat','limitOver500000CoverBuildingCat','2019-06-30', '2020-07-31',0.010773);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingCat','limitOver250000CoverBuildingCat','2019-06-30', '2020-07-31',0.0118503);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingCat','limitOver100000CoverBuildingCat','2019-06-30', '2020-07-31',0.015561);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingCat','limitOver0CoverBuildingCat','2019-06-30', '2020-07-31',0.0172368);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingNonCat','limitOver500000CoverBuildingNonCat','2019-06-30', '2020-07-31',0.0090804);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingNonCat','limitOver250000CoverBuildingNonCat','2019-06-30', '2020-07-31',0.0095412);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingNonCat','limitOver100000CoverBuildingNonCat','2019-06-30', '2020-07-31',0.0100548);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingNonCat','limitOver0CoverBuildingNonCat','2019-06-30', '2020-07-31',0.0110808);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeCat','limitOver500000CoverBusIncomeCat','2019-06-30', '2020-07-31',0.0096957);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeCat','limitOver250000CoverBusIncomeCat','2019-06-30', '2020-07-31',0.01066527);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeCat','limitOver100000CoverBusIncomeCat','2019-06-30', '2020-07-31',0.0140049);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeCat','limitOver0CoverBusIncomeCat','2019-06-30', '2020-07-31',0.01551312);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeNonCat','limitOver500000CoverBusIncomeNonCat','2019-06-30', '2020-07-31',0.00817236);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeNonCat','limitOver250000CoverBusIncomeNonCat','2019-06-30', '2020-07-31',0.00858708);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeNonCat','limitOver100000CoverBusIncomeNonCat','2019-06-30', '2020-07-31',0.00904932);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeNonCat','limitOver0CoverBusIncomeNonCat','2019-06-30', '2020-07-31',0.00997272);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingCat','limitOver500000CoverBuildingCat','2019-07-01', '2020-07-31',0.010773);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingCat','limitOver250000CoverBuildingCat','2019-07-01', '2020-07-31',0.0118503);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingCat','limitOver100000CoverBuildingCat','2019-07-01', '2020-07-31',0.015561);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingCat','limitOver0CoverBuildingCat','2019-07-01', '2020-07-31',0.0172368);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingNonCat','limitOver500000CoverBuildingNonCat','2019-07-01', '2020-07-31',0.0090804);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingNonCat','limitOver250000CoverBuildingNonCat','2019-07-01', '2020-07-31',0.0095412);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingNonCat','limitOver100000CoverBuildingNonCat','2019-07-01', '2020-07-31',0.0100548);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingNonCat','limitOver0CoverBuildingNonCat','2019-07-01', '2020-07-31',0.0110808);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeCat','limitOver500000CoverBusIncomeCat','2019-07-01', '2020-07-31',0.0096957);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeCat','limitOver250000CoverBusIncomeCat','2019-07-01', '2020-07-31',0.01066527);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeCat','limitOver100000CoverBusIncomeCat','2019-07-01', '2020-07-31',0.0140049);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeCat','limitOver0CoverBusIncomeCat','2019-07-01', '2020-07-31',0.01551312);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeNonCat','limitOver500000CoverBusIncomeNonCat','2019-07-01', '2020-07-31',0.00817236);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeNonCat','limitOver250000CoverBusIncomeNonCat','2019-07-01', '2020-07-31',0.00858708);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeNonCat','limitOver100000CoverBusIncomeNonCat','2019-07-01', '2020-07-31',0.00904932);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeNonCat','limitOver0CoverBusIncomeNonCat','2019-07-01', '2020-07-31',0.00997272);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingCat','limitOver500000CoverBuildingCat','2019-08-01', '2020-07-31',0.010773);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingCat','limitOver250000CoverBuildingCat','2019-08-01', '2020-07-31',0.0118503);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingCat','limitOver100000CoverBuildingCat','2019-08-01', '2020-07-31',0.015561);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingCat','limitOver0CoverBuildingCat','2019-08-01', '2020-07-31',0.0172368);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingNonCat','limitOver500000CoverBuildingNonCat','2019-08-01', '2020-07-31',0.0090804);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingNonCat','limitOver250000CoverBuildingNonCat','2019-08-01', '2020-07-31',0.0095412);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingNonCat','limitOver100000CoverBuildingNonCat','2019-08-01', '2020-07-31',0.0100548);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingNonCat','limitOver0CoverBuildingNonCat','2019-08-01', '2020-07-31',0.0110808);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeCat','limitOver500000CoverBusIncomeCat','2019-08-01', '2020-07-31',0.0096957);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeCat','limitOver250000CoverBusIncomeCat','2019-08-01', '2020-07-31',0.01066527);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeCat','limitOver100000CoverBusIncomeCat','2019-08-01', '2020-07-31',0.0140049);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeCat','limitOver0CoverBusIncomeCat','2019-08-01', '2020-07-31',0.01551312);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeNonCat','limitOver500000CoverBusIncomeNonCat','2019-08-01', '2020-07-31',0.00817236);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeNonCat','limitOver250000CoverBusIncomeNonCat','2019-08-01', '2020-07-31',0.00858708);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeNonCat','limitOver100000CoverBusIncomeNonCat','2019-08-01', '2020-07-31',0.00904932);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeNonCat','limitOver0CoverBusIncomeNonCat','2019-08-01', '2020-07-31',0.00997272);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingCat','limitOver500000CoverBuildingCat','2019-09-01', '2020-07-31',0.010773);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingCat','limitOver250000CoverBuildingCat','2019-09-01', '2020-07-31',0.0118503);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingCat','limitOver100000CoverBuildingCat','2019-09-01', '2020-07-31',0.015561);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingCat','limitOver0CoverBuildingCat','2019-09-01', '2020-07-31',0.0172368);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingNonCat','limitOver500000CoverBuildingNonCat','2019-09-01', '2020-07-31',0.0090804);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingNonCat','limitOver250000CoverBuildingNonCat','2019-09-01', '2020-07-31',0.0095412);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingNonCat','limitOver100000CoverBuildingNonCat','2019-09-01', '2020-07-31',0.0100548);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingNonCat','limitOver0CoverBuildingNonCat','2019-09-01', '2020-07-31',0.0110808);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeCat','limitOver500000CoverBusIncomeCat','2019-09-01', '2020-07-31',0.0096957);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeCat','limitOver250000CoverBusIncomeCat','2019-09-01', '2020-07-31',0.01066527);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeCat','limitOver100000CoverBusIncomeCat','2019-09-01', '2020-07-31',0.0140049);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeCat','limitOver0CoverBusIncomeCat','2019-09-01', '2020-07-31',0.01551312);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeNonCat','limitOver500000CoverBusIncomeNonCat','2019-09-01', '2020-07-31',0.00817236);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeNonCat','limitOver250000CoverBusIncomeNonCat','2019-09-01', '2020-07-31',0.00858708);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeNonCat','limitOver100000CoverBusIncomeNonCat','2019-09-01', '2020-07-31',0.00904932);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeNonCat','limitOver0CoverBusIncomeNonCat','2019-09-01', '2020-07-31',0.00997272);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingCat','limitOver500000CoverBuildingCat','2019-10-01', '2020-07-31',0.010773);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingCat','limitOver250000CoverBuildingCat','2019-10-01', '2020-07-31',0.0118503);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingCat','limitOver100000CoverBuildingCat','2019-10-01', '2020-07-31',0.015561);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingCat','limitOver0CoverBuildingCat','2019-10-01', '2020-07-31',0.0172368);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingNonCat','limitOver500000CoverBuildingNonCat','2019-10-01', '2020-07-31',0.0090804);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingNonCat','limitOver250000CoverBuildingNonCat','2019-10-01', '2020-07-31',0.0095412);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingNonCat','limitOver100000CoverBuildingNonCat','2019-10-01', '2020-07-31',0.0100548);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingNonCat','limitOver0CoverBuildingNonCat','2019-10-01', '2020-07-31',0.0110808);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeCat','limitOver500000CoverBusIncomeCat','2019-10-01', '2020-07-31',0.0096957);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeCat','limitOver250000CoverBusIncomeCat','2019-10-01', '2020-07-31',0.01066527);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeCat','limitOver100000CoverBusIncomeCat','2019-10-01', '2020-07-31',0.0140049);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeCat','limitOver0CoverBusIncomeCat','2019-10-01', '2020-07-31',0.01551312);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeNonCat','limitOver500000CoverBusIncomeNonCat','2019-10-01', '2020-07-31',0.00817236);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeNonCat','limitOver250000CoverBusIncomeNonCat','2019-10-01', '2020-07-31',0.00858708);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeNonCat','limitOver100000CoverBusIncomeNonCat','2019-10-01', '2020-07-31',0.00904932);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeNonCat','limitOver0CoverBusIncomeNonCat','2019-10-01', '2020-07-31',0.00997272);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingCat','limitOver500000CoverBuildingCat','2019-11-01', '2020-07-31',0.010773);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingCat','limitOver250000CoverBuildingCat','2019-11-01', '2020-07-31',0.0118503);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingCat','limitOver100000CoverBuildingCat','2019-11-01', '2020-07-31',0.015561);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingCat','limitOver0CoverBuildingCat','2019-11-01', '2020-07-31',0.0172368);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingNonCat','limitOver500000CoverBuildingNonCat','2019-11-01', '2020-07-31',0.0090804);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingNonCat','limitOver250000CoverBuildingNonCat','2019-11-01', '2020-07-31',0.0095412);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingNonCat','limitOver100000CoverBuildingNonCat','2019-11-01', '2020-07-31',0.0100548);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingNonCat','limitOver0CoverBuildingNonCat','2019-11-01', '2020-07-31',0.0110808);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeCat','limitOver500000CoverBusIncomeCat','2019-11-01', '2020-07-31',0.0096957);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeCat','limitOver250000CoverBusIncomeCat','2019-11-01', '2020-07-31',0.01066527);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeCat','limitOver100000CoverBusIncomeCat','2019-11-01', '2020-07-31',0.0140049);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeCat','limitOver0CoverBusIncomeCat','2019-11-01', '2020-07-31',0.01551312);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeNonCat','limitOver500000CoverBusIncomeNonCat','2019-11-01', '2020-07-31',0.00817236);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeNonCat','limitOver250000CoverBusIncomeNonCat','2019-11-01', '2020-07-31',0.00858708);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeNonCat','limitOver100000CoverBusIncomeNonCat','2019-11-01', '2020-07-31',0.00904932);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeNonCat','limitOver0CoverBusIncomeNonCat','2019-11-01', '2020-07-31',0.00997272);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingCat','limitOver500000CoverBuildingCat','2019-12-01', '2020-07-31',0.010773);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingCat','limitOver250000CoverBuildingCat','2019-12-01', '2020-07-31',0.0118503);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingCat','limitOver100000CoverBuildingCat','2019-12-01', '2020-07-31',0.015561);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingCat','limitOver0CoverBuildingCat','2019-12-01', '2020-07-31',0.0172368);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingNonCat','limitOver500000CoverBuildingNonCat','2019-12-01', '2020-07-31',0.0090804);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingNonCat','limitOver250000CoverBuildingNonCat','2019-12-01', '2020-07-31',0.0095412);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingNonCat','limitOver100000CoverBuildingNonCat','2019-12-01', '2020-07-31',0.0100548);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingNonCat','limitOver0CoverBuildingNonCat','2019-12-01', '2020-07-31',0.0110808);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeCat','limitOver500000CoverBusIncomeCat','2019-12-01', '2020-07-31',0.0096957);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeCat','limitOver250000CoverBusIncomeCat','2019-12-01', '2020-07-31',0.01066527);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeCat','limitOver100000CoverBusIncomeCat','2019-12-01', '2020-07-31',0.0140049);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeCat','limitOver0CoverBusIncomeCat','2019-12-01', '2020-07-31',0.01551312);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeNonCat','limitOver500000CoverBusIncomeNonCat','2019-12-01', '2020-07-31',0.00817236);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeNonCat','limitOver250000CoverBusIncomeNonCat','2019-12-01', '2020-07-31',0.00858708);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeNonCat','limitOver100000CoverBusIncomeNonCat','2019-12-01', '2020-07-31',0.00904932);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeNonCat','limitOver0CoverBusIncomeNonCat','2019-12-01', '2020-07-31',0.00997272);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingCat','limitOver500000CoverBuildingCat','2020-01-01', '2020-07-31',0.010773);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingCat','limitOver250000CoverBuildingCat','2020-01-01', '2020-07-31',0.0118503);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingCat','limitOver100000CoverBuildingCat','2020-01-01', '2020-07-31',0.015561);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingCat','limitOver0CoverBuildingCat','2020-01-01', '2020-07-31',0.0172368);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingNonCat','limitOver500000CoverBuildingNonCat','2020-01-01', '2020-07-31',0.0090804);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingNonCat','limitOver250000CoverBuildingNonCat','2020-01-01', '2020-07-31',0.0095412);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingNonCat','limitOver100000CoverBuildingNonCat','2020-01-01', '2020-07-31',0.0100548);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingNonCat','limitOver0CoverBuildingNonCat','2020-01-01', '2020-07-31',0.0110808);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeCat','limitOver500000CoverBusIncomeCat','2020-01-01', '2020-07-31',0.0096957);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeCat','limitOver250000CoverBusIncomeCat','2020-01-01', '2020-07-31',0.01066527);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeCat','limitOver100000CoverBusIncomeCat','2020-01-01', '2020-07-31',0.0140049);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeCat','limitOver0CoverBusIncomeCat','2020-01-01', '2020-07-31',0.01551312);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeNonCat','limitOver500000CoverBusIncomeNonCat','2020-01-01', '2020-07-31',0.00817236);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeNonCat','limitOver250000CoverBusIncomeNonCat','2020-01-01', '2020-07-31',0.00858708);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeNonCat','limitOver100000CoverBusIncomeNonCat','2020-01-01', '2020-07-31',0.00904932);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeNonCat','limitOver0CoverBusIncomeNonCat','2020-01-01', '2020-07-31',0.00997272);




INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingCat','limitOver500000CoverBuildingCat','2020-02-01', '2020-07-31',0.010773);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingCat','limitOver250000CoverBuildingCat','2020-02-01', '2020-07-31',0.0118503);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingCat','limitOver100000CoverBuildingCat','2020-02-01', '2020-07-31',0.015561);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingCat','limitOver0CoverBuildingCat','2020-02-01', '2020-07-31',0.0172368);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingNonCat','limitOver500000CoverBuildingNonCat','2020-02-01', '2020-07-31',0.0090804);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingNonCat','limitOver250000CoverBuildingNonCat','2020-02-01', '2020-07-31',0.0095412);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingNonCat','limitOver100000CoverBuildingNonCat','2020-02-01', '2020-07-31',0.0100548);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingNonCat','limitOver0CoverBuildingNonCat','2020-02-01', '2020-07-31',0.0110808);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeCat','limitOver500000CoverBusIncomeCat','2020-02-01', '2020-07-31',0.0096957);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeCat','limitOver250000CoverBusIncomeCat','2020-02-01', '2020-07-31',0.01066527);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeCat','limitOver100000CoverBusIncomeCat','2020-02-01', '2020-07-31',0.0140049);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeCat','limitOver0CoverBusIncomeCat','2020-02-01', '2020-07-31',0.01551312);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeNonCat','limitOver500000CoverBusIncomeNonCat','2020-02-01', '2020-07-31',0.00817236);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeNonCat','limitOver250000CoverBusIncomeNonCat','2020-02-01', '2020-07-31',0.00858708);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeNonCat','limitOver100000CoverBusIncomeNonCat','2020-02-01', '2020-07-31',0.00904932);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeNonCat','limitOver0CoverBusIncomeNonCat','2020-02-01', '2020-07-31',0.00997272);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingCat','limitOver500000CoverBuildingCat','2020-03-01', '2020-07-31',0.010773);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingCat','limitOver250000CoverBuildingCat','2020-03-01', '2020-07-31',0.0118503);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingCat','limitOver100000CoverBuildingCat','2020-03-01', '2020-07-31',0.015561);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingCat','limitOver0CoverBuildingCat','2020-03-01', '2020-07-31',0.0172368);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingNonCat','limitOver500000CoverBuildingNonCat','2020-03-01', '2020-07-31',0.0090804);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingNonCat','limitOver250000CoverBuildingNonCat','2020-03-01', '2020-07-31',0.0095412);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingNonCat','limitOver100000CoverBuildingNonCat','2020-03-01', '2020-07-31',0.0100548);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingNonCat','limitOver0CoverBuildingNonCat','2020-03-01', '2020-07-31',0.0110808);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeCat','limitOver500000CoverBusIncomeCat','2020-03-01', '2020-07-31',0.0096957);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeCat','limitOver250000CoverBusIncomeCat','2020-03-01', '2020-07-31',0.01066527);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeCat','limitOver100000CoverBusIncomeCat','2020-03-01', '2020-07-31',0.0140049);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeCat','limitOver0CoverBusIncomeCat','2020-03-01', '2020-07-31',0.01551312);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeNonCat','limitOver500000CoverBusIncomeNonCat','2020-03-01', '2020-07-31',0.00817236);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeNonCat','limitOver250000CoverBusIncomeNonCat','2020-03-01', '2020-07-31',0.00858708);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeNonCat','limitOver100000CoverBusIncomeNonCat','2020-03-01', '2020-07-31',0.00904932);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeNonCat','limitOver0CoverBusIncomeNonCat','2020-03-01', '2020-07-31',0.00997272);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingCat','limitOver500000CoverBuildingCat','2020-04-01', '2020-07-31',0.010773);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingCat','limitOver250000CoverBuildingCat','2020-04-01', '2020-07-31',0.0118503);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingCat','limitOver100000CoverBuildingCat','2020-04-01', '2020-07-31',0.015561);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingCat','limitOver0CoverBuildingCat','2020-04-01', '2020-07-31',0.0172368);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingNonCat','limitOver500000CoverBuildingNonCat','2020-04-01', '2020-07-31',0.0090804);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingNonCat','limitOver250000CoverBuildingNonCat','2020-04-01', '2020-07-31',0.0095412);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingNonCat','limitOver100000CoverBuildingNonCat','2020-04-01', '2020-07-31',0.0100548);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingNonCat','limitOver0CoverBuildingNonCat','2020-04-01', '2020-07-31',0.0110808);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeCat','limitOver500000CoverBusIncomeCat','2020-04-01', '2020-07-31',0.0096957);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeCat','limitOver250000CoverBusIncomeCat','2020-04-01', '2020-07-31',0.01066527);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeCat','limitOver100000CoverBusIncomeCat','2020-04-01', '2020-07-31',0.0140049);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeCat','limitOver0CoverBusIncomeCat','2020-04-01', '2020-07-31',0.01551312);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeNonCat','limitOver500000CoverBusIncomeNonCat','2020-04-01', '2020-07-31',0.00817236);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeNonCat','limitOver250000CoverBusIncomeNonCat','2020-04-01', '2020-07-31',0.00858708);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeNonCat','limitOver100000CoverBusIncomeNonCat','2020-04-01', '2020-07-31',0.00904932);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeNonCat','limitOver0CoverBusIncomeNonCat','2020-04-01', '2020-07-31',0.00997272);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingCat','limitOver500000CoverBuildingCat','2020-05-01', '2020-07-31',0.010773);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingCat','limitOver250000CoverBuildingCat','2020-05-01', '2020-07-31',0.0118503);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingCat','limitOver100000CoverBuildingCat','2020-05-01', '2020-07-31',0.015561);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingCat','limitOver0CoverBuildingCat','2020-05-01', '2020-07-31',0.0172368);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingNonCat','limitOver500000CoverBuildingNonCat','2020-05-01', '2020-07-31',0.0090804);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingNonCat','limitOver250000CoverBuildingNonCat','2020-05-01', '2020-07-31',0.0095412);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingNonCat','limitOver100000CoverBuildingNonCat','2020-05-01', '2020-07-31',0.0100548);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingNonCat','limitOver0CoverBuildingNonCat','2020-05-01', '2020-07-31',0.0110808);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeCat','limitOver500000CoverBusIncomeCat','2020-05-01', '2020-07-31',0.0096957);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeCat','limitOver250000CoverBusIncomeCat','2020-05-01', '2020-07-31',0.01066527);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeCat','limitOver100000CoverBusIncomeCat','2020-05-01', '2020-07-31',0.0140049);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeCat','limitOver0CoverBusIncomeCat','2020-05-01', '2020-07-31',0.01551312);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeNonCat','limitOver500000CoverBusIncomeNonCat','2020-05-01', '2020-07-31',0.00817236);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeNonCat','limitOver250000CoverBusIncomeNonCat','2020-05-01', '2020-07-31',0.00858708);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeNonCat','limitOver100000CoverBusIncomeNonCat','2020-05-01', '2020-07-31',0.00904932);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeNonCat','limitOver0CoverBusIncomeNonCat','2020-05-01', '2020-07-31',0.00997272);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingCat','limitOver500000CoverBuildingCat','2020-06-01', '2020-07-31',0.010773);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingCat','limitOver250000CoverBuildingCat','2020-06-01', '2020-07-31',0.0118503);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingCat','limitOver100000CoverBuildingCat','2020-06-01', '2020-07-31',0.015561);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingCat','limitOver0CoverBuildingCat','2020-06-01', '2020-07-31',0.0172368);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingNonCat','limitOver500000CoverBuildingNonCat','2020-06-01', '2020-07-31',0.0090804);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingNonCat','limitOver250000CoverBuildingNonCat','2020-06-01', '2020-07-31',0.0095412);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingNonCat','limitOver100000CoverBuildingNonCat','2020-06-01', '2020-07-31',0.0100548);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingNonCat','limitOver0CoverBuildingNonCat','2020-06-01', '2020-07-31',0.0110808);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeCat','limitOver500000CoverBusIncomeCat','2020-06-01', '2020-07-31',0.0096957);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeCat','limitOver250000CoverBusIncomeCat','2020-06-01', '2020-07-31',0.01066527);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeCat','limitOver100000CoverBusIncomeCat','2020-06-01', '2020-07-31',0.0140049);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeCat','limitOver0CoverBusIncomeCat','2020-06-01', '2020-07-31',0.01551312);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeNonCat','limitOver500000CoverBusIncomeNonCat','2020-06-01', '2020-07-31',0.00817236);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeNonCat','limitOver250000CoverBusIncomeNonCat','2020-06-01', '2020-07-31',0.00858708);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeNonCat','limitOver100000CoverBusIncomeNonCat','2020-06-01', '2020-07-31',0.00904932);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeNonCat','limitOver0CoverBusIncomeNonCat','2020-06-01', '2020-07-31',0.00997272);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingCat','limitOver500000CoverBuildingCat','2020-07-01', '2020-07-31',0.010773);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingCat','limitOver250000CoverBuildingCat','2020-07-01', '2020-07-31',0.0118503);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingCat','limitOver100000CoverBuildingCat','2020-07-01', '2020-07-31',0.015561);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingCat','limitOver0CoverBuildingCat','2020-07-01', '2020-07-31',0.0172368);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingNonCat','limitOver500000CoverBuildingNonCat','2020-07-01', '2020-07-31',0.0090804);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingNonCat','limitOver250000CoverBuildingNonCat','2020-07-01', '2020-07-31',0.0095412);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingNonCat','limitOver100000CoverBuildingNonCat','2020-07-01', '2020-07-31',0.0100548);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingNonCat','limitOver0CoverBuildingNonCat','2020-07-01', '2020-07-31',0.0110808);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeCat','limitOver500000CoverBusIncomeCat','2020-07-01', '2020-07-31',0.0096957);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeCat','limitOver250000CoverBusIncomeCat','2020-07-01', '2020-07-31',0.01066527);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeCat','limitOver100000CoverBusIncomeCat','2020-07-01', '2020-07-31',0.0140049);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeCat','limitOver0CoverBusIncomeCat','2020-07-01', '2020-07-31',0.01551312);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeNonCat','limitOver500000CoverBusIncomeNonCat','2020-07-01', '2020-07-31',0.00817236);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeNonCat','limitOver250000CoverBusIncomeNonCat','2020-07-01', '2020-07-31',0.00858708);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeNonCat','limitOver100000CoverBusIncomeNonCat','2020-07-01', '2020-07-31',0.00904932);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeNonCat','limitOver0CoverBusIncomeNonCat','2020-07-01', '2020-07-31',0.00997272);




INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'standardCoverageUpTo50000','standardCoverageUpTo50000','2019-06-30', '2020-06-30',1631);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'standardCoverage50001To100000','standardCoverage50001To100000','2019-06-30', '2020-06-30',1874);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'standardCoverage100001To200000','standardCoverage100001To200000','2019-06-30', '2020-06-30',2153);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'standardCoverage200001To350000','standardCoverage200001To350000','2019-06-30', '2020-06-30',2470);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'standardCoverage350001To500000','standardCoverage350001To500000','2019-06-30', '2020-06-30',2939);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'standardCoverage500001To1M','standardCoverage500001To1M','2019-06-30', '2020-06-30',3141);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'standardCoverage1MAndOver','standardCoverage1MAndOver','2019-06-30', '2020-06-30',3359);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'liabilityOnlyUpTo50000','liabilityOnlyUpTo50000','2019-06-30', '2020-06-30',1631);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'liabilityOnly50001To100000','liabilityOnly50001To100000','2019-06-30', '2020-06-30',1874);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'liabilityOnly100001To200000','liabilityOnly100001To200000','2019-06-30', '2020-06-30',2153);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'liabilityOnly200001To350000','liabilityOnly200001To350000','2019-06-30', '2020-06-30',2470);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'liabilityOnly350001To500000','liabilityOnly350001To500000','2019-06-30', '2020-06-30',2939);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'liabilityOnly500001To1M','liabilityOnly500001To1M','2019-06-30', '2020-06-30',3141);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'liabilityOnly1MAndOver','liabilityOnly1MAndOver','2019-06-30', '2020-06-30',3359);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'discontinuedOperation','discontinuedOperation','2019-06-30', '2020-06-30',816);




INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'medicalExpense','medicalExpense','2019-06-30', '2020-06-30',55);


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'nonOwnedAutoLiability100K','nonOwnedAutoLiability100K','2019-06-30', '2020-06-30',113);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'nonOwnedAutoLiability1M','nonOwnedAutoLiability1M','2019-06-30', '2020-06-30',849);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'excessLiabilityCoverage1M','excessLiabilityCoverage1M','2019-06-30', '2020-06-30',567);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'excessLiabilityCoverage2M','excessLiabilityCoverage2M','2019-06-30', '2020-06-30',1133);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'excessLiabilityCoverage3M','excessLiabilityCoverage3M','2019-06-30', '2020-06-30',1700);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'excessLiabilityCoverage4M','excessLiabilityCoverage4M','2019-06-30', '2020-06-30',2266);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'excessLiabilityCoverage9M','excessLiabilityCoverage9M','2019-06-30', '2020-06-30',5381);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'TAEOunder100k','TAEOunder100k','2019-06-30', '2020-06-30',283);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'TAEO100kTo500k','TAEO100kTo500k','2019-06-30', '2020-06-30',340);




INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'poolLiabilityOver50k','poolLiabilityOver50k','2019-06-30', '2020-06-30',1189);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'poolLiabilityOver20k','poolLiabilityOver20k','2019-06-30', '2020-06-30',793);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'poolLiabilityOver0','poolLiabilityOver0','2019-06-30', '2020-06-30',396);




INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'proRataFactorsJan','proRataFactorsJan','2020-01-01', '2020-12-31',0.493);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'proRataFactorsFeb','proRataFactorsFeb','2020-01-01', '2020-12-31',0.408);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'proRataFactorsMar','proRataFactorsMar','2020-01-01', '2020-12-31',0.332);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'proRataFactorsApr','proRataFactorsApr','2020-01-01', '2020-12-31',0.247);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'proRataFactorsMay','proRataFactorsMay','2020-01-01', '2020-12-31',0.164);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'proRataFactorsJun','proRataFactorsJun','2020-01-01', '2020-12-31',0.079);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'proRataFactorsJul','proRataFactorsJul','2020-01-01', '2020-12-31',1.000);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'proRataFactorsAug','proRataFactorsAug','2020-01-01', '2020-12-31',0.912);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'proRataFactorsSep','proRataFactorsSep','2020-01-01', '2020-12-31',0.827);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'proRataFactorsOct','proRataFactorsOct','2020-01-01', '2020-12-31',0.745);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'proRataFactorsNov','proRataFactorsNov','2020-01-01', '2020-12-31',0.660);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'proRataFactorsDec','proRataFactorsDec','2020-01-01', '2020-12-31',0.578);




INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'padiFee','padiFee','2020-01-01', '2020-12-31',50);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan0','groupCoverageMoreThan0','2020-01-01', '2020-01-31',693);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan25000','groupCoverageMoreThan25000','2020-01-01', '2020-01-31',1143);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan50000','groupCoverageMoreThan50000','2020-01-01', '2020-01-31',1455);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan100000','groupCoverageMoreThan100000','2020-01-01', '2020-01-31',1767);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan150000','groupCoverageMoreThan150000','2020-01-01', '2020-01-31',2286);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan200000','groupCoverageMoreThan200000','2020-01-01', '2020-01-31',2427);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan250000','groupCoverageMoreThan250000','2020-01-01', '2020-01-31',2910);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan350000','groupCoverageMoreThan350000','2020-01-01', '2020-01-31',3120);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan500000','groupCoverageMoreThan500000','2020-01-01', '2020-01-31',3465);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan0','groupCoverageMoreThan0','2020-02-01', '2020-02-29',578);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan25000','groupCoverageMoreThan25000','2020-02-01', '2020-02-29',953);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan50000','groupCoverageMoreThan50000','2020-02-01', '2020-02-29',1213);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan100000','groupCoverageMoreThan100000','2020-02-01', '2020-02-29',1473);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan150000','groupCoverageMoreThan150000','2020-02-01', '2020-02-29',1905);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan200000','groupCoverageMoreThan200000','2020-02-01', '2020-02-29',2023);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan250000','groupCoverageMoreThan250000','2020-02-01', '2020-02-29',2425);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan350000','groupCoverageMoreThan350000','2020-02-01', '2020-02-29',2600);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan500000','groupCoverageMoreThan500000','2020-02-01', '2020-02-29',2888);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan0','groupCoverageMoreThan0','2020-03-01', '2020-03-31',462);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan25000','groupCoverageMoreThan25000','2020-03-01', '2020-03-31',762);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan50000','groupCoverageMoreThan50000','2020-03-01', '2020-03-31',970);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan100000','groupCoverageMoreThan100000','2020-03-01', '2020-03-31',1178);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan150000','groupCoverageMoreThan150000','2020-03-01', '2020-03-31',1524);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan200000','groupCoverageMoreThan200000','2020-03-01', '2020-03-31',1618);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan250000','groupCoverageMoreThan250000','2020-03-01', '2020-03-31',1940);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan350000','groupCoverageMoreThan350000','2020-03-01', '2020-03-31',2080);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan500000','groupCoverageMoreThan500000','2020-03-01', '2020-03-31',2310);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan0','groupCoverageMoreThan0','2020-04-01', '2020-04-30',347);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan25000','groupCoverageMoreThan25000','2020-04-01', '2020-04-30',572);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan50000','groupCoverageMoreThan50000','2020-04-01', '2020-04-30',728);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan100000','groupCoverageMoreThan100000','2020-04-01', '2020-04-30',884);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan150000','groupCoverageMoreThan150000','2020-04-01', '2020-04-30',1143);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan200000','groupCoverageMoreThan200000','2020-04-01', '2020-04-30',1214);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan250000','groupCoverageMoreThan250000','2020-04-01', '2020-04-30',1455);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan350000','groupCoverageMoreThan350000','2020-04-01', '2020-04-30',1560);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan500000','groupCoverageMoreThan500000','2020-04-01', '2020-04-30',1733);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan0','groupCoverageMoreThan0','2020-05-01', '2020-05-31',231);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan25000','groupCoverageMoreThan25000','2020-05-01', '2020-05-31',381);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan50000','groupCoverageMoreThan50000','2020-05-01', '2020-05-31',485);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan100000','groupCoverageMoreThan100000','2020-05-01', '2020-05-31',589);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan150000','groupCoverageMoreThan150000','2020-05-01', '2020-05-31',762);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan200000','groupCoverageMoreThan200000','2020-05-01', '2020-05-31',809);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan250000','groupCoverageMoreThan250000','2020-05-01', '2020-05-31',970);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan350000','groupCoverageMoreThan350000','2020-05-01', '2020-05-31',1040);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan500000','groupCoverageMoreThan500000','2020-05-01', '2020-05-31',1155);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan0','groupCoverageMoreThan0','2020-06-01', '2020-06-30',116);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan25000','groupCoverageMoreThan25000','2020-06-01', '2020-06-30',191);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan50000','groupCoverageMoreThan50000','2020-06-01', '2020-06-30',243);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan100000','groupCoverageMoreThan100000','2020-06-01', '2020-06-30',295);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan150000','groupCoverageMoreThan150000','2020-06-01', '2020-06-30',381);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan200000','groupCoverageMoreThan200000','2020-06-01', '2020-06-30',405);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan250000','groupCoverageMoreThan250000','2020-06-01', '2020-06-30',485);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan350000','groupCoverageMoreThan350000','2020-06-01', '2020-06-30',520);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan500000','groupCoverageMoreThan500000','2020-06-01', '2020-06-30',578);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan0','groupCoverageMoreThan0','2020-07-01', '2020-07-31',1271);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan25000','groupCoverageMoreThan25000','2020-07-01', '2020-07-31',2286);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan50000','groupCoverageMoreThan50000','2020-07-01', '2020-07-31',2910);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan100000','groupCoverageMoreThan100000','2020-07-01', '2020-07-31',3534);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan150000','groupCoverageMoreThan150000','2020-07-01', '2020-07-31',4572);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan200000','groupCoverageMoreThan200000','2020-07-01', '2020-07-31',4854);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan250000','groupCoverageMoreThan250000','2020-07-01', '2020-07-31',5820);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan350000','groupCoverageMoreThan350000','2020-07-01', '2020-07-31',6240);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan500000','groupCoverageMoreThan500000','2020-07-01', '2020-07-31',6930);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan0','groupCoverageMoreThan0','2020-08-01', '2020-08-31',1155);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan25000','groupCoverageMoreThan25000','2020-08-01', '2020-08-31',2096);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan50000','groupCoverageMoreThan50000','2020-08-01', '2020-08-31',2668);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan100000','groupCoverageMoreThan100000','2020-08-01', '2020-08-31',3240);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan150000','groupCoverageMoreThan150000','2020-08-01', '2020-08-31',4191);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan200000','groupCoverageMoreThan200000','2020-08-01', '2020-08-31',4450);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan250000','groupCoverageMoreThan250000','2020-08-01', '2020-08-31',5335);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan350000','groupCoverageMoreThan350000','2020-08-01', '2020-08-31',5720);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan500000','groupCoverageMoreThan500000','2020-08-01', '2020-08-31',6353);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '0 to $25,000','groupCoverageMoreThan0','2020-01-01', '2020-01-31',693,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$25,001 to $50,000','groupCoverageMoreThan25000','2020-01-01', '2020-01-31',1143,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$50,001 to $100,000','groupCoverageMoreThan50000','2020-01-01', '2020-01-31',1455,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$100,001 to $150,000','groupCoverageMoreThan100000','2020-01-01', '2020-01-31',1767,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$150,001 to $200,000','groupCoverageMoreThan150000','2020-01-01', '2020-01-31',2286,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$200,001 to $250,000','groupCoverageMoreThan200000','2020-01-01', '2020-01-31',2427,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$250,001 to $350,000','groupCoverageMoreThan250000','2020-01-01', '2020-01-31',2910,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$350,001 to $500,000','groupCoverageMoreThan350000','2020-01-01', '2020-01-31',3120,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$500,001 and up','groupCoverageMoreThan500000','2020-01-01', '2020-01-31',3465,1,'groupCoverageNoneSelected');

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '0 to $25,000','groupCoverageMoreThan0','2020-02-01', '2020-02-29',578,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$25,001 to $50,000','groupCoverageMoreThan25000','2020-02-01', '2020-02-29',953,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$50,001 to $100,000','groupCoverageMoreThan50000','2020-02-01', '2020-02-29',1213,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$100,001 to $150,000','groupCoverageMoreThan100000','2020-02-01', '2020-02-29',1473,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$150,001 to $200,000','groupCoverageMoreThan150000','2020-02-01', '2020-02-29',1905,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$200,001 to $250,000','groupCoverageMoreThan200000','2020-02-01', '2020-02-29',2023,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$250,001 to $350,000','groupCoverageMoreThan250000','2020-02-01', '2020-02-29',2425,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$350,001 to $500,000','groupCoverageMoreThan350000','2020-02-01', '2020-02-29',2600,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$500,001 and up','groupCoverageMoreThan500000','2020-02-01', '2020-02-29',2888,1,'groupCoverageNoneSelected');

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '0 to $25,000','groupCoverageMoreThan0','2020-03-01', '2020-03-31',462,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$25,001 to $50,000','groupCoverageMoreThan25000','2020-03-01', '2020-03-31',762,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$50,001 to $100,000','groupCoverageMoreThan50000','2020-03-01', '2020-03-31',970,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$100,001 to $150,000','groupCoverageMoreThan100000','2020-03-01', '2020-03-31',1178,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$150,001 to $200,000','groupCoverageMoreThan150000','2020-03-01', '2020-03-31',1524,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$200,001 to $250,000','groupCoverageMoreThan200000','2020-03-01', '2020-03-31',1618,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$250,001 to $350,000','groupCoverageMoreThan250000','2020-03-01', '2020-03-31',1940,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$350,001 to $500,000','groupCoverageMoreThan350000','2020-03-01', '2020-03-31',2080,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$500,001 and up','groupCoverageMoreThan500000','2020-03-01', '2020-03-31',2310,1,'groupCoverageNoneSelected');

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '0 to $25,000','groupCoverageMoreThan0','2020-04-01', '2020-04-30',347,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$25,001 to $50,000','groupCoverageMoreThan25000','2020-04-01', '2020-04-30',572,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$50,001 to $100,000','groupCoverageMoreThan50000','2020-04-01', '2020-04-30',728,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$100,001 to $150,000','groupCoverageMoreThan100000','2020-04-01', '2020-04-30',884,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$150,001 to $200,000','groupCoverageMoreThan150000','2020-04-01', '2020-04-30',1143,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$200,001 to $250,000','groupCoverageMoreThan200000','2020-04-01', '2020-04-30',1214,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$250,001 to $350,000','groupCoverageMoreThan250000','2020-04-01', '2020-04-30',1455,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$350,001 to $500,000','groupCoverageMoreThan350000','2020-04-01', '2020-04-30',1560,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$500,001 and up','groupCoverageMoreThan500000','2020-04-01', '2020-04-30',1733,1,'groupCoverageNoneSelected');

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '0 to $25,000','groupCoverageMoreThan0','2020-05-01', '2020-05-31',231,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$25,001 to $50,000','groupCoverageMoreThan25000','2020-05-01', '2020-05-31',381,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$50,001 to $100,000','groupCoverageMoreThan50000','2020-05-01', '2020-05-31',485,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$100,001 to $150,000','groupCoverageMoreThan100000','2020-05-01', '2020-05-31',589,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$150,001 to $200,000','groupCoverageMoreThan150000','2020-05-01', '2020-05-31',762,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$200,001 to $250,000','groupCoverageMoreThan200000','2020-05-01', '2020-05-31',809,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$250,001 to $350,000','groupCoverageMoreThan250000','2020-05-01', '2020-05-31',970,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$350,001 to $500,000','groupCoverageMoreThan350000','2020-05-01', '2020-05-31',1040,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$500,001 and up','groupCoverageMoreThan500000','2020-05-01', '2020-05-31',1155,1,'groupCoverageNoneSelected');

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '0 to $25,000','groupCoverageMoreThan0','2020-06-01', '2020-06-30',116,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$25,001 to $50,000','groupCoverageMoreThan25000','2020-06-01', '2020-06-30',191,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$50,001 to $100,000','groupCoverageMoreThan50000','2020-06-01', '2020-06-30',243,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$100,001 to $150,000','groupCoverageMoreThan100000','2020-06-01', '2020-06-30',295,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$150,001 to $200,000','groupCoverageMoreThan150000','2020-06-01', '2020-06-30',381,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$200,001 to $250,000','groupCoverageMoreThan200000','2020-06-01', '2020-06-30',405,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$250,001 to $350,000','groupCoverageMoreThan250000','2020-06-01', '2020-06-30',485,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$350,001 to $500,000','groupCoverageMoreThan350000','2020-06-01', '2020-06-30',520,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$500,001 and up','groupCoverageMoreThan500000','2020-06-01', '2020-06-30',578,1,'groupCoverageNoneSelected');

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '0 to $25,000','groupCoverageMoreThan0','2020-07-01', '2020-07-31',1271,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$25,001 to $50,000','groupCoverageMoreThan25000','2020-07-01', '2020-07-31',2286,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$50,001 to $100,000','groupCoverageMoreThan50000','2020-07-01', '2020-07-31',2910,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$100,001 to $150,000','groupCoverageMoreThan100000','2020-07-01', '2020-07-31',3534,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$150,001 to $200,000','groupCoverageMoreThan150000','2020-07-01', '2020-07-31',4572,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$200,001 to $250,000','groupCoverageMoreThan200000','2020-07-01', '2020-07-31',4854,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$250,001 to $350,000','groupCoverageMoreThan250000','2020-07-01', '2020-07-31',5820,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$350,001 to $500,000','groupCoverageMoreThan350000','2020-07-01', '2020-07-31',6240,1,'groupCoverageNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '$500,001 and up','groupCoverageMoreThan500000','2020-07-01', '2020-07-31',6930,1,'groupCoverageNoneSelected');


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '1M','groupExcessLiability1M','2020-01-01', '2020-12-31',65.00,1,'groupLiabilityNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '2M','groupExcessLiability2M','2020-01-01', '2020-12-31',125.00,1,'groupLiabilityNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '3M','groupExcessLiability3M','2020-01-01', '2020-12-31',160.00,1,'groupLiabilityNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '4M','groupExcessLiability4M','2020-01-01', '2020-12-31',200.00,1,'groupLiabilityNoneSelected');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Store', '9M','groupExcessLiability9M','2020-01-01', '2020-12-31',350.00,1,'groupLiabilityNoneSelected');


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupExcessLiability1M','groupExcessLiability1M','2020-01-01', '2020-12-31',65);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupExcessLiability2M','groupExcessLiability2M','2020-01-01', '2020-12-31',125);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupExcessLiability3M','groupExcessLiability3M','2020-01-01', '2020-12-31',160);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupExcessLiability4M','groupExcessLiability4M','2020-01-01', '2020-12-31',200);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupExcessLiability9M','groupExcessLiability9M','2020-01-01', '2020-12-31',350);


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan0','groupCoverageMoreThan0','2020-09-01', '2020-09-30',1040);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan25000','groupCoverageMoreThan25000','2020-09-01', '2020-09-30',1905);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan50000','groupCoverageMoreThan50000','2020-09-01', '2020-09-30',2425);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan100000','groupCoverageMoreThan100000','2020-09-01', '2020-09-30',2945);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan150000','groupCoverageMoreThan150000','2020-09-01', '2020-09-30',3810);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan200000','groupCoverageMoreThan200000','2020-09-01', '2020-09-30',4045);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan250000','groupCoverageMoreThan250000','2020-09-01', '2020-09-30',4850);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan350000','groupCoverageMoreThan350000','2020-09-01', '2020-09-30',5200);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan500000','groupCoverageMoreThan500000','2020-09-01', '2020-09-30',5775);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan0','groupCoverageMoreThan0','2020-10-01', '2020-10-31',924);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan25000','groupCoverageMoreThan25000','2020-10-01', '2020-10-31',1715);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan50000','groupCoverageMoreThan50000','2020-10-01', '2020-10-31',2183);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan100000','groupCoverageMoreThan100000','2020-10-01', '2020-10-31',2651);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan150000','groupCoverageMoreThan150000','2020-10-01', '2020-10-31',3429);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan200000','groupCoverageMoreThan200000','2020-10-01', '2020-10-31',3641);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan250000','groupCoverageMoreThan250000','2020-10-01', '2020-10-31',4365);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan350000','groupCoverageMoreThan350000','2020-10-01', '2020-10-31',4680);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan500000','groupCoverageMoreThan500000','2020-10-01', '2020-10-31',5198);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan0','groupCoverageMoreThan0','2020-11-01', '2020-11-30',809);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan25000','groupCoverageMoreThan25000','2020-11-01', '2020-11-30',1524);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan50000','groupCoverageMoreThan50000','2020-11-01', '2020-11-30',1940);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan100000','groupCoverageMoreThan100000','2020-11-01', '2020-11-30',2356);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan150000','groupCoverageMoreThan150000','2020-11-01', '2020-11-30',3048);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan200000','groupCoverageMoreThan200000','2020-11-01', '2020-11-30',3236);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan250000','groupCoverageMoreThan250000','2020-11-01', '2020-11-30',3880);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan350000','groupCoverageMoreThan350000','2020-11-01', '2020-11-30',4160);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan500000','groupCoverageMoreThan500000','2020-11-01', '2020-11-30',4620);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan0','groupCoverageMoreThan0','2020-12-01', '2020-12-31',674);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan25000','groupCoverageMoreThan25000','2020-12-01', '2020-12-31',1334);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan50000','groupCoverageMoreThan50000','2020-12-01', '2020-12-31',1698);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan100000','groupCoverageMoreThan100000','2020-12-01', '2020-12-31',2062);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan150000','groupCoverageMoreThan150000','2020-12-01', '2020-12-31',2667);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan200000','groupCoverageMoreThan200000','2020-12-01', '2020-12-31',2832);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan250000','groupCoverageMoreThan250000','2020-12-01', '2020-12-31',3395);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan350000','groupCoverageMoreThan350000','2020-12-01', '2020-12-31',3640);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan500000','groupCoverageMoreThan500000','2020-12-01', '2020-12-31',4043);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupExcessLiability1M','groupExcessLiability1M','2020-01-01', '2020-12-31',65);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupExcessLiability2M','groupExcessLiability2M','2020-01-01', '2020-12-31',125);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupExcessLiability3M','groupExcessLiability3M','2020-01-01', '2020-12-31',160);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupExcessLiability4M','groupExcessLiability4M','2020-01-01', '2020-12-31',200);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupExcessLiability9M','groupExcessLiability9M','2020-01-01', '2020-12-31',350);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupPadiFee','groupPadiFee','2020-01-01', '2020-12-31',175);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'propertyDeductibles1000','propertyDeductibles1000','2020-01-01', '2020-12-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'propertyDeductibles2500','propertyDeductibles2500','2020-01-01', '2020-12-31',4.37);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'propertyDeductibles5000','propertyDeductibles5000','2020-01-01', '2020-12-31',8.93);