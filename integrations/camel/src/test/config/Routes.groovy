callback.URL = 'http4://localhost:8080'

routes {
    route = [
        ['from':'activemq:topic:ORGANIZATION_ADDED', 'to':["${callback.URL}/callback/chat/addorg",
                                                           "${callback.URL}/callback/crm/addorg"]]
    ]
}
    