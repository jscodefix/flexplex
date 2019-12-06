#!/usr/bin/env python3
#
# Refer to the python code at:
# https://developer.atlassian.com/cloud/jira/software/rest/#api-rest-agile-1-0-board-boardId-sprint-get

import sys
import os
import requests # http://docs.python-requests.org
from requests.auth import HTTPBasicAuth
import json

server = 'https://athndev.atlassian.net/'

try:  
  user = os.environ["JIRA_API_USER"]
  apikey = os.environ["JIRA_API_KEY"]
except KeyError: 
  print("ERROR: shell environment variables required: JIRA_API_USER, JIRA_API_KEY")
  sys.exit(1)

# TODO: query for board id
# https://your-domain.atlassian.net/rest/agile/1.0/board/{boardId}/sprint
#url = "https://athndev.atlassian.net/rest/agile/1.0/board/4/sprint?startAt=0&maxResults=10"
auth = HTTPBasicAuth(user, apikey)
headers = {
   "Accept": "application/json"
}

sprint_names_active = []

def fetch_sprints():
  blockSize = 10
  offset = 0
  i = 0
  while True:
    url = f'https://athndev.atlassian.net/rest/agile/1.0/board/4/sprint?startAt={offset}&maxResults={blockSize}'
    response = requests.request(
      "GET",
      url,
      headers=headers,
      auth=auth
    )
    
    results = json.loads(response.text)
    #print(json.dumps(results, sort_keys=True, indent=4, separators=(",", ": ")))
  
    for sprint in results['values']:
      if( is_active_sprint(sprint) ):
        sprint_names_active.append( sprint['name'] )
  
    i += 1
    offset = i * blockSize
    #print("DEBUG: ", results['isLast'])
  
    if( results['isLast'] ):
      break

def is_active_sprint(sprint):
  #print("DEBUG:  name:  ", sprint['name'])
  #print("DEBUG:  state: ", sprint['state'])
  try:
    if( sprint['state'] == 'active' ):
      return True
  except KeyError:
      return False
  return False

# main ------------------------------------------------------------------------
fetch_sprints()
print(sprint_names_active)

# -----------------------------------------------------------------------------
# example results:
# {"maxResults"=>50, "startAt"=>0, "isLast"=>true, "values"=>[
#  {"id"=>7,
#   "self"=>"https://athndev.atlassian.net/rest/agile/1.0/sprint/7",
#   "state"=>"closed",
#   "name"=>"Release 92",
#   "startDate"=>"2019-01-18T13:49:55.705Z",
#   "endDate"=>"2019-02-01T13:49:00.000Z",
#   "completeDate"=>"2019-01-25T14:54:40.096Z",
#   "originBoardId"=>4,
#   "goal"=>""},
#        {
#            "endDate": "2019-11-08T12:50:00.000Z",
#            "goal": "",
#            "id": 54,
#            "name": "127",
#            "originBoardId": 4,
#            "self": "https://athndev.atlassian.net/rest/agile/1.0/sprint/54",
#            "startDate": "2019-11-03T15:46:00.000Z",
#            "state": "active"
#        },
#        {
#            "endDate": "2019-11-15T12:28:00.000Z",
#            "goal": "",
#            "id": 68,
#            "name": "128",
#            "originBoardId": 4,
#            "self": "https://athndev.atlassian.net/rest/agile/1.0/sprint/68",
#            "startDate": "2019-11-11T14:24:13.070Z",
#            "state": "active"
#        },
#        {
#            "goal": "",
#            "id": 67,
#            "name": "CDC Updates - Specimen Form",
#            "originBoardId": 4,
#            "self": "https://athndev.atlassian.net/rest/agile/1.0/sprint/67",
#            "state": "future"
#        },

# Ruby code:
#sprints['values'].each do |sprint|
#  puts "sprint -> id: #{sprint['id']}"
#  puts "  name: #{sprint['name']}"
#  puts "  state: #{sprint['state']}"
#  puts "  startDate: #{sprint['startDate']}"
#  puts "  endDate: #{sprint['endDate']}"
#  puts "  completeDate: #{sprint['completeDate']}"
#  puts "  originBoardId: #{sprint['originBoardId']}"
#  puts "  goal: #{sprint['goal']}"
#end

