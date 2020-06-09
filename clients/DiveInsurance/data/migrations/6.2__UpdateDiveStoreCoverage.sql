UPDATE premium_rate_card SET coverage = '0 to $25,000',coverage_category = 'GROUP_COVERAGE' WHERE product = 'Dive Store' and `key` = 'groupCoverageMoreThan0';
UPDATE premium_rate_card SET coverage = '$25,001 to $50,000',coverage_category = 'GROUP_COVERAGE' WHERE product = 'Dive Store' and `key` = 'groupCoverageMoreThan25000';
UPDATE premium_rate_card SET coverage = '$50,001 to $100,000',coverage_category = 'GROUP_COVERAGE' WHERE product = 'Dive Store' and `key` = 'groupCoverageMoreThan50000';
UPDATE premium_rate_card SET coverage = '$100,001 to $150,000',coverage_category = 'GROUP_COVERAGE' WHERE product = 'Dive Store' and `key` = 'groupCoverageMoreThan100000';
UPDATE premium_rate_card SET coverage = '$150,001 to $200,000',coverage_category = 'GROUP_COVERAGE' WHERE product = 'Dive Store' and `key` = 'groupCoverageMoreThan150000';
UPDATE premium_rate_card SET coverage = '$200,001 to $250,000',coverage_category = 'GROUP_COVERAGE' WHERE product = 'Dive Store' and `key` = 'groupCoverageMoreThan200000';
UPDATE premium_rate_card SET coverage = '$250,001 to $350,000',coverage_category = 'GROUP_COVERAGE' WHERE product = 'Dive Store' and `key` = 'groupCoverageMoreThan250000';
UPDATE premium_rate_card SET coverage = '$350,001 to $500,000',coverage_category = 'GROUP_COVERAGE' WHERE product = 'Dive Store' and `key` = 'groupCoverageMoreThan350000';
UPDATE premium_rate_card SET coverage = '$500,001 and up',coverage_category = 'GROUP_COVERAGE' WHERE product = 'Dive Store' and `key` = 'groupCoverageMoreThan500000';

UPDATE premium_rate_card SET coverage = '1M',coverage_category = 'GROUP_EXCESS_LIABILITY' WHERE product = 'Dive Store' and `key` = 'groupExcessLiability1M';
UPDATE premium_rate_card SET coverage = '2M',coverage_category = 'GROUP_EXCESS_LIABILITY' WHERE product = 'Dive Store' and `key` = 'groupExcessLiability2M';
UPDATE premium_rate_card SET coverage = '3M',coverage_category = 'GROUP_EXCESS_LIABILITY' WHERE product = 'Dive Store' and `key` = 'groupExcessLiability3M';
UPDATE premium_rate_card SET coverage = '4M',coverage_category = 'GROUP_EXCESS_LIABILITY' WHERE product = 'Dive Store' and `key` = 'groupExcessLiability4M';
UPDATE premium_rate_card SET coverage = '9M',coverage_category = 'GROUP_EXCESS_LIABILITY' WHERE product = 'Dive Store' and `key` = 'groupExcessLiability9M';

UPDATE premium_rate_card SET coverage = 'PADI Fee',coverage_category = 'GROUP_PADI_FEE' WHERE product in ('Dive Store','Dive Boat') and `key` = 'groupPadiFee';

UPDATE premium_rate_card SET coverage = 'Property Deductible ($1,000)',coverage_category = 'PROPERTY_DEDUCTIBLE' WHERE product = 'Dive Store' and `key` = 'propertyDeductibles1000';
UPDATE premium_rate_card SET coverage = 'Property Deductible ($2,500)',coverage_category = 'PROPERTY_DEDUCTIBLE' WHERE product = 'Dive Store' and `key` = 'propertyDeductibles2500';
UPDATE premium_rate_card SET coverage = 'Property Deductible ($5,000)',coverage_category = 'PROPERTY_DEDUCTIBLE' WHERE product = 'Dive Store' and `key` = 'propertyDeductibles5000';

UPDATE premium_rate_card SET coverage = 'Non Own Auto Liability ($100K)' WHERE product = 'Dive Store' and `key` = 'nonOwnedAutoLiability100K';
UPDATE premium_rate_card SET coverage = 'Non Own Auto Liability ($1M)' WHERE product = 'Dive Store' and `key` = 'nonOwnedAutoLiability1M';

