require "sinatra"
require "better_errors"
require "sass"
require "github/markdown"

configure do
    set :avatar, "/images/avatars/curly-512px.jpg"
    set :pages, ["about", "projects", "quotes"]
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
        GitHub::Markdown.render_gfm(content)
    end
end
