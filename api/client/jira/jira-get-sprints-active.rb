#!/usr/bin/env ruby
# This script prints the current athndev Jira sprints by querying the Atlassian Jira server API.
# Two shell environment variables must be set:
#   JIRA_API_USER - the email address associated with a Jira account
#   JIRA_API_KEY - create at: Jira -> Account Settings -> Security -> API token (or https://id.atlassian.com/manage/api-tokens)
#
# Jira API docs: https://developer.atlassian.com/cloud/jira/software/rest/#api-rest-agile-1-0-board-boardId-sprint-get

require 'rubygems'
require 'pp'
#require 'jira-ruby'  # instead use Ruby standard library Net::HTTP
require 'net/http'    # ruby-doc.org/stdlib-2.7.0/libdoc/net/http/rdoc/index.html
require 'openssl'
require 'json'

server = 'athndev.atlassian.net'
board_id = 4  # project board ID found by navigating to Jira board in browser, eg.: athndev.atlassian.net/secure/RapidBoard.jspa?rapidView=4
user = ENV['JIRA_API_USER']
apikey = ENV['JIRA_API_KEY']

if (user.empty? || apikey.empty?)
  puts 'ERROR: shell environment variables required: JIRA_API_USER, JIRA_API_KEY'
  exit(1)
end

def fetch_sprints(server:, board_id:, user:, apikey:)
  active_sprints = []
  blockSize = 10
  offset = 0
  i = 0
  sprint_hash = nil

  loop do
    url = "https://#{server}/rest/agile/1.0/board/#{board_id}/sprint?startAt=#{offset}&maxResults=#{blockSize}"
    uri = URI(url)
    Net::HTTP.start(
      uri.host,
      uri.port,
      :use_ssl => uri.scheme == 'https', 
      :verify_mode => OpenSSL::SSL::VERIFY_NONE # or VERIFY_PEER
      ) do |http|

      request = Net::HTTP::Get.new uri.request_uri
      request.basic_auth user, apikey

      response = http.request request # Net::HTTPResponse object

      sprint_hash = JSON.parse(response.body)

      # iterate over sprints to find active ones
      sprint_hash['values'].each do |sprint|
        if is_active_sprint(sprint)
          active_sprints << sprint['name']
        end
      end
    end

    i += 1
    offset = i * blockSize
    break if sprint_hash['isLast']
  end

  active_sprints
end

def is_active_sprint(sprint)
  if( sprint['state'] == 'active' )
    return true
  end
  return false
end

# main ------------------------------------------------------------------------
sprint_names_active = fetch_sprints(server: server, board_id: board_id, user: user, apikey: apikey)
puts sprint_names_active.join(',')

__END__

sample response.body:
{"maxResults":10,"startAt":0,"isLast":false,
  "values":[
    {
      "id":7,
      "self":"https://athndev.atlassian.net/rest/agile/1.0/sprint/7",
      "state":"closed",
      "name":"Release 92",
      "startDate":"2019-01-18T13:49:55.705Z",
      "endDate":"2019-02-01T13:49:00.000Z",
      "completeDate":"2019-01-25T14:54:40.096Z",
      "originBoardId":4,
      "goal":""
    },

sprints['values'].each do |sprint|
  puts "sprint -> id: #{sprint['id']}"
  puts "  name: #{sprint['name']}"
  puts "  state: #{sprint['state']}"
  puts "  startDate: #{sprint['startDate']}"
  puts "  endDate: #{sprint['endDate']}"
  puts "  completeDate: #{sprint['completeDate']}"
  puts "  originBoardId: #{sprint['originBoardId']}"
  puts "  goal: #{sprint['goal']}"
end

