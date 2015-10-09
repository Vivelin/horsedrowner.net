require 'sinatra/base'
require 'better_errors'
require 'tilt/haml'
require 'tilt/sass'
require 'tilt/kramdown'
require 'yaml'

class App < Sinatra::Base
    configure :development do
      use BetterErrors::Middleware
      BetterErrors.application_root = __dir__
    end

    configure do
      set :haml, escape_html: true
      set :sass, views: 'styles'
      set :markdown, views: 'pages', layout_options: { views: 'views' },
                     layout_engine: :haml, smartypants: true

      set :hersir_names, YAML.load_file('data/hersir.yml')
      set :avatar, '/images/avatars/MIDNA 5.png'
      set :avatar_style, %w[large inline avatar]
    end

    not_found do
      haml :not_found
    end

    get '/style.css' do
      sass :main
    end

    get '/name' do
      headers 'Content-Type' => 'text/plain'
      
      first_name = settings.hersir_names['first_names'].sample
      surname_first = settings.hersir_names['surnames_first'].sample
      surname_second = settings.hersir_names['surnames_second'].sample
      "#{ first_name } #{ surname_first }#{ surname_second }"
    end

    get '/strtools' do
      # require 'unicode_tools' on Windows literally takes minutes, so do this
      # as late as possible
      require './app/strtools.rb'

      q = StrTools.new(params[:q])

      haml :inspect_string, locals: { q: q }
    end

    get '/:page?' do
      page = params[:page] || 'about'

      begin
        markdown page.to_sym
      rescue Errno::ENOENT
        halt 404
      end
    end
end
