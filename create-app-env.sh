echo " creating database instance"
if [ "$#" -ne 4 ]; then
   echo "You have some missing parameters"
   echo -n "the paramters should be displayed in this format:"
   echo -n " 1) Rds db instance identifier name"
   echo -n "2)db name"
   echo -n "3)s3 bucket name1"
   echo -n "4) s3 bucket name 2"
   
   exit 0
else
echo "all parameters conditions are matching ..we can go ahead"
fi
#Creating database subnet group
#aws rds create-db-subnet-group --db-subnet-group-name ITMO544-rg-subnet-group --db-subnet-group-description "subnet group for rg" --subnet-ids subnet-cf82a1ab

#Creating database instance
Dbinstance=`aws rds create-db-instance --db-name $2  --db-instance-identifier $1 --db-instance-class db.t1.micro --engine MySQL --master-username controller1 --master-user-password mastirg_6 --allocated-storage 5 --vpc-security-group-ids sg-1db27864 --publicly-accessible --port 3306 --allocated-storage 5`


echo "Please wait until the database instance is available. This might take a lot of time."
aws rds wait db-instance-available --db-instance-identifier $Dbinstance


#Creating a read replica
#aws rds create-db-instance-read-replica --db-instance-identifier rg-db-read --source-db-instance-identifier rg-db --publicly-accessible

## SNS generation
topicARN=(`aws sns create-topic --name SNSMetric`)
aws sns set-topic-attributes --topic-arn $topicARN --attribute-name DisplayName --attribute-value SNSMetric

aws sns subscribe --topic-arn $topicARN --protocol email --notification-endpoint rgupta28@hawk.iit.edu

## SQS generation
QueueUrl =(`aws sqs create-queue --queue-name radhika-inclass`)


##S3 bucket
aws S3
aws S3 ls
#aws s3 mb s3://$1
#aws s3 mb s3://$2
aws s3api create-bucket --bucket $3 --region us-west-2
aws s3api create-bucket --bucket $4 --region us-west-2
