
To build the docker container
$ docker build -t activemq .

To run the docker container
$ docker run --network="host" -it activemq

//For Windows
$ docker run -p 61616:61616 -p 8161:8161 -p 5672:5672 -p 61613:61613 -p 61614:61614 activemq