require "sinatra"
require "better_errors"
require "sass"
require "kramdown"
require "yaml"
require "./lib/steam_id"
require "./lib/lastfm_user"

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
end

configure :development do
    use BetterErrors::Middleware
    BetterErrors.application_root = __dir__
end

helpers do
    ##
    # Overrides find_template to use different directories for different engines
    #
    def find_template(views, name, engine, &block)
        _, folder = views.detect { |k,v| engine == Tilt[k] }
        folder ||= views[:default]
        super(folder, name, engine, &block)
    end

    ##
    # Determines the last modified date of the file corresponding to the specified template.
    #
    def last_modified_date(name, engine)
        find_template settings.views, name, engine do |file|
            return File.mtime(file) if File.exists?(file)
        end
        Time.now
    end

    ##
    # Determines the last modified date of the specified page.
    #
    def page_modified(name)
        [
            last_modified_date(name, Tilt[:markdown]),
            last_modified_date(:main_layout, Tilt[:erb]),
            last_modified_date(:footer, Tilt[:erb])
        ].max
    end

    ##
    # Determines the last modified date of the specified style.
    def style_modified(name)
        last_modified_date(name, Tilt[:sass])
    end
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

get "/ip" do
    erb :ip_info, :layout => :main_layout
end

get "/:page?" do
    @page = params[:page] || "about"
    last_modified page_modified(@page)

    erb :main_layout, :layout => false do
        markdown @page.to_sym
    end
end
