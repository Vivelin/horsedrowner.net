require 'sinatra'
require './app'

# Never use the Sinatra web server when starting via Rackup
disable :run, :reload

run Sinatra::Application