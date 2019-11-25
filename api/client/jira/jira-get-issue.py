#!/usr/bin/env python
#
# 191111 jsheffel Initial version, not working
#
# NOTE: this script uses the Jira python library (which must be installed)

# library modules
from jira import JIRA

# jira-athn-jsheffel
user = 'jsheffel@athn.org'
apikey = 'somekey'
server = 'https://athn.atlassian.net'

options = {
 'server': server
}

jira = JIRA(options, basic_auth=(user,apikey) )

ticket = 'AR-162758490'
issue = jira.issue(ticket)

summary = issue.fields.summary

print('ticket: ', ticket, summary)
