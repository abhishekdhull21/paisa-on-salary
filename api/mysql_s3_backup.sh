#!/bin/bash

# MySQL credentials
USER="sot_admin"
PASSWORD="sot_admin@!121"
HOST="localhost"
DATABASE="lms_sotcrm"

# AWS S3 settings
S3_BUCKET="s3://backups-sot/databases"
FILE_NAME="lms_sotcrm_$(date +'%Y%m%d_%H%M%S').sql.gz"
AWS_PROFILE="default"  # Ensure this matches the profile where your credentials are stored

# Log file location
LOG_FILE="/home/ubuntu/scripts/logs/mysql_s3_backup.log"

# Check if AWS CLI is installed
if ! command -v aws &> /dev/null
then
    echo "AWS CLI not installed. Please install it and configure your credentials." | tee -a $LOG_FILE
    exit 1
fi

# Ensure the log directory exists
mkdir -p ~/scripts/logs

# Start backup and log
echo -e "\nStarting backup of MySQL database '$DATABASE' at $(date)" | tee -a $LOG_FILE

# Dump MySQL database, compress with gzip, and upload to S3 directly using the specified AWS profile
mysqldump -u $USER -p"$PASSWORD" --single-transaction --quick $DATABASE | pv | gzip | aws s3 cp - "$S3_BUCKET/$FILE_NAME" --profile $AWS_PROFILE

# Check if the upload was successful
if [ $? -eq 0 ]; then
    echo "Backup successfully uploaded to S3: $S3_BUCKET/$FILE_NAME at $(date)" | tee -a $LOG_FILE
else
    echo "Failed to upload backup to S3." | tee -a $LOG_FILE
    exit 1
fi

echo "Backup process completed at $(date)" | tee -a $LOG_FILE

exit 0
