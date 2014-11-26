require "net/http"
require "json"

class Home
  attr_accessor :twitch_streams

  def initialize
    @twitch_streams = []
    @res = nil
  end

  def live_streams
    return @res["streams"] unless @res.nil?

    begin
      uri = URI("https://api.twitch.tv/kraken/streams/")
      uri.query = URI.encode_www_form(:channel => streams)

      puts "Requesting #{ uri }..."
      @res = JSON.parse(Net::HTTP.get(uri))

      if @res["error"]
        puts "Twitch returned #{ @res["status"] } #{ @res["error"] }: #{ @res["message"] }"
        return nil
      end

      @res["streams"]
    rescue
      puts "Twitch fucked up"
      return nil
    end
  end

  private
  
  def streams
    @twitch_streams.map(&:downcase).join(",")
  end
end