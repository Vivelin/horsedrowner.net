require "sinatra"
require "better_errors"
require "sass"
require "redcarpet"

configure do
    set :views, {
        :markdown => 'pages',
        :sass => 'styles',
        :default => 'views'
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
    def find_template(views, name, engine, &block)
        _, folder = views.detect { |k,v| engine == Tilt[k] }
        folder ||= views[:default]
        super(folder, name, engine, &block)
    end
end

get "/style.css" do
    sass :main
end

get "/:page?" do
    @page = params[:page] || "about"
    erb :main_layout, :layout => false do
        markdown @page.to_sym
    end
end
