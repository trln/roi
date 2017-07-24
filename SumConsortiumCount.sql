CREATE VIEW SumConsortiumCount AS 
SELECT loc_TRLN_Costs.TRLN_Year, Count(*) AS CountSubs, loc_SERSOL_Providers.SERSOL_ProvName
FROM loc_SERSOL_Providers INNER JOIN (loc_SERSOL_Databases INNER JOIN loc_TRLN_Costs ON loc_SERSOL_Databases.SERSOL_DBCode = loc_TRLN_Costs.SERSOL_DBCode) ON loc_SERSOL_Providers.SERSOL_ProvID = loc_SERSOL_Databases.SERSOL_ProvID
WHERE (((loc_TRLN_Costs.PUB_ISSNL) Not Like "F%"))
GROUP BY loc_TRLN_Costs.TRLN_Year, loc_SERSOL_Providers.SERSOL_ProvName;
