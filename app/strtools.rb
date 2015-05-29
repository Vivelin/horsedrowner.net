require 'unicode_utils'

class StrTools
  def initialize(str)
    @str = str
  end

  def inspect_string
    return %w[] if @str.nil?
    @str.each_char.map do |g|
      begin
        { char: g, ord: g.ord, sid: UnicodeUtils.sid(g) }
      rescue
        { char: g, ord: g.ord, sid: UnicodeUtils.char_name(g) }
      end
    end
  end

  def nil?
    @str.nil?
  end
end