UPDATE premium_rate_card SET coverage = 'Discontinued Operation' WHERE product = 'Dive Store' and `key` = 'discontinuedOperation';

UPDATE premium_rate_card SET coverage = 'Medical Expense' WHERE product = 'Dive Store' and `key` = 'medicalExpense';

UPDATE premium_rate_card SET coverage = 'Standard Coverage (Up to $50,000)' WHERE product = 'Dive Store' and `key` = 'standardCoverageUpTo50000';
UPDATE premium_rate_card SET coverage = 'Standard Coverage ($50,001 to $100,000)' WHERE product = 'Dive Store' and `key` = 'standardCoverage50001To100000';
UPDATE premium_rate_card SET coverage = 'Standard Coverage ($100,001 to $200,000)' WHERE product = 'Dive Store' and `key` = 'standardCoverage100001To200000';
UPDATE premium_rate_card SET coverage = 'Standard Coverage ($200,001 to $350,000)' WHERE product = 'Dive Store' and `key` = 'standardCoverage200001To350000';
UPDATE premium_rate_card SET coverage = 'Standard Coverage ($350,001 to $500,000)' WHERE product = 'Dive Store' and `key` = 'standardCoverage350001To500000';
UPDATE premium_rate_card SET coverage = 'Standard Coverage ($500,001 to $1M)' WHERE product = 'Dive Store' and `key` = 'standardCoverage500001To1M';
UPDATE premium_rate_card SET coverage = 'Standard Coverage ($1M and Over)' WHERE product = 'Dive Store' and `key` = 'standardCoverage1MAndOver';

UPDATE premium_rate_card SET coverage = 'Liability Only (Up to $50,000)' WHERE product = 'Dive Store' and `key` = 'liabilityOnlyUpTo50000';
UPDATE premium_rate_card SET coverage = 'Liability Only ($50,001 to $100,000)' WHERE product = 'Dive Store' and `key` = 'liabilityOnly50001To100000';
UPDATE premium_rate_card SET coverage = 'Liability Only ($100,001 to $200,000)' WHERE product = 'Dive Store' and `key` = 'liabilityOnly100001To200000';
UPDATE premium_rate_card SET coverage = 'Liability Only ($200,001 to $350,000)' WHERE product = 'Dive Store' and `key` = 'liabilityOnly200001To350000';
UPDATE premium_rate_card SET coverage = 'Liability Only ($350,001 to $500,000)' WHERE product = 'Dive Store' and `key` = 'liabilityOnly350001To500000';
UPDATE premium_rate_card SET coverage = 'Liability Only ($500,001 to $1M)' WHERE product = 'Dive Store' and `key` = 'liabilityOnly500001To1M';
UPDATE premium_rate_card SET coverage = 'Liability Only ($1M and Over)' WHERE product = 'Dive Store' and `key` = 'liabilityOnly1MAndOver';

UPDATE premium_rate_card SET coverage = 'Cover Building CAT - Limit Over 0' WHERE product = 'Dive Store' and `key` = 'limitOver0CoverBuildingCat';
UPDATE premium_rate_card SET coverage = 'Cover Building CAT - Limit Over 100000' WHERE product = 'Dive Store' and `key` = 'limitOver100000CoverBuildingCat';
UPDATE premium_rate_card SET coverage = 'Cover Building CAT - Limit Over 250000' WHERE product = 'Dive Store' and `key` = 'limitOver250000CoverBuildingCat';
UPDATE premium_rate_card SET coverage = 'Cover Building CAT - Limit Over 500000' WHERE product = 'Dive Store' and `key` = 'limitOver500000CoverBuildingCat';

UPDATE premium_rate_card SET coverage = 'Cover Building Non CAT - Limit Over 0' WHERE product = 'Dive Store' and `key` = 'limitOver0CoverBuildingNonCat';
UPDATE premium_rate_card SET coverage = 'Cover Building Non CAT - Limit Over 100000' WHERE product = 'Dive Store' and `key` = 'limitOver100000CoverBuildingNonCat';
UPDATE premium_rate_card SET coverage = 'Cover Building Non CAT - Limit Over 250000' WHERE product = 'Dive Store' and `key` = 'limitOver250000CoverBuildingNonCat';
UPDATE premium_rate_card SET coverage = 'Cover Building Non CAT - Limit Over 500000' WHERE product = 'Dive Store' and `key` = 'limitOver500000CoverBuildingNonCat';

