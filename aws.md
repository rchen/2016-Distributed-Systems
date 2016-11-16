# Create spark server in aws.

## use spark-ec2 script to create spark server.

bash shell

```
export AWS_SECRET_ACCESS_KEY=
export AWS_ACCESS_KEY_ID=

./spark-ec2/spark-ec2 \ 
    --key-pair=spark \ --identity-file=/spark.pem \ # ec2 key pair    
    --region=ap-northeast-1 \ 						# server region
    --zone=ap-northeast-1a \ 						# server zone
    --slaves=1 --master-instance-type=m3.large \	# slave number and 
    --instance-type=m3.large \ 						# msater instance type
    --spark-version=1.6.0 \ 						# use spark version
    --hadoop-major-version=2 \ 						# hadoop version
    launch  grant-spark								# launch and spark name.
```



