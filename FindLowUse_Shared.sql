CREATE VIEW FindLowUse_Shared AS
SELECT loc_PUB_Titles.PUB_ISSNL, loc_PUB_Titles.PUB_Title, loc_PUB_Titles.LC_Class, ROUND(temp_TitleSummaryShared.Year1Cost, 2) AS Year1Cost, temp_TitleSummaryShared.Year1Use, temp_TitleSummaryShared.Year2Use
FROM loc_PUB_Titles INNER JOIN temp_TitleSummaryShared ON loc_PUB_Titles.PUB_ISSNL = temp_TitleSummaryShared.PUB_ISSNL
WHERE (((loc_PUB_Titles.PUB_ISSNL) Not Like "FULL%") AND ((temp_TitleSummaryShared.Year1Cost)>0))
ORDER BY temp_TitleSummaryShared.Year1Use LIMIT 25;