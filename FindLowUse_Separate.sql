CREATE VIEW FindLowUse_Separate AS
SELECT loc_PUB_Titles.PUB_ISSNL, loc_PUB_Titles.PUB_Title, loc_PUB_Titles.LC_Class, ROUND(temp_TitleSummarySeparate.Year1Cost, 2) AS Year1Cost, temp_TitleSummarySeparate.Year1Use, temp_TitleSummarySeparate.Year2Use
FROM temp_TitleSummarySeparate INNER JOIN loc_PUB_Titles ON temp_TitleSummarySeparate.PUB_ISSNL = loc_PUB_Titles.PUB_ISSNL
WHERE (((loc_PUB_Titles.PUB_ISSNL) Not Like "FULL%") AND ((temp_TitleSummarySeparate.Year1Cost)>0))
ORDER BY temp_TitleSummarySeparate.Year1Use LIMIT 25;