require "sinatra"
require "better_errors"
require "sass"

configure :development do
    use BetterErrors::Middleware
    BetterErrors.application_root = __dir__
end

get '/' do
    @title = "horsedrowner.net"
    erb :index, :layout => :page
end

get '/style.css' do
    sass :style
end
