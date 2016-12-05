# rgupta28
ITMO-544
Hello Prof.
sorry for late instructions
In create-app.sh
Parameters need to be added is
ami,keyname,security group,launch cinfig name, count and iam profile

In create-app.sh
Parameters need to be added is
rds instance identifier name, db name,  s3 bucket 1 name and s3 bucket 2 name

In destroy.sh 
you need to add sqs queue name to run script


In install-app.sh
there is procedure for all the instructions we need to do while we login to instance like which tools to install like php curl and how to clone directory

First using create-env.sh script we will cretae instances , load balancers, auto scaling group and launch configuration using IAM PROFILE.
Then after this using create-app.sh we will create a database with db-instance-identifier rg-db and username controller1 and password radhika6 and then s3 busckets and then SNS and SQS service we will create.

Using install-app.sh we can login into instance...load php tools to render php pages, we will create index.php using userid "rgupta28@hawk.iit.edu" and password radhika . INfact you can login with your hawkid and password ilovebunnies
which is login pag and we will create a table in which we will insert values.
Then s3test.php is to upload  an image using s3 bucket.
DBtest.php is to create a database.
Then galery.php is to see all pages using upload a user can upload , using admin user can be on same page itself and logout will display index.php page.
Then upload will upload the file to server
then uploader will display error message regarding file is uploaded or not.

After rendering all php pages which help in creating an application on server
we will destroy all that we created like first it will delete instance then launch configuration load balancer and then auto scaling group and then RDS
