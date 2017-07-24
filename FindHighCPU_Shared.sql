CREATE VIEW FindHighCPU_Shared AS
SELECT loc_PUB_Titles.PUB_ISSNL, loc_PUB_Titles.PUB_Title, loc_PUB_Titles.LC_Class, ROUND(temp_TitleSummaryShared.Year1Cost, 2) AS Year1Cost, ROUND(temp_TitleSummaryShared.Year1CPU, 2) AS Year1CPU, ROUND(temp_TitleSummaryShared.Year2CPU, 2) AS Year2CPU, ROUND(temp_TitleSummaryShared.Year3CPU, 2) AS Year3CPU, ROUND(temp_TitleSummaryShared.Year4CPU, 2) AS Year4CPU, ROUND(((temp_TitleSummaryShared.Year1CPU + temp_TitleSummaryShared.Year2CPU + temp_TitleSummaryShared.Year3CPU + temp_TitleSummaryShared.Year4CPU) / 4), 2) AS '4YearAvg'
FROM loc_PUB_Titles INNER JOIN temp_TitleSummaryShared ON loc_PUB_Titles.PUB_ISSNL = temp_TitleSummaryShared.PUB_ISSNL
WHERE (((temp_TitleSummaryShared.Year1Cost)>0) AND ((temp_TitleSummaryShared.Year1CPU) Is Not Null))
ORDER BY temp_TitleSummaryShared.Year1CPU DESC LIMIT 25;
