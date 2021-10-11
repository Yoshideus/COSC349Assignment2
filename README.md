# COSC349Assignment2

This is built to support two EC2 virtual machines, a MySQL RDS for data storage, and Lambda functionality. One of the EC2 machines runs the web interface for the application and the other runs a data reporting program for the admin. The Lambda functions allow an admin to easily activate or deactivate the system at once.

Users can make accounts, log in, and play against other users, slowly building up statistics which are displayed. Score is also tracked which is evaluated on the leaderboard. It is a turn based sort of gameplay. You make one turn and then send it to you're opponent and wait till they send you a move back. There is also a database reporting feature through the terminal. There are a few preset accounts in the system for example so you don't have to set too much up but to see how games work you will have to make them yourself.

The files in this directory are to support the database set up but you will need to set up the virtual machines and database (and optionally the lambda functions) yourself.

## Interactions
The web server interacts with the database through mysqli in php using the admin user. This tool allows it to read and write data to and from the database. The reporting server also interacts with the database but in a purely reading based functionality. Whereas the web server can update and write data as well as read, the reporting is purely for reading so it can make reports and not interfere with the data. The EC2 don’t directly interact with each other but indirectly interact through the changes in the data in the RDS. The lambda functions interact with all instances, as they can start and stop them.

## Set Up
Set up RDS:
Follow this to set up:
https://aws.amazon.com/getting-started/hands-on/create-mysql-db/

For connecting to it use this:
https://docs.aws.amazon.com/AmazonRDS/latest/UserGuide/USER_ConnectToInstance.html
- Use the terminal and then used the database.sql file to fill it's structure.
- Put in each command one by one. Make sure you do Use <database name> after you create it.

```PHP
$db_host   = '<RDS Link';
$db_name   = '<database name';
$db_user   = 'admin';
$db_passwd = '<your password>';
```

Set up EC2 Instances:
https://docs.aws.amazon.com/AmazonRDS/latest/UserGuide/CHAP_Tutorials.WebServerDB.CreateWebServer.html
- Make the web server a t2.large instead
- Stop at Install an Apache web server with PHP

Check each php webpage and replace the database details with yours

```PHP
$db_host   = '<RDS Link';
$db_name   = '<database name';
$db_user   = 'admin';
$db_passwd = '<your password>';
```

To set up THIS web server (FOR WINDOWS):

Place the html folder into the ec2 machine
```bash
scp -r -i var/www/html ec2-user@<Web Server Instance Link>:
```

This enter it yourself
```bash
ssh -i "<Key Pair>.pem" ec2-user@<Web Server Instance Link>
```

Move it to the correct location
```bash
mv html ../../var/www
```

Then do Install an Apache web server with PHP but don't continue past that section

- Make a second one but don't install the web server onto it
- Use the same Key Pair and security group

Place the script into the ec2 machine
```bash
scp -i reporting.py ec2-user@<Reporting Instance Link>:
```

Use this as a guide to install python:
https://praneeth-kandula.medium.com/running-python-scripts-on-an-aws-ec2-instance-8c01f9ee7b2f

To install the right MySQL module run:
```bash
sudo pip install pymysql
```

If pip is not installed run:
```bash
sudo yum updated
```
or
```bash
sudo yum install pip
```

This was a key issue as the AWS EC2 don't have apt-get and many python modules like mysql.connector didn't work for me. This is a very popular module online and it is recommended at most places. I ended up install a lot of different python and mysql modules before it worked so it may be a mixture of modules. Keep trying different ones to find what works for you but this worked for me. 

Set up Lambda functions:
https://aws.amazon.com/premiumsupport/knowledge-center/start-stop-lambda-cloudwatch/
https://aws.amazon.com/blogs/database/schedule-amazon-rds-stop-and-start-using-aws-lambda/

- I used a mix of these 2 methods that can be shown here
- Make sure you set up both policies and a role

My mixture of these functions (start):
```python
import boto3
region = 'us-east-1'
instances = ['i-091fd67b3b5c0aea0', 'i-0db6797c27d668d0c']
rds = boto3.client('rds', region_name=region)
ec2 = boto3.client('ec2', region_name=region)
response = rds.describe_db_instances()

def lambda_handler(event, context):
    ec2.start_instances(InstanceIds=instances)
    print('started your ec2 instances: ' + str(instances))

    for i in response['DBInstances']:
        if i['DBInstanceStatus'] == 'available':
            print('{0} DB instance is already available'.format(i['DBInstanceIdentifier']))
        elif i['DBInstanceStatus'] == 'stopped':
            rds.start_db_instance(DBInstanceIdentifier = i['DBInstanceIdentifier'])
            print('Started DB Instance {0}'.format(i['DBInstanceIdentifier']))
        elif i['DBInstanceStatus']=='starting':
            print('DB Instance {0} is already in starting state'.format(i['DBInstanceIdentifier']))
        elif i['DBInstanceStatus']=='stopping':
            print('DB Instance {0} is in stopping state. Please wait before starting'.format(i['DBInstanceIdentifier']))
```
- You can use this to create your stop function too.

## Stop and Start AWS Instance

https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/Stop_Start.html

If Lambda functions set up, run start or stop functions.

## Usage
### Web Server
To use the web app, insert your Web Sever Instance Link and change https:// tp http:// into a web browser and you will be placed on the login / register page. Interact with it like any website.

### Reporting

To use the database reporting, while in the project directory where the application was booted, transfer into the reporting virtual machine.

```bash
ssh -i "<Key Pair>.pem" ec2-user@<Reporting Instance Link>
```

Then run the reporting script and follow its prompts

```bash
python reporting.py
```

To leave this virtual machine simply use CTRL+D

## Terminate instances

https://docs.aws.amazon.com/AWSEC2/latest/WindowsGuide/terminating-instances.html
