#!/bin/bash

Instance=`aws ec2 describe-instances --filters "Name=instance-state-code,Values=16" --query 'Reservations[*].Instances[*].InstanceId'`
aws autoscaling update-auto-scaling-group --auto-scaling-group-name server-rg  --min-size 0 --max-size 0 --desired-capacity 0

aws ec2 wait instance-terminated --instance-ids $Instance
aws autoscaling detach-load-balancers --load-balancer-names itmo-544 --auto-scaling-group-name server-rg
aws autoscaling delete-auto-scaling-group --auto-scaling-group-name server-rg
aws autoscaling delete-launch-configuration --launch-configuration-name  itmo-544-lcn
aws elb delete-load-balancer --load-balancer-name itmo-544
