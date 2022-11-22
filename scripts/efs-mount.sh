#!/bin/bash
EC2="ec2-3-86-33-23.compute-1.amazonaws.com"
FS_ID="fs-07451f8ab8d7152f2.efs.us-east-1.amazonaws.com"


# efs-mount-point=/efs
sudo yum -y update 
sudo yum -y install nfs-utils


mkdir /var/www/html/WordPress/efs

sudo mount -t nfs -o nfsvers=4.1,rsize=1048576,wsize=1048576,hard,timeo=600,retrans=2,noresvport $FS_ID:/   /var/www/html/WordPress/efs
# sudo mount -t nfs -o nfsvers=4.1,rsize=1048576,wsize=1048576,hard,timeo=600,retrans=2,noresvport fs-07451f8ab8d7152f2.efs.us-east-1.amazonaws.com:/   ~/efs
echo "${FS_ID}:/ /var/www/html/WordPress/efs nfs4 nfsvers=4.1,rsize=1048576,wsize=1048576,hard,timeo=600,retrans=2,noresvport,_netdev 0 0" >> /etc/fstab

# echo "sudo mount -t efs ${FS_ID}:/ /var/www/html/WordPress/efs" >> /etc/fstab
# echo