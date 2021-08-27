if(System.getenv("HOST")){
    callback.URL = "http4://${System.getenv("HOST")}:8080"
} else {
    callback.URL = "http4://localhost:8080"
}
routes {
    route = [
        ['from':'activemq:topic:ACCOUNT_ADDED', 'to':["${callback.URL}/callback/chat/addaccount"]],
        ['from':'activemq:topic:ACCOUNT_UPDATED', 'to':["${callback.URL}/callback/chat/updateaccount"]],
        ['from':'activemq:topic:ACCOUNT_DELETED', 'to':["${callback.URL}/callback/chat/deleteaccount"]],
        ['from':'activemq:topic:USERTOACCOUNT_ADDED', 'to':["${callback.URL}/callback/chat/adduser"]],
        

        ['from':'activemq:topic:PROJECT_ADDED', 'to':["${callback.URL}/callback/task/addproject",
                                                      "${callback.URL}/callback/chat/createchannel"]],
        ['from':'activemq:topic:PROJECT_UPDATED', 'to':["${callback.URL}/callback/task/updateproject",
                                                        "${callback.URL}/callback/chat/updatechannel"]],
        ['from':'activemq:topic:PROJECT_DELETED', 'to':["${callback.URL}/callback/task/deleteproject",
                                                        "${callback.URL}/callback/chat/deletechannel"]],

        ['from':'activemq:topic:DELETION_USERFROMPROJECT', 'to':["${callback.URL}/callback/task/deleteuserfromtasktracker"]],
        ['from':'activemq:topic:ADDITION_USERTOPROJECT', 'to':["${callback.URL}/callback/task/addusertotasktracker"]],

        ['from':'activemq:topic:USERTOPROJECT_ADDED', 'to':["${callback.URL}/callback/chat/addusertochannel"]],
        ['from':'activemq:topic:USERTOPROJECT_DELETED', 'to':["${callback.URL}/callback/chat/removeuserfromchannel"]],

        ['from':'activemq:topic:GROUP_ADDED', 'to':["${callback.URL}/callback/task/creategroup",
                                                    "${callback.URL}/callback/chat/createchannel"]],
        ['from':'activemq:topic:GROUP_UPDATED', 'to':["${callback.URL}/callback/task/updategroup",
                                                        "${callback.URL}/callback/chat/updatechannel"]],
        ['from':'activemq:topic:GROUP_DELETED', 'to':["${callback.URL}/callback/task/deletegroup",
                                                        "${callback.URL}/callback/chat/deletechannel"]],
        ['from':'activemq:topic:USERTOGROUP_ADDED', 'to':["${callback.URL}/callback/chat/addusertochannel"]],
        ['from':'activemq:topic:USERTOGROUP_DELETED', 'to':["${callback.URL}/callback/chat/removeuserfromchannel"]],
        ['from':'activemq:topic:USERTOGROUP_UPDATED', 'to':["${callback.URL}/callback/task/updategroupusers"]],

        ['from':'activemq:topic:ADD_CALENDAR_EVENT', 'to':["${callback.URL}/callback/calendar/addevent"]],
        ['from':'activemq:topic:USER_ADDED', 'to':["${callback.URL}/callback/ox/createuser",
                                                    "${callback.URL}/callback/chat/adduser",
                                                    "${callback.URL}/app/da8f0152-b8d3-43bf-8090-40103bb98d5e/delegate/UserMigration"]],
        ['from':'activemq:queue:FILE_ADDED', 'to':["${callback.URL}/callback/file/update",
                                                    "${callback.URL}/fileindexer/file"]],
        ['from':'activemq:queue:FILE_UPDATED', 'to':["${callback.URL}/callback/file/update",
                                                     "${callback.URL}/fileindexer/file"]],
        ['from':'activemq:queue:FILE_DELETED', 'to':["${callback.URL}/fileindexer"]],
        ['from':'activemq:topic:PROCESS_BATCH_INDEX', 'to':["${callback.URL}/fileindexer/batch"]],
        ['from':'activemq:topic:SEND_SMS', 'to':["${callback.URL}/callback/communication/sendsms"]],
        ['from':'activemq:topic:MAKE_CALL', 'to':["${callback.URL}/callback/communication/makecall"]],
        ['from':'activemq:topic:COMMANDS', 'to':["${callback.URL}/callback/workflow/servicetask"]],
            ['from':'activemq:topic:SAVE_CHAT_BOT', 'to':["${callback.URL}/callback/chat/savebot"]],
            ['from':'activemq:topic:DISABLE_CHAT_BOT', 'to':["${callback.URL}/callback/chat/disablebot"]],
            ['from':'activemq:topic:CHAT_APPBOT_NOTIFICATION', 'to':["${callback.URL}/callback/chat/appbotnotification"]],
            ['from':'activemq:topic:DOCUMENT_SIGNED', 'to':["${callback.URL}/callback/esign/finalized"]]
        // ['from':'activemq:topic:USER_ADDED', 'to':["${callback.URL}"]],
        // ['from':'activemq:topic:USER_DELETED', 'to':["${callback.URL}"]]
    ]
}