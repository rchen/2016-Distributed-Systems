#!/bin/bash
days=10

rm *.CSV*
rm export.csv

if [ ! -z "$1" ]; then
   days=$1
fi

for (( i=1; i<=$days; i++ ))
do
   DATE=$(date -v -"$i"d +"%Y%m%d")
   curl -O --silent -J http://data.gdeltproject.org/events/"$DATE".export.CSV.zip
   unzip "$DATE".export.CSV.zip
done
cat *.CSV > export.csv
rm *.CSV*
