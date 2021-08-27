callback.URL = 'http4://172.16.1.49:8080'
routes {
    route = [
            ['from':'activemq:topic:FILE', 'to':["${callback.URL}/fileindexer"]]
    ]
}