UPDATE premium_rate_card SET coverage = 'Cover Business Income CAT - Limit Over 0' WHERE product = 'Dive Store' and `key` = 'limitOver0CoverBusIncomeCat';
UPDATE premium_rate_card SET coverage = 'Cover Business Income CAT - Limit Over 100000' WHERE product = 'Dive Store' and `key` = 'limitOver100000CoverBusIncomeCat';
UPDATE premium_rate_card SET coverage = 'Cover Business Income CAT - Limit Over 250000' WHERE product = 'Dive Store' and `key` = 'limitOver250000CoverBusIncomeCat';
UPDATE premium_rate_card SET coverage = 'Cover Business Income CAT - Limit Over 500000' WHERE product = 'Dive Store' and `key` = 'limitOver500000CoverBusIncomeCat';

UPDATE premium_rate_card SET coverage = 'Cover Business Income Non CAT - Limit Over 0' WHERE product = 'Dive Store' and `key` = 'limitOver0CoverBusIncomeNonCat';
UPDATE premium_rate_card SET coverage = 'Cover Business Income Non CAT - Limit Over 100000' WHERE product = 'Dive Store' and `key` = 'limitOver100000CoverBusIncomeNonCat';
UPDATE premium_rate_card SET coverage = 'Cover Business Income Non CAT - Limit Over 250000' WHERE product = 'Dive Store' and `key` = 'limitOver250000CoverBusIncomeNonCat';
UPDATE premium_rate_card SET coverage = 'Cover Business Income Non CAT - Limit Over 500000' WHERE product = 'Dive Store' and `key` = 'limitOver500000CoverBusIncomeNonCat';


UPDATE premium_rate_card SET coverage = 'TAEO (Under 100k)' WHERE product = 'Dive Store' and `key` = 'TAEOunder100k';
UPDATE premium_rate_card SET coverage = 'TAEO (100K to 500K)' WHERE product = 'Dive Store' and `key` = 'TAEO100kTo500k';

UPDATE premium_rate_card SET coverage = 'Pool Liability Over 0' WHERE product = 'Dive Store' and `key` = 'poolLiabilityOver0';
UPDATE premium_rate_card SET coverage = 'Pool Liability Over 20K' WHERE product = 'Dive Store' and `key` = 'poolLiabilityOver20k';
UPDATE premium_rate_card SET coverage = 'Pool Liability Over 50K' WHERE product = 'Dive Store' and `key` = 'poolLiabilityOver50k';

UPDATE premium_rate_card SET coverage = 'ProRata Factor' WHERE product = 'Dive Store' and `key` in ('proRataFactorsJan','proRataFactorsFeb','proRataFactorsMar','proRataFactorsApr','proRataFactorsMay','proRataFactorsJun','proRataFactorsJul','proRataFactorsAug','proRataFactorsSep','proRataFactorsOct','proRataFactorsNov','proRataFactorsDec');

UPDATE premium_rate_card SET coverage = 'PADI Fee',coverage_category = 'STORE_PADI_FEE' WHERE product = 'Dive Store' and `key` = 'padiFee';

UPDATE premium_rate_card SET coverage = '1M',coverage_category = 'EXCESS_LIABILITY' WHERE product = 'Dive Store' and `key` = 'excessLiabilityCoverage1M';
UPDATE premium_rate_card SET coverage = '2M',coverage_category = 'EXCESS_LIABILITY' WHERE product = 'Dive Store' and `key` = 'excessLiabilityCoverage2M';
UPDATE premium_rate_card SET coverage = '3M',coverage_category = 'EXCESS_LIABILITY' WHERE product = 'Dive Store' and `key` = 'excessLiabilityCoverage3M';
UPDATE premium_rate_card SET coverage = '4M',coverage_category = 'EXCESS_LIABILITY' WHERE product = 'Dive Store' and `key` = 'excessLiabilityCoverage4M';
UPDATE premium_rate_card SET coverage = '9M',coverage_category = 'EXCESS_LIABILITY' WHERE product = 'Dive Store' and `key` = 'excessLiabilityCoverage9M';
