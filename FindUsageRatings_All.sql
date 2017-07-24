CREATE VIEW FindUsageRatings_All AS
SELECT SumTitleUsage.TRLN_Year, Count(SumTitleUsage.PUB_ISSNL) AS CountOfPUB_ISSNL, "High" AS UsageRating
FROM SumTitleUsage
WHERE (((SumTitleUsage.Total_COUNTER_AllMonths)>499))
GROUP BY "High", SumTitleUsage.TRLN_Year
UNION
SELECT SumTitleUsage.TRLN_Year, Count(SumTitleUsage.PUB_ISSNL) AS CountOfPUB_ISSNL, "Mid" AS UsageRating
FROM SumTitleUsage
WHERE (((SumTitleUsage.Total_COUNTER_AllMonths)>99) AND ((SumTitleUsage.Total_COUNTER_AllMonths)<500))
GROUP BY "Mid", SumTitleUsage.TRLN_Year
UNION SELECT SumTitleUsage.TRLN_Year, Count(SumTitleUsage.PUB_ISSNL) AS CountOfPUB_ISSNL, "Low" AS UsageRating
FROM SumTitleUsage
WHERE (((SumTitleUsage.Total_COUNTER_AllMonths)<100))
GROUP BY "Low", SumTitleUsage.TRLN_Year;
