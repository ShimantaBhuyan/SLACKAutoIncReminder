# SLACKAutoIncReminder
This repo contains scripts to find the AUTO_INCREMENT id's in the tables of a MySQL database and check whether they are nearing their end. If they are above a certain limit(defined in the script), an alert is sent to a SLACK channel with the use of their API's Incoming Webhooks.<br>
The autoIncReminder.php script alerts a message to the SLACK channel of tables whose AUTO_INCREMENT id's are above the limit defined.<br>
The autoIncReminder2.php script alerts a message to the SLACK channel of all tables and their percentage completion of the AUTO_INCREMENT id's.

### PRE REQUISITES
1. Create a Slack app (if you don't have one already)
2. Enable Incoming Webhooks
3. Create an Incoming Webhook
4. Get the incoming webhook url
5. Modify url in the scripts
6. Modify MySQL database parameters in the script

### A SLICK WAY TO IMPLEMENT ON A LINUX SERVER
From Wikipedia
> The software utility cron is a time-based job scheduler in Unix-like computer operating systems. People who set up and maintain software environments use cron to schedule jobs (commands or shell scripts) to run periodically at fixed times, dates, or intervals. 

We can set up a cron job to run this script on a periodic basis, like monthly or weekly and then get periodic alerts on our Slack channel.<br>
_How Slick!_
