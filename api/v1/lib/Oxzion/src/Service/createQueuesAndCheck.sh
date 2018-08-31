#!/bin/bash
# Check if gedit is running
# -x flag only match processes whose name (or command line if -f is
# specified) exactly match the pattern. 
numprocesses=$(ps ax | grep 'SentinelMaster.php' | wc -l)
inumprocesses=$(ps ax | grep 'MasterQueueListener.php' | wc -l)
qnumprocesses=$(ps ax | grep 'ElasticQueueListener.php' | wc -l)
anumprocesses=$(ps ax | grep 'AttachmentQueueListener.php' | wc -l)
if  [[ $numprocesses -lt 2 ]] ; then
   echo "Starting Sentinel Master";
    nohup php /home/clubvaco/public_html/library/VA/ExternalLogic/SentinelMaster.php &
fi

if  [[ $inumprocesses -lt 2 ]] ; then
   echo "Starting Queue Master";
    nohup php /home/clubvaco/public_html/library/VA/ExternalLogic/MasterQueueListener.php &
fi
if  [[ $inumprocesses -lt 2 ]] ; then
   echo "Starting Index Queue Master";
    nohup php /home/clubvaco/public_html/library/VA/ExternalLogic/ElasticQueueListener.php &
fi
if  [[ $anumprocesses -lt 2 ]] ; then
   echo "Starting Attachment Queue Master";
    nohup php /home/clubvaco/public_html/library/VA/ExternalLogic/AttachmentQueueListener.php &
fi
exit;