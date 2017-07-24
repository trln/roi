CREATE VIEW SumTitleUsage AS
SELECT loc_PUB_Titles.PUB_ISSNL, loc_TRLN_Costs.TRLN_Year, loc_PUB_Titles.PUB_Title, loc_TRLN_Libraries.TRLN_LibraryName, loc_TRLN_Universities.TRLN_UniversityName, loc_SERSOL_Providers.SERSOL_ProvName, Sum(COUNTER_Jan+COUNTER_Feb+COUNTER_Mar+COUNTER_Apr+COUNTER_May+COUNTER_Jun) AS Total_COUNTER_JanJun, Sum(COUNTER_Jul+COUNTER_Aug+COUNTER_Sep+COUNTER_Oct+COUNTER_Nov+COUNTER_Dec) AS Total_COUNTER_JulDec, Sum(COUNTER_Jan+COUNTER_Feb+COUNTER_Mar+COUNTER_Apr+COUNTER_May+COUNTER_Jun +COUNTER_Jul+COUNTER_Aug+COUNTER_Sep+COUNTER_Oct+COUNTER_Nov+COUNTER_Dec) AS Total_COUNTER_AllMonths, CONCAT(loc_TRLN_Costs.PUB_ISSNL, loc_TRLN_Costs.TRLN_Year, loc_TRLN_Libraries.TRLN_LibraryName) AS 'Key' 
FROM loc_SERSOL_Providers INNER JOIN (loc_SERSOL_Databases INNER JOIN (loc_PUB_Titles INNER JOIN (loc_TRLN_Universities INNER JOIN (loc_TRLN_Costs INNER JOIN (loc_COUNTER_JR1 INNER JOIN loc_TRLN_Libraries ON loc_COUNTER_JR1.TRLN_UniversityID = loc_TRLN_Libraries.TRLN_UniversityID) ON (loc_TRLN_Libraries.TRLN_LibraryID = loc_TRLN_Costs.TRLN_LibraryID) AND (loc_TRLN_Costs.PUB_ISSNL = loc_COUNTER_JR1.PUB_ISSNL) AND (loc_TRLN_Costs.TRLN_Year = loc_COUNTER_JR1.TRLN_Year)) ON loc_TRLN_Universities.TRLN_UniversityID = loc_COUNTER_JR1.TRLN_UniversityID) ON loc_PUB_Titles.PUB_ISSNL = loc_TRLN_Costs.PUB_ISSNL) ON loc_SERSOL_Databases.SERSOL_DBCode = loc_TRLN_Costs.SERSOL_DBCode) ON (loc_SERSOL_Providers.SERSOL_ProvID = loc_SERSOL_Databases.SERSOL_ProvID) AND (loc_SERSOL_Providers.SERSOL_ProvID = loc_COUNTER_JR1.SERSOL_ProvID)
GROUP BY loc_PUB_Titles.PUB_ISSNL, loc_TRLN_Costs.TRLN_Year, loc_PUB_Titles.PUB_Title, loc_TRLN_Libraries.TRLN_LibraryName, loc_TRLN_Universities.TRLN_UniversityName, loc_SERSOL_Providers.SERSOL_ProvName, CONCAT(loc_TRLN_Costs.PUB_ISSNL, loc_TRLN_Costs.TRLN_Year, loc_TRLN_Libraries.TRLN_LibraryName);