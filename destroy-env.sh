#!/bin/bash

echo "destroy script"
if [ "$#" -ne 1 ]; then
   echo "You have some missing parameters"
   echo -n "the paramters should be displayed in this format:"
   echo -n " 1) SQS queue name"
   exit 0
else
echo "all parameters conditions are matching ..we can go ahead"
fi


echo "instances are"



Instance=`aws ec2 describe-instances --filters "Name=instance-state-code,Values=16" --query 'Reservations[*].Instances[*].InstanceId'`

echo "scaling roups are"

$scaling=` aws autoscaling describe-auto-scaling-groups --output json | grep AutoScalingGroupName | sed "s/[\"\:\, ]//g" | sed "s/AutoScalingGroupName//g"`


echo "updating auto scaling groups"


aws autoscaling update-auto-scaling-group --auto-scaling-group-name $scaling --min-size 0 --max-size 0 --desired-capacity 0


echo "wait this instances are deleted"

aws ec2 wait instance-terminated --instance-ids $Instance


echo "load balancers in aws are"
$loadbalancer =`aws elb describe-load-balancers --output json | grep LoadBalancerName | sed "s/[\"\:\, ]//g" | sed "s/LoadBalancerName//g"`
echo "load balancers are $loadbalancer"

aws autoscaling detach-load-balancers --load-balancer-names $loadbalancer --auto-scaling-group-name $scaling


echo "launch configuration is"
$config=`aws autoscaling describe-launch-configurations --output json | grep LaunchConfigurationName | sed "s/[\"\:\, ]//g" | sed "s/LaunchConfigurationName//g"`
aws autoscaling delete-launch-configuration --launch-configuration-name $config
aws elb delete-load-balancer --load-balancer-name $loadbalancer

echo "rds in the aws is"
$rds=`aws rds describe-db-instances --output json | grep "\"DBInstanceIdentifier" | sed "s/[\"\:\, ]//g" | sed "s/DBInstanceIdentifier//g"`

echo "deleting rds"

aws rds delete-db-instance --db-instance-identifier $rds  --skip-final-snapshot --output text
aws rds wait db-instance-deleted --db-instance-identifier $rds  --output text

echo " s3 buckets are"
$s3=`aws s3api list-buckets --query 'Buckets[].Name'`
`
aws s3api delete-bucket --bucket $s3 --region us-west-2

echo" sns topics listing"
$snstopics=`aws sns list-topics --output json | grep TopicArn | sed "s/[\"\:\, ]//g" | sed "s/TopicArn//g"`

aws sns delete-topic --topic-arn $snstopic
aws sns unsubscribe --subscription-arn $snstopic


echo "deleting sqs queue"
$sqs=`aws sqs get-queue-url --queue-name $1`
aws sqs delete-queue --queue-url $sqs



echo "all done"
