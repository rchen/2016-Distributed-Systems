#GDELT Bigquery
<https://cloud.google.com/bigquery/public-data/gdelt-books>
#Bigquery SQL
	SELECT
  		SQLDATE,Year,
  		Actor1CountryCode,
  		Actor2CountryCode,
  		EventRootCode //EventCode
	FROM [gdelt-bq:full.events]
	WHERE
  		(Year == 2015 OR Year == 2016)
  		AND Actor1CountryCode IS NOT NULL
  		AND Actor2CountryCode IS NOT NULL
  		AND Actor1CountryCode <> Actor2CountryCode
  		AND EventRootCode = '19' //EventCode = '061' Cooperate economically
	ORDER BY SQLDATE DESC LIMIT 20000000
