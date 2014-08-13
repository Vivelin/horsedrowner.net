require "sinatra"
require "better_errors"
require "sass"
require "redcarpet"

configure do
    set :views, {
        :markdown => "pages",
        :sass => "styles",
        :default => "views"
    }
    set :markdown, {
        :no_intra_emphasis  => true, # no_emphasis_here
        :tables             => true,
        :fenced_code_blocks => true, # Parses blocks surrounded by ``` as code
        :autolink           => true,
        :strikethrough      => true, # ~~strikethrough~~
        :lax_spacing        => true, # Don't require blank lines around HTML blocks
        :superscript        => true, # super^(script)
        :quote              => true, # Parse "quotes" as <q>quotes</q>
        :footnotes          => true, # Parse footnotes[^1]
    }

    set :avatar, "/images/avatars/curly-512px.jpg"
    set :pages, ["about", "projects", "quotes"]
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

    def last_modified_date(name, engine)
        find_template settings.views, name, engine do |file|
            return File.mtime(file) if File.exists?(file)
        end
        Time.now
    end

    def page_modified(name)
        [
            last_modified_date(name, Tilt[:markdown]),
            last_modified_date(:main_layout, Tilt[:erb]),
            last_modified_date(:footer, Tilt[:erb])
        ].max
    end

    def style_modified(name)
        last_modified_date(name, Tilt[:sass])
    end
end

get "/style.css" do
    last_modified style_modified(:main)
    sass :main
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
