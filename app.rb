require "sinatra"
require "better_errors"
require "sass"
require "kramdown"
require "yaml"
require "./lib/template_utils"
require "./lib/name_utils"
require "./lib/steam_id"
require "./lib/lastfm_user"
require "./lib/home"

helpers TemplateUtils, NameUtils

configure do
  set :views, {
    :markdown => "pages",
    :sass => "styles",
    :default => "views"
  }
  set :markdown, :smartypants => true

  app_config = YAML.load_file("config/application.yml")
  set :avatar, app_config["avatar_href"] || "/images/avatars/curly-512px.jpg"
  set :pages, app_config["navbar_pages"] || [ "about" ]
  set :steamid, app_config["steamid"] || 76561197994245359
  set :steam_api_key, app_config["steam_api_key"]
  set :lastfm_user, app_config["lastfm_user"]
  set :lastfm_api_key, app_config["lastfm_api_key"]
  set :twitch_streams, app_config["twitch_streams"]
end

configure :development do
  use BetterErrors::Middleware
  BetterErrors.application_root = __dir__
end

get "/style.css" do
  last_modified style_modified(:main)
  sass :main
end

get "/status/steam" do
  headers "Content-Type" => "application/json"

  id = SteamId.new(settings.steamid)
  id.api_key = settings.steam_api_key
  JSON.generate(id.fetch)
end

get "/status/lastfm" do
  headers "Content-Type" => "application/json"

  user = LastFmUser.new(settings.lastfm_user)
  user.api_key = settings.lastfm_api_key
  JSON.generate(user.fetch)
end

get "/home" do
  home = Home.new
  home.twitch_streams = settings.twitch_streams
  erb :home, :layout => :main_layout, :locals => { :home => home }
end

get "/ip" do
  erb :ip_info, :layout => :main_layout
end

get "/error" do
  raise "oops"
end

get "/name" do
    headers "Content-Type" => "text/plain"
    hersir_name
end

get "/:page?" do
  @page = params[:page] || "about"
  last_modified page_modified(@page)

  begin
    erb :main_layout, :layout => false do
      markdown @page.to_sym
    end
  rescue Errno::ENOENT
    halt 404
  end
end

not_found do
  erb :error, :layout => false, :locals => { 
    :err_name => "HTTP/1.1 404 Not Found" 
  }
end

error do
  erb :error, :layout => false, :locals => { 
    :err_name => "Internal Server Error",
    :err_desc => env["sinatra.error"].to_s
  }
end