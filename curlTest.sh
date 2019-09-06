#!/bin/bash

MSG="${1:-test msg}"
CHANNEL="${2:-test-readonly}"

USERNAME="user_b"
PASSWORD="password"
SERVER="https://testrocket"
USERID="userid_of_user_b"

# GET authToken
AUTHTOKEN="$(curl -s ${SERVER}/api/v1/login -d "username=${USERNAME}&password=${PASSWORD}" | grep "authToken" | cut -d"\"" -f4)"

# post message
curl -s -H "X-Auth-Token: ${AUTHTOKEN}" \
     -H "X-User-Id: ${USERID}" \
     -H "Content-type:application/json" \
     ${SERVER}/api/v1/chat.postMessage \
     -d '{"channel": "#'${CHANNEL}'", "text": "'"${MSG}"'"}'

# logout                
curl -s -H "X-Auth-Token: ${AUTHTOKEN}" \
     -H "X-User-Id: ${USERID}" ${SERVER}/api/v1/logout
