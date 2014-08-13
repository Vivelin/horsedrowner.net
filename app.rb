require "sinatra"
require "better_errors"
require "sass"
require "redcarpet"

configure do
    set :avatar, "/images/avatars/curly-512px.jpg"
    set :pages, ["about", "projects", "quotes"]
    set :markdown, {
        :no_intra_emphasis => true, # no_emphasis_here
        :tables => true,
        :fenced_code_blocks => true, # Parses blocks surrounded by ``` as code
        :autolink => true,
        :strikethrough => true, # ~~strikethrough~~
        :lax_spacing => true, # Don't require blank lines around HTML blocks
        :superscript => true, # super^(script)
        :quote => true, # Parse "quotes" as <q>quotes</q>
        :footnotes => true, # Parse footnotes[^1]
    }
end

configure :development do
    use BetterErrors::Middleware
    BetterErrors.application_root = __dir__
end

get "/style.css" do
    sass :style
end

get "/:page?" do
    @page = params[:page] || "about"
    filename = "pages/#{ @page }.md"
    content = File.read(filename).force_encoding "utf-8"
    erb :main_layout, :layout => false do
        markdown content
    end
end
