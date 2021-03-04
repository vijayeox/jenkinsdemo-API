IP=`hostname -I | awk '{ print $1 }'`
sed -ri -e "s/^HOST=.*/HOST=$IP/" \
	-ri -e "s/^DB_HOST=.*/DB_HOST=$IP/" \
	.env

docker-compose up -d --build