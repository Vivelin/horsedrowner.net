require "net/http"
require "json"
require "active_support/core_ext/numeric/time"
require "active_support/core_ext/time/calculations"

class LastFmUser
  class << self
    attr_accessor :response
    attr_accessor :timestamp
  end

  ##
  # Gets or sets the Last.fm API key
  #
  attr_accessor :api_key

  ##
  # Gets the name of the album of the currently playing song
  #
  attr_reader :album

  ##
  # Gets the name of the artist of the currently playing song
  #
  attr_reader :artist

  ##
  # Gets the name of the currently playing song
  #
  attr_reader :name

  ##
  # Gets the link to the Last.fm page of the currently playing song
  #
  attr_reader :url

  def initialize(username)
    @username = username
  end

  ##
  # True if a response has been received
  #
  def loaded?
    !self.class.response.nil?
  end

  ##
  # True if a response has been received in the last 30 seconds
  #
  def uptodate?
    loaded? && self.class.timestamp > 30.seconds.ago
  end

  ##
  # Determines if a song is currently playing
  #
  def playing?
    @playing == true
  end

  ##
  # Makes a Last.fm API request if needed and parses the response.
  #
  def fetch
    method = "user.getRecentTracks"
    api_url = "http://ws.audioscrobbler.com/2.0/?method=#{ method }&format=json&api_key=#{ @api_key }"
    api_url << "&user=#{ URI::encode(@username) }&limit=1&nowplaying=true"

    if uptodate?
      puts "Re-using response from #{ self.class.timestamp }"
      response = self.class.response
    else
      puts "Requesting #{ api_url }..."
      response_body = Net::HTTP.get(URI(api_url))
      response = JSON.parse(response_body)

      self.class.response = response
      self.class.timestamp = Time.now
    end

    parse
  end

  ##
  # Parses the currently loaded response and returns a hash.
  #
  def parse
    begin
      if loaded?
        track = self.class.response["recenttracks"]["track"][0]
        if track["@attr"]["nowplaying"] == "true"
          @playing = true
          @album = track["album"]["#text"]
          @artist = track["artist"]["#text"]
          @name = track["name"]
          @url = track["url"]

          {
            :playing => true,
            :album => @album,
            :artist => @artist,
            :name => @name,
            :url => @url
          }
        end
      end
    rescue
      @playing = false
      { :playing => false }
    end
  end
end
