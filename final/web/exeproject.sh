#!/bin/bash
ecode="061"
datestr=`date +%m%d%H%M%S`

if [ ! -z "$1" ]; then
   ecode=$1
fi 

/usr/bin/bq query -n 0 --destination_table=dsfinaldb.gdelt_$datestr --allow_large_results \
"SELECT SQLDATE,Year,Actor1CountryCode,Actor2CountryCode,EventCode FROM [gdelt-bq:full.events] \
WHERE (Year == 2015 OR Year == 2016) AND Actor1CountryCode IS NOT NULL AND \
Actor2CountryCode IS NOT NULL AND Actor1CountryCode <> Actor2CountryCode AND \
EventCode LIKE '$ecode%' ORDER BY SQLDATE DESC LIMIT 20000000"

echo "gdelt_$datestr" > log/$1_$datestr.log

stime=`date +%s`
/usr/bin/spark-submit /var/www/html/dsfinal-project_2.11-1.8.jar dsfinaldb gdelt_$datestr output_$datestr
etime=`date +%s`
runtime=$((etime-stime))
echo "output_$datestr" >> log/$1_$datestr.log
echo "$runtime" >> log/$1_$datestr.log

echo "<?php \$output_$datestr='" > log/$1_$datestr.php
/usr/bin/bq query -n 300 --format=json "SELECT * FROM [dsfinaldb.output_$datestr] ORDER BY ranking2016 LIMIT 300" >> log/$1_$datestr.php
echo "'; \$output=trim(\$output_$datestr);" >> log/$1_$datestr.php
