#!/usr/bin/env ruby

require 'rubygems'
require 'pp'
require 'jira-ruby'

# Consider the use of :use_ssl and :ssl_verify_mode options if running locally for tests.
# NOTE: basic auth no longer works with Jira, you must generate an API token, to do so you must have jira instance access rights.
# You can generate a token here: https://id.atlassian.com/manage/api-tokens
# You will see JIRA::HTTPError (JIRA::HTTPError) if you attempt to use basic auth with your user's password

# jira-athn-jsheffel
user = 'jsheffel@athn.org'
apikey = 'I3Y714F6zd52Hpfd8x7wDD8D'
server = 'https://athndev.atlassian.net/'

options = {
  :username => user,
  :password => apikey,
  :site     => server,
  :context_path => '',  # used for local testing?, or path part of the url?
  :auth_type => :basic,
  :use_ssl => true,
  :read_timeout => 120
}

client = JIRA::Client.new(options)

# show all boards
boards = client.Board.all

boards.each do |board|
  puts "Board -> id: #{board.id}, name: #{board.name}"
end

#@client.Agile.get_sprints(BOARD_ID) # retrieve sprints
#client.Agile.get_backlog_issues(BOARD_ID, maxResults: ISSUE_READING_PAGE_SIZE) # limit results

