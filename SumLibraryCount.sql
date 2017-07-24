CREATE VIEW SumLibraryCount AS
SELECT loc_TRLN_Costs.TRLN_Year, Count(*) AS CountSubs, loc_TRLN_Libraries.TRLN_LibraryName, loc_SERSOL_Providers.SERSOL_ProvName
FROM loc_SERSOL_Providers INNER JOIN (loc_TRLN_Universities INNER JOIN (loc_TRLN_Libraries INNER JOIN (loc_SERSOL_Databases INNER JOIN loc_TRLN_Costs ON loc_SERSOL_Databases.SERSOL_DBCode = loc_TRLN_Costs.SERSOL_DBCode) ON loc_TRLN_Libraries.TRLN_LibraryID = loc_TRLN_Costs.TRLN_LibraryID) ON loc_TRLN_Universities.TRLN_UniversityID = loc_TRLN_Libraries.TRLN_UniversityID) ON loc_SERSOL_Providers.SERSOL_ProvID = loc_SERSOL_Databases.SERSOL_ProvID
WHERE (((loc_TRLN_Costs.PUB_ISSNL) Not Like "F%"))
GROUP BY loc_TRLN_Costs.TRLN_Year, loc_TRLN_Libraries.TRLN_LibraryName, loc_SERSOL_Providers.SERSOL_ProvName;