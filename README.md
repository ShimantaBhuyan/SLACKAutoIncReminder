# SLACKAutoIncReminder
This repo contains scripts to find the AUTO_INCREMENT id's in the tables of a MySQL database and check whether they are nearing their end. If they are above a certain limit(defined in the script), an alert is sent to a SLACK channel with the use of their API's Incoming Webhooks.
The autoIncReminder.php script alerts a message to the SLACK channel of tables whose AUTO_INCREMENT id's are above the limit defined.
The autoIncReminder2.php script alerts a message to the SLACK channel of all tables and their percentage completion of the AUTO_INCREMENT id's.

# PRE REQUISITES
1. Create a Slack app (if you don't have one already)
2. Enable Incoming Webhooks
3. Create an Incoming Webhook
4. Get the incoming webhook url
5. Modify url in the scripts
6. Modify MySQL database parameters in the script
