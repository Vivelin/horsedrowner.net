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
      set :haml, escape_html: true, ugly: true, remove_whitespace: true
      set :sass, views: 'styles'
      set :markdown, views: 'pages', layout_options: { views: 'views' },
                     layout_engine: :haml, smartypants: true

      set :hersir_names, YAML.load_file('data/hersir.yml')
      set :avatar, {
          thumb: '/images/avatars/MIDNA 5.png',
          thumb_style: %w[large inline avatar],
          full: '/images/avatars/MIDNA 1.png',
          full_style: %w[full avatar],
          source: 'http://cubesona.deviantart.com/art/COMMISSION-Midna-565090804'
        }
    end

    not_found do
      haml :not_found
    end

    get '/style.css' do
      sass :main
    end

    get '/avatar' do
      request.accept.each do |type|
        case type.to_s
        when /^image\//
          halt send_file settings.avatar.full
        when 'text/html'
          halt haml :avatar
        end
      end

      haml :avatar 
    end

    get '/name' do
      headers 'Content-Type' => 'text/plain'
      
      first_name = settings.hersir_names['first_names'].sample
      surname_first = settings.hersir_names['surnames_first'].sample
      surname_second = settings.hersir_names['surnames_second'].sample
      "#{ first_name } #{ surname_first }#{ surname_second }"
    end

    ['/', '/tools'].each do |route|
      get route do
        # require 'unicode_tools' on Windows literally takes minutes, so do this
        # as late as possible
        puts "If you're on Windows, go get a drink because this will take a while..."
        require './app/strtools.rb'

        strtools = StrTools.new(params[:string])

        haml :tools, locals: { strtools: strtools }
      end
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
