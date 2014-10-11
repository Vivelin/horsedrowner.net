require "net/http"
require "json"
require "time"
require "active_support/core_ext/numeric/time"
require "active_support/core_ext/time/calculations"

class TwitchChannel
  class << self
    attr_accessor :response
    attr_accessor :timestamp
  end

  attr_reader :channel_name

  def initialize(channel)
    @channel_name = channel
  end  

  def loaded?
    !self.class.response.nil?
  end

  def uptodate?
    loaded? && self.class.timestamp > 30.seconds.ago
  end

  def live?
    loaded? && !self.class.response["stream"].nil?
  end

  def channel
    live? ? self.class.response["stream"]["channel"]["display_name"] : @channel_name
  end

  def status
    live? ? self.class.response["stream"]["channel"]["status"] : ""
  end

  def game
    live? ? self.class.response["stream"]["game"] : ""
  end

  def viewers
    live? ? self.class.response["stream"]["viewers"] : 0
  end

  def link
    live? ? self.class.response["stream"]["channel"]["url"] : ""
  end

  def fetch
    if uptodate?
      puts "Re-using response from #{ self.class.timestamp }"
      response = self.class.response
    else
      begin
        api_url = URI("https://api.twitch.tv/kraken/streams/#{ u @channel_name }")

        puts "Requesting #{ api_url }..."
        response_body = Net::HTTP.get(api_url)
        response = JSON.parse(response_body)

        self.class.response = response
        self.class.timestamp = Time.now
      rescue
        puts "Twitch fucked up"
      end
    end

    parse
  end

  def parse
    if loaded?
      stream = self.class.response["stream"]
      {
        :live => live?,
        :channel => channel,
        :game => game,
        :status => status,
        :viewers => viewers,
        :link => link
      }
    end
  end

  ##
  # Encodes a value for use in URIs.
  #
  def u(value)
    Rack::Utils.escape_path(value)
  end
end