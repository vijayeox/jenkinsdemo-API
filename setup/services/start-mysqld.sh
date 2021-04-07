#!/bin/bash
echo "=> Checking MySQL ..."
RET=$(pgrep mysql | wc -l)
echo $RET
if [ $RET -ne 0 ]; then
    echo "=> Starting MySQL ..."
    if [ -e /var/run/mysqld/mysqld.sock ];then
        rm /var/run/mysqld/mysqld.sock
    fi

    /usr/bin/mysqld_safe > /dev/null 2>&1 &

    RET=0
    while [[ RET -ne 0 ]]; do
        echo "=> Waiting for confirmation of MySQL service startup"
        sleep 5
        RET=$(pgrep mysql | wc -l)
    done
fi
echo "=> MySQL Running ..."
