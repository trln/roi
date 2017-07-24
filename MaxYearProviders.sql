CREATE VIEW MaxYearProviders AS
SELECT TotalSummaryConsortium.SERSOL_ProvName, Max(TotalSummaryConsortium.TRLN_Year) AS MaxOfTRLN_Year
FROM TotalSummaryConsortium
GROUP BY TotalSummaryConsortium.SERSOL_ProvName;