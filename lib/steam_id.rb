require "net/http"
require "json"
require "active_support/core_ext/numeric/time"
require "active_support/core_ext/time/calculations"

##
# Represents a Steam Community profile
#
class SteamId
  class << self
    attr_accessor :response
    attr_accessor :timestamp
  end

  ##
  # Gets or sets the Steam Web API key
  #
  attr_accessor :api_key

  ##
  # Gets the player's SteamID64
  #
  attr_reader :steam_id64

  ##
  # Gets the player's current display name
  #
  attr_reader :nickname

  ##
  # Initializes a new instance for the specified SteamID64
  #
  def initialize(id)
    @steam_id64 = id
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
  # Returns the player's current status
  #
  def status
    return :ingame if playing?

    case @personastate
    when 1 then :online
    when 2 then :busy
    when 3 then :away
    when 4 then :snooze
    when 5 then :looking_to_trade
    when 6 then :looking_to_play
    else :offline
    end
  end

  ##
  # Returns a message containing the player's name and status.
  #
  def state_message
    case
    when playing? then "#{ nickname } - #{ playing }"
    when loaded? then "#{ nickname } - #{ status.capitalize }"
    else "horsedrowner"
    end
  end

  ##
  # Determines whether the player is currently online or not.
  #
  def online?
    status != :offline
  end

  ##
  # Gets the name of the game currently being played, or nil.
  #
  def playing
    @gamename || nil
  end

  ##
  # Determines whether the player is in-game or not.
  #
  def playing?
    !@gameid.nil?
  end

  ##
  # Makes a Steam Web API request if needed and parses the response.
  #
  def fetch
    interface = "ISteamUser"
    method = "GetPlayerSummaries"
    version = "0002"
    api_url = "http://api.steampowered.com/#{ interface }/#{ method }/v#{ version }/?key=#{ @api_key }&format=json"
    api_url << "&steamids=#{ @steam_id64 }"

    if uptodate?
      puts "Re-using response from #{ self.class.timestamp }"
      response = self.class.response
    else
      begin
        puts "Requesting #{ api_url }..."
        response_body = Net::HTTP.get(URI(api_url))
        response = JSON.parse(response_body)

        self.class.response = response
        self.class.timestamp = Time.now
      rescue
        puts "Steam fucked up again"
      end
    end

    parse
  end

  ##
  # Parses the currently loaded response and returns a hash.
  #
  def parse
    if loaded?
      @summary = self.class.response["response"]["players"][0]

      @nickname = @summary["personaname"]
      @personastate = @summary["personastate"]
      @gameid = @summary["gameid"] || nil
      @gamename = @summary["gameextrainfo"] || nil

      result = {
        :nickname => @nickname,
        :message => state_message,
        :status => status,
        :playing => playing?,
        :game => playing
      }
    end
  end
end
