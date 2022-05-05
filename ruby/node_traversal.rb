#
# 220505 Thursday
# Woflow submission by Jeff Sheffel, jeff@sheffel.org
#
# solution answer:
# $ ruby node_traversal.rb
# unique node count: 30
# most shared node:  a06c90bf-e635-4812-992e-f7b1e2408a3f
#
# https://woflow.notion.site/woflow/Nodes-on-Nodes-Challenge-350310b095174781a89a69fe7e325deb
# ## Nodes API
# An API endpoint returns one or more nodes based on the ID(s) given (comma separated).
#
# Each node contains two keys:
# - `id` - a UUID unique to the node
# - `child_node_ids` - an array of other node IDs
#
# Example request using comma separated IDs:
#
# ```
# GET: https://nodes-on-nodes-challenge.herokuapp.com/nodes/ac0e9fe4-8de0-41e6-9656-b07b40194f47,59013ddb-d691-43c8-8274-7c87e1d739b4
# ```
#
# ```json
# [
# 	{
# 		"id": "ac0e9fe4-8de0-41e6-9656-b07b40194f47",
# 		"child_node_ids": ["f1f509be-e589-479e-a2d8-04cddbddc154", "9e145221-5a5a-446b-abdd-8092ced6a6cf"]
# 	},
# 	{
# 		"id": "59013ddb-d691-43c8-8274-7c87e1d739b4",
# 		"child_node_ids": []
# 	}
# ]
# ```
#
# Given a single starting node ID `089ef556-dfff-4ff2-9733-654645be56fe`, write an algorithm to traverse the complete node tree in order to answer the 2 following questions:
#
# 1. What is the total number of unique nodes?
# 2. Which node ID is shared the most among all other nodes?
#
# Please respond with any code you wrote to complete this challenge along with the answers to the 2 questions above. You can use any language / libraries you prefer to complete this challenge.

require 'net/http'
require 'json'

url = 'https://nodes-on-nodes-challenge.herokuapp.com/nodes/089ef556-dfff-4ff2-9733-654645be56fe'
uri = URI(url)
response = Net::HTTP.get(uri)

first = JSON.parse(response, symbolize_names: true)

targets = []
seen = {}
first.each { |el|
  seen[el[:id]] ||= 0
  seen[el[:id]] = seen[el[:id]] + 1
  el[:child_node_ids].each { |ch|
    targets.push ch
  }
}

while (target = targets.pop) do
  # puts "processing target: #{target}"

  url = "https://nodes-on-nodes-challenge.herokuapp.com/nodes/#{target}"
  uri = URI(url)
  response = Net::HTTP.get(uri)

  arr = JSON.parse(response, symbolize_names: true)

  arr.each { |el|
    el[:child_node_ids].each { |ch|
      # puts "pushing #{ch}"
      targets.push ch unless seen.keys.include? target
    }

    seen[el[:id]] ||= 0
    seen[el[:id]] = seen[el[:id]] + 1
    # puts "seen [ #{el[:id]} ] = #{seen[el[:id]]}"
  }
end

# puts seen

puts "unique node count: #{seen.keys.count}"
most_shared_node = seen.max_by{|k,v| v}[0]
puts "most shared node:  #{most_shared_node}"
