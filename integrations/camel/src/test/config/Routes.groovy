callback.URL = 'http4://192.168.225.48:8080'

routes {
    route = [
        ['from':'activemq:topic:ORGANIZATION_ADDED', 'to':["${callback.URL}/callback/chat/addorg",
                                                           "${callback.URL}/callback/crm/addorg"]]
    ]
}
